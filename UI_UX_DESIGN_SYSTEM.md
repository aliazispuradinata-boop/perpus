# 🎨 UI/UX Design System - RetroLib

## 📋 Daftar Isi
1. Design System
2. Halaman Publik (Guest)
3. Halaman User
4. Halaman Petugas
5. Halaman Admin
6. Components & Colors

---

# Part 1: Design System

## Color Palette

### Primary Colors
- **Brown Primary**: `#8B4513` (Main brand color)
- **Brown Dark**: `#6B3410` (Navbar top)
- **Brown Medium**: `#A0522D` (Sidebar, secondary)
- **Brown Light**: `#D2691E` (Accents, borders)

### Secondary Colors
- **Success**: `#28a745` (Approve, positive actions)
- **Danger**: `#dc3545` (Reject, delete actions)
- **Warning**: `#ffc107` (Pending, caution)
- **Info**: `#17a2b8` (Information)
- **Light**: `#f8f9fa` (Backgrounds)
- **Dark**: `#343a40` (Text, dark backgrounds)

### Special Colors
- **Gold Hover**: `#FFE4B5` (Moccasin - navbar hover)
- **Peach**: `#FFF8DC` (Cornsilk - light background)

## Typography

### Font Family
- **Headings**: Merriweather (serif) - Professional, elegant
- **Body**: Open Sans (sans-serif) - Clean, readable

### Font Sizes
- **H1**: 32px - Page title
- **H2**: 28px - Section title
- **H3**: 24px - Card title
- **H4**: 20px - Subsection
- **H5**: 18px - Small title
- **H6**: 16px - Regular text
- **Body**: 14px - Default text
- **Small**: 12px - Help text

### Font Weights
- **Bold**: 700 - Titles, important text
- **Semi-bold**: 600 - Labels, nav items
- **Regular**: 400 - Body text

## Spacing
- **xs**: 4px
- **sm**: 8px
- **md**: 16px
- **lg**: 24px
- **xl**: 32px
- **2xl**: 48px

## Border & Shadows
- **Border radius**: 4-8px (cards), 0px (navbar)
- **Box shadow**: 
  - Light: `0 2px 8px rgba(0,0,0,0.1)`
  - Medium: `0 4px 12px rgba(0,0,0,0.15)`
  - Heavy: `0 4px 15px rgba(0,0,0,0.3)`
- **Text shadow**: `0.5px 0.5px 1px rgba(0,0,0,0.3)` (navbar text)

---

# Part 2: Halaman Publik (Guest)

## 2.1 Landing Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │  Height: 60px
│ Logo | Katalog | Login | Daftar     │
└─────────────────────────────────────┘
│                                     │
│   HERO SECTION                      │  Height: 400-500px
│   Welcome to RetroLib               │
│   Perpustakaan Digital Retro        │
│   [Search Bar]                      │
│                                     │
├─────────────────────────────────────┤
│  FEATURED BOOKS SECTION             │  Grid: 4 columns
│  "Buku Terbaru & Terpilih"          │  Cards: 250x350px
│                                     │
│  [Book 1] [Book 2] [Book 3] [Book 4]│
│  [Book 5] [Book 6] [Book 7] [Book 8]│
│  [Book 9] [Book 10] [Book 11] [Book 12]│
│                                     │
├─────────────────────────────────────┤
│  CATEGORIES SECTION                 │  Grid: 6 columns
│  "Jelajahi Kategori"                │  Cards: 150x120px
│                                     │
│  [Fiksi] [Non-Fiksi] [Biografi]    │
│  [Sejarah] [Sains] [Seni]           │
│                                     │
├─────────────────────────────────────┤
│  FOOTER                             │  Background: #2C1810
│  About | Contact | Social Media     │
└─────────────────────────────────────┘
```

### Components Detail

**Hero Section**:
- Background: Gradient brown (#6B3410 → #8B4513)
- Title: Merriweather, 42px, bold, white
- Subtitle: Open Sans, 18px, light text
- CTA Button: "Mulai Jelajahi" - Primary button

**Book Cards**:
- Image: 220x280px
- Title: 2-line truncate
- Author: Small gray text
- Rating: ⭐ with number
- Button: "Pinjam" (if logged) or "Lihat Detail"
- Badge: "Baru", "Populer" (optional)

**Category Cards**:
- Icon: Font Awesome, 48px
- Name: 16px, bold
- Count: "(15 buku)" small text
- Hover: Scale 1.05, shadow increase

---

## 2.2 Books List/Catalog Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │  Padding: 32px
│  "Katalog Buku"                     │
│  Filter & Search Section            │
│                                     │
├─────────────────────────────────────┤
│  FILTERS (Sidebar)      │ BOOKS     │
│  ┌──────────────────┐   │           │
│  │ Kategori         │   │ [Book 1]  │
│  │ ☑ Fiksi         │   │ [Book 2]  │
│  │ ☑ Non-Fiksi     │   │ [Book 3]  │
│  │ ☑ Biografi      │   │ [Book 4]  │
│  │                 │   │ [Book 5]  │
│  │ Rating          │   │ [Book 6]  │
│  │ ★★★★★ (5)      │   │ [Book 7]  │
│  │ ★★★★☆ (4)      │   │ [Book 8]  │
│  │                 │   │ [Book 9]  │
│  │ [Apply Filter]  │   │ [Book 10] │
│  └──────────────────┘   │           │
│                         │ Pagination│
│                         │ << 1 2 3>>│
│                         │           │
└─────────────────────────────────────┘
```

