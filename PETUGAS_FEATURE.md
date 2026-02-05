# 📋 Fitur Petugas - Verifikasi Peminjaman

## Ringkasan
Fitur petugas memungkinkan staff perpustakaan untuk mengelola dan memverifikasi semua peminjaman buku dari user. Petugas dapat melihat data lengkap peminjaman, mengkonfirmasi pengambilan buku, dan memverifikasi pengembalian.

---

## 📍 Akses Menu
- **URL**: `/petugas/borrowings`
- **Navbar**: Petugas melihat link "Verifikasi" di navbar
- **Dashboard**: `/petugas/dashboard`

---

## 🎯 Fitur Utama

### 1. **Dashboard Petugas** (`/petugas/dashboard`)
Menampilkan:
- Statistik peminjaman (Menunggu, Aktif, Terlambat, Dikembalikan)
- Aktivitas terbaru (5 peminjaman terakhir)
- Tugas hari ini (reminder peminjaman yang memerlukan tindakan)
- Aksi cepat untuk filter berdasarkan status

**Controller**: `App\Http\Controllers\Petugas\DashboardController`

### 2. **Daftar Semua Peminjaman** (`/petugas/borrowings`)
Tabel interaktif menampilkan:
- ID Peminjaman
- Data User (Nama, Email)
- Nama & Penulis Buku
- Durasi Peminjaman
- Tanggal Pinjam & Kembali
- Status (Menunggu, Aktif, Dikembalikan, Terlambat)
- Aksi (Lihat, Konfirmasi, Verifikasi)

**Fitur Filter**:
- Cari berdasarkan nama user atau judul buku
- Filter berdasarkan status
- Pagination (20 item per halaman)
- Export ke CSV

### 3. **Detail Peminjaman** (`/petugas/borrowings/{id}`)
Menampilkan informasi lengkap:
- **Informasi Peminjaman**: ID, Status, Durasi, Tanggal, Sisa Hari
- **Data Buku**: Cover, Judul, Penulis, Penerbit, Kategori, ISBN, Stok
- **Data Peminjam**: Nama, Email, Telepon, Alamat
- **QR Code**: Untuk verifikasi cepat
- **Aksi Kontekstual**:
  - Jika Status "Active" & Belum Dikonfirmasi: Tombol "Konfirmasi Pengambilan"
  - Jika Status "Active" & Sudah Dikonfirmasi: Tombol "Verifikasi Pengembalian"
  - Jika Status "Overdue": Tombol "Verifikasi Pengembalian" dengan notifikasi

---

## 🔄 Workflow Peminjaman

```
User Daftar & Pinjam Buku
         ↓
Status: PENDING (Menunggu Persetujuan Admin)
         ↓
Admin Setujui Peminjaman
         ↓
Status: ACTIVE (Aktif)
         ↓
Petugas Konfirmasi Pengambilan Buku
         ↓
confirmed_at diisi dengan timestamp
confirmed_by_petugas_id = ID petugas
         ↓
User Mengembalikan Buku ke Perpustakaan
         ↓
Petugas Verifikasi Pengembalian
         ↓
Status: RETURNED (Dikembalikan)
returned_at diisi
verified_by_petugas_id = ID petugas
Stok buku increment +1
```

---

## 📊 Aksi Petugas

### ✅ Konfirmasi Pengambilan (`/petugas/borrowings/{id}/confirm`)
**Kondisi**: Status = "active" & Belum dikonfirmasi

**Proses**:
1. Set `confirmed_at` = sekarang
2. Set `confirmed_by_petugas_id` = ID petugas saat ini
3. Kirim notifikasi ke user: "Peminjaman Dikonfirmasi"

**Tombol**: Tombol "Konfirmasi Pengambilan" di detail peminjaman

---

### ✔️ Verifikasi Pengembalian (`/petugas/borrowings/{id}/verify-return`)
**Kondisi**: Status = "active" (atau "overdue")

**Proses**:
1. Set `status` = "returned"
2. Set `returned_at` = sekarang
3. Set `verified_by_petugas_id` = ID petugas saat ini
4. Increment stok buku: `Book::stock++`
5. Kirim notifikasi ke user: "Buku Dikembalikan"

**Tombol**: Tombol "Verifikasi Pengembalian" di detail peminjaman

---

## 🗄️ Database Changes

