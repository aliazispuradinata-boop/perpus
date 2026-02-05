@extends('layouts.app')

@section('title', 'Bukti Peminjaman - ' . $borrowing->book->title)

@section('content')
<div data-borrowing-status="{{ $borrowing->status }}"></div>

<!-- Modal Notifikasi Tunggu Konfirmasi Petugas -->
@if($borrowing->status === 'pending')
<div class="modal fade" id="pendingConfirmationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(139, 69, 19, 0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; border: none; padding: 2rem;">
                <div style="text-align: center; width: 100%;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem; animation: bounce 1s infinite;">⏳</div>
                    <h5 class="modal-title" style="font-size: 1.3rem; font-weight: 700; margin: 0;">
                        Menunggu Konfirmasi Petugas
                    </h5>
                </div>
            </div>
            <div class="modal-body text-center" style="padding: 2.5rem;">
                <div class="mb-4">
                    <p style="color: #2C1810; font-size: 1rem; margin-bottom: 1rem;">
                        <strong>Permintaan peminjaman Anda telah berhasil dikirim!</strong>
                    </p>
                    <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">
                        Permintaan peminjaman buku <strong>"{{ $borrowing->book->title }}"</strong> sedang diproses oleh petugas perpustakaan. 
                        Anda akan menerima notifikasi segera setelah petugas mengkonfirmasi permintaan Anda.
                    </p>
                </div>

                <div style="background: #FFF8DC; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 2px solid #E8D5C4;">
                    <div style="color: #8B4513; font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">
                        KODE PEMINJAMAN
                    </div>
                    <div style="color: #2C1810; font-size: 2rem; font-weight: 700; font-family: monospace; letter-spacing: 3px;">
                        #{{ str_pad($borrowing->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </div>

                <div style="background: #E8F4F8; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: left;">
                    <strong style="color: #2C1810;">📋 Informasi Peminjaman:</strong>
                    <div style="color: #666; font-size: 0.9rem; margin-top: 0.5rem; line-height: 1.8;">
                        <div>Buku: <strong>{{ $borrowing->book->title }}</strong></div>
                        <div>Durasi: <strong>{{ $borrowing->duration_days }} hari</strong></div>
                        <div>Tgl Peminjaman: <strong>{{ optional($borrowing->borrowed_at)->format('d M Y') ?? 'Belum dikonfirmasi' }}</strong></div>
                        <div>Tgl Kembali: <strong>{{ $borrowing->due_date->format('d M Y') }}</strong></div>
                    </div>
                </div>

                <div class="alert alert-info" style="background-color: #d1ecf1; border-color: #0c5460; color: #0c5460; margin-bottom: 0;">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Tips:</strong> Cek notifikasi Anda secara berkala untuk update status peminjaman.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #E8D5C4; padding: 1.5rem; gap: 0.75rem;">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="location.reload();">
                    <i class="fas fa-sync-alt"></i> Cek Status
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); border: none;">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pendingModal = new bootstrap.Modal(document.getElementById('pendingConfirmationModal'), {
            backdrop: 'static',
            keyboard: false
        });
        pendingModal.show();

        // Auto-refresh halaman setiap 5 detik untuk check status update
        const autoRefreshInterval = setInterval(function() {
            fetch(window.location.href, {
                method: 'GET',
                headers: {
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse response untuk check status
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newStatus = doc.querySelector('[data-borrowing-status]')?.getAttribute('data-borrowing-status');
                const currentStatus = document.querySelector('[data-borrowing-status]')?.getAttribute('data-borrowing-status');
                
                // Jika status berubah, refresh page
                if (newStatus && newStatus !== currentStatus && newStatus !== 'pending') {
                    location.reload();
                }
            })
            .catch(error => console.log('Auto-refresh check failed:', error));
        }, 5000); // Check setiap 5 detik

        // Clear interval setelah 5 menit (untuk hemat resource)
        setTimeout(() => {
            clearInterval(autoRefreshInterval);
        }, 5 * 60 * 1000);
    });
</script>
@endif

@php
    $showSuccessModal = ($borrowing->status === 'active' && $borrowing->confirmed_at);
@endphp