### Components Detail

**Search & Sort Bar**:
- Search input: Width 100%, placeholder "Cari buku..."
- Sort dropdown: "Terbaru", "A-Z", "Rating", "Populer"
- View toggle: Grid (default) / List view

**Filter Sidebar**:
- Background: #f8f9fa
- Width: 250px (sticky)
- Sections: Category, Rating, Availability
- Buttons: "Apply Filters" (primary), "Reset" (secondary)

**Book Grid**:
- Default: 4 columns (responsive: 3 tablet, 2 mobile)
- Cards: 220x350px
- Content: Image, Title, Author, Rating, Price (if any)
- Actions: "Pinjam" button on hover

---

## 2.3 Book Detail Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
│  < Kembali                          │
└─────────────────────────────────────┘
│                                     │
│  HERO SECTION                       │
│  [Back] Book Detail [Similar Books] │
│                                     │
├──────────────┬──────────────────────┤
│              │                      │
│  COVER       │  DETAILS             │
│  IMAGE       │  Judul Buku Panjang  │
│  200x300     │  Penulis             │
│              │  Penerbit: XXX       │
│  [Add to     │  ISBN: XXXXXXXXXXXX  │
│   Wishlist]  │  Tahun: 2024         │
│              │  Halaman: 350        │
│              │  Rating: ⭐⭐⭐⭐⭐ (47) │
│              │  Status: Tersedia (5)│
│              │                      │
│              │  [PINJAM] [WISHLIST] │
│              │                      │
├──────────────┴──────────────────────┤
│                                     │
│  TABS                               │
│  [Deskripsi] [Preview] [Review]     │
│                                     │
│  DESKRIPSI                          │
│  Lorem ipsum dolor sit amet...      │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  REVIEWS & RATINGS                  │
│  Average Rating: ⭐⭐⭐⭐⭐ (4.5/5)     │
│  123 Reviews                        │
│                                     │
│  ┌─────────────────────────────┐   │
│  │ User: "Amazing book!"       │   │
│  │ Rating: ⭐⭐⭐⭐⭐             │   │
│  │ 2 weeks ago                 │   │
│  └─────────────────────────────┘   │
│                                     │
│  [Tulis Review] (if member)         │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  BUKU SERUPA                        │
│  [Book 1] [Book 2] [Book 3] [Book 4]│
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Cover & Details**:
- Cover image: 200x300px, with shadow
- Title: 24px, bold
- Metadata: Small labels with icons
- Status badge: Green "Tersedia", Red "Dipinjam" (with count)
- CTA Buttons: "PINJAM" (primary, large), "WISHLIST" (secondary)

**Tabs**:
- Tab style: Underline active, gray inactive
- Content padding: 24px

**Review Card**:
- Avatar: 40x40px circle
- Name & date: 14px gray
- Rating: Stars
- Review text: 14px, max 3 lines preview
- Actions: Helpful (👍), Report (⚠️)

---

## 2.4 Login Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR                      │
└─────────────────────────────────────┘
│                                     │
│         ┌──────────────────┐        │
│         │   RETRO ICON     │        │
│         │                  │        │
│         │  Selamat Datang  │        │
│         │   RetroLib       │        │
│         │                  │        │
│         │ Email:           │        │
│         │ [input field]    │        │
│         │                  │        │
│         │ Password:        │        │
│         │ [input field]    │        │
│         │                  │        │
│         │ [MASUK] Button   │        │
│         │                  │        │
│         │ ☐ Ingat Saya     │        │
│         │ Lupa Password?   │        │
│         │                  │        │
│         │ Belum punya      │        │
│         │ akun?            │        │
│         │ [DAFTAR SEKARANG]│        │
│         │                  │        │
│         └──────────────────┘        │
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Login Card**:
- Width: 400px (600px on mobile)
- Background: White
- Shadow: Medium
- Centered on page with backdrop

**Input Fields**:
- Width: 100%
- Height: 44px
- Border: 1px #ddd
- Radius: 4px
- Focus: Border #8B4513, shadow
- Icon: Email/Lock icon on left

**Buttons**:
- "MASUK": Primary, 44px height, bold text
- "DAFTAR": Link style, brown color
- "Lupa Password": Link style, small text

---

## 2.5 Register Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR                      │
└─────────────────────────────────────┘
│                                     │
│         ┌──────────────────┐        │
│         │  RETRO ICON      │        │
│         │                  │        │
│         │  Daftar Akun     │        │
│         │   RetroLib       │        │
│         │                  │        │
│         │ Nama Lengkap:    │        │
│         │ [input field]    │        │
│         │                  │        │
│         │ Email:           │        │
│         │ [input field]    │        │
│         │                  │        │
│         │ Password:        │        │
│         │ [input field]    │        │
│         │ ☐ Tampilkan      │        │
│         │                  │        │
│         │ Konfirmasi Pass: │        │
│         │ [input field]    │        │
│         │                  │        │
│         │ No. Telepon:     │        │
│         │ [input field]    │        │
│         │                  │        │
│         │ Alamat:          │        │
│         │ [text area]      │        │
│         │                  │        │
│         │ ☐ Setuju Terms   │        │
│         │                  │        │
│         │ [DAFTAR] Button  │        │
│         │                  │        │
│         │ Sudah punya      │        │
│         │ akun?            │        │
│         │ [MASUK SEKARANG] │        │
│         │                  │        │
│         └──────────────────┘        │
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Register Form**:
- Width: 450px
- Multiple input fields stacked
- Textarea: For address (4 lines)
- Checkbox: Terms & conditions
- Error messages: Red text below field
- Success feedback: Green checkmark

