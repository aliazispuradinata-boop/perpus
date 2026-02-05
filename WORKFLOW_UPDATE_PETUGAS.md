# 📋 Workflow Peminjaman - Update dengan Persetujuan Petugas

## 🔄 Workflow Lengkap (Updated)

```
┌─────────────────────────────────────┐
│   User Daftar & Pinjam Buku         │
│   Status: PENDING                   │
└────────────┬────────────────────────┘
             ↓
┌─────────────────────────────────────┐
│   Petugas Lihat di Dashboard        │
│   Tabel: "Peminjaman Menunggu       │
│   Persetujuan Anda"                 │
│                                     │
│   Aksi: Setujui / Tolak             │
└────────────┬────────────────────────┘
             ↓
    ┌────────┴────────┐
    ↓                 ↓
┌───────────┐   ┌────────────┐
│ SETUJUI   │   │  TOLAK     │
│ Status:   │   │ Hapus      │
│ PENDING_  │   │ Record     │
│ PETUGAS   │   │ Notifikasi │
└────┬──────┘   └────────────┘
     ↓
┌─────────────────────────────────────┐
│   Diteruskan ke Admin               │
│   Admin Lihat di Dashboard          │
│                                     │
│   Aksi: Setujui / Tolak             │
└────────────┬────────────────────────┘
             ↓
    ┌────────┴────────┐
    ↓                 ↓
┌───────────┐   ┌──────────────┐
│ SETUJUI   │   │   TOLAK      │
│ Status:   │   │ Hapus Record │
│ ACTIVE    │   │ Notifikasi   │
│ Stok -1   │   │              │
└────┬──────┘   └──────────────┘
     ↓
┌─────────────────────────────────────┐
│   Petugas Konfirmasi Pengambilan    │
│   User Mengambil Buku               │
│   confirmed_at diisi                │
└────────────┬────────────────────────┘
             ↓
┌─────────────────────────────────────┐
│   User Mengembalikan Buku           │
│   Petugas Verifikasi Pengembalian   │
│                                     │
│   Status: RETURNED                  │
│   returned_at diisi                 │
│   Stok +1                           │
└─────────────────────────────────────┘
```

---

## 📊 Status Peminjaman

| Status | Deskripsi | Peran | Aksi |
|--------|-----------|-------|------|
| `pending` | Menunggu persetujuan petugas | Petugas | ✅ Setujui / ❌ Tolak |
| `pending_petugas` | Menunggu persetujuan admin (setelah petugas setuju) | Admin | ✅ Setujui / ❌ Tolak |
| `active` | Telah disetujui, user bisa ambil | Petugas | 📋 Konfirmasi pengambilan |
| `returned` | Buku sudah dikembalikan | - | ✅ Selesai |
| `overdue` | Melewati batas waktu | Petugas | 📋 Verifikasi pengembalian |

---

## 🎯 Dashboard Petugas - Tabel Baru

### "Peminjaman Menunggu Persetujuan Anda"

**Tampilan**: Tabel dengan kolom:
- User (Nama + Email)
- Buku (Judul + Penulis)
- Durasi (Badge)
- Tanggal Pinjam
- Aksi (Lihat, Setujui, Tolak)

**Data Ditampilkan**:
- Max 10 peminjaman status "pending"
- Order by created_at (paling lama terlebih dahulu)
- Live count: "X Menunggu" (badge)

**Kondisi Kosong**:
- Menampilkan: "Tidak ada peminjaman yang menunggu persetujuan" ✅

---

## 🔐 Aksi Petugas di Dashboard

### 1. **Setujui Peminjaman** ✅
**Route**: `POST /petugas/borrowings/{id}/approve-pending`

**Kondisi**: Status = "pending"

**Proses**:
```php
$borrowing->update(['status' => 'pending_petugas']);
// Notifikasi ke user: "Peminjaman Disetujui Petugas"
```

**Hasil**:
- Status berubah: `pending` → `pending_petugas`
- User menerima notifikasi
- Diteruskan ke admin untuk persetujuan akhir