### Tabel `borrowings` - Kolom Baru
```sql
- confirmed_at (timestamp, nullable)
  Waktu petugas mengkonfirmasi pengambilan buku

- confirmed_by_petugas_id (unsigned bigint, nullable)
  ID petugas yang mengkonfirmasi (FK ke users)

- verified_by_petugas_id (unsigned bigint, nullable)
  ID petugas yang memverifikasi pengembalian (FK ke users)
```

**Migration**: `2026_01_25_000002_add_petugas_verification_to_borrowings_table.php`

---

## 📂 File-File yang Dibuat

### Controllers
```
app/Http/Controllers/Petugas/
├── BorrowingController.php    (Mengelola peminjaman)
└── DashboardController.php    (Dashboard petugas)
```

### Views
```
resources/views/petugas/
├── borrowings/
│   ├── index.blade.php        (Daftar peminjaman)
│   └── show.blade.php         (Detail peminjaman)
└── dashboard.blade.php        (Dashboard)
```

### Routes
```php
// Dashboard
Route::get('/petugas/dashboard', [PetugasDashboardController::class, 'index'])
    ->name('petugas.dashboard');

// Borrowings
Route::get('/petugas/borrowings', [PetugasBorrowingController::class, 'index'])
    ->name('petugas.borrowings.index');
Route::get('/petugas/borrowings/{borrowing}', [PetugasBorrowingController::class, 'show'])
    ->name('petugas.borrowings.show');
Route::post('/petugas/borrowings/{borrowing}/confirm', [PetugasBorrowingController::class, 'confirm'])
    ->name('petugas.borrowings.confirm');
Route::post('/petugas/borrowings/{borrowing}/verify-return', [PetugasBorrowingController::class, 'verifyReturn'])
    ->name('petugas.borrowings.verify-return');
Route::get('/petugas/borrowings/export', [PetugasBorrowingController::class, 'export'])
    ->name('petugas.borrowings.export');
```

---

## 🎨 UI/UX Details

### Dashboard Cards
- **Menunggu Persetujuan**: Badge warning, icon clock
- **Peminjaman Aktif**: Badge success, icon book
- **Terlambat**: Badge danger, icon warning
- **Dikembalikan**: Badge secondary, icon undo

### Status Badges (Daftar Peminjaman)
- **Menunggu**: `badge bg-warning` - Orange/Amber
- **Aktif**: `badge bg-success` - Green
- **Terlambat**: `badge bg-danger` - Red
- **Dikembalikan**: `badge bg-secondary` - Gray

### Action Buttons
- Eye icon: Lihat detail
- Check icon: Konfirmasi pengambilan
- Undo icon: Verifikasi pengembalian

---

## 🔐 Security & Permissions
- Hanya user dengan `role='petugas'` dapat akses fitur ini
- Middleware: `['auth', 'role:petugas']`
- Query scope: Petugas melihat **semua** peminjaman (bukan hanya miliknya)

---

## 📈 Fitur Tambahan

### Export CSV
- Akses: `/petugas/borrowings/export?status=...`
- Format: ID, User, Email, Buku, Tgl Pinjam, Tgl Kembali, Durasi, Status, Denda
- Encoding: UTF-8 dengan BOM (support Excel)

### Notifikasi
Sistem secara otomatis membuat notifikasi untuk user ketika:
1. Peminjaman dikonfirmasi pengambilan
2. Peminjaman dikonfirmasi pengembalian

---

## ✨ Highlights

✅ Tabel responsif dengan hover effects  
✅ Filter & search dalam satu form  
✅ Detail peminjaman dengan QR code  
✅ Dashboard dengan statistik real-time  
✅ Export data ke CSV  
✅ Validasi status sebelum aksi  
✅ Notifikasi otomatis ke user  
✅ Responsive design (mobile-friendly)  

---

## 🚀 Testing Checklist

- [ ] Login sebagai petugas (role='petugas')
- [ ] Dashboard menampilkan statistik benar
- [ ] Filter peminjaman berdasarkan status
- [ ] Cari peminjaman berdasarkan nama user/buku
- [ ] Buka detail peminjaman
- [ ] Konfirmasi pengambilan buku
- [ ] Verifikasi pengembalian buku
- [ ] Stok buku meningkat setelah return
- [ ] Notifikasi terkirim ke user
- [ ] Export CSV berhasil
- [ ] Responsive di mobile

---

## 📝 Notes
- Petugas dapat melihat **semua** peminjaman dari semua user
- Hanya petugas dan admin yang bisa mengkonfirmasi dan verifikasi
- User mendapatkan notifikasi otomatis untuk setiap aksi petugas
- Data peminjaman tersimpan lengkap untuk laporan dan audit
