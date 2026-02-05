# RetroLib - Aplikasi Perpustakaan Digital Retro-Modern

## 📖 Deskripsi Proyek

RetroLib adalah aplikasi perpustakaan digital yang dikembangkan dengan Laravel 10 dan Bootstrap 5. Aplikasi ini menggabungkan desain **retro-modern dengan palet warna vintage** (orange, cream, brown) dan layout yang clean untuk memberikan pengalaman pengguna yang menyenangkan.

### Fitur Utama:

✅ **3 Role Pengguna**: Admin, Member, Guest  
✅ **Sistem Peminjaman Buku** dengan durasi 14 hari  
✅ **10 Kategori Buku Trending**  
✅ **Sistem Rating & Review** untuk setiap buku  
✅ **Wishlist** untuk menyimpan buku favorit  
✅ **Notifikasi Peminjaman** otomatis  
✅ **Dashboard Analytics** untuk Admin  
✅ **Search & Filter** dengan kategori dan sorting  

---

## 🎨 Desain Visual

### Palet Warna (Retro-Modern):
- **Primary**: #FF6B35 (Orange)
- **Secondary**: #FFA500 (Amber)
- **Accent**: #C67C4E (Brown)
- **Light**: #F5E6D3 (Cream)
- **Dark**: #2C2C2C (Charcoal)
- **Cream**: #FFFEF0 (Off White)
- **Brown**: #8B4513 (Saddle Brown)

### Typography:
- **Heading**: Merriweather Serif (untuk tampilan klasik)
- **Body**: Open Sans (untuk keterbacaan modern)

---

## 📁 Struktur Database

### Tabel-Tabel Utama:

#### 1. **users**
```sql
- id (PK)
- name
- email (unique)
- password
- role: admin|member|guest
- phone (nullable)
- address (nullable)
- profile_photo (nullable)
- last_login (nullable)
- is_active (boolean)
- suspended_until (nullable)
- timestamps
```

#### 2. **categories**
```sql
- id (PK)
- name (unique)
- slug (unique)
- description (nullable)
- icon (emoji)
- color (hex color)
- display_order
- is_active (boolean)
- timestamps
```

#### 3. **books**
```sql
- id (PK)
- category_id (FK)
- title
- slug (unique)
- description (nullable)
- author
- isbn (unique, nullable)
- publisher (nullable)
- published_year (nullable)
- pages (nullable)
- language
- cover_image (nullable)
- total_copies
- available_copies
- rating (decimal)
- review_count
- content_preview (nullable)
- is_featured
- is_active
- timestamps
- soft delete
```

#### 4. **borrowings**
```sql
- id (PK)
- user_id (FK)
- book_id (FK)
- borrowed_at (datetime)
- due_date (datetime)
- returned_at (nullable)
- status: active|returned|overdue|lost
- renewal_count
- last_renewal_date (nullable)
- notes (nullable)
- timestamps
```

#### 5. **reviews**
```sql
- id (PK)
- user_id (FK)
- book_id (FK) - unique dengan user_id
- rating (1-5)
- title (nullable)
- content
- is_verified_purchase
- helpful_count
- is_published
- timestamps
- soft delete
```

#### 6. **wishlists**
```sql
- id (PK)
- user_id (FK)
- book_id (FK) - unique dengan user_id
- priority
- added_at (datetime)
- timestamps
```

#### 7. **notifications**
```sql
- id (PK)
- user_id (FK)
- borrowing_id (nullable, FK)
- type: due_soon|overdue|returned|available|new_book|system
- title
- message
- is_read
- read_at (nullable)
- data (JSON)
- timestamps
```

---

## 10️⃣ Kategori Buku Trending

1. **Fiction** 📖 - Novels dan short stories
2. **Self-Help** 💪 - Personal development
3. **Business** 💼 - Entrepreneurship & management
4. **Technology & AI** 💻 - Programming & AI
5. **Fantasy & Sci-Fi** 🚀 - Fantasy & science fiction
6. **Biography** 👤 - Biographies & memoirs
7. **Health & Wellness** 🏥 - Health & fitness
8. **Psychology** 🧠 - Human behavior
9. **Romance** 💕 - Love stories
10. **Mystery & Thriller** 🔍 - Mystery & thrillers

