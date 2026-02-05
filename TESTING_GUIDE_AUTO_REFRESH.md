# 🧪 Testing Guide - Auto-Refresh Status Peminjaman

## 📋 Quick Start Testing

Ikuti langkah-langkah ini untuk menguji fitur auto-refresh status peminjaman:

---

## Test Scenario 1: Complete Approval Flow (5 menit)

### Setup
- Siapkan 3 browser/tab: **User Tab**, **Petugas Tab**, **Admin Tab**
- Atau gunakan incognito windows dengan akun berbeda

### Langkah-Langkah

#### Step 1: User Membuat Peminjaman (User Tab)
```
1. Login sebagai regular user
2. Buka halaman daftar buku: /books
3. Cari buku yang ada stok (available_copies > 0)
4. Klik "Pinjam Buku"
5. Isi form peminjaman:
   - Durasi: 7 hari (atau bebas)
   - Catatan: (optional)
   - Klik "Kirim Permintaan"
6. **JANGAN CLOSE TAB INI** - biarkan tetap di halaman bukti peminjaman
```

**Expected Result**:
- Redirect ke halaman `/borrowings/{id}/proof`
- Modal "Menunggu Konfirmasi Petugas ⏳" tampil
- Button di modal: "Dashboard", "Cek Status", "Tutup"
- Data yang ditampilkan:
  - KODE PEMINJAMAN: #000001 (atau angka lain)
  - Informasi buku
  - Tanggal peminjaman, due date

**Browser Console** (F12):
- Seharusnya tidak ada error
- Polling fetch mulai setiap 5 detik ke URL yang sama

---

#### Step 2: Petugas Approve Peminjaman (Petugas Tab)
```
1. Login sebagai petugas (jika belum)
2. Buka dashboard petugas: /petugas/dashboard
3. Cari tabel "Peminjaman Menunggu Persetujuan Anda"
4. Lihat peminjaman dengan status "pending" dari user di Step 1
5. Klik button "Setujui" (hijau) atau buka detail peminjaman
6. Confirm: "Setujui peminjaman ini?"
```

**Expected Result**:
- Petugas melihat success message: "Peminjaman berhasil disetujui"
- Database update: status `pending` → `pending_petugas`
- Notification dibuat untuk user

---

#### Step 3: Monitor User Tab (User Tab)
```
1. JANGAN melakukan apapun di User Tab
2. Biarkan halaman tetap terbuka di proof page
3. Tunggu maksimal 5 detik...
```

**Expected Result** ✅:
- **Halaman secara OTOMATIS reload tanpa user action**
- Modal "Menunggu Konfirmasi Petugas" HILANG
- Status badge berubah menjadi: "Menunggu Konfirmasi Admin" (biru)
- Pesan di halaman: "Status: Menunggu Konfirmasi Admin"

**Jika tidak terjadi**:
- Klik manual button "Cek Status" di modal
- Halaman seharusnya reload dengan data terbaru

---

#### Step 4: Admin Approve Peminjaman (Admin Tab)
```
1. Login sebagai admin (jika belum)
2. Buka dashboard admin: /admin
3. Lihat card "Kelola Peminjaman"
4. Atau buka langsung: /admin/borrowings
5. Filter: Pilih status "Menunggu Persetujuan Admin" 
   (atau cari peminjaman yang baru dibuat)
6. Klik button "Setujui" atau buka detail
7. Confirm approval
```

**Expected Result**:
- Admin melihat success message
- Database update: status `pending_petugas` → `active`
- User notification: "Peminjaman Disetujui Admin"

---

#### Step 5: Final Check (User Tab)
```
Kembali ke User Tab yang masih terbuka
Tunggu maksimal 5 detik...
```

**Expected Result** ✅✅:
- **Halaman OTOMATIS reload LAGI**
- Status badge: "Sedang Dipinjam" (hijau)
- Pesan: "Status: Sedang Dipinjam"
- User dapat melihat tombol pengambilan/verifikasi
- Polling otomatis BERHENTI (status sudah bukan pending)

---

## Test Scenario 2: Manual Refresh (30 detik)

### Langkah-Langkah
```
1. Buat peminjaman baru (seperti Step 1 di atas)
2. Modal muncul dengan button "Cek Status"
3. JANGAN TUNGGU 5 detik, langsung klik "Cek Status"
4. Halaman seharusnya reload seketika
```

**Expected**: Refresh seketika tanpa menunggu polling

---

## Test Scenario 3: Polling Timeout (5+ menit)

### Langkah-Langkah
```
1. Buat peminjaman baru
2. Buka Browser Console (F12 → Console tab)
3. Catat waktu sekarang
4. Tunggu 5 menit lebih
5. Lihat Network tab di DevTools
```

**Expected**:
- Fetch requests terhenti setelah 5 menit
- Tidak ada permintaan ke server setelah timeout
- User masih bisa klik "Cek Status" untuk manual refresh

**Command di Console untuk monitor**:
```javascript
// Cek berapa kali fetch terjadi dalam 1 menit
let fetchCount = 0;
setInterval(() => {
    console.log(`Fetch calls: ${fetchCount}/menit`);
    fetchCount = 0;
}, 60000);

// Tambah counter setiap fetch (perlu pasang sebelum polling)
```

