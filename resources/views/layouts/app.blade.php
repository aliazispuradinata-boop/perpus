<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - RetroLib | Perpustakaan Digital</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- RetroLib Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Navbar Component CSS -->
    <link rel="stylesheet" href="{{ asset('css/components/navbar.css') }}">
    
    @yield('extra-css')
    
    <!-- Force navbar visible override -->
    <style>
        nav.navbar, .navbar {
            background: linear-gradient(135deg, #6B3410 0%, #8B4513 50%, #A0522D 100%) !important;
            background-color: #8B4513 !important;
            box-shadow: 0 4px 18px rgba(0,0,0,0.35) !important;
            border-bottom: 3px solid #D2691E !important;
        }
        nav.navbar .navbar-collapse {
            background: linear-gradient(135deg, rgba(107,52,16,0.98) 0%, rgba(139,69,19,0.95) 100%) !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
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
                            <a class="nav-link" href="
                                @if(auth()->user()->isPetugas())
                                    {{ route('petugas.dashboard') }}
                                @elseif(auth()->user()->isAdmin())
                                    {{ route('dashboard') }}
                                @else
                                    {{ route('dashboard') }}
                                @endif
                            ">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        
                        <!-- User Wishlist Link -->
                        @if(auth()->user()->isUser())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('wishlist.index') }}">
                                    <i class="fas fa-heart"></i> Favorit
                                    @php $wishlistCount = auth()->user()->wishlists()->count(); @endphp
                                    @if($wishlistCount > 0)
                                        <span class="badge bg-danger ms-1">{{ $wishlistCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('borrowings.history') }}">
                                <i class="fas fa-history"></i> Peminjaman
                            </a>
                        </li>
                        
                        @if(auth()->user()->isPetugas())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('petugas.borrowings.index') }}">
                                    <i class="fas fa-tasks"></i> Verifikasi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('petugas.books.index') }}">
                                    <i class="fas fa-book"></i> Kelola Buku
                                </a>
                            </li>
                        @endif
                        
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                @php $adminNotifCount = \App\Models\Notification::where('is_read', false)->count(); @endphp
                                <a class="nav-link" href="{{ route('admin.books.index') }}">
                                    <i class="fas fa-cogs"></i> Admin
                                    @if($adminNotifCount > 0)
                                        <span class="badge bg-danger ms-1">{{ $adminNotifCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user"></i> Profil Saya</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-login me-2" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-register" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-circle"></i> Terjadi Kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-book"></i> RetroLib
                    </h5>
                    <p>Perpustakaan digital dengan tema retro-modern. Jelajahi ribuan koleksi buku dan temukan bacaan favorit Anda.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Navigasi</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li><a href="{{ route('books.index') }}" class="text-white text-decoration-none">Katalog Buku</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-white text-decoration-none">Dashboard</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Hubungi Kami</h5>
                    <p>
                        <i class="fas fa-envelope"></i> info@retrolib.test<br>
                        <i class="fas fa-phone"></i> +62 812-3456-7890<br>
                        <i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia
                    </p>
                </div>
            </div>
            <hr class="bg-white opacity-25">
            <div class="text-center pt-3">
                <p>&copy; 2026 RetroLib. Dibuat dengan <i class="fas fa-heart text-danger"></i> untuk pecinta buku.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('extra-js')
</body>
</html>
