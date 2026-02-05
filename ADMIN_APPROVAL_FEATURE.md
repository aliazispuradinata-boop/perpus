# 📊 Admin Dashboard - Persetujuan Peminjaman

## Overview
Admin dashboard sekarang memiliki fitur lengkap untuk melihat dan melakukan persetujuan akhir atas peminjaman buku yang telah disetujui oleh petugas.

---

## 🎯 Fitur Admin untuk Persetujuan

### 1. **Lihat Daftar Peminjaman**
**Route**: `GET /admin/borrowings`

**Fitur**:
- Filter berdasarkan status
- Search berdasarkan nama user atau judul buku
- Status badge untuk setiap peminjaman
- Pagination

**Status yang Ditampilkan**:
- `pending` - Menunggu Persetujuan Petugas
- `pending_petugas` - **Menunggu Persetujuan Admin** ⭐ (Admin action di sini)
- `active` - Sedang Dipinjam
- `returned` - Sudah Dikembalikan
- `overdue` - Terlambat

---

### 2. **Lihat Detail Peminjaman**
**Route**: `GET /admin/borrowings/{id}`

**Menampilkan**:
- Informasi peminjam lengkap
- Informasi buku lengkap
- Informasi peminjaman (tgl pinjam, jatuh tempo, durasi)
- QR code (jika ada)
- Catatan

**Action Buttons** (kontekstual berdasarkan status):

#### Status `pending_petugas` (Menunggu Admin):
```
[✅ Setujui Peminjaman] [❌ Tolak Peminjaman] [🗑️ Hapus]
```

#### Status `pending` (Menunggu Petugas):
```
⚠️ "Menunggu persetujuan dari petugas"
```

#### Status `active` (Sedang Dipinjam):
```
[✏️ Edit] [✅ Setujui Pengembalian] [🗑️ Hapus]
```

#### Status `returned` (Sudah Dikembalikan):
```
✅ "Peminjaman telah selesai."
```

---

## 🔐 Controller Methods

### `approvePending(Borrowing $borrowing)` ✅
**Kondisi**: Status = `pending_petugas` (dari petugas)

**Proses**:
```php
1. Validasi status ✓
2. Cek stok buku tersedia
3. Update status: pending_petugas → active
4. Decrement stok buku: -1
5. Buat notifikasi: "Peminjaman Disetujui Admin"
```

**Notifikasi ke User**:
- Type: `borrowing_approved_admin`
- Message: "Permintaan peminjaman buku 'XXX' telah disetujui oleh admin. Silakan ambil buku di perpustakaan."

---

### `rejectPending(Borrowing $borrowing)` ❌
**Kondisi**: Status = `pending_petugas` (dari petugas)

**Proses**:
```php
1. Validasi status ✓
2. Hapus record peminjaman
3. Buat notifikasi: "Peminjaman Ditolak Admin"
```

**Notifikasi ke User**:
- Type: `borrowing_rejected_admin`
- Message: "Permintaan peminjaman buku 'XXX' telah ditolak oleh admin."

---

## 📋 Workflow Dual Approval

```
┌─────────────────────────────────────┐
│   User Daftar & Pinjam Buku         │
│   Status: PENDING                   │
└────────────┬────────────────────────┘
             ↓
┌─────────────────────────────────────┐
│   PETUGAS MELIHAT DI DASHBOARD       │
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
│ Status →  │   │ ❌ Dihapus │
│ PENDING_  │   │            │
│ PETUGAS   │   │ Notifikasi │
└────┬──────┘   └────────────┘
     ↓
┌─────────────────────────────────────┐
│   ADMIN MELIHAT DI DASHBOARD         │
│   Filter: "Menunggu Persetujuan     │
│   Admin" (status = pending_petugas)  │
│                                     │
│   Aksi: Setujui / Tolak             │
└────────────┬────────────────────────┘
             ↓
    ┌────────┴────────┐
    ↓                 ↓
┌───────────┐   ┌──────────────┐
│ SETUJUI   │   │   TOLAK      │
│ Status →  │   │ ❌ Dihapus   │
│ ACTIVE    │   │ Notifikasi   │
│ Stok -1   │   │              │
└────┬──────┘   └──────────────┘
     ↓
┌─────────────────────────────────────┐
│   Petugas Konfirmasi Pengambilan    │
│   User Ambil Buku                   │
│   confirmed_at diisi                │
└────────────┬────────────────────────┘
             ↓
┌─────────────────────────────────────┐
│   User Kembalikan Buku              │
│   Petugas Verifikasi                │
│   Status: RETURNED                  │
│   Stok +1                           │
└─────────────────────────────────────┘
```