@if($showSuccessModal)
<!-- Success Modal (shown after admin + petugas confirmation) -->
<div class="modal fade" id="successBorrowingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(139, 69, 19, 0.3);">
            <div class="modal-body p-5" style="background: linear-gradient(135deg, #FFFDF9 0%, #FFF8F2 100%);">
                <div class="text-center mb-4">
                    <div style="width:80px;height:80px;border-radius:50%;background:#fff;padding:18px;margin:0 auto;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                        <div style="font-size:32px;color:#D2691E;">✓</div>
                    </div>
                    <h2 style="margin-top:12px;color:#2C1810;font-weight:700">Peminjaman Berhasil!</h2>
                    <p style="color:#666">Pesanan buku Anda telah kami terima. Silakan ambil di konter utama dan tunjukkan bukti ini kepada petugas.</p>
                </div>

                <div class="row gx-4">
                    <div class="col-md-5">
                        <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.06);text-align:center;">
                            @if($borrowing->qr_code)
                                <img src="{{ asset('storage/' . $borrowing->qr_code) }}" alt="QR Code" style="max-width:220px; width:100%; border-radius:6px; border:2px solid #D2691E; padding:10px; background:#fff;">
                            @else
                                <div style="width:220px;height:220px;margin:0 auto;background:#f6f6f6;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#999;">QR</div>
                            @endif
                            <div style="margin-top:14px;color:#8B4513;font-weight:700;">Tunjukkan QR ini ke petugas</div>
                            <div style="margin-top:8px;color:#2C1810;font-family:monospace">ORDER ID: #{{ str_pad($borrowing->id,6,'0',STR_PAD_LEFT) }}</div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div style="background:#fff;padding:18px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.06);">
                            <h5 style="color:#2C1810;font-weight:700">Berikan Rating & Ulasan</h5>
                            <p style="color:#666">Bagaimana kesan Anda terhadap buku yang Anda pinjam?</p>

                            <form method="POST" action="{{ route('books.reviews.store', $borrowing->book) }}">
                                @csrf
                                <input type="hidden" name="rating" id="modal_rating" value="5">
                                <div class="mb-2">
                                    <div id="starWrapper" style="font-size:1.25rem;color:#F4A460;">
                                        <button type="button" class="btn btn-link p-0 me-1 star-btn" data-value="1">☆</button>
                                        <button type="button" class="btn btn-link p-0 me-1 star-btn" data-value="2">☆</button>
                                        <button type="button" class="btn btn-link p-0 me-1 star-btn" data-value="3">☆</button>
                                        <button type="button" class="btn btn-link p-0 me-1 star-btn" data-value="4">☆</button>
                                        <button type="button" class="btn btn-link p-0 me-1 star-btn" data-value="5">☆</button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <textarea name="content" class="form-control" rows="4" placeholder="Tulis komentar Anda tentang buku ini..."></textarea>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                                    <button type="submit" class="btn" style="background:#D2691E;color:#fff;border:none;">Simpan Ulasan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successModal = new bootstrap.Modal(document.getElementById('successBorrowingModal'), {
            backdrop: 'static', keyboard: false
        });
        successModal.show();

        // Star rating buttons
        document.querySelectorAll('#starWrapper .star-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const v = this.getAttribute('data-value');
                document.getElementById('modal_rating').value = v;
                // highlight
                document.querySelectorAll('#starWrapper .star-btn').forEach(s => s.textContent = '☆');
                for (let i=0;i<v;i++) document.querySelectorAll('#starWrapper .star-btn')[i].textContent = '★';
            });
        });
    });
</script>

@endif

<div style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; padding: 3rem 2rem; margin: -2rem -2rem 2rem -2rem; border-radius: 0 0 12px 12px;">
    <div class="container-fluid">
        <h1 style="font-size: 2rem; font-weight: 700; margin: 0;">
            <i class="fas fa-receipt"></i> Bukti Peminjaman Buku
        </h1>
        <p class="mb-0" style="opacity: 0.9;">Status: 
            @if($borrowing->status === 'pending')
                <span class="badge bg-warning text-dark">Menunggu Konfirmasi Petugas</span>
            @elseif($borrowing->status === 'pending_petugas')
                <span class="badge bg-info">Menunggu Konfirmasi Admin</span>
            @elseif($borrowing->status === 'active')
                <span class="badge bg-success">Sedang Dipinjam</span>
            @elseif($borrowing->status === 'returned')
                <span class="badge bg-secondary">Sudah Dikembalikan</span>
            @elseif($borrowing->status === 'overdue')
                <span class="badge bg-danger">Terlambat</span>
            @endif
        </p>
    </div>