---

# Part 3B: Halaman Profil (Semua Role)

## 3B.1 Profile Show Page (View Profile)

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │
│  "Profil Saya"  [Edit Profil]       │
│                                     │
├──────────────┬──────────────────────┤
│              │                      │
│  DATA CARD   │  STATUS CARD         │
│  ┌─────────┐ │  ┌────────────────┐ │
│  │Nama     │ │  │👤 User Avatar  │ │
│  │Email    │ │  │(Large Icon)    │ │
│  │HP       │ │  │ID: #123        │ │
│  │Alamat   │ │  │✅ Aktif        │ │
│  │Role:👤 │ │  │[Ubah Password] │ │
│  │Tgl Reg │ │  └────────────────┘ │
│  │Update  │ │                      │
│  └─────────┘ │  STATS CARD         │
│              │  ┌────────────────┐ │
│              │  │Buku Dipinjam   │ │
│              │  │Buku Dikembalikan│
│              │  │Wishlist        │ │
│              │  │(Per role)      │ │
│              │  └────────────────┘ │
│              │                      │
├──────────────┴──────────────────────┤
│                                     │
│  QUICK ACTIONS                      │
│  [Edit Profil] [Ubah PW] [Katalog]  │
│  [Riwayat/Verifikasi/Admin]         │
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Data Card** (Info Pribadi):
- Grid 2 columns: Nama/Email, HP/Role
- Background: White with left brown border
- Labels: Bold brown, semi-bold gray values
- Registration & Update timestamps

**Status Card**:
- Avatar: 72px circle, brown color
- Account status: Green success badge
- CTA: "Ubah Password" button

**Stats Card** (Role-specific):
- **User Stats**:
  - Buku Dipinjam (Aktif)
  - Buku Dikembalikan
  - Wishlist
- **Petugas Stats**:
  - Menunggu Persetujuan
  - Peminjaman Aktif
  - Terlambat
- **Admin Stats**:
  - Total Pengguna
  - Total Buku
  - Peminjaman Aktif

**Quick Actions**:
- Button group responsive
- Icons + labels
- Role-aware destinations

---

## 3B.2 Profile Edit Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │
│  "Edit Profil"                      │
│  Perbarui informasi data pribadi    │
│                                     │
├──────────────┬──────────────────────┤
│              │                      │
│  FORM CARD   │  INFO BOX            │
│  ┌─────────┐ │  ┌────────────────┐ │
│  │Nama     │ │  │Peran Akun:     │ │
│  │[input]  │ │  │[Badge]         │ │
│  │          │ │  │                │ │
│  │Email    │ │  │Catatan:        │ │
│  │[input]  │ │  │Pastikan email  │ │
│  │          │ │  │aktif           │ │
│  │HP       │ │  │                │ │
│  │[input]  │ │  │────────────────│ │
│  │          │ │  │SECURITY BOX    │ │
│  │Alamat   │ │  │Ubah password   │ │
│  │[textarea]│ │  │[Ubah PW Btn]   │ │
│  │          │ │  └────────────────┘ │
│  │[Simpan] │ │                      │
│  │[Batal]  │ │                      │
│  └─────────┘ │                      │
│              │                      │
└──────────────┴──────────────────────┘
```

### Components Detail

**Form Fields**:
- Large inputs (44px height)
- Brown focus border & shadow
- Placeholder text
- Validation feedback
- Helper text below each field

**Buttons**:
- "Simpan Perubahan": Primary brown (100% width on mobile)
- "Batal": Secondary outline
- Submit on form validation

---

## 3B.3 Change Password Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │
│  "Ubah Password"                    │
│  Perbarui password untuk keamanan   │
│                                     │
├──────────────┬──────────────────────┤
│              │                      │
│  FORM CARD   │  TIPS KEAMANAN       │
│  ┌─────────┐ │  ┌────────────────┐ │
│  │Current  │ │  │Password Kuat:  │ │
│  │Password │ │  │✓ Min 8 char    │ │
│  │[input]👁│ │  │✓ Besar & kecil │ │
│  │          │ │  │✓ Angka        │ │
│  │────────│ │  │✓ Karakter khusus│
│  │New PW  │ │  │                │ │
│  │[input]👁│ │  │Yang dihindari: │ │
│  │Strength:    │  │✗ Tanggal lahir │ │
│  │[====  50%] │ │  │✗ Nama user     │ │
│  │Sedang      │ │  │✗ Kata umum    │ │
│  │          │ │  │                │ │
│  │Confirm  │ │  │─────────────────│ │
│  │[input]👁│ │  │Ubah setiap 3 bln│
│  │          │ │  └────────────────┘ │
│  │[Ubah]   │ │                      │
│  │[Batal]  │ │                      │
│  └─────────┘ │                      │
│              │                      │
└──────────────┴──────────────────────┘
```

### Components Detail

**Password Fields**:
- Input with toggle visibility (👁)
- Current password field first
- Two new password fields (input + confirm)
- Show/hide toggle with icon change

**Strength Indicator**:
- Progress bar (0-100%)
- Color coded:
  - Red (<30%): Sangat Lemah
  - Orange (30-50%): Lemah
  - Blue (50-70%): Sedang
  - Green (70-90%): Kuat
  - Teal (>90%): Sangat Kuat