---

## 📊 Statistik & Monitoring

Admin dapat melihat:
1. **Jumlah peminjaman pending_petugas** (menunggu persetujuan)
2. **Jumlah peminjaman active** (sedang dipinjam)
3. **Jumlah peminjaman overdue** (terlambat)
4. **Jumlah peminjaman returned** (selesai)

---

## 🔗 Routes

```php
POST /admin/borrowings/{id}/approve-pending
    → approvePending()
    → Status: pending_petugas → active

POST /admin/borrowings/{id}/reject-pending
    → rejectPending()
    → Delete record, send notification
```

---

## 📝 Database/Models

### Notification Types Baru
- `borrowing_approved_petugas` - Petugas setuju
- `borrowing_approved_admin` - Admin setuju ⭐
- `borrowing_rejected` - Petugas tolak
- `borrowing_rejected_admin` - Admin tolak ⭐

---

## 🎨 UI Changes

### Admin Borrowings List
```
Status Filter:
- Menunggu Persetujuan Petugas  (pending)
- Menunggu Persetujuan Admin     (pending_petugas) ⭐ Highlight
- Sedang Dipinjam               (active)
- Sudah Dikembalikan            (returned)
- Terlambat                     (overdue)

Action Buttons:
- pending_petugas: ✅ Setujui, ❌ Tolak, 👁️ Lihat
- pending:         👁️ Lihat (no action)
- active:          ✅ Setujui Pengembalian, ✏️ Edit, 👁️ Lihat
- returned:        ✏️ Edit, 👁️ Lihat
```

### Admin Borrowing Detail
```
Status Badge Highlights:
- Menunggu Admin (info/blue)
- Menunggi Petugas (warning/amber)
- Active (success/green)

Action Section:
- pending_petugas: ✅ Setujui Peminjaman, ❌ Tolak Peminjaman
- pending:         ⚠️ Alert: Menunggu persetujuan dari petugas
- active:          ✅ Setujui Pengembalian, ✏️ Edit
- returned:        ✅ Alert: Peminjaman telah selesai
```

---

## ✅ Checklist Testing

- [ ] Admin login
- [ ] Lihat daftar peminjaman dengan filter status
- [ ] Filter `pending_petugas` menampilkan peminjaman dari petugas
- [ ] Klik detail peminjaman `pending_petugas`
- [ ] Lihat tombol "Setujui Peminjaman" dan "Tolak Peminjaman"
- [ ] Klik "Setujui" → Status berubah ke `active`
- [ ] Stok buku berkurang setelah approval
- [ ] User menerima notifikasi "Disetujui Admin"
- [ ] Klik "Tolak" → Record dihapus
- [ ] User menerima notifikasi "Ditolak Admin"
- [ ] Responsive di mobile

---

## 🚀 Key Features

✅ Dual-approval system (Petugas + Admin)  
✅ Status tracking yang jelas  
✅ Notifikasi otomatis ke user  
✅ Detail view untuk setiap peminjaman  
✅ Filter & search functionality  
✅ Stok management otomatis  
✅ Responsive design  

---

## 📌 Important Notes

- Admin hanya bisa action peminjaman dengan status `pending_petugas`
- Peminjaman status `pending` sedang menunggu persetujuan petugas
- Approval admin adalah langkah final sebelum user bisa ambil buku
- Semua action create notifikasi untuk audit trail
- Stok buku di-decrement saat admin approve, di-increment saat user return