</div>

<div class="container-fluid mb-5">
    <div class="row">
        <!-- Main Proof Card -->
        <div class="col-lg-8">
            <div class="card" style="border: none; box-shadow: 0 4px 15px rgba(139, 69, 19, 0.15); border-top: 4px solid #8B4513;">
                <div class="card-body p-4">
                    <!-- Header -->
                    <div style="text-align: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px solid #E8D5C4;">
                        <div style="font-size: 2.5rem; color: #8B4513; margin-bottom: 0.5rem;">📚 RetroLib</div>
                        <div style="color: #D2691E; font-size: 0.9rem; font-style: italic;">Perpustakaan Digital Retro-Modern</div>
                    </div>

                    <!-- Kode Peminjaman -->
                    <div style="background: #FFF8DC; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center;">
                        <div style="color: #8B4513; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem;">KODE PEMINJAMAN</div>
                        <div style="color: #2C1810; font-size: 1.8rem; font-weight: 700; font-family: monospace; letter-spacing: 2px;">
                            #{{ str_pad($borrowing->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>

                    <!-- Status Message -->
                    @if($borrowing->status === 'pending')
                        <div class="alert alert-warning" style="background-color: #fff3cd; border-color: #ffc107; color: #856404;">
                            <i class="fas fa-hourglass-half"></i>
                            <strong>Sedang Diproses</strong><br>
                            <small>Permintaan peminjaman Anda sedang menunggu konfirmasi dari petugas perpustakaan. Anda akan menerima notifikasi setelah petugas mengkonfirmasi peminjaman ini.</small>
                        </div>
                    @elseif($borrowing->status === 'active')
                        <div class="alert alert-success" style="background-color: #d4edda; border-color: #28a745; color: #155724;">
                            <i class="fas fa-check-circle"></i>
                            <strong>Disetujui</strong><br>
                            <small>Peminjaman Anda telah disetujui. Silakan tunjukkan QR code di bawah kepada petugas untuk mengambil buku.</small>
                        </div>
                    @endif

                    <!-- QR Code Section -->
                    @if($borrowing->qr_code)
                        <div style="text-align: center; margin: 2rem 0; padding: 1.5rem; background: #F5F5F5; border-radius: 8px;">
                            <div style="color: #8B4513; font-weight: 600; margin-bottom: 1rem;">
                                <i class="fas fa-qrcode"></i> PINDAI QR CODE INI
                            </div>
                            <img src="{{ asset('storage/' . $borrowing->qr_code) }}" alt="QR Code" style="max-width: 250px; width: 100%; border: 3px solid #D2691E; border-radius: 8px; padding: 10px; background: white;">
                            <div style="color: #666; font-size: 0.9rem; margin-top: 1rem;">
                                Tunjukkan QR code ini kepada petugas perpustakaan
                            </div>
                        </div>
                    @endif

                    <!-- Book Information -->
                    <div style="margin: 2rem 0;">
                        <div style="color: #8B4513; font-weight: 700; font-size: 1.1rem; margin-bottom: 1rem;">
                            📖 INFORMASI BUKU
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                @if($borrowing->book->cover_image)
                                    <img src="{{ $borrowing->book->cover_url }}" alt="Cover" style="max-width: 100%; max-height: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                @else
                                    <div style="width: 100%; aspect-ratio: 2/3; background: #E8D5C4; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-book fa-3x" style="color: #8B4513;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <div class="mb-2">
                                    <div style="color: #666; font-size: 0.85rem;">Judul Buku</div>
                                    <div style="color: #2C1810; font-weight: 600; font-size: 1.2rem;">{{ $borrowing->book->title }}</div>
                                </div>
                                <div class="mb-2">
                                    <div style="color: #666; font-size: 0.85rem;">Penulis</div>
                                    <div style="color: #2C1810; font-weight: 500;">{{ $borrowing->book->author }}</div>
                                </div>
                                <div class="mb-2">
                                    <div style="color: #666; font-size: 0.85rem;">Penerbit</div>
                                    <div style="color: #2C1810; font-weight: 500;">{{ $borrowing->book->publisher ?? '-' }}</div>
                                </div>
                                <div class="mb-2">
                                    <div style="color: #666; font-size: 0.85rem;">Kategori</div>
                                    <span class="badge" style="background-color: #8B4513;">{{ $borrowing->book->category->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Borrowing Details -->
                    <div style="background: #FFF8DC; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0;">
                        <div style="color: #8B4513; font-weight: 700; margin-bottom: 1rem;">
                            📋 DETAIL PEMINJAMAN
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div style="color: #666; font-size: 0.85rem; margin-bottom: 0.3rem;">Tanggal Peminjaman</div>
                                <div style="color: #2C1810; font-weight: 600; font-size: 1.1rem;">
                                    {{ optional($borrowing->borrowed_at)->format('d F Y') ?? 'Belum dikonfirmasi' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div style="color: #666; font-size: 0.85rem; margin-bottom: 0.3rem;">Tanggal Harus Dikembalikan</div>
                                <div style="color: #2C1810; font-weight: 600; font-size: 1.1rem;">
                                    {{ optional($borrowing->due_date)->format('d F Y') }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div style="color: #666; font-size: 0.85rem; margin-bottom: 0.3rem;">Durasi Peminjaman</div>
                                <div style="color: #2C1810; font-weight: 600; font-size: 1.1rem;">
                                    {{ $borrowing->duration_days ?? '-' }} hari
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div style="color: #666; font-size: 0.85rem; margin-bottom: 0.3rem;">Denda Keterlambatan</div>
                                <div style="color: #d9534f; font-weight: 600; font-size: 1.1rem;">
                                    Rp 5.000 / hari
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div style="margin: 1.5rem 0;">
                        <div style="color: #8B4513; font-weight: 700; margin-bottom: 1rem;">
                            👤 INFORMASI PEMINJAM
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div style="color: #666; font-size: 0.85rem;">Nama Anggota</div>
                                <div style="color: #2C1810; font-weight: 600;">{{ $borrowing->user->name }}</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div style="color: #666; font-size: 0.85rem;">Email</div>
                                <div style="color: #2C1810; font-weight: 600;">{{ $borrowing->user->email }}</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div style="color: #666; font-size: 0.85rem;">Telepon</div>
                                <div style="color: #2C1810; font-weight: 600;">{{ $borrowing->user->phone ?? '-' }}</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div style="color: #666; font-size: 0.85rem;">Alamat</div>
                                <div style="color: #2C1810; font-weight: 600;">{{ Str::limit($borrowing->user->address ?? '-', 40) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #E8D5C4; text-align: center; color: #666; font-size: 0.9rem;">
                        <p class="mb-1">Dokumen ini dicetak pada {{ now()->format('d F Y H:i:s') }}</p>
                        <p class="mb-0" style="font-size: 0.85rem;">Harap jaga baik-baik dokumen ini sebagai bukti peminjaman Anda.</p>
                        <p class="mb-0" style="font-size: 0.85rem; color: #999;">RetroLib © 2026 - Semua Hak Dilindungi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card mb-3" style="border: none; box-shadow: 0 4px 15px rgba(139, 69, 19, 0.15); border-top: 4px solid #8B4513;">
                <div class="card-body">
                    <h5 class="card-title" style="color: #8B4513; font-weight: 700;">
                        <i class="fas fa-tasks"></i> Aksi
                    </h5>
                    
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('borrowings.history') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Lihat Riwayat Peminjaman
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-secondary">
                            <i class="fas fa-print"></i> Cetak Bukti
                        </button>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-book"></i> Jelajahi Buku
                        </a>
                    </div>
                </div>
            </div>

            <!-- Review Section -->
            <div class="card" style="border: none; box-shadow: 0 4px 15px rgba(139, 69, 19, 0.15); border-top: 4px solid #D2691E; margin-top: 2rem;">
                <div class="card-body p-4">
                    <h5 style="color: #8B4513; font-weight: 700; margin-bottom: 1.5rem;">
                        <i class="fas fa-star"></i> Berikan Rating & Ulasan Anda
                    </h5>

                    @if($userReview)
                        <!-- Review yang sudah ada -->
                        <div class="alert alert-info" style="background: #d1ecf1; border-color: #0c5460; color: #0c5460;">
                            <i class="fas fa-check-circle"></i> Anda sudah memberikan ulasan untuk buku ini.
                        </div>

                        <div class="card mb-3" style="background: #FFF8F2; border: 1px solid #E8D5C4;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">{{ $userReview->title ?? 'Tanpa Judul' }}</h6>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star"></i> {{ $userReview->rating }}/5
                                    </span>
                                </div>
                                <p class="card-text text-muted mb-2">{{ $userReview->content }}</p>
                                <small class="text-muted">
                                    Dikirim {{ $userReview->created_at->diffForHumans() }}
                                    @if(!$userReview->is_published)
                                        <span class="badge bg-secondary ms-2">Menunggu Moderasi</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                    @else
                        <!-- Form baru untuk review -->
                        <p style="color: #666; margin-bottom: 1.5rem;">Bagaimana kesan Anda terhadap buku yang Anda pinjam? Rating dan ulasan Anda membantu pembaca lain menemukan buku yang tepat.</p>

                        <form method="POST" action="{{ route('books.reviews.store', $borrowing->book) }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label" style="color: #8B4513; font-weight: 600;">Rating <span class="text-danger">*</span></label>
                                <div id="ratingStars" style="font-size: 2rem; color: #F4A460;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" class="btn btn-link p-0 me-2 star-btn" data-value="{{ $i }}" style="color: #F4A460; text-decoration: none; font-size: 2rem;">☆</button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingValue" value="5" required>
                                <small style="color: #666; display: block; margin-top: 0.5rem;">Klik bintang untuk memberi rating</small>
                            </div>

                            <div class="mb-3">
                                <label for="reviewTitle" class="form-label" style="color: #8B4513; font-weight: 600;">Judul Ulasan (opsional)</label>
                                <input type="text" class="form-control" id="reviewTitle" name="title" placeholder="Contoh: Buku yang sangat menginspirasi" maxlength="255">
                            </div>

                            <div class="mb-3">
                                <label for="reviewContent" class="form-label" style="color: #8B4513; font-weight: 600;">Ulasan <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="reviewContent" name="content" rows="5" placeholder="Ceritakan pengalaman Anda membaca buku ini..." required></textarea>
                                <small style="color: #999;">Minimal 10 karakter</small>
                            </div>

                            <div class="d-flex justify-content-between gap-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Lewati
                                </a>
                                <button type="submit" class="btn" style="background: #D2691E; color: white; border: none;">
                                    <i class="fas fa-paper-plane"></i> Kirim Ulasan
                                </button>
                            </div>

                            <div class="alert alert-info mt-3" style="background: #e7f3ff; border-color: #b3d9ff; color: #004085; font-size: 0.9rem;">
                                <i class="fas fa-info-circle"></i> Ulasan Anda akan dikirim ke admin untuk moderasi sebelum ditampilkan ke pengunjung lain.
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="card" style="background: linear-gradient(135deg, rgba(210, 105, 30, 0.1) 0%, rgba(244, 164, 96, 0.1) 100%); border: 2px solid #E8D5C4; margin-top: 2rem;">
                <div class="card-body">
                    <h5 class="card-title" style="color: #8B4513; font-weight: 700;">
                        <i class="fas fa-info-circle"></i> Informasi Penting
                    </h5>
                    <ul class="list-unstyled" style="font-size: 0.9rem;">
                        <li class="mb-2">
                            <strong style="color: #8B4513;">✓ Tunjukkan QR Code</strong><br>
                            <small style="color: #666;">Tunjukkan QR code ini kepada petugas untuk verifikasi peminjaman.</small>
                        </li>
                        <li class="mb-2">
                            <strong style="color: #8B4513;">✓ Simpan Dokumen Ini</strong><br>
                            <small style="color: #666;">Simpan atau cetak dokumen ini sebagai bukti peminjaman.</small>
                        </li>
                        <li class="mb-2">
                            <strong style="color: #8B4513;">✓ Kembalikan Tepat Waktu</strong><br>
                            <small style="color: #666;">Kembalikan buku sebelum tanggal jatuh tempo untuk menghindari denda.</small>
                        </li>
                        <li>
                            <strong style="color: #8B4513;">✓ Denda Keterlambatan</strong><br>
                            <small style="color: #666;">Rp 5.000 per hari untuk setiap hari keterlambatan.</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('#ratingStars .star-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const value = this.getAttribute('data-value');
            document.getElementById('ratingValue').value = value;
            
            // Update star display
            document.querySelectorAll('#ratingStars .star-btn').forEach((b, idx) => {
                b.textContent = idx < value ? '★' : '☆';
            });
        });
    });

    // Initialize stars (set 5 as default)
    window.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#ratingStars .star-btn').forEach((b, idx) => {
            b.textContent = idx < 5 ? '★' : '☆';
        });
    });
</script>

@endsection