- Real-time update on input

**Tips Box** (Right sidebar):
- Green header: "Tips Keamanan"
- Requirements with checkmarks
- Warnings with X marks
- Best practice note

---

# Part 3C: Halaman Profil

## 3.1 User Dashboard

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
└─────────────────────────────────────┘
│                                     │
│  GREETING SECTION                   │  Padding: 32px
│  "Selamat datang kembali, Nama!"    │
│  "Anda memiliki 3 peminjaman aktif" │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  STATS CARDS (4 columns)            │  Height: 120px each
│  ┌─────────┬──────┬──────┬──────┐  │
│  │ Aktif   │ Tar. │ Sele.│ Total│  │
│  │ 3       │ 1    │ 5    │ 12   │  │
│  │ 📚      │ ⚠️   │ ♥️   │ 📊   │  │
│  └─────────┴──────┴──────┴──────┘  │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  QUICK ACTIONS (4 columns)          │  Height: 80px each
│  ┌─────────┬──────┬──────┬──────┐  │
│  │Lihat    │Wishlist│Ulasan│History│
│  │Peminjaman│     │    │      │
│  └─────────┴──────┴──────┴──────┘  │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  ACTIVE BORROWINGS                  │  Cards in row
│  "Peminjaman Aktif Anda"            │
│                                     │
│  ┌────────────┬─────────┬────────┐  │
│  │ Book Cover │ Details │ Actions│  │
│  │            │ Title   │ Kembalikan│
│  │            │ Due: 5d │ Perpanjang│
│  │            │ Fine: Rp- │       │
│  └────────────┴─────────┴────────┘  │
│                                     │
│  ┌────────────┬─────────┬────────┐  │
│  │ Book 2     │ Details │ Actions│  │
│  └────────────┴─────────┴────────┘  │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  RECOMMENDATIONS                    │
│  "Buku yang Mungkin Anda Suka"      │  Grid: 4 columns
│                                     │
│  [Book 1] [Book 2] [Book 3] [Book 4]│
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Greeting**:
- H1: "Selamat datang kembali, {nama}!"
- Subtitle: Summary of borrowings

**Stats Cards**:
- Icon on left (48px)
- Number: Bold, 28px
- Label: Gray, 14px
- Background: Light with accent color
- Hover: Slight lift

**Active Borrowings Card**:
- Horizontal layout
- Cover: 80x120px on left
- Details: Flex column
  - Title (bold), Author (gray), Due date, Fine amount
- Actions: Button group
  - "Kembalikan" (primary)
  - "Perpanjang" (secondary, if available)

---

## 3.2 Borrowing History Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │
│  "Riwayat Peminjaman Anda"          │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  FILTER BAR                         │
│  Search: [input] | Status: [select] │
│  Dates: [from] - [to] | [Apply]     │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  BORROWING TABLE                    │  Responsive
│  ┌──────┬────────┬──────┬────────┐  │
│  │ Buku │ Tanggal│ Durasi│ Status │  │
│  ├──────┼────────┼──────┼────────┤  │
│  │ Book1│ 1/1-2/1│ 31d  │ Return.│  │
│  │      │        │      │ ⭐ 5/5 │  │ (with review stars)
│  │      │ [View] │[Review]       │  │
│  ├──────┼────────┼──────┼────────┤  │
│  │ Book2│ 12/15- │ 30d  │ Active │  │
│  │      │ 1/14   │      │ ⏰ 5d  │  │
│  │      │        │      │ Denda:0│  │
│  │      │[View]│[Return][Renew] │  │
│  ├──────┼────────┼──────┼────────┤  │
│  │ Book3│ 11/30- │ 15d  │Overdue│  │
│  │      │ 12/15  │      │ ⚠️ 11d│  │
│  │      │        │      │Denda: Rp│  │
│  │      │[View] │ [Return] [Pay] │  │
│  └──────┴────────┴──────┴────────┘  │
│                                     │
│  Pagination: << 1 2 3 >>            │
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Status Badges**:
- Pending: Yellow badge with icon ⏳
- Active: Green badge with countdown
- Overdue: Red badge with warning icon
- Returned: Gray badge with checkmark

**Actions by Status**:
- Pending: "View", "Cancel" (if allowed)
- Active: "Return", "Renew" (if allowed), "View"
- Overdue: "Return", "Pay Fine", "View"
- Returned: "View", "Review" (if not reviewed)

**Table Responsive**:
- Desktop: Full table
- Tablet: Hide author, show less info
- Mobile: Card view instead of table

---

## 3.3 Wishlist Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │
│  "Daftar Keinginan Saya"            │
│  "12 Buku"                          │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  WISHLIST GRID (4 columns)          │
│                                     │
│  ┌──────────────┬──────────────┐   │
│  │ Cover        │ Cover        │   │
│  │ Title        │ Title        │   │
│  │ Author       │ Author       │   │
│  │ Avail: Yes   │ Avail: No    │   │
│  │              │              │   │
│  │[Pinjam]      │[Notifikasi]  │   │
│  │[Hapus]       │[Hapus]       │   │
│  └──────────────┴──────────────┘   │
│                                     │
│  ┌──────────────┬──────────────┐   │
│  │ ...          │ ...          │   │
│  └──────────────┴──────────────┘   │
│                                     │
│  [Pinjam Semua yang Tersedia]       │
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Wishlist Card**:
- Grid responsive: 4 desktop, 3 tablet, 2 mobile
- Cover: Full width, 250x350px
- Info: Title (bold), Author (gray), Availability
- Buttons:
  - If available: "Pinjam" (primary), "Hapus" (secondary)
  - If not: "Notifikasi" (secondary), "Hapus" (secondary)

