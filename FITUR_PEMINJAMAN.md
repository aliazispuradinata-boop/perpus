# Dokumentasi Fitur Peminjaman Buku - RetroLib

## Update Terbaru (25 Januari 2026)

Berikut adalah fitur-fitur baru yang telah ditambahkan untuk sistem peminjaman buku di RetroLib:

### 1. **Register User (User Baru)**
- **Status Sebelum**: Default role saat register adalah 'petugas'
- **Status Sekarang**: Default role saat register adalah 'user'
- **File yang Diubah**: `app/Http/Controllers/RegisterController.php`
- **Deskripsi**: User baru yang mendaftar sekarang akan memiliki role 'user' bukan 'petugas', sesuai dengan desain sistem yang mengharapkan user umum untuk melakukan peminjaman.

### 2. **Dashboard User dengan Greeting**
- **Lokasi**: `/dashboard` (untuk user)
- **Fitur Baru**:
  - Menampilkan greeting "Selamat datang kembali, [nama user]!"
  - Seperti halaman dashboard petugas
  - Menampilkan buku unggulan dan trending

### 3. **Navbar Terpadu di Semua Halaman User**
- **Lokasi**: Semua halaman dengan layout app
- **Fitur**:
  - Navbar seragam di semua halaman
  - Link ke Dashboard, Katalog, dan Peminjaman
  - Menu dropdown untuk user profile dan logout
  - Support untuk multiple roles (admin, petugas, user)

### 4. **Modal Peminjaman Buku**
- **Lokasi**: Halaman Katalog (`/books`)
- **Fitur Utama**:
  - **Tombol Pinjam**: Muncul di setiap buku yang tersedia
  - **Form Peminjaman**:
    - Pilihan tanggal peminjaman (date picker)
    - Durasi peminjaman: 1-30 hari (with +/- buttons)
    - Tampilan info buku: cover, judul, penulis, penerbit, stok
    - Menampilkan tanggal harus dikembalikan otomatis
    - Keterangan denda keterlambatan (Rp 5.000/hari)
  - **QR Code Generation**: Generate QR code setelah submit

### 5. **Status Peminjaman Pending (Menunggu Konfirmasi)**
- **Status Baru**: 'pending' ditambahkan ke enum status
- **Workflow**:
  1. User submit peminjaman → status = 'pending'
  2. Admin approve/confirm → status = 'active' (buku mulai dipinjam)
  3. Admin reject → peminjaman dihapus
- **Database**: Migrasi `2026_01_25_000001_update_borrowings_table.php`
- **Field Baru**:
  - `qr_code` (nullable): Path ke file QR code
  - `duration_days` (integer): Durasi peminjaman dalam hari
  - `fine_notes` (text nullable): Catatan denda

### 6. **Halaman Status Peminjaman User**
- **Lokasi**: `/borrowings/history`
- **Fitur**:
  - Filter berdasarkan status: Menunggu Konfirmasi, Sedang Dipinjam, Sudah Dikembalikan, Terlambat
  - Tampilkan semua peminjaman dengan status jelas
  - Untuk status 'pending': tombol untuk melihat QR code
  - Untuk status 'active': tombol kembalikan dan perpanjang (jika masih bisa)
  - Detail: nama buku, penulis, tanggal pinjam, harus dikembalikan

### 7. **Admin Management - Approve/Reject Peminjaman**
- **Lokasi**: Admin > Kelola Peminjaman (`/admin/borrowings`)
- **Fitur Baru**:
  - Filter status baru: "Menunggu Konfirmasi"
  - Untuk status pending: 
    - Tombol **Setujui** → ubah status ke 'active', decrement stok buku
    - Tombol **Tolak** → hapus record peminjaman
  - Notifikasi otomatis dikirim ke user setelah approve/reject
- **Controller**: `app/Http/Controllers/Admin/BorrowingController.php`
- **Routes Baru**:
  - `POST /admin/borrowings/{borrowing}/approve-pending` → approvePending()
  - `POST /admin/borrowings/{borrowing}/reject-pending` → rejectPending()

### 8. **QR Code Generation**
- **Package**: `simplesoftwareio/simple-qrcode`
- **Fitur**:
  - Generate QR code untuk setiap peminjaman
  - Data QR berisi: ID unik, book ID, user ID, timestamp
  - Disimpan di: `storage/app/public/qrcodes/`
  - Accessible via: `/storage/qrcodes/{uuid}.png`
