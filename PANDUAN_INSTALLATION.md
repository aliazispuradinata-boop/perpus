# Panduan Installation & Setup - Fitur Peminjaman Buku

## 📋 Prerequisites
- PHP 8.0+
- Laravel 10+
- MySQL 5.7+
- Composer
- Node.js & NPM (untuk asset compilation)

---

## 🚀 Installation Steps

### 1. Pull Latest Code
```bash
git pull origin main
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Install QR Code Package
```bash
composer require simplesoftwareio/simple-qrcode
```

### 4. Run Database Migrations
```bash
php artisan migrate
```

Ini akan menjalankan migration baru:
- `2026_01_25_000001_update_borrowings_table.php`

Yang menambahkan:
- Column `qr_code` (nullable string)
- Column `duration_days` (integer, default 14)
- Column `fine_notes` (nullable text)
- Ubah enum status menjadi: `['pending', 'active', 'returned', 'overdue', 'lost']`

### 5. Create Storage Link
```bash
php artisan storage:link
```

Ini membuat symlink dari `public/storage` ke `storage/app/public`
- Penting untuk serve QR code files publicly!

### 6. Clear Cache
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Compile Assets
```bash
npm run build
```

### 8. Start Development Server
```bash
php artisan serve
```

Akses di: `http://localhost:8000`

---

## ⚙️ Configuration

### Environment Variables (.env)
Pastikan sudah setup:
```env
APP_NAME=RetroLib
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpus
DB_USERNAME=root
DB_PASSWORD=

MAIL_DRIVER=log  # atau sesuaikan dengan SMTP provider
MAIL_FROM_ADDRESS=noreply@retrolib.local
```

### Storage Directory Permissions
```bash
# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Windows
# Jalankan cmd as Administrator
icacls "C:\xampp\htdocs\perpus\storage" /grant Users:F /T
icacls "C:\xampp\htdocs\perpus\bootstrap\cache" /grant Users:F /T
```

---

## 🔍 Testing Checklist

### Pre-Deployment Testing

#### 1. User Registration
```
Steps:
1. Buka /register
2. Isi form: name, email, password, phone, address
3. Submit
4. Cek: user.role = 'user' (bukan 'petugas')
```

#### 2. User Login & Dashboard
```
Steps:
1. Login dengan user account
2. Akses /dashboard
3. Cek: Greeting "Selamat datang kembali, [name]!" tampil
4. Navbar menampilkan: Katalog, Dashboard, Profile dropdown
```

#### 3. Modal Peminjaman
```
Steps:
1. Buka /books (Katalog)
2. Lihat buku dengan stok > 0
3. Klik tombol "Pinjam"
4. Cek modal:
   - Cover image, title, author, publisher, stock tampil
   - Date input dengan default = today
   - Duration input 1-30 dengan +/- buttons
   - Due date auto-update
   - Fine info tampil
5. Atur: tanggal, durasi
6. Klik "Pinjam"
```

#### 4. QR Code Generation
```
Steps:
1. Setelah klik "Pinjam"
2. Cek: QR code modal muncul
3. QR code image visible
4. Response JSON berhasil dengan qr_code URL
```

#### 5. Peminjaman Status
```
Steps:
1. Buka /borrowings/history
2. Cek: Peminjaman terbaru status = "Menunggu Konfirmasi"
3. Filter dropdown tampil dengan opsi:
   - Menunggu Konfirmasi
   - Sedang Dipinjam
   - Sudah Dikembalikan
   - Terlambat
4. Test masing-masing filter
```

#### 6. Admin Approval
```
Steps:
1. Login sebagai admin
2. Buka /admin/borrowings
3. Filter: "Menunggu Konfirmasi"
4. Lihat pending requests
5. Klik "Setujui" pada satu peminjaman
6. Cek:
   - Status berubah → "Sedang Dipinjam"
   - Stok buku berkurang 1
   - Notif terkirim ke user
```

#### 7. Admin Rejection
```
Steps:
1. Di /admin/borrowings, filter "Menunggu Konfirmasi"
2. Klik "Tolak" pada satu peminjaman
3. Cek:
   - Record dihapus
   - Notif terkirim ke user
```

#### 8. Database Check
```bash
# SSH ke database
mysql -u root -p perpus

# Check borrowings table
SELECT * FROM borrowings;

# Cek status enum dan columns baru
DESCRIBE borrowings;

# Check QR code file
ls -la storage/app/public/qrcodes/
```