---

## 3.4 Borrowing Modal (Popup)

### Layout
```
┌──────────────────────────────────────┐
│  MODAL OVERLAY                       │
│  ┌────────────────────────────────┐  │
│  │  Pinjam Buku         [X]       │  │
│  ├────────────────────────────────┤  │
│  │                                │  │
│  │  BOOK INFO                     │  │
│  │  [Cover] Title, Author, Stock │  │
│  │                                │  │
│  │  ┌──────────────────────────┐ │  │
│  │  │ Tanggal Pinjam:          │ │  │
│  │  │ [Date Picker] (min today)│ │  │
│  │  │                          │ │  │
│  │  │ Durasi Peminjaman:       │ │  │
│  │  │ [- 1 - +] hari (max 30)  │ │  │
│  │  │                          │ │  │
│  │  │ Tanggal Kembali:         │ │  │
│  │  │ Auto-calculated          │ │  │
│  │  │                          │ │  │
│  │  │ ⚠️ Denda Keterlambatan    │ │  │
│  │  │ Rp 5.000/hari            │ │  │
│  │  │                          │ │  │
│  │  │ [PINJAM] [BATAL]         │ │  │
│  │  └──────────────────────────┘ │  │
│  │                                │  │
│  └────────────────────────────────┘  │
│                                      │
└──────────────────────────────────────┘
```

### Components Detail

**Modal**:
- Max width: 500px
- Centered on screen
- Backdrop: Semi-transparent black
- Border radius: 8px
- Shadow: Heavy

**Date Picker**:
- HTML5 input type="date"
- Min: Today
- Calendar popup on click

**Duration Selector**:
- Input with +/- buttons
- Range: 1-30 days
- Buttons on sides
- Real-time due date update

**Alerts**:
- Fine warning: Yellow alert box
- Stock info: Informational text

---

# Part 4: Halaman Petugas

## 4.1 Petugas Dashboard

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │  
│  [Petugas] Dashboard | Verifikasi   │
└─────────────────────────────────────┘
│                                     │
│  GREETING                           │
│  "Selamat datang kembali, Budi!"    │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  STATS CARDS (4 columns)            │  Colorful icons
│  ┌─────┬──────┬──────┬──────┐      │
│  │Meng.│Aktif │Delay │Kemb.│      │
│  │  3  │  8   │  1   │ 12  │      │
│  │⏳   │✅    │⚠️    │↩️   │      │
│  └─────┴──────┴──────┴──────┘      │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  TABLE: "Peminjaman Menunggu        │
│          Persetujuan Anda"          │
│                                     │
│  ┌────┬──────┬────────┬────────┐   │
│  │User│Book  │Durasi  │Aksi    │   │
│  ├────┼──────┼────────┼────────┤   │
│  │Name│Title │7 hari  │[✅][❌]│   │
│  │    │Author│        │[Lihat] │   │
│  ├────┼──────┼────────┼────────┤   │
│  │...                         │   │
│  └────┴──────┴────────┴────────┘   │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  RECENT ACTIVITIES                  │  Last 5
│  Table: User | Book | Action | Time │
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Stats Cards**:
- Menunggu: Warning color (orange/yellow)
- Aktif: Success color (green)
- Terlambat: Danger color (red)
- Dikembalikan: Secondary color (gray)

**Approval Table**:
- Columns: User info, Book info, Duration, Actions
- Action buttons: Approve (green ✅), Reject (red ❌), View detail
- Rows: Hoverable, light background on hover

---

## 4.2 Petugas Borrowing Verification List

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │
│  "Verifikasi Peminjaman"            │
│  [Export CSV]                       │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  FILTER BAR                         │
│  Search: [input] | Status: [select] │
│  [Apply] [Reset]                    │
│                                     │
│  Status options:                    │
│  - Menunggu Persetujuan (pending)   │
│  - Menunggu Admin (pending_petugas) │
│  - Aktif (active)                   │
│  - Terlambat (overdue)              │
│  - Dikembalikan (returned)          │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  TABLE                              │  Scrollable
│  ┌──┬──────┬────┬───┬───┬───┬───┐  │
│  │ID│User  │Book│Dur│Tgl│St │Aksi│ │
│  ├──┼──────┼────┼───┼───┼───┼───┤  │
│  │#1│Andi  │Book│ 7d│1/1│⏳ │[👁][✅][❌]│
│  │#2│Budi  │Book│30d│1/5│✅ │[👁][☑️][↩️]│
│  │#3│Citra │Book│14d│12/│⚠️ │[👁][↩️]   │
│  │#4│Doni  │Book│21d│11/│↩️ │[👁][✏️]   │
│  └──┴──────┴────┴───┴───┴───┴───┘  │
│                                     │
│  Pagination                         │
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Status Badges & Actions**:
- Pending (⏳): "Lihat", "Setujui" (✅), "Tolak" (❌)
- Pending_Petugas (⏳): "Lihat" (read-only, waiting admin)
- Active (✅): "Lihat", "Konfirmasi" (☑️), "Verifikasi Return" (↩️)
- Overdue (⚠️): "Lihat", "Verifikasi Return" (↩️)
- Returned (↩️): "Lihat" (view detail only)

