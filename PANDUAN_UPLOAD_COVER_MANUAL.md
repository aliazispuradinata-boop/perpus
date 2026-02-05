# 📚 PANDUAN UPLOAD COVER BUKU MANUAL

## Folder Penyimpanan Cover

**Path di server:**
```
C:\xampp\htdocs\perpus\storage\app\public\covers\
```

**atau di browser:**
```
http://127.0.0.1:8000/storage/covers/
```

---

## CARA 1: Upload via Admin Panel (PALING MUDAH)

### Langkah-langkah:

1. **Login ke Admin Panel**
   - Buka: `http://127.0.0.1:8000/admin/books`
   - Login dengan akun admin

2. **Untuk Buku Baru:**
   - Klik tombol **"+ Tambah Buku Baru"**
   - Isi semua data buku
   - Di bagian **"Cover Buku"**, klik **"Pilih File"**
   - Select gambar cover dari komputer Anda
   - Klik **"Simpan Buku"**

3. **Untuk Mengedit Buku Existing:**
   - Pada halaman daftar buku, cari buku yang ingin diedit
   - Klik tombol **"Edit"** (icon pensil)
   - Di bagian **"Cover Buku"**, klik **"Pilih File"**
   - Select gambar cover baru
   - Klik **"Update Buku"**

---

## CARA 2: Manual Copy File ke Folder Storage

Jika ingin copy file langsung tanpa upload via web:

### Langkah-langkah:

1. **Siapkan gambar cover** dengan format:
   - Nama file: `nama-buku-slug.jpg` (contoh: `the-great-gatsby.jpg`)
   - Ukuran: 300x450px atau lebih (landscape portrait book cover)
   - Format: JPG, PNG, atau WEBP

2. **Copy ke folder:**
   ```
   C:\xampp\htdocs\perpus\storage\app\public\covers\
   ```

3. **Update database** - Buka Admin panel:
   - Klik **Edit** pada buku yang sesuai
   - Di bagian Cover Buku, tidak perlu upload file
   - Klik **"Update Buku"** saja
   - Atau langsung update field `cover_image` di database dengan value: `covers/nama-file.jpg`

---

## CARA 3: Direct Database Update (Advanced)

Jika sudah punya file di folder covers:

1. Buka **phpMyAdmin**: `http://127.0.0.1/phpmyadmin`
2. Login
3. Pilih database: **perpus**
4. Pilih table: **books**
5. Cari buku yang ingin diupdate
6. Di kolom `cover_image`, edit dan isi dengan: `covers/nama-file.jpg`
7. Klik Update

---

## Format Naming File

Gunakan format ini agar mudah diidentifikasi:

```
[judul-buku-slug].jpg

Contoh:
- the-great-gatsby.jpg
- to-kill-a-mockingbird.jpg
- atomic-habits.jpg
- the-lord-of-the-rings.jpg
```

---

## Rekomendasi Cover Ukuran

- **Width:** 300px
- **Height:** 450px
- **Format:** JPG (lebih kecil) atau PNG (lebih detail)
- **Compression:** Jangan terlalu besar (max 2MB)

Jika gambar terlalu besar, bisa compress di: https://tinypng.com

---

## Troubleshooting

### Cover tidak muncul setelah upload?
1. Jalankan command:
   ```bash
   php artisan storage:link
   ```

2. Clear browser cache: `Ctrl + Shift + Delete`

### Folder covers tidak ada?
1. Buat folder manual:
   ```
   C:\xampp\htdocs\perpus\storage\app\public\covers\
   ```

2. Atau buat lewat Admin (upload sekali dulu)

### Permission Denied?
1. Right-click folder `storage` → Properties → Security
2. Berikan full permission ke folder

---