---

## 👥 Role & Permission

### 1. **Admin**
- ✅ Manage semua buku (CRUD)
- ✅ View semua peminjaman
- ✅ Kelola kategori buku
- ✅ Dashboard analytics
- ✅ View statistik (total buku, members, peminjaman aktif, overdue)

### 2. **Member**
- ✅ View katalog buku
- ✅ Pinjam buku (max 1 buku per judul)
- ✅ Perpanjang peminjaman (max 3x)
- ✅ Kembalikan buku
- ✅ Buat & edit review (setelah buku dikembalikan)
- ✅ Tambah/hapus wishlist
- ✅ View riwayat peminjaman

### 3. **Guest**
- ✅ View katalog buku (read-only)
- ✅ Lihat detail buku
- ✅ Lihat rating & review
- ❌ Tidak bisa pinjam buku
- ❌ Tidak bisa membuat review
- ❌ Prompt untuk login/register

---

## 🛠️ Tech Stack

- **Backend**: Laravel 10
- **Frontend**: Blade Templates + Bootstrap 5
- **Database**: MySQL
- **CSS Framework**: Bootstrap 5
- **Icon Library**: Font Awesome 6.4
- **JavaScript**: Vanilla JS + Bootstrap JS

---

## 📋 File Structure

```
perpus/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── BookController.php
│   │   │   ├── BorrowingController.php
│   │   │   └── Admin/
│   │   │       ├── BookController.php
│   │   │       └── BorrowingController.php
│   │   ├── Middleware/
│   │   │   └── CheckRole.php
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── Book.php
│   │   ├── Borrowing.php
│   │   ├── Review.php
│   │   ├── Wishlist.php
│   │   └── Notification.php
│   └── Policies/
│       └── UserPolicy.php
├── database/
│   ├── migrations/
│   │   └── [semua migration files]
│   └── seeders/
│       ├── UserSeeder.php
│       ├── CategorySeeder.php
│       └── BookSeeder.php
├── resources/
│   ├── css/
│   │   └── app.css (Custom CSS)
│   ├── js/
│   │   └── app.js (Custom JS)
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── books/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── borrowings/
│       │   └── history.blade.php
│       ├── dashboard/
│       │   ├── admin.blade.php
│       │   ├── member.blade.php
│       │   └── guest.blade.php
│       ├── admin/
│       │   └── books/
│       │       ├── index.blade.php
│       │       ├── create.blade.php
│       │       ├── edit.blade.php
│       │       └── borrowings/
│       │           └── index.blade.php
│       └── welcome.blade.php
├── routes/
│   └── web.php
└── .env
```

---

## 🚀 Cara Menjalankan Aplikasi

### 1. Setup Awal

```bash
# Clone atau copy project ke folder
cd c:\xampp\htdocs\perpus

# Install dependencies
composer install

# Generate APP_KEY
php artisan key:generate

# Setup database
# Edit .env untuk konfigurasi database
php artisan migrate

# Seed database dengan data awal
php artisan db:seed
```

### 2. Jalankan Server

```bash
php artisan serve --port=8000
```

Buka browser: `http://localhost:8000`

---

## 🔐 Demo Akun

### Admin
- **Email**: admin@retrolib.test
- **Password**: password
- **Role**: Admin

### Member
- **Email**: budi@retrolib.test
- **Password**: password
- **Role**: Member

### Guest
- **Email**: andi@retrolib.test
- **Password**: password
- **Role**: Guest

---

## 📊 Fitur Detail

### Dashboard Admin
- Statistik total buku, members, peminjaman aktif, terlambat
- List peminjaman terbaru
- Manage buku
- Manage peminjaman

### Dashboard Member
- Statistik peminjaman aktif, terlambat, total dikembalikan, wishlist
- List peminjaman aktif dengan progress bar
- Aksi perpanjang & kembalikan buku
- Wishlist favorit
- Rekomendasi buku unggulan

