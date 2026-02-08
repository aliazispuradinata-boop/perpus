@extends('layouts.app')

@section('title', $book->title . ' - Detail Buku')

@section('extra-css')
    <style>
        .detail-header {
            margin-bottom: 2rem;
        }

        .placeholder-cover {
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0f0f0 0%, #e9ecef 100%);
            border-radius: 8px;
        }

        .info-section {
            margin: 1.5rem 0;
        }

        .stat-box {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #8B4513;
        }

        .stat-box h3 {
            margin: 0.5rem 0;
            color: #8B4513;
        }

        .stat-box p {
            margin: 0;
            color: #666;
        }
    </style>
@endsection

@section('content')
<div class="container mt-4 detail-header">
    <a href="{{ route('petugas.books.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="row">
        <div class="col-md-3">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="img-fluid rounded">
            @else
                <div class="placeholder-cover">
                    <i class="fas fa-book fa-5x text-muted"></i>
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <h1>{{ $book->title }}</h1>
            <h5 class="text-muted mb-3">Oleh: {{ $book->author }}</h5>
            
            <div class="row info-section">
                <div class="col-md-4">
                    <p><strong><i class="fas fa-barcode"></i> ISBN:</strong> {{ $book->isbn ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong><i class="fas fa-print"></i> Penerbit:</strong> {{ $book->publisher ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong><i class="fas fa-calendar"></i> Tahun:</strong> {{ $book->published_year ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong><i class="fas fa-file-pdf"></i> Halaman:</strong> {{ $book->pages ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong><i class="fas fa-language"></i> Bahasa:</strong> {{ $book->language ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong><i class="fas fa-folder"></i> Kategori:</strong> <span class="badge bg-secondary">{{ $book->category->name ?? '-' }}</span></p>
                </div>
            </div>

            @if($book->description)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-book-reader"></i> Deskripsi</h5>
                    </div>
                    <div class="card-body">
                        {{ $book->description }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Stock Status -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Status Stok</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-box">
                                <h3 style="color: #2ecc71;">{{ $book->available_copies }}</h3>
                                <p><i class="fas fa-check-circle"></i> Tersedia</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-box" style="border-left-color: #e74c3c;">
                                <h3 style="color: #e74c3c;">{{ $book->total_copies - $book->available_copies }}</h3>
                                <p><i class="fas fa-book"></i> Sedang Dipinjam</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-box" style="border-left-color: #3498db;">
                                <h3 style="color: #3498db;">{{ $book->total_copies }}</h3>
                                <p><i class="fas fa-cubes"></i> Total Stok</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Borrowings -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Peminjaman Aktif</h5>
                </div>
                <div class="card-body">
                    @php
                        $activeBorrowings = $book->borrowings()->where('status', 'active')->with('user')->get();
                    @endphp
                    @if($activeBorrowings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Peminjam</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeBorrowings as $borrowing)
                                        <tr>
                                            <td>
                                                <strong>{{ $borrowing->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $borrowing->user->email }}</small>
                                            </td>
                                            <td>{{ $borrowing->borrowed_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td>{{ $borrowing->due_date->format('d/m/Y') }}</td>
                                            <td>
                                                @if(now() > $borrowing->due_date)
                                                    <span class="badge bg-danger"><i class="fas fa-exclamation-circle"></i> Terlambat</span>
                                                    <br>
                                                    <small class="text-danger">{{ now()->diffInDays($borrowing->due_date) }} hari</small>
                                                @else
                                                    <span class="badge bg-info"><i class="fas fa-clock"></i> Aktif</span>
                                                    <br>
                                                    <small class="text-info">{{ now()->diffInDays($borrowing->due_date) }} hari lagi</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-info-circle"></i> Tidak ada peminjaman aktif
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