---

## 4.3 Petugas Borrowing Detail Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
│  [< Kembali]                        │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │
│  "Detail Peminjaman #4"             │
│                                     │
├──────────────┬──────────────────────┤
│              │                      │
│  PEMINJAMAN  │  PEMINJAM            │
│  ID: #4      │  Nama: Andi Wijaya  │
│  Status: ⏳  │  Email: andi@...     │
│              │  HP: 081...          │
│  Tgl Pinjam: │  Alamat: Jl. ...     │
│  1 Jan 2026  │                      │
│              │  BUKU                │
│  Durasi:     │  Cover image         │
│  7 hari      │  Judul: Why We Sleep│
│              │  Penulis: Matthew... │
│  Batas Kemb: │  Penerbit: Penguin  │
│  8 Jan 2026  │  Stok: 5             │
│              │                      │
│  QR Code:    │                      │
│  [QR Image]  │                      │
│              │                      │
├──────────────┴──────────────────────┤
│                                     │
│  ACTIONS                            │
│  Status: Menunggu Admin             │
│  (Admin belum menyetujui)           │
│                                     │
│  Tombol:                            │
│  [Lihat Detail Lengkap]             │
│  [Export to PDF]                    │
│                                     │
│  Atau (jika status active):         │
│  [✅ Konfirmasi Pengambilan]        │
│  [↩️ Verifikasi Pengembalian]       │
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Two-column Layout**:
- Left: Borrowing info + QR
- Right: User info + Book info

**Status Indicator**:
- Color-coded badge
- Explanation text below

**QR Code Display**:
- 200x200px
- Centered
- With instruction text: "Scan untuk verifikasi"

---

# Part 5: Halaman Admin

## 5.1 Admin Dashboard (Overview)

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
│  [Admin] Dashboard | Books | Borrow │
└─────────────────────────────────────┘
│                                     │
│  GREETING                           │
│  "Selamat datang, Admin!"           │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  STATS CARDS (5 columns)            │
│  ┌────┬────┬────┬────┬────┐        │
│  │Total│Users│Books│Borr│Overdue │
│  │ 150 │ 45 │ 320 │ 23 │   2    │
│  │📊  │👥  │📚  │📋  │⚠️  │       │
│  └────┴────┴────┴────┴────┘        │
│                                     │
├─────────────────────────────────────┤
│  CHARTS (if available)              │
│  ┌──────────────────────────────┐  │
│  │ Borrowing Trend (Last 30d)   │  │
│  │ [Line Chart]                 │  │
│  └──────────────────────────────┘  │
│                                     │
├─────────────────────────────────────┤
│  RECENT ACTIVITIES                  │
│  Table: Activity | User | Time      │
│                                     │
└─────────────────────────────────────┘
```

---

## 5.2 Admin Borrowing Management

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │
│  "Kelola Peminjaman"                │
│  [+ Tambah Peminjaman]              │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  FILTER BAR                         │
│  Search: [input]  | Status: [select]│
│  [Apply] [Reset]                    │
│                                     │
│  Status options:                    │
│  - Menunggu Petugas (pending)       │
│  - Menunggu Admin (pending_petugas) │  ← Focus for approval
│  - Aktif (active)                   │
│  - Terlambat (overdue)              │
│  - Dikembalikan (returned)          │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  TABLE                              │  Dark header
│  ┌───┬─────┬────┬──┬──┬──┬───┐    │
│  │No.│User │Book│Tg│JT│St│Aksi   │
│  ├───┼─────┼────┼──┼──┼──┼───┤    │
│  │ 1 │Andi │Book│1/│8/│⏳ │[👁][✅][❌]│
│  │   │     │    │1 │1 │  │       │
│  ├───┼─────┼────┼──┼──┼──┼───┤    │
│  │ 2 │Budi │Book│1/│8/│✅ │[👁][↩️]  │
│  │   │     │    │5 │5 │  │       │
│  ├───┼─────┼────┼──┼──┼──┼───┤    │
│  │ 3 │Citra│Book│12│15│⚠️ │[👁]   │
│  │   │     │    │/30│/│  │       │
│  └───┴─────┴────┴──┴──┴──┴───┘    │
│                                     │
│  Pagination                         │
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Admin-specific Features**:
- Can see all borrowings (not filtered by user)
- Filter by status includes `pending_petugas`
- Actions contextual to status
- Can create manual borrowing (+Tambah)

**Status & Actions** (Admin Specific):
- Pending (⏳): Read-only (waiting petugas), "Lihat"
- Pending_Petugas (⏳): "Lihat", "Setujui" (✅), "Tolak" (❌)
- Active (✅): "Lihat", "Edit", "Setujui Pengembalian" (↩️)
- Overdue (⚠️): "Lihat", "Edit", "Setujui Pengembalian" (↩️)
- Returned (↩️): "Lihat", "Edit"

---

## 5.3 Admin Borrowing Detail Page

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
│  [< Kembali]                        │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │
│  "Detail Peminjaman #4"             │  Dark header
│  [< Kembali]                        │
│                                     │
├──────────────┬──────────────────────┤
│              │                      │
│  PEMINJAM    │  BUKU                │
│  ┌─────────┐ │  ┌────────────────┐ │
│  │Nama     │ │  │Cover Image     │ │
│  │Email    │ │  │Judul, Penulis  │ │
│  │HP       │ │  │Penerbit, ISBN  │ │
│  │Alamat   │ │  │Kategori        │ │
│  │         │ │  │Stok            │ │
│  └─────────┘ │  └────────────────┘ │
│              │                      │
│  PEMINJAMAN  │  AKSI               │
│  ┌─────────┐ │  ┌──────────────┐   │
│  │Tgl Pjm │ │  │Status:       │   │
│  │Durasi  │ │  │Pending Admin │   │
│  │Tgl Kmbli│ │  │              │   │
│  │Denda   │ │  │[✅ Setujui]   │   │
│  │Catatan │ │  │[❌ Tolak]     │   │
│  │        │ │  │[✏️ Edit]      │   │
│  │        │ │  │[🗑️ Hapus]     │   │
│  │        │ │  │[< Kembali]    │   │
│  └─────────┘ │  └──────────────┘   │
│              │                      │
└──────────────┴──────────────────────┘
```