- **Cara Kerja**:
  - Setelah user submit peminjaman, QR code di-generate
  - User melihat QR code di modal popup
  - User tunjukkan QR code ke petugas untuk konfirmasi

### 9. **Flow Peminjaman Lengkap**

```
User
  ↓
1. Klik tombol "Pinjam" di katalog buku
  ↓
2. Fill form: tanggal pinjam + durasi (1-30 hari)
  ↓
3. Lihat preview: tanggal harus dikembalikan + info denda
  ↓
4. Klik "Pinjam" → generate QR code
  ↓
5. Melihat QR code di modal → screenshot/catat
  ↓
6. Kemudian → Status peminjaman = "Menunggu Konfirmasi"
  ↓
7. Tunjukkan QR code ke petugas/admin

Admin/Petugas
  ↓
1. Lihat di Admin > Kelola Peminjaman
  ↓
2. Filter: "Menunggu Konfirmasi"
  ↓
3. Lihat detail peminjaman (user, buku, QR)
  ↓
4. Click "Setujui" atau "Tolak"
  ↓
5. Jika Setujui:
   - Status → "Sedang Dipinjam" (active)
   - Stok buku berkurang 1
   - Notifikasi terkirim ke user
  ↓
6. Jika Tolak:
   - Peminjaman dihapus
   - Notifikasi terkirim ke user

User (sambil meminjam)
  ↓
1. Lihat status di /borrowings/history
  ↓
2. Status: "Sedang Dipinjam"
  ↓
3. Tombol: Kembalikan, Perpanjang (maks 3x)
  ↓
4. Jika klik "Kembalikan" → status "Sudah Dikembalikan"
```

### 10. **Notifikasi Otomatis**
Notifikasi dibuat otomatis untuk:
- **User**: Ketika peminjaman disetujui (approved)
- **User**: Ketika peminjaman ditolak (rejected)
- **User**: Ketika ada reminder peminjaman akan jatuh tempo

### 11. **Validasi dan Error Handling**
- User tidak bisa meminjam buku yang sudah dipinjam (aktif)
- User tidak bisa meminjam buku yang tidak tersedia
- Durasi peminjaman validasi: 1-30 hari
- Tanggal peminjaman tidak boleh di masa lalu
- Admin tidak bisa approve jika stok buku sudah 0

## File yang Dimodifikasi

1. **Controllers**:
   - `app/Http/Controllers/RegisterController.php` (ubah default role)
   - `app/Http/Controllers/BorrowingController.php` (refactor untuk pending status)
   - `app/Http/Controllers/Admin/BorrowingController.php` (tambah approve/reject methods)
   - `app/Http/Controllers/DashboardController.php` (greeting di user dashboard)

2. **Models**:
   - `app/Models/Borrowing.php` (tambah field baru)

3. **Migrations**:
   - `database/migrations/2026_01_25_000001_update_borrowings_table.php` (new)

4. **Views**:
   - `resources/views/dashboard/user.blade.php` (greeting + navbar)
   - `resources/views/books/index.blade.php` (modal peminjaman)
   - `resources/views/borrowings/history.blade.php` (filter & status)
   - `resources/views/admin/borrowings/index.blade.php` (approve/reject buttons)

5. **Routes**:
   - `routes/web.php` (tambah routes approve/reject)

## Package Baru
- `simplesoftwareio/simple-qrcode` (untuk generate QR code)

## Testing Checklist

- [ ] User baru register dengan role 'user'
- [ ] Login sebagai user
- [ ] Lihat dashboard user dengan greeting
- [ ] Navbar konsisten di semua halaman
- [ ] Klik tombol pinjam di katalog
- [ ] Modal muncul dengan form peminjaman
- [ ] Duration +/- buttons bekerja
- [ ] Due date update otomatis
- [ ] QR code generate setelah submit
- [ ] Status peminjaman jadi 'pending'
- [ ] Login sebagai admin
- [ ] Lihat peminjaman pending di admin panel
- [ ] Approve peminjaman → status jadi 'active'
- [ ] Reject peminjaman → record dihapus
- [ ] User lihat status di /borrowings/history
- [ ] Filter status berfungsi
- [ ] Notifikasi terkirim ke user

## TODO Fitur Tambahan (Optional)
- [ ] Email notification untuk user
- [ ] SMS reminder untuk peminjaman akan jatuh tempo
- [ ] Denda calculator untuk overdue
- [ ] Report/analytics peminjaman
- [ ] Integration dengan payment gateway untuk denda
