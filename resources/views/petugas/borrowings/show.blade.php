@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="page-title">
                <i class="fas fa-file-alt"></i> Detail Peminjaman
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('petugas.borrowings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Borrowing Status -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-book"></i> Informasi Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">ID Peminjaman</label>
                            <p class="h6"><code>#{{ $borrowing->id }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Status</label>
                            <p>
                                @if($borrowing->status === 'pending')
                                    <span class="badge bg-warning" style="font-size: 0.9rem;">
                                        <i class="fas fa-clock"></i> Menunggu Persetujuan
                                    </span>
                                @elseif($borrowing->status === 'active')
                                    <span class="badge bg-success" style="font-size: 0.9rem;">
                                        <i class="fas fa-check"></i> Aktif
                                    </span>
                                @elseif($borrowing->status === 'returned')
                                    <span class="badge bg-secondary" style="font-size: 0.9rem;">
                                        <i class="fas fa-undo"></i> Dikembalikan
                                    </span>
                                @elseif($borrowing->status === 'overdue')
                                    <span class="badge bg-danger" style="font-size: 0.9rem;">
                                        <i class="fas fa-exclamation"></i> Terlambat
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tanggal Peminjaman</label>
                            <p class="h6">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d F Y') : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Durasi Peminjaman</label>
                            <p class="h6">{{ $borrowing->duration_days ?? '-' }} hari</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Batas Pengembalian</label>
                            <p class="h6">{{ $borrowing->due_date ? $borrowing->due_date->format('d F Y') : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Sisa Hari</label>
                            <p class="h6">
                                @if($borrowing->status === 'returned')
                                    Sudah dikembalikan
                                @elseif($borrowing->due_date->isPast())
                                    <span class="text-danger">Terlambat {{ $borrowing->due_date->diffInDays(now()) }} hari</span>
                                @else
                                    <span class="text-success">{{ $borrowing->due_date->diffInDays(now()) }} hari</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($borrowing->returned_at)
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Tanggal Pengembalian</label>
                                <p class="h6">{{ $borrowing->returned_at->format('d F Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Denda (jika ada)</label>
                                <p class="h6">{{ $borrowing->fine_notes ?? '-' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Book Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-book-open"></i> Informasi Buku
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            @if($borrowing->book->cover_image)
                                <img src="{{ $borrowing->book->cover_url }}" 
                                     class="img-fluid rounded" style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-book" style="font-size: 3rem; color: #ccc;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h5>{{ $borrowing->book->title }}</h5>
                            <p class="text-muted mb-2">
                                <strong>Penulis:</strong> {{ $borrowing->book->author ?? 'Tidak diketahui' }}
                            </p>
                            <p class="text-muted mb-2">
                                <strong>Penerbit:</strong> {{ $borrowing->book->publisher ?? 'Tidak diketahui' }}
                            </p>
                            <p class="text-muted mb-2">
                                <strong>Kategori:</strong> {{ $borrowing->book->category->name ?? '-' }}
                            </p>
                            <p class="text-muted mb-2">
                                <strong>ISBN:</strong> {{ $borrowing->book->isbn ?? '-' }}
                            </p>
                            <p class="text-muted">
                                <strong>Stok Tersedia:</strong> {{ $borrowing->book->stock }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Information & Actions -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> Data Peminjam
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">Nama</p>
                        <h6>{{ $borrowing->user->name }}</h6>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Email</p>
                        <h6>{{ $borrowing->user->email }}</h6>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">No. Telepon</p>
                        <h6>{{ $borrowing->user->phone ?? '-' }}</h6>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Alamat</p>
                        <h6>{{ $borrowing->user->address ?? '-' }}</h6>
                    </div>
                </div>
            </div>

            <!-- QR Code -->
            @if($borrowing->qr_code)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-qrcode"></i> QR Code
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset($borrowing->qr_code) }}" class="img-fluid" style="max-width: 200px;">
                        <p class="text-muted mt-2" style="font-size: 0.85rem;">
                            Scan QR code untuk verifikasi
                        </p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks"></i> Aksi
                    </h5>
                </div>
                <div class="card-body">
                    @if($borrowing->status === 'pending')
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i>
                            <strong>Menunggu Persetujuan</strong><br>
                            <small>Admin perlu menyetujui peminjaman ini sebelum pengguna dapat memegangnya.</small>
                        </div>
                    @elseif($borrowing->status === 'active' && !$borrowing->confirmed_at)
                        <form action="{{ route('petugas.borrowings.confirm', $borrowing) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2" 
                                    onclick="return confirm('Konfirmasi pengambilan buku oleh user?')">
                                <i class="fas fa-check-circle"></i> Konfirmasi Pengambilan
                            </button>
                        </form>
                        <small class="text-muted">
                            Klik tombol ini ketika user telah mengambil bukunya.
                        </small>
                    @elseif($borrowing->status === 'active' && $borrowing->confirmed_at)
                        <div class="alert alert-success mb-3" role="alert">
                            <i class="fas fa-check-circle"></i> Pengambilan Dikonfirmasi<br>
                            <small>{{ $borrowing->confirmed_at->format('d F Y H:i') }}</small>
                        </div>
                        <form action="{{ route('petugas.borrowings.verify-return', $borrowing) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info w-100"
                                    onclick="return confirm('Verifikasi pengembalian buku?')">
                                <i class="fas fa-undo-alt"></i> Verifikasi Pengembalian
                            </button>
                        </form>
                        <small class="text-muted d-block mt-2">
                            Klik tombol ini ketika user mengembalikan bukunya.
                        </small>
                    @elseif($borrowing->status === 'returned')
                        <div class="alert alert-secondary" role="alert">
                            <i class="fas fa-undo-alt"></i> Sudah Dikembalikan<br>
                            <small>{{ $borrowing->returned_at->format('d F Y H:i') }}</small>
                        </div>
                    @elseif($borrowing->status === 'overdue')
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> Terlambat<br>
                            <small>Buku belum dikembalikan melebihi batas waktu.</small>
                        </div>
                        <form action="{{ route('petugas.borrowings.verify-return', $borrowing) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100"
                                    onclick="return confirm('Verifikasi pengembalian buku terlambat?')">
                                <i class="fas fa-undo-alt"></i> Verifikasi Pengembalian
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-title {
        color: #8B4513;
        font-weight: 700;
    }
    
    .card-header {
        border-bottom: 2px solid #e9ecef;
    }
    
    .form-label {
        font-weight: 600;
    }
</style>
@endsection
