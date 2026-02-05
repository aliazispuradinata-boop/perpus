@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}">
@endsection

@section('content')
<div class="page-title" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; padding: 3rem 2rem; margin: -2rem -2rem 2rem -2rem; border-radius: 0 0 12px 12px;">
    <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem; font-family: 'Merriweather', serif;"><i class="fas fa-user-tie"></i> Dashboard Petugas</h1>
    <p style="font-size: 1.1rem; margin: 0; opacity: 0.9;">Selamat datang kembali, {{ auth()->user()->name }}!</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center" style="border: none; box-shadow: 0 4px 15px rgba(139, 69, 19, 0.15); border-top: 4px solid #8B4513;">
            <div class="card-body">
                <i class="fas fa-hand-holding-heart fa-3x mb-2" style="color: #8B4513;"></i>
                <h6 class="card-title" style="color: #2C1810; font-weight: 600;">Peminjaman Aktif</h6>
                <h2 style="color: #8B4513; font-weight: 700;">{{ $stats['active_borrowings'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center" style="border: none; box-shadow: 0 4px 15px rgba(210, 105, 30, 0.15); border-top: 4px solid #D2691E;">
            <div class="card-body">
                <i class="fas fa-exclamation-triangle fa-3x mb-2" style="color: #D2691E;"></i>
                <h6 class="card-title" style="color: #2C1810; font-weight: 600;">Terlambat</h6>
                <h2 style="color: #D2691E; font-weight: 700;">{{ $stats['overdue_borrowings'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center" style="border: none; box-shadow: 0 4px 15px rgba(244, 164, 96, 0.15); border-top: 4px solid #F4A460;">
            <div class="card-body">
                <i class="fas fa-check-circle fa-3x mb-2" style="color: #F4A460;"></i>
                <h6 class="card-title" style="color: #2C1810; font-weight: 600;">Total Dikembalikan</h6>
                <h2 style="color: #F4A460; font-weight: 700;">{{ $stats['total_returned'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center" style="border: none; box-shadow: 0 4px 15px rgba(139, 69, 19, 0.15); border-top: 4px solid #8B4513; opacity: 0.8;">
            <div class="card-body">
                <i class="fas fa-heart fa-3x mb-2" style="color: #8B4513;"></i>
                <h6 class="card-title" style="color: #2C1810; font-weight: 600;">Wishlist</h6>
                <h2 style="color: #8B4513; font-weight: 700;">{{ $stats['wishlist_count'] }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Active Borrowings -->
<h2 class="section-title mb-3" style="font-size: 1.8rem; font-weight: 700; color: #8B4513; border-bottom: 3px solid #D2691E; padding-bottom: 0.5rem;">Peminjaman Aktif Saya</h2>

@if($active_borrowings->count() > 0)
    <div class="row mb-4">
        @foreach($active_borrowings as $borrowing)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($borrowing->book->title, 40) }}</h5>
                        <p class="card-text text-muted">{{ $borrowing->book->author }}</p>

                        <div class="mb-3">
                            <p class="mb-1">
                                <small><strong>Tanggal Pinjam:</strong> {{ $borrowing->borrowed_at->format('d M Y') }}</small>
                            </p>
                            <p class="mb-2">
                                <small><strong>Harus Dikembalikan:</strong> {{ $borrowing->due_date->format('d M Y') }}</small>
                            </p>
                            <div class="progress" style="height: 5px;">
                                @php
                                    $total_days = 14;
                                    $days_passed = $borrowing->borrowed_at->diffInDays($borrowing->due_date);
                                    $days_used = $borrowing->borrowed_at->diffInDays(now());
                                    $percentage = ($days_used / $total_days) * 100;
                                @endphp
                                <div class="progress-bar @if($borrowing->isOverdue()) bg-danger @else bg-success @endif" 
                                     style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            @if($borrowing->isOverdue())
                                <span class="badge bg-danger">TERLAMBAT!</span>
                            @else
                                <span class="badge bg-success">Aktif</span>
                            @endif
                            @if($borrowing->canRenew())
                                <span class="badge bg-info">Bisa diperpanjang</span>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning w-100 mb-2">
                                    <i class="fas fa-undo"></i> Kembalikan Buku
                                </button>
                            </form>

                            @if($borrowing->canRenew())
                                <form method="POST" action="{{ route('borrowings.renew', $borrowing) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-info w-100">
                                        <i class="fas fa-sync"></i> Perpanjang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle"></i> Anda belum meminjam buku apapun. 
        <a href="{{ route('books.index') }}" class="alert-link">Jelajahi katalog buku</a>
    </div>
@endif

<div class="row">
    <!-- Wishlist -->
    <div class="col-md-6 mb-4">
        <h3 class="section-title mb-3" style="font-size: 1.5rem; font-weight: 700; color: #8B4513; border-bottom: 2px solid #D2691E; padding-bottom: 0.5rem;">Wishlist Saya</h3>
        
        @if($wishlist_books->count() > 0)
            @foreach($wishlist_books->take(5) as $wishlist)
                <div class="card mb-2">
                    <div class="card-body">
                        <h6 class="card-title mb-1">{{ Str::limit($wishlist->book->title, 40) }}</h6>
                        <p class="card-text text-muted small mb-2">{{ $wishlist->book->author }}</p>
                        <a href="{{ route('books.show', $wishlist->book->slug) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
            
            @if($wishlist_books->count() > 5)
                <a href="{{ route('books.index') }}" class="btn btn-outline-primary w-100 mt-2">
                    Lihat Semua Wishlist
                </a>
            @endif
        @else
            <p class="text-muted">Wishlist Anda kosong</p>
        @endif
    </div>

    <!-- Featured Books -->
    <div class="col-md-6 mb-4">
        <h3 class="section-title mb-3" style="font-size: 1.5rem; font-weight: 700; color: #8B4513; border-bottom: 2px solid #D2691E; padding-bottom: 0.5rem;">Buku Unggulan</h3>
        
        @foreach($featured_books->take(5) as $book)
            <div class="card mb-2">
                <div class="card-body">
                    <h6 class="card-title mb-1">{{ Str::limit($book->title, 40) }}</h6>
                    <p class="card-text text-muted small mb-2">
                        <i class="fas fa-star text-warning"></i> {{ number_format($book->rating, 1) }}
                    </p>
                    <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card" style="background: linear-gradient(135deg, rgba(210, 105, 30, 0.1) 0%, rgba(244, 164, 96, 0.1) 100%); border: 2px solid #E8D5C4; box-shadow: 0 4px 15px rgba(139, 69, 19, 0.1);">
            <div class="card-body">
                <h5 class="card-title mb-3" style="color: #8B4513; font-weight: 700;">Aksi Cepat</h5>
                <a href="{{ route('books.index') }}" class="btn me-2" style="background: #8B4513; color: white; border: none;">
                    <i class="fas fa-book-open"></i> Jelajahi Buku
                </a>
                <a href="{{ route('borrowings.history') }}" class="btn me-2" style="background: #D2691E; color: white; border: none;">
                    <i class="fas fa-history"></i> Riwayat Peminjaman
                </a>
                <a href="{{ route('books.index', ['sort' => 'rating']) }}" class="btn" style="background: #F4A460; color: #2C1810; border: none;">
                    <i class="fas fa-star"></i> Buku Rating Tinggi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
    <script src="{{ asset('js/pages/dashboard.js') }}"></script>
@endsection