---

## 🐛 Troubleshooting

### Error: "QR Code generation failed"
**Cause**: GD library tidak installed atau permissions issue

**Fix**:
```bash
# Linux
sudo apt-get install php8.1-gd

# Windows
# Enable php_gd2.dll di php.ini
# Uncomment: extension=gd
```

### Error: "Storage symlink not found"
```bash
# Hapus jika sudah ada
rm public/storage

# Buat lagi
php artisan storage:link
```

### Modal tidak muncul
**Cause**: Bootstrap JS tidak loaded

**Fix**:
```bash
npm install
npm run build
```

### QR Code tidak tersimpan
**Cause**: Storage permissions

**Fix**:
```bash
# Create directory jika belum ada
mkdir -p storage/app/public/qrcodes
chmod -R 755 storage/app/public/qrcodes
```

### Migration error: "Unknown column in borrowings"
**Cause**: Migration sudah jalan sebelumnya tapi incomplete

**Fix**:
```bash
# Lihat status migration
php artisan migrate:status

# Rollback jika perlu
php artisan migrate:rollback

# Re-run
php artisan migrate
```

---

## 📊 Performance Optimization

### 1. Database Indexes
Migration sudah include indexes untuk:
- user_id
- book_id
- status
- due_date

### 2. Query Optimization
Di BorrowingController:
```php
// Gunakan eager loading
$borrowings = $user->borrowings()->with('book')->get();

// Bukan
$borrowings = $user->borrowings()->get();
```

### 3. File Cleanup
Hapus QR codes yang sudah lama:
```bash
# Clear old QR codes (lebih dari 30 hari)
find storage/app/public/qrcodes -mtime +30 -delete
```

---

## 🔒 Security Considerations

### 1. Authorization
✅ Sudah implemented:
- Only auth users bisa pinjam
- Only own borrowings bisa dilihat user
- Only admin bisa approve/reject

### 2. Input Validation
✅ Sudah implemented:
- Duration: 1-30 hari
- Borrow date: tidak boleh masa lalu
- Book availability check

### 3. CSRF Protection
✅ Sudah implemented:
- Form punya @csrf token
- Routes protected

### 4. Rate Limiting (Optional)
```php
// Di routes/api.php atau middleware
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/borrowings', [BorrowingController::class, 'store']);
});
```

---

## 📈 Monitoring & Logging

### Log Files Location
```
storage/logs/laravel.log
```

### Debug Mode
```env
APP_DEBUG=true  # Only untuk development!
APP_DEBUG=false # Production
```

### Monitor QR Code Generation
```php
// Log successful QR generation
Log::info('QR code generated', [
    'borrowing_id' => $borrowing->id,
    'qr_code' => $qr_code_path
]);
```

---

## 🚀 Production Deployment

### 1. Server Requirements
- Ubuntu 20.04+ atau equivalent
- PHP 8.1+
- MySQL 5.7+ atau PostgreSQL
- Nginx atau Apache
- SSL Certificate

### 2. Pre-Deployment Checklist
```bash
# Set permissions
chmod -R 755 storage bootstrap/cache

# Set env untuk production
APP_ENV=production
APP_DEBUG=false

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Web Server Configuration

**Nginx**:
```nginx
server {
    server_name retrolib.com;
    root /var/www/perpus/public;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

### 4. Cronjob untuk Overdue Check
```bash
# Edit crontab
crontab -e

# Add this line
0 0 * * * cd /var/www/perpus && php artisan schedule:run >> /dev/null 2>&1
```

Dalam `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        (new BorrowingController)->checkOverdue();
    })->daily();
}
```

---

## 📞 Support & Contact

Untuk issue atau pertanyaan:
- Report bug di GitHub Issues
- Email: support@retrolib.local
- Dokumentasi lengkap: `/FITUR_PEMINJAMAN.md`

---

## 📝 Changelog

### Version 1.0.0 - 25 Jan 2026
- ✅ Register dengan role 'user'
- ✅ Dashboard greeting
- ✅ Navbar terpadu
- ✅ Modal peminjaman dengan date picker
- ✅ Duration selector (1-30 hari)
- ✅ QR code generation
- ✅ Status pending untuk approval
- ✅ Admin approve/reject workflow
- ✅ History & status tracking

---

**Last Updated**: 25 Januari 2026
**Version**: 1.0.0 - Release Candidate