---

### 2. **Tolak Peminjaman** ❌
**Route**: `POST /petugas/borrowings/{id}/reject-pending`

**Kondisi**: Status = "pending"

**Proses**:
```php
$borrowing->delete();
// Notifikasi ke user: "Peminjaman Ditolak"
```

**Hasil**:
- Record dihapus dari database
- User menerima notifikasi penolakan
- Peminjaman selesai (tidak dilanjutkan ke admin)

---

## 📂 File-File yang Diupdate

### Controllers
```php
// Petugas/BorrowingController.php
- approvePending($borrowing)      // Status pending → pending_petugas
- rejectPending($borrowing)       // Hapus record
- confirm($borrowing)             // Konfirmasi pengambilan
- verifyReturn($borrowing)        // Verifikasi pengembalian
```

```php
// Petugas/DashboardController.php
- index()                         // Load pending_approvals
```

### Views
```blade
// petugas/dashboard.blade.php
+ Tabel "Peminjaman Menunggu Persetujuan Anda"

// petugas/borrowings/index.blade.php
+ Status badge: "pending_petugas"
+ Tombol: Setujui, Tolak (untuk status pending)
```

### Routes
```php
Route::post('/{borrowing}/approve-pending', 'approvePending')
    ->name('petugas.borrowings.approve-pending');
Route::post('/{borrowing}/reject-pending', 'rejectPending')
    ->name('petugas.borrowings.reject-pending');
```

### Database
```sql
-- Status enum updated
ALTER TABLE borrowings 
MODIFY COLUMN status ENUM(
    'pending', 
    'pending_petugas',  -- NEW
    'active', 
    'returned', 
    'overdue'
);
```

---

## 🎨 UI Changes

### Dashboard
- **Tabel Baru**: "Peminjaman Menunggu Persetujuan Anda"
- **Tombol**: Lihat, Setujui (hijau), Tolak (merah)
- **Badge**: "X Menunggu" (warning)

### Borrowing List (Daftar Peminjaman)
- **Status Badge Baru**: `pending_petugas` (info/biru)
- **Tombol Kontekstual**:
  - Pending: Setujui (thumb up) + Tolak (times)
  - Pending_Petugas: View only (waiting admin)
  - Active: Konfirmasi pengambilan
  - Active + Confirmed: Verifikasi pengembalian

---

## 📝 Error Fixes

✅ **Fixed**: `Call to a member function format() on null`
- Added null safety checks di view:
  ```blade
  {{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}
  {{ $borrowing->duration_days ?? '-' }}
  ```

---

## 🔗 Migration Files

1. `2026_01_25_000003_add_pending_petugas_status_to_borrowings.php`
   - Adds new enum value `pending_petugas` to status column

---

## 🚀 Testing Checklist

- [ ] Login sebagai petugas
- [ ] Dashboard muncul tabel "Peminjaman Menunggu Persetujuan"
- [ ] Klik "Setujui" → Status berubah ke pending_petugas
- [ ] Klik "Tolak" → Record dihapus, notifikasi terkirim
- [ ] User menerima notifikasi "Disetujui Petugas"
- [ ] Admin melihat peminjaman status pending_petugas di dashboard
- [ ] Responsive di mobile

---

## 💡 Key Points

✅ Petugas adalah **gatekeeper pertama** (pre-approval)  
✅ Admin adalah **approval akhir** sebelum buku diambil  
✅ Dual-approval system menambah kontrol kualitas  
✅ Petugas bisa tolak tanpa melibatkan admin  
✅ Semua aksi menghasilkan notifikasi ke user  
✅ Null safety checks mencegah error format()  

---

## 📊 New Status Flow Summary

**Petugas Persetujuan** → **Admin Persetujuan** → **Pengambilan** → **Pengembalian**

`pending` → `pending_petugas` → `active` → `returned`

Petugas memiliki **decision power** di tahap awal! 🎯