### Components Detail

**Three-Section Layout**:
- Left: Borrower info
- Center: Book info
- Right: Borrowing details + Actions

**Admin Actions** (Contextual):
- Pending_Petugas: Approve, Reject, Edit, Delete
- Active: Edit, Approve Return, Delete
- Returned: Edit, Delete
- Overdue: Edit, Approve Return, Delete

---

## 5.4 Admin Books Management

### Layout
```
┌─────────────────────────────────────┐
│         NAVBAR (sticky)             │
└─────────────────────────────────────┘
│                                     │
│  HEADER                             │
│  "Kelola Buku"                      │
│  [+ Tambah Buku]                    │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  FILTER BAR                         │
│  Search: [input] | Category: [sel]  │
│  Status: [Active/Inactive]          │
│                                     │
├─────────────────────────────────────┤
│                                     │
│  TABLE                              │
│  ┌───┬────┬──────┬────┬──┬──┬───┐  │
│  │No.│Cov │Judul │Krt │Sk│St│Aksi│ │
│  ├───┼────┼──────┼────┼──┼──┼───┤  │
│  │1  │[Img│Title │Fik │5 │✅│[✏️][👁][❌]│
│  │   │]   │      │    │  │  │   │
│  ├───┼────┼──────┼────┼──┼──┼───┤  │
│  │2  │[Img│Title │NF  │0 │⚠️│[✏️][👁][❌]│
│  │   │]   │      │    │  │  │   │
│  └───┴────┴──────┴────┴──┴──┴───┘  │
│                                     │
│  [Pagination]                       │
│                                     │
└─────────────────────────────────────┘
```

### Components Detail

**Book Cards/List**:
- Thumbnail preview
- Title, Category
- Stock count (0 = red warning)
- Status indicator (active/inactive)
- Actions: Edit, View, Delete

---

# Part 6: Navbar & Global Components

## 6.1 Navigation Bar (All Pages)

### Layout (Desktop)
```
┌─────────────────────────────────────┐
│ 📚 RetroLib | Katalog | Peminjaman  │ Dashboard │ Admin │ Verifikasi │ User ▼ │ Logout │
└─────────────────────────────────────┘
Background: Gradient #6B3410 → #8B4513 → #A0522D
Height: 60px
```

### Navbar Components

