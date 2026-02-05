@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<div class="hero">
    <h1><i class="fas fa-book-open"></i> Selamat Datang di RetroLib</h1>
    <p class="lead">Perpustakaan Digital dengan Tema Retro-Modern</p>
    <p>Jelajahi ribuan koleksi buku, pinjam buku favorit, dan bagikan ulasan Anda dengan komunitas pembaca lainnya.</p>
    <div class="mt-4">
        <a href="{{ route('books.index') }}" class="btn btn-light btn-lg me-2">
            <i class="fas fa-search"></i> Jelajahi Katalog
        </a>
        @if(!auth()->check())
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-home"></i> Dashboard
            </a>
        @endif
    </div>
</div>

<!-- Features Section -->
<div class="row mb-5">
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-book fa-3x text-primary mb-3"></i>
                <h5 class="card-title">10 Kategori Trending</h5>
                <p class="card-text">Fiction, Self-Help, Business, Technology & AI, Fantasy & Sci-Fi, Biography, Health & Wellness, Psychology, Romance, dan Mystery & Thriller.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-hand-holding-heart fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Sistem Peminjaman</h5>
                <p class="card-text">Pinjam buku dengan mudah selama 14 hari. Bisa diperpanjang hingga 3 kali jika diperlukan.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-star fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Rating & Review</h5>
                <p class="card-text">Berikan ulasan dan rating untuk buku yang telah Anda baca. Bagikan pengalaman dengan pembaca lainnya.</p>
            </div>
        </div>
    </div>
</div>

<!-- Roles Section -->
<h2 class="section-title mb-4">Tiga Peran Pengguna</h2>
<div class="row mb-5">
    <div class="col-md-4 mb-4">
        <div class="card border-start border-5" style="border-color: var(--primary) !important;">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-tie text-primary"></i> Admin</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-primary"></i> Kelola semua buku</li>
                    <li><i class="fas fa-check text-primary"></i> Kelola peminjaman pengguna</li>
                    <li><i class="fas fa-check text-primary"></i> Dashboard analytics</li>
                    <li><i class="fas fa-check text-primary"></i> Manajemen kategori</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-start border-5" style="border-color: var(--secondary) !important;">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user text-warning"></i> Member</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-warning"></i> Pinjam buku</li>
                    <li><i class="fas fa-check text-warning"></i> Kembalikan & perpanjang</li>
                    <li><i class="fas fa-check text-warning"></i> Riwayat peminjaman</li>
                    <li><i class="fas fa-check text-warning"></i> Buat & bagikan review</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-start border-5" style="border-color: var(--brown) !important;">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-eye text-secondary"></i> Guest</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-secondary"></i> Browse katalog buku</li>
                    <li><i class="fas fa-check text-secondary"></i> Lihat detail buku</li>
                    <li><i class="fas fa-check text-secondary"></i> Lihat rating & review</li>
                    <li><i class="fas fa-check text-secondary"></i> Prompt untuk login</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Featured Books Section -->
<h2 class="section-title mb-4">Buku Unggulan</h2>
<div class="row mb-5">
    @foreach(['📖 The Great Gatsby', '💼 Zero to One', '🚀 Dune', '💪 Atomic Habits'] as $book)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="ratio ratio-2x3 bg-light">
                    <div class="d-flex align-items-center justify-content-center text-muted">
                        <i class="fas fa-book fa-5x"></i>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="card-title">{{ $book }}</h6>
                    <div class="mb-2">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star"></i> 4.5
                        </span>
                    </div>
                    <p class="card-text text-muted small">Klik untuk melihat detail lengkap buku</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- CTA Section -->
<div class="alert bg-light-primary border-0 py-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="mb-2"><i class="fas fa-lightbulb text-primary"></i> Bergabunglah dengan RetroLib</h4>
            <p class="mb-0">Daftar sekarang dan nikmati akses penuh ke ribuan buku berkualitas. Mulai membaca dan berbagi pengalaman Anda hari ini!</p>
        </div>
        <div class="col-md-4 text-md-end">
            @if(!auth()->check())
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus"></i> Daftar Gratis
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
