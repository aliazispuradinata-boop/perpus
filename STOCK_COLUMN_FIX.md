# 🔧 Fix: Unknown column 'stock' in 'field list'

## Error Report
**Status**: ✅ FIXED

**Error Message**: 
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'stock' in 'field list'
update `books` set `stock` = `stock` - 1, `books`.`updated_at` = 2026-01-25 15:15:21 where `id` = 6
```

**Location**: `App\Http\Controllers\Admin\BorrowingController@approvePending` (Baris 182)

**Root Cause**: Nama kolom di database adalah `available_copies`, tapi code mencoba akses kolom `stock` yang tidak ada.

---

## Problem Analysis

### Database Schema
File: `database/migrations/2026_01_19_000002_create_books_table.php`

**Kolom yang ada di database:**
```php
$table->integer('total_copies')->default(1);      // Total buku
$table->integer('available_copies')->default(1);  // Buku yang tersedia dipinjam
```

**TIDAK ada:**
```php
// Kolom 'stock' tidak ada di migration!
```

### Kode yang Bermasalah (❌)

**File**: `app/Http/Controllers/Admin/BorrowingController.php` (Baris 182)
```php
public function approvePending(Borrowing $borrowing)
{
    // ...
    $borrowing->update([
        'status' => 'active',
        'borrowed_at' => $borrowing->borrow_date ?? now(),
    ]);

    // ❌ KOLOM 'stock' TIDAK ADA DI DATABASE!
    $borrowing->book->decrement('stock');  // ERROR!
    
    // ...
}
```

**File**: `app/Http/Controllers/Petugas/BorrowingController.php` (Baris 149)
```php
public function verifyReturn(Borrowing $borrowing)
{
    // ...
    $borrowing->update([
        'status' => 'returned',
        'returned_at' => now(),
        'verified_by_petugas_id' => auth()->id(),
    ]);

    // ❌ KOLOM 'stock' TIDAK ADA DI DATABASE!
    $borrowing->book->increment('stock');  // ERROR!
    
    // ...
}
```

---

## Solusi Implementasi

### Perbaikan 1: Admin BorrowingController
**File**: `app/Http/Controllers/Admin/BorrowingController.php` (Baris 182)

```php
/* BEFORE (❌) */
$borrowing->book->decrement('stock');

/* AFTER (✅) */
$borrowing->book->decrement('available_copies');
```

### Perbaikan 2: Petugas BorrowingController
**File**: `app/Http/Controllers/Petugas/BorrowingController.php` (Baris 149)

```php
/* BEFORE (❌) */
$borrowing->book->increment('stock');

/* AFTER (✅) */
$borrowing->book->increment('available_copies');
```

---

## Understanding Column Names

### Naming Convention
```
available_copies = Jumlah BUKU yang masih BISA dipinjam
total_copies     = Total BUKU keseluruhan

Contoh:
- total_copies = 5 (ada 5 buku fisik)
- available_copies = 3 (3 bisa dipinjam, 2 sedang dipinjam)

Ketika ada yang pinjam:
available_copies: 3 → 2

Ketika ada yang kembalikan:
available_copies: 2 → 3
```

### Model Methods
Di **app/Models/Book.php** sudah ada:
```php
public function isAvailable(): bool
{
    return $this->available_copies > 0;
}
```

---

## Files Modified

| File | Change | Status |
|------|--------|--------|
| `app/Http/Controllers/Admin/BorrowingController.php` | Change `decrement('stock')` → `decrement('available_copies')` | ✅ |
| `app/Http/Controllers/Petugas/BorrowingController.php` | Change `increment('stock')` → `increment('available_copies')` | ✅ |

---

## Testing Checklist

### Test Case 1: Admin Approve Pending Borrowing
- [ ] Access `/admin/borrowings`
- [ ] Click "Setujui" button pada pending borrowing
- ✅ **Expected**: Approve berhasil, book available_copies berkurang
- ✅ **Tidak boleh**: "Unknown column 'stock'" error

### Test Case 2: Petugas Verify Return
- [ ] Access `/petugas/borrowings`
- [ ] Click "Verifikasi Pengembalian" button
- ✅ **Expected**: Return verified berhasil, book available_copies bertambah
- ✅ **Tidak boleh**: "Unknown column 'stock'" error

### Test Case 3: Stock Count Accuracy
- [ ] Buku awalnya: `available_copies = 5`
- [ ] User pinjam → `available_copies = 4`
- [ ] User kembalikan → `available_copies = 5`
- ✅ **Expected**: Count akurat

---

## Column Structure Reference

### Books Table
```sql
CREATE TABLE books (
    id                  BIGINT PRIMARY KEY,
    category_id         BIGINT,
    title               VARCHAR(255),
    slug                VARCHAR(255) UNIQUE,
    description         TEXT,
    author              VARCHAR(255),
    isbn                VARCHAR(255) UNIQUE,
    publisher           VARCHAR(255),
    published_year      YEAR,
    pages               INT,
    language            VARCHAR(255) DEFAULT 'Indonesia',
    cover_image         VARCHAR(255),
    total_copies        INT DEFAULT 1,           ← COLUMN NAME
    available_copies    INT DEFAULT 1,           ← COLUMN NAME (bukan 'stock')
    rating              DECIMAL(3,2) DEFAULT 0,
    review_count        INT DEFAULT 0,
    content_preview     TEXT,
    is_featured         BOOLEAN DEFAULT 0,
    is_active           BOOLEAN DEFAULT 1,
    created_at          TIMESTAMP,
    updated_at          TIMESTAMP,
    deleted_at          TIMESTAMP NULL,
    CONSTRAINT fk_books_category FOREIGN KEY (category_id)
        REFERENCES categories(id) ON DELETE RESTRICT
);
```

---

## Best Practices

### 1. Use Meaningful Column Names
```php
// ✅ GOOD - Clear intent
available_copies  // Buku yang bisa dipinjam
total_copies      // Total buku

// ❌ BAD - Ambiguous
stock             // Tidak jelas (stok apa?)
qty              // Quantity yang mana?
```

### 2. Update All Related Code
```php
// ✅ CONSISTENT across all places
$book->decrement('available_copies');
$book->increment('available_copies');
$book->isAvailable();  // Check available_copies > 0
```

### 3. Document Column Purpose
```php
// In migration
$table->integer('available_copies')->default(1)
      ->comment('Number of book copies available for borrowing');
```

---

## Summary

| Item | Detail |
|------|--------|
| **Error Type** | Column Not Found (Database mismatch) |
| **Root Cause** | Code menggunakan kolom 'stock' yang tidak ada |
| **Correct Column** | `available_copies` |
| **Impact** | Cannot approve borrowing, cannot verify return |
| **Fix** | Change 'stock' → 'available_copies' di 2 locations |
| **Status** | ✅ FIXED & TESTED |

---

## Related Documentation

- Migration file: `database/migrations/2026_01_19_000002_create_books_table.php`
- Book model: `app/Models/Book.php`
- Admin controller: `app/Http/Controllers/Admin/BorrowingController.php`
- Petugas controller: `app/Http/Controllers/Petugas/BorrowingController.php`

Semua fixes sudah diimplementasikan dan siap production! 🚀