### Dashboard Guest
- Featured books & trending books
- Fitur showcase tanpa akses peminjaman
- CTA untuk register/login

### Katalog Buku
- Search by judul & penulis
- Filter by kategori
- Sort (terbaru, rating, judul, penulis)
- Pagination
- Rating & review display

### Detail Buku
- Full book information
- Rating & review system (member only)
- Related books suggestion
- Wishlist toggle
- Borrow action (member only)

### Sistem Peminjaman
- Auto 14 hari rental period
- Perpanjang up to 3x
- Kembalikan buku
- Riwayat peminjaman lengkap
- Status tracking (active, returned, overdue)

### Review & Rating
- 5-star rating system
- Text review dengan judul (opsional)
- Verified purchase badge
- Helpful count

---

## 🎯 Fitur Notifikasi

1. **Due Soon** - Pengingat peminjaman akan berakhir
2. **Overdue** - Notifikasi buku terlambat
3. **Returned** - Konfirmasi buku dikembalikan
4. **Available** - Notifikasi buku kembali tersedia
5. **New Book** - Buku baru tersedia
6. **System** - Notifikasi sistem

---

## 💾 Model Relationships

```
User
  ├── has Many Borrowings
  ├── has Many Reviews
  ├── has Many Wishlists
  └── has Many Notifications

Book
  ├── belongs To Category
  ├── has Many Borrowings
  ├── has Many Reviews
  └── has Many Wishlists

Category
  └── has Many Books

Borrowing
  ├── belongs To User
  ├── belongs To Book
  └── has Many Notifications

Review
  ├── belongs To User
  └── belongs To Book

Wishlist
  ├── belongs To User
  └── belongs To Book

Notification
  ├── belongs To User
  └── belongs To Borrowing (nullable)
```

---

## 🔄 API Endpoints

### Auth
- `POST /login` - Login
- `POST /register` - Register
- `POST /logout` - Logout

### Dashboard
- `GET /dashboard` - Show dashboard (role-specific)

### Books
- `GET /books` - List books with search & filter
- `GET /books/{slug}` - Show book detail
- `POST /books/{book}/wishlist` - Add to wishlist
- `DELETE /books/{book}/wishlist` - Remove from wishlist
- `POST /books/{book}/reviews` - Create/update review

### Borrowing
- `GET /borrowings/history` - Borrowing history
- `POST /borrowings` - Borrow book
- `POST /borrowings/{borrowing}/return` - Return book
- `POST /borrowings/{borrowing}/renew` - Renew borrowing

### Admin
- `GET /admin/books` - List all books
- `GET /admin/books/create` - Create book form
- `POST /admin/books` - Store book
- `GET /admin/books/{book}/edit` - Edit book form
- `PUT /admin/books/{book}` - Update book
- `DELETE /admin/books/{book}` - Delete book
- `GET /admin/borrowings` - List all borrowings

---

## 🎨 CSS Custom Variables

Semua warna dapat dikustomisasi via CSS variables di `/resources/css/app.css`:

```css
:root {
    --primary: #FF6B35;
    --secondary: #FFA500;
    --accent: #C67C4E;
    --light: #F5E6D3;
    --dark: #2C2C2C;
    --cream: #FFFEF0;
    --brown: #8B4513;
}
```

---

## 📝 Mock Data

Database sudah di-seed dengan:
- **5 Users** (1 Admin, 2 Members, 2 Guests)
- **10 Categories** (Trending book categories)
- **20 Books** (2 per kategori dengan Faker data)

---

## ⚙️ Konfigurasi Penting

### .env
```
APP_NAME=RetroLib
APP_ENV=local
APP_DEBUG=true
DB_DATABASE=perpus
DB_USERNAME=root
DB_PASSWORD=
```

### Database
Database: `perpus`
Username: `root`
Password: (kosong)
Host: `127.0.0.1`

---

## 📞 Kontak & Support

**Email**: info@retrolib.test  
**Phone**: +62 812-3456-7890  
**Location**: Jakarta, Indonesia

---

## 📄 License

Built with ❤️ for book lovers. RetroLib 2026.
