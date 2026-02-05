@extends('layouts.app')

@section('title', 'Dashboard')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/books.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/navbar.css') }}">
@endsection

@section('content')

<div class="page-title" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; padding: 3rem 2rem; margin: -2rem -2rem 2rem -2rem; border-radius: 0 0 12px 12px;">
    <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem; font-family: 'Merriweather', serif;"><i class="fas fa-user"></i> Dashboard</h1>
    <p style="font-size: 1.1rem; margin: 0; opacity: 0.9;">Selamat datang kembali, {{ auth()->user()->name }}!</p>
</div>

<!-- Hero CTA -->
<div style="background: linear-gradient(135deg, rgba(210, 105, 30, 0.15) 0%, rgba(244, 164, 96, 0.15) 100%); border: 2px solid #E8D5C4; border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="mb-2" style="color: #8B4513; font-weight: 700;"><i class="fas fa-lightbulb" style="color: #D2691E;"></i> Ingin Meminjam Buku?</h4>
            <p class="mb-0" style="color: #2C1810;">Upgrade akun Anda menjadi Petugas dan nikmati akses penuh ke sistem peminjaman buku berkualitas kami. Proses cepat, mudah, dan gratis!</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('books.index') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; border: none; padding: 0.75rem 1.5rem;">
                <i class="fas fa-book"></i> Jelajahi Buku
            </a>
        </div>
    </div>
</div>

<!-- Featured Books -->
<h2 class="section-title mb-4" style="font-size: 1.8rem; font-weight: 700; color: #8B4513; border-bottom: 3px solid #D2691E; padding-bottom: 0.5rem;">Buku Unggulan Bulan Ini</h2>
<div class="row mb-5">
    @foreach($featured_books as $book)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="ratio ratio-2x3 bg-light">
                    @if($book->cover_image)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-fluid">
                    @else
                        <div class="d-flex align-items-center justify-content-center text-muted">
                            <i class="fas fa-book fa-5x"></i>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <h6 class="card-title">{{ Str::limit($book->title, 50) }}</h6>
                    <p class="card-text text-muted small">{{ $book->author }}</p>
                    
                    <div class="mb-2">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star"></i> {{ number_format($book->rating, 1) }}
                        </span>
                        <span class="badge badge-category">{{ $book->category->name }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-primary w-100">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Trending Books -->
<h2 class="section-title mb-4" style="font-size: 1.8rem; font-weight: 700; color: #8B4513; border-bottom: 3px solid #D2691E; padding-bottom: 0.5rem;">Buku Trending</h2>
<div class="row mb-5">
    @foreach($trending_books as $book)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="ratio ratio-2x3 bg-light">
                    @if($book->cover_image)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-fluid">
                    @else
                        <div class="d-flex align-items-center justify-content-center text-muted">
                            <i class="fas fa-book fa-5x"></i>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <h6 class="card-title">{{ Str::limit($book->title, 50) }}</h6>
                    <p class="card-text text-muted small">{{ $book->author }}</p>
                    
                    <div class="mb-2">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star"></i> {{ number_format($book->rating, 1) }}
                        </span>
                        <span class="badge badge-category">{{ $book->category->name }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-primary w-100">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Features Section -->
<h2 class="section-title mb-4" style="font-size: 1.8rem; font-weight: 700; color: #8B4513; border-bottom: 3px solid #D2691E; padding-bottom: 0.5rem;">Mengapa Memilih RetroLib?</h2>
<div class="row mb-5">
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-book-open fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Koleksi Lengkap</h5>
                <p class="card-text">Ribuan buku dari 10 kategori trending untuk semua jenis pembaca.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-hand-holding-heart fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Peminjaman Mudah</h5>
                <p class="card-text">Pinjam buku dengan mudah selama 14 hari. Bisa diperpanjang hingga 3 kali.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-star fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Rating & Review</h5>
                <p class="card-text">Lihat rating dan review dari pembaca lain untuk memilih buku terbaik.</p>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card border-0" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; box-shadow: 0 8px 30px rgba(139, 69, 19, 0.2);">
            <div class="card-body text-center py-5">
                <h3 class="card-title mb-3" style="font-weight: 700; font-size: 1.8rem;">Siap Memulai Petualangan Membaca?</h3>
                <p class="card-text mb-4" style="font-size: 1.1rem; opacity: 0.95;">Bergabunglah dengan ribuan pembaca di RetroLib dan mulai meminjam buku favorit Anda hari ini!</p>
                <div>
                    <a href="{{ route('books.index') }}" class="btn btn-light btn-lg me-2" style="background: white; color: #8B4513; border: none; font-weight: 600;">
                        <i class="fas fa-book"></i> Jelajahi Buku
                    </a>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
    <script src="{{ asset('js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('js/pages/books.js') }}"></script>
@endsection
