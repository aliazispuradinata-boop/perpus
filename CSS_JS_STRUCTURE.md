# Struktur CSS dan JavaScript

## Overview
Setiap halaman di RetroLib memiliki CSS dan JavaScript yang terpisah dan terorganisir dengan baik untuk memastikan performa optimal dan maintainability yang tinggi.

## Direktori Struktur

```
resources/
├── css/
│   ├── app.css (Global styles)
│   ├── pages/
│   │   ├── dashboard.css
│   │   ├── books.css
│   │   ├── admin.css
│   │   └── auth.css
│   └── components/
│       ├── navbar.css (untuk header/navbar jika ada)
│       ├── sidebar.css (untuk sidebar jika ada)
│       └── footer.css (untuk footer jika ada)
│
└── js/
    ├── app.js (Global scripts)
    ├── utils.js (Utility functions)
    ├── bootstrap.js (Bootstrap configuration)
    ├── pages/
    │   ├── dashboard.js
    │   ├── books.js
    │   ├── admin.js
    │   └── auth.js
    └── components/
        ├── navbar.js
        ├── sidebar.js
        └── modal.js
```

## File CSS untuk Setiap Halaman

### 1. Dashboard CSS (`pages/dashboard.css`)
Digunakan untuk halaman dashboard semua role (admin, petugas, user).
**Fitur:**
- Page title styling
- Statistics cards styling
- Section title styling
- Alert dan badge styling
- Card hover effects

### 2. Books CSS (`pages/books.css`)
Digunakan untuk halaman katalog buku dan detail buku.
**Fitur:**
- Book grid layout
- Book card styling
- Search/filter container
- Pagination styling
- Rating display

### 3. Admin CSS (`pages/admin.css`)
Digunakan untuk semua halaman admin panel.
**Fitur:**
- Admin panel header styling
- Admin table styling
- Stats grid layout
- Form styling khusus admin
- Modal styling

### 4. Auth CSS (`pages/auth.css`)
Digunakan untuk halaman login dan register.
**Fitur:**
- Auth container dengan gradient background
- Auth card styling
- Form input styling
- Demo credentials box
- Error/success message styling

## File JavaScript untuk Setiap Halaman

### 1. Dashboard JS (`pages/dashboard.js`)
Menangani interaksi halaman dashboard.
**Fitur:**
- Tooltip dan popover initialization
- Stat card animation
- Progress bar animation
- Notification system

### 2. Books JS (`pages/books.js`)
Menangani interaksi halaman buku.
**Fitur:**
- Book card lazy loading
- Search dan filter functionality
- Wishlist toggle (AJAX)
- Book rating display
- Rating animation

### 3. Admin JS (`pages/admin.js`)
Menangani interaksi halaman admin.
**Fitur:**
- Table row hover effects
- Form validation
- Delete confirmation
- Dynamic form field management
- Stats counter animation
- Image preview for book covers
- Field validation real-time

### 4. Auth JS (`pages/auth.js`)
Menangani interaksi halaman auth.
**Fitur:**
- Form validation real-time
- Password visibility toggle
- Email validation
- Password strength indicator
- Password match validation
- Demo credentials copy to clipboard

### 5. Utils JS (`utils.js`)
Utility functions global yang digunakan di semua halaman.
**Fungsi:**
- `confirm()` - Confirmation dialog
- `formatCurrency()` - Format angka ke mata uang IDR
- `formatDate()` - Format tanggal
- `showLoading()` - Tampilkan loading spinner
- `hideLoading()` - Sembunyikan loading spinner
- `getCsrfToken()` - Ambil CSRF token
- `apiRequest()` - Membuat HTTP request
- `showToast()` - Tampilkan toast notification

## Cara Menggunakan

### 1. Menambahkan CSS Page-Specific
Pada setiap blade.php, tambahkan di section `@section('extra-css')`:

```php
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}">
@endsection
```

### 2. Menambahkan JS Page-Specific
Pada setiap blade.php, tambahkan di section `@section('extra-js')`:

```php
@section('extra-js')
    <script src="{{ asset('js/pages/dashboard.js') }}"></script>
@endsection
```

### 3. Menggunakan Utility Functions
Dari JavaScript page-specific, Anda bisa mengakses utility functions:

```javascript
// Menampilkan toast
AppUtils.showToast('Berhasil!', 'success');

// Format currency
const formatted = AppUtils.formatCurrency(100000);

// API request
AppUtils.apiRequest('/api/books', 'GET').then(data => {
    console.log(data);
});
```

## Load Order

Urutan loading script di layout:
1. Bootstrap CSS (CDN)
2. Google Fonts (CDN)
3. Font Awesome (CDN)
4. `css/app.css` (Global CSS)
5. `@yield('extra-css')` (Page-specific CSS)
6. Bootstrap JS (CDN)
7. `js/utils.js` (Utility functions)
8. `js/app.js` (Global JS)
9. `@yield('extra-js')` (Page-specific JS)

## Best Practices

### CSS
- Gunakan CSS classes dari Bootstrap jika memungkinkan
- Hindari inline styles
- Gunakan CSS variables untuk konsistensi warna
- Gunakan responsive design dengan breakpoints Bootstrap
- Minimal specificity untuk override

### JavaScript
- Selalu gunakan event delegation untuk dynamic elements
- Hindari global variables
- Gunakan `const` dan `let` bukan `var`
- Gunakan async/await untuk promises
- Tambahkan data attributes untuk DOM targeting
- Hindari jQuery jika memungkinkan (gunakan vanilla JS)

### HTML/Blade
- Gunakan data attributes untuk JavaScript hooks: `data-toggle-feature`
- Tambahkan proper semantic HTML
- Gunakan Bootstrap utility classes
- Hindari inline event handlers

## Contoh Implementasi

### Form dengan Real-time Validation
```blade.php
@section('extra-js')
    <script src="{{ asset('js/pages/admin.js') }}"></script>
@endsection

<form data-admin-form="edit">
    <input type="email" class="form-control" required>
    <!-- Script akan handle validation otomatis -->
</form>
```

### AJAX Request dengan Utility
```javascript
AppUtils.apiRequest('/api/books/wishlist', 'POST', { book_id: 1 })
    .then(response => {
        if (response.success) {
            AppUtils.showToast('Ditambahkan ke wishlist!', 'success');
        }
    });
```

## Performance Optimization

- Lazy load images dengan `loading="lazy""`
- Minify CSS dan JS pada production
- Gunakan Vite untuk bundling
- Cache static assets
- Defer non-critical JS

## Browser Support
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Bootstrap 5 support semua modern browsers
- IE11 tidak disupport (sesuai dengan Bootstrap 5)