**Logo**:
- Icon + Text: "📚 RetroLib"
- Font: Merriweather, bold, white
- Size: 20px
- Hover: Gold (#FFE4B5)

**Nav Links**:
- Font: Open Sans, 600, white, 14px
- Text shadow: 0.5px 0.5px 1px rgba(0,0,0,0.3)
- Hover: Gold (#FFE4B5)
- Active: Bold with underline

**Dropdown (User Menu)**:
- Avatar/Name on click
- Options: Profile, Settings, Logout
- Dark background: #2C1810

**Mobile**:
- Hamburger menu (3 lines)
- Overlay menu on click
- Full-width options

---

## 6.2 Footer

### Layout
```
┌─────────────────────────────────────┐
│  FOOTER                             │  Background: #2C1810
├─────────────────────────────────────┤
│  © 2026 RetroLib. All rights.       │
│  About | Contact | Privacy | Terms  │
│                                     │
│  Follow: 📘 🐦 📷 🎬               │
│                                     │
└─────────────────────────────────────┘
```

---

## 6.3 Global Components

### Buttons

```
PRIMARY BUTTON:
- Background: #8B4513
- Text: White, bold
- Padding: 10px 24px
- Height: 44px
- Radius: 4px
- Hover: #6B3410, shadow
- Font: Open Sans, 14px

SECONDARY BUTTON:
- Background: #f8f9fa
- Text: #8B4513, bold
- Border: 1px #8B4513
- Padding: 10px 24px
- Hover: #f0f0f0

DANGER BUTTON:
- Background: #dc3545
- Text: White
- Hover: #c82333

SUCCESS BUTTON:
- Background: #28a745
- Text: White
- Hover: #218838
```

### Form Inputs

```
TEXT INPUT:
- Width: 100%
- Height: 44px
- Border: 1px #ddd
- Radius: 4px
- Padding: 12px 16px
- Font: Open Sans, 14px
- Focus: Border #8B4513, shadow
- Placeholder: Gray, italic

TEXTAREA:
- Padding: 12px 16px
- Min-height: 120px
- Resize: Vertical

SELECT:
- Height: 44px
- Padding: 12px 16px
- Border: 1px #ddd
- Appearance: Standard dropdown
```

### Alerts & Messages

```
SUCCESS ALERT:
- Background: #d4edda
- Border: 1px #c3e6cb
- Text: #155724
- Icon: ✓ green

ERROR ALERT:
- Background: #f8d7da
- Border: 1px #f5c6cb
- Text: #721c24
- Icon: ✗ red

WARNING ALERT:
- Background: #fff3cd
- Border: 1px #ffeaa7
- Text: #856404
- Icon: ⚠️ orange

INFO ALERT:
- Background: #d1ecf1
- Border: 1px #bee5eb
- Text: #0c5460
- Icon: ℹ️ blue
```

### Modals/Dialogs

```
MODAL:
- Max-width: 500px
- Background: White
- Border-radius: 8px
- Box-shadow: 0 4px 15px rgba(0,0,0,0.3)
- Padding: 24px
- Header: Bold title, close button (X)
- Footer: Action buttons

BACKDROP:
- Background: rgba(0,0,0,0.5)
- Full screen
- Click to close (optional)
```

### Cards

```
CARD:
- Background: White
- Border: None
- Border-radius: 4-8px
- Box-shadow: 0 2px 8px rgba(0,0,0,0.1)
- Hover: Shadow increase (0 4px 12px)
- Padding: 16-24px

CARD HEADER:
- Background: Slightly darker (#f8f9fa) or colored
- Border-bottom: 1px #ddd
- Padding: 16px
- Font-weight: 600
```

### Badges & Badges

```
BADGE STYLES:
- Primary (brown): #8B4513 on white
- Success (green): #28a745 on white
- Danger (red): #dc3545 on white
- Warning (yellow): #ffc107 on dark
- Info (blue): #17a2b8 on white
- Secondary: #6c757d on white

- Border-radius: 12px
- Padding: 4px 8px
- Font: 12px, semi-bold
- Display: Inline-block
```

---

# Part 7: Responsive Design

## Breakpoints
- **Mobile**: < 576px
- **Tablet**: 576px - 768px
- **Desktop**: 768px - 1024px
- **Large**: > 1024px

## Responsive Behavior

### Navigation
- Desktop: Full navbar with all links
- Tablet: Condensed navbar
- Mobile: Hamburger menu

### Grids
- Desktop: 4 columns (books), 3 columns (cards)
- Tablet: 3 columns (books), 2 columns (cards)
- Mobile: 2 columns (books), 1 column (cards), Stack vertically

### Tables
- Desktop: Full table
- Tablet: Scrollable horizontal
- Mobile: Card view instead of table

### Forms
- Width: 100% on mobile, max-width 400px on desktop
- Labels: Above input on all sizes
- Buttons: Full width on mobile, auto on desktop

---

# Part 8: Figma Design Notes

## Naming Convention for Figma

```
Components:
- Button/Primary
- Button/Secondary
- Button/Danger
- Input/Text
- Input/Textarea
- Alert/Success
- Alert/Error
- Card/Default
- Badge/Primary
- Navbar
- Footer
- Modal/Default

Frames:
- Landing Page
- Auth/Login
- Auth/Register
- User/Dashboard
- User/Borrowing History
- User/Wishlist
- Petugas/Dashboard
- Petugas/Verification List
- Petugas/Borrowing Detail
- Admin/Dashboard
- Admin/Borrowing List
- Admin/Borrowing Detail
- Admin/Books
```

## Figma File Structure

```
📁 RetroLib Design System
├── 📁 Colors
├── 📁 Typography
├── 📁 Components
│  ├── Buttons
│  ├── Forms
│  ├── Alerts
│  ├── Cards
│  └── Navigation
├── 📁 Pages
│  ├── Publik (Landing, Auth, Catalog)
│  ├── User (Dashboard, History, Wishlist)
│  ├── Petugas (Dashboard, Verification, Detail)
│  └── Admin (Dashboard, Management, Detail)
└── 📁 Assets
   ├── Icons
   ├── Illustrations
   └── Logos
```

## Export Assets for Development

- Buttons: 44px height (accessibility)
- Icons: 16px, 24px, 48px variants
- Spacing: 4px, 8px, 16px, 24px grid
- Colors: Export as CSS variables
- Typography: Export font sizes & weights

---

# Summary Table

| Halaman | Role | Fitur Utama | Status Badge |
|---------|------|-------------|--------------|
| Landing | Guest | Browse, Search, Filter | ✅ |
| Catalog | Guest | Grid, Filter, Detail | ✅ |
| Login | Guest | Email/Password auth | ✅ |
| Register | Guest | Form, Validation | ✅ |
| Dashboard | User | Stats, Active borrowing, Recommendations | ✅ |
| History | User | Filter, Search, Status tracking | ✅ |
| Wishlist | User | Grid, Add/Remove | ✅ |
| Modal Pinjam | User | Date picker, Duration selector | ✅ |
| Dashboard | Petugas | Stats, Approval queue, Activities | ✅ |
| Verification List | Petugas | Filter, Action buttons, Approve/Reject | ✅ |
| Detail | Petugas | Full info, Contextual actions, QR code | ✅ |
| Dashboard | Admin | Stats, Charts, Activities | ✅ |
| Borrowing Mgmt | Admin | Filter, Approval from admin, CRUD | ✅ |
| Detail | Admin | Dual approval, Edit, Delete | ✅ |
| Books Mgmt | Admin | CRUD, Status, Stock management | ✅ |

---

**Design completed for RetroLib Application** 🎨✨
