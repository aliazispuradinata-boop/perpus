<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RetroLib - Perpustakaan Digital</title>
    
    <!-- Navbar Component CSS -->
    <link rel="stylesheet" href="{{ asset('css/components/navbar.css') }}">
    
    <!-- Vite CSS -->
    @vite(['resources/css/pages/landing.css'])
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-book"></i> RetroLib
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('books.index') }}">
                            <i class="fas fa-book-open"></i> Katalog
                        </a>
                    </li>
                    
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.books.index') }}">
                                    <i class="fas fa-cogs"></i> Admin
                                </a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Profil Saya</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item btn-auth">
                            <a class="btn btn-login" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item btn-auth">
                            <a class="btn btn-register" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1>Jelajahi Dunia Buku Tanpa Batas</h1>
                    <p>Temukan koleksi lengkap buku digital kami dan nikmati pengalaman membaca yang tak terlupakan.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('books.index') }}" class="btn btn-explore">
                            <i class="fas fa-search"></i> Jelajahi Katalog
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-explore btn-explore-white">
                                <i class="fas fa-user-plus"></i> Daftar Sekarang
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-book-reader" style="font-size: 10rem; color: rgba(255, 255, 255, 0.2);"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\Book::where('is_active', true)->count() }}</div>
                        <div class="stat-label">Buku</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\Category::where('is_active', true)->count() }}</div>
                        <div class="stat-label">Kategori</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\User::count() }}</div>
                        <div class="stat-label">Pengguna Aktif</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\Borrowing::where('status', 'returned')->count() }}</div>
                        <div class="stat-label">Peminjaman Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Books Section -->
    <section class="featured-section">
        <div class="container">
            <h2 class="section-title">
                <i class="fas fa-star"></i> Buku Pilihan
            </h2>
            <p class="section-subtitle">Koleksi buku-buku terbaik yang wajib Anda baca</p>

            @if($books->count() > 0)
                <div class="row">
                    @foreach($books as $book)
                        <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                            <div class="book-card">
                                <div class="book-cover">
                                    @if($book->cover_image)
                                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}">
                                    @else
                                        <div class="book-cover-placeholder">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                    <div class="book-badge">
                                        <i class="fas fa-star"></i> {{ number_format($book->rating, 1) }}
                                    </div>
                                </div>
                                <div class="book-info">
                                    <h5 class="book-title">{{ Str::limit($book->title, 50) }}</h5>
                                    <p class="book-author">{{ Str::limit($book->author, 40) }}</p>
                                    
                                    <div class="book-rating">
                                        <span class="stars">
                                            @for($i = 0; $i < floor($book->rating); $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            @if($book->rating - floor($book->rating) > 0)
                                                <i class="fas fa-star-half-alt"></i>
                                            @endif
                                        </span>
                                        <span class="rating-value">({{ $book->review_count }})</span>
                                    </div>

                                    <div class="book-footer">
                                        <a href="{{ route('books.show', $book->slug) }}" class="btn-view-detail">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('books.index') }}" class="btn btn-explore" style="padding: 0.8rem 3rem;">
                        <i class="fas fa-arrow-right"></i> Lihat Semua Buku
                    </a>
                </div>
            @else
                <div class="no-books">
                    <i class="fas fa-inbox"></i>
                    <h3>Belum Ada Buku</h3>
                    <p>Koleksi buku sedang dalam persiapan. Silakan kembali lagi nanti.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container">
            <h2 class="section-title">
                <i class="fas fa-th-large"></i> Kategori Buku
            </h2>
            <p class="section-subtitle">Telusuri berbagai kategori koleksi kami</p>

            @if($categories->count() > 0)
                <div class="row">
                    @foreach($categories as $category)
                        <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                            <a href="{{ route('books.index', ['category' => $category->id]) }}" class="text-decoration-none">
                                <div class="category-card">
                                    <i class="fas fa-{{ $category->icon ?? 'book' }}"></i>
                                    <div class="category-name">{{ $category->name }}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Siap Memulai Petualangan Membaca?</h2>
            <p class="cta-description">Bergabunglah dengan ribuan pembaca yang telah menemukan buku favorit mereka di RetroLib.</p>
            @guest
                <div>
                    <a href="{{ route('register') }}" class="btn btn-explore" style="background-color: white; color: var(--primary-color);">
                        <i class="fas fa-user-plus"></i> Daftar Gratis
                    </a>
                </div>
            @else
                <div>
                    <a href="{{ route('books.index') }}" class="btn btn-explore" style="background-color: white; color: var(--primary-color);">
                        <i class="fas fa-book-open"></i> Jelajahi Sekarang
                    </a>
                </div>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-book"></i> RetroLib</h5>
                    <p>Platform perpustakaan digital dengan koleksi buku terlengkap dan sistem peminjaman yang mudah.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Menu Cepat</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="{{ route('books.index') }}">Katalog Buku</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Daftar</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kontak</h5>
                    <p>
                        <i class="fas fa-envelope"></i> info@retrolib.com<br>
                        <i class="fas fa-phone"></i> +62 (0)1234567890<br>
                        <i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia
                    </p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 RetroLib. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Vite JS -->
    @vite(['resources/js/pages/landing.js'])
</body>
</html>