---

## Test Scenario 4: Rejection by Petugas (2 menit)

### Langkah-Langkah
```
1. User membuat peminjaman (Step 1)
2. Modal muncul di halaman user
3. Petugas TOLAK permintaan (button merah)
4. Database: peminjaman dihapus
5. User notification: "Peminjaman Ditolak"
```

**Expected**:
- User Tab: Halaman otomatis reload atau show error message
- Status berubah atau halaman redirect ke halaman dengan pesan ditolak

---

## Test Scenario 5: Rejection by Admin (2 menit)

### Langkah-Langkah
```
1. User membuat peminjaman
2. Petugas approve → status pending_petugas
3. User Tab: Halaman otomatis update (5 detik)
4. Admin TOLAK permintaan → status dihapus
5. User notification: "Peminjaman Ditolak Admin"
```

**Expected**: User Tab reload dengan informasi penolakan

---

## 🔍 Debugging Checklist

### Browser Console Issues (F12)
```javascript
// 1. Verifikasi data-borrowing-status ada:
document.querySelector('[data-borrowing-status]')
// Should return: <div data-borrowing-status="pending"></div>

// 2. Cek current status value:
document.querySelector('[data-borrowing-status]').getAttribute('data-borrowing-status')
// Should return: "pending", "pending_petugas", "active", etc.

// 3. Monitor polling setiap fetch:
// Buka Network tab → lihat requests setiap 5 detik
```

### Database Check
```bash
# Login ke MySQL:
mysql -u root -p perpus

# Cek status peminjaman terbaru:
SELECT id, user_id, book_id, status, created_at 
FROM borrowings 
ORDER BY created_at DESC 
LIMIT 5;

# Output example:
+-----+---------+---------+------------------+---------------------+
| id  | user_id | book_id | status           | created_at          |
+-----+---------+---------+------------------+---------------------+
| 5   | 2       | 1       | active           | 2026-01-25 10:30:00 |
| 4   | 2       | 2       | pending_petugas  | 2026-01-25 10:25:00 |
| 3   | 2       | 3       | pending          | 2026-01-25 10:20:00 |
+-----+---------+---------+------------------+---------------------+
```

### Network Monitoring
```
1. Buka DevTools (F12)
2. Buka tab "Network"
3. Refresh halaman bukti peminjaman
4. Filter: XHR (XMLHttpRequest)
5. Tunggu 5-10 detik
6. Seharusnya melihat 1-2 request GET ke /borrowings/{id}/proof
```

---

## ✅ Success Criteria

| Kriteria | Status | Notes |
|----------|--------|-------|
| Modal muncul untuk status pending | ✅ | Harus otomatis |
| Auto-refresh dalam 5 detik | ✅ | Tolerance: 4-6 detik |
| Manual refresh button bekerja | ✅ | Klik "Cek Status" |
| Polling berhenti setelah 5 menit | ✅ | Lihat Network tab |
| Tidak ada error di console | ✅ | Clean console |
| Status update di database | ✅ | Verified di MySQL |
| User notification terkirim | ✅ | Check notification table |
| Modal tidak bisa di-ESC | ✅ | Backdrop static |

---

## 📱 Mobile Testing

```
1. Buka aplikasi di mobile (Android/iOS)
2. Login sebagai user
3. Buat peminjaman
4. Modal seharusnya center dan readable
5. Button "Cek Status" harus mudah diklik
6. Tunggu status update atau klik manual button
```

**Expected**: Semua fitur berfungsi sama seperti desktop

---

## 🎬 Video Testing (Optional)

Untuk dokumentasi, Anda bisa:
1. Buka incognito User window
2. Buka incognito Petugas window  
3. Screen record kedua window
4. Jalankan test scenario
5. Simpan video sebagai documentation

---

## 📞 Common Issues & Solutions

### "Modal tidak muncul"
- [ ] Verifikasi status = 'pending' di database
- [ ] Clear browser cache (Ctrl+Shift+Delete)
- [ ] Check Bootstrap CSS/JS loaded
- [ ] Inspect element: `<div id="pendingConfirmationModal">`

### "Halaman tidak auto-reload"
- [ ] Buka F12 Console → cek ada error?
- [ ] Check Network tab → ada fetch request?
- [ ] Verify data-borrowing-status di halaman
- [ ] Try manual button "Cek Status"

### "Status berbeda di database vs halaman"
- [ ] Buka `/borrowings/{id}/proof` langsung
- [ ] Verifikasi latest data dari database
- [ ] Check untuk cached response

### "Polling timeout tidak bekerja"
- [ ] Buka DevTools Network tab
- [ ] Lihat apakah fetch berhenti setelah 5 menit
- [ ] Check console untuk warning/error

---

## 📊 Performance Metrics

Target performance untuk feature ini:

| Metrik | Target | Actual | Status |
|--------|--------|--------|--------|
| Fetch time | < 500ms | - | ? |
| Page reload time | < 2s | - | ? |
| Memory leak (5 min) | < 5MB increase | - | ? |
| CPU usage (idle) | < 1% | - | ? |

Lakukan performance profiling dengan Chrome DevTools untuk verifikasi.

---

**Last Updated**: 2026-01-25
**Test Version**: 1.0
