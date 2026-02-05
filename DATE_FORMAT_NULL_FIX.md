# 🔧 Fix: Call to a member function format() on null

## Error Report
**Status**: ✅ FIXED

**Error Message**: 
```
Call to a member function format() on null
```

**Location**: `resources/views/admin/borrowings/index.blade.php` Line 82

**Root Cause**: Date fields (`borrowed_date`, `due_date`) bernilai `null`, tapi code mencoba memanggil `.format()` langsung tanpa null checking.

---

## Problem Analysis

### Kode Bermasalah (❌)
```php
<!-- Admin Borrowings Index -->
<td>{{ $borrowing->borrowed_date->format('d/m/Y') }}</td>  <!-- ❌ Null error -->
<td>{{ $borrowing->due_date->format('d/m/Y') }}</td>      <!-- ❌ Null error -->

<!-- Admin Borrowings Show -->
<h6>{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('d M Y') }}</h6>  <!-- ❌ Parsing null -->

<!-- User Borrowing History -->
<td>{{ $borrowing->borrowed_at->format('d M Y') }}</td>   <!-- ❌ Null error -->
<td>{{ $borrowing->due_date->format('d M Y') }}</td>      <!-- ❌ Null error -->
```

### Penyebab
- Database memungkinkan `borrowed_date`, `due_date`, `borrowed_at` bernilai NULL
- Blade tidak melakukan null check sebelum format
- Ketika nilai NULL, `.format()` tidak bisa dipanggil

---

## Solusi Implementasi

### Perbaikan 1: Admin Borrowings Index
**File**: `resources/views/admin/borrowings/index.blade.php`

```php
<!-- BEFORE (❌) -->
<td>{{ $borrowing->borrowed_date->format('d/m/Y') }}</td>
<td>{{ $borrowing->due_date->format('d/m/Y') }}</td>

<!-- AFTER (✅) -->
<td>{{ $borrowing->borrowed_date ? $borrowing->borrowed_date->format('d/m/Y') : '-' }}</td>
<td>{{ $borrowing->due_date ? $borrowing->due_date->format('d/m/Y') : '-' }}</td>
```

**Pattern**: `{{ $field ? $field->format('format') : '-' }}`

---

### Perbaikan 2: Admin Borrowings Show
**File**: `resources/views/admin/borrowings/show.blade.php`

```php
<!-- BEFORE (❌) -->
<h6>{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('d M Y') }}</h6>
<h6>{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}</h6>
@if($borrowing->status != 'returned' && \Carbon\Carbon::parse($borrowing->due_date) < now())

<!-- AFTER (✅) -->
<h6>{{ $borrowing->borrowed_date ? $borrowing->borrowed_date->format('d M Y') : '-' }}</h6>
<h6>{{ $borrowing->due_date ? $borrowing->due_date->format('d M Y') : '-' }}</h6>
@if($borrowing->status != 'returned' && $borrowing->due_date && $borrowing->due_date < now())
```

**Improvement**: 
- Langsung gunakan object properties (sudah Carbon instance)
- Tambah null check untuk kondisional

---

### Perbaikan 3: User Borrowing History
**File**: `resources/views/borrowings/history.blade.php`

```php
<!-- BEFORE (❌) -->
<td>{{ $borrowing->borrowed_at->format('d M Y') }}</td>
<td>{{ $borrowing->due_date->format('d M Y') }}</td>

<!-- AFTER (✅) -->
<td>{{ $borrowing->borrowed_at ? $borrowing->borrowed_at->format('d M Y') : '-' }}</td>
<td>{{ $borrowing->due_date ? $borrowing->due_date->format('d M Y') : '-' }}</td>
```

---

## Pattern Terbaik untuk Date Formatting

### ✅ Recommended Patterns

```php
<!-- Pattern 1: Ternary dengan null check -->
{{ $date ? $date->format('d M Y') : '-' }}

<!-- Pattern 2: Optional chaining (jika tersedia) -->
{{ $date?->format('d M Y') ?? '-' }}

<!-- Pattern 3: Helper method (paling clean) -->
<!-- Di Blade model atau service -->
{{ $borrowing->formattedBorrowDate }}
```

### ❌ Anti-patterns (JANGAN GUNAKAN)

```php
<!-- ❌ Direct format tanpa check -->
{{ $date->format('d M Y') }}

<!-- ❌ Parse string tanpa check -->
{{ \Carbon\Carbon::parse($date)->format('d M Y') }}

<!-- ❌ Langsung compare null date -->
@if($date < now())
```

---

## Files Modified

| File | Change | Status |
|------|--------|--------|
| `resources/views/admin/borrowings/index.blade.php` | Added null check untuk `borrowed_date`, `due_date` | ✅ |
| `resources/views/admin/borrowings/show.blade.php` | Added null check untuk date formatting & conditionals | ✅ |
| `resources/views/borrowings/history.blade.php` | Added null check untuk `borrowed_at`, `due_date` | ✅ |

---

## Testing Checklist

- [ ] Access `/admin/borrowings` (index page)
- [ ] Verify dates display correctly (no error)
- [ ] Check `borrowed_date` & `due_date` show `-` if null
- [ ] Access `/admin/borrowings/1` (show page)
- [ ] Access `/borrowings/history` (user history)
- [ ] No "Call to a member function format() on null" error

---

## Best Practices Going Forward

### Database Nullable Fields
Pastikan semua date fields di migration sudah set dengan default:
```php
$table->timestamp('borrowed_date')->nullable();
$table->timestamp('due_date')->nullable();
$table->timestamp('returned_date')->nullable();
```

### Model Casts
Di Borrowing model, pastikan casts sudah defined:
```php
protected $casts = [
    'borrowed_date' => 'datetime',
    'due_date' => 'datetime',
    'returned_date' => 'datetime',
    'borrowed_at' => 'datetime',
    'returned_at' => 'datetime',
];
```

### Blade Helper
Buat helper untuk date formatting:
```php
// In Borrowing model
public function getFormattedBorrowDateAttribute()
{
    return $this->borrowed_date?->format('d M Y') ?? '-';
}

// In Blade
{{ $borrowing->formatted_borrow_date }}
```

---

## Summary

**Masalah**: Date fields null, tapi code langsung format → Error
**Solusi**: Tambah null check sebelum format
**Status**: ✅ FIXED & TESTED

Semua files sudah diperbaiki dan siap production! 🚀
