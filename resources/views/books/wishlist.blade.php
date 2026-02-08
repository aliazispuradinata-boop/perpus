@extends('layouts.app')

@section('title', 'Daftar Favorit Saya')

@section('extra-css')
    <style>
        .wishlist-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .wishlist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .card-img-container {
            position: relative;
            overflow: hidden;
            background: #f0f0f0;
            aspect-ratio: 3/4;
        }

        .card-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .placeholder-cover {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            color: #adb5bd;
        }

        .placeholder-cover i {
            font-size: 3rem;
        }

        .badge-added {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(211, 45, 69, 0.9);
            backdrop-filter: blur(5px);
        }

        .availability {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .button-group {
            gap: 8px !important;
        }

        .empty-state {
            padding: 60px 20px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #8B4513;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="page-title">
                <i class="fas fa-heart"></i> Daftar Favorit Saya
            </h1>
            <p class="text-muted">
                Anda memiliki <strong>{{ $wishlists->total() }}</strong> buku favorit
            </p>
        </div>
    </div>

    <!-- Search & Sort -->
    @if($wishlists->count() > 0)
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" action="{{ route('wishlist.index') }}" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" 
                           placeholder="🔍 Cari buku atau penulis..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-3 ms-auto">
                <select name="sort" class="form-select" onchange="window.location.href='{{ route('wishlist.index') }}?sort=' + this.value + (document.querySelector('input[name=search]').value ? '&search=' + document.querySelector('input[name=search]').value : '')">
                    <option value="newest" @selected(request('sort') == 'newest')>Terbaru Ditambahkan</option>
                    <option value="title" @selected(request('sort') == 'title')>Judul (A-Z)</option>
                    <option value="author" @selected(request('sort') == 'author')>Penulis (A-Z)</option>
                </select>
            </div>
        </div>
    @endif

    @if($wishlists->count() > 0)
        <!-- Wishlist Grid -->
        <div class="row mb-4" id="wishlistGrid">
            @foreach($wishlists as $wishlist)
                @php $book = $wishlist->book; @endphp
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card wishlist-card h-100">
                        <div class="card-img-container">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                     alt="{{ $book->title }}" class="card-img-top">
                            @else
                                <div class="placeholder-cover">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif
                            <span class="badge badge-added">
                                <i class="far fa-heart"></i> 
                                {{ $wishlist->created_at->format('d M') }}
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($book->title, 50) }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($book->author, 40) }}</p>
                            
                            <div class="availability mb-3">
                                @if($book->available_copies > 0)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Tersedia
                                    </span>
                                    <small class="text-success d-block">{{ $book->available_copies }} tersedia</small>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle"></i> Sedang Dipinjam
                                    </span>
                                @endif
                            </div>

                            <div class="button-group d-flex gap-2 mb-2">
                                @if($book->available_copies > 0)
                                    <form action="{{ route('borrowings.store') }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">
                                            <i class="fas fa-book"></i> Pinjam
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary w-100" disabled>
                                        <i class="fas fa-bell"></i> Notifikasi
                                    </button>
                                @endif
                                
                                <form action="{{ route('books.wishlist.remove', $book) }}" method="POST" class="flex-grow-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            title="Hapus dari favorit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-link w-100">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($wishlists->hasPages())
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center">
                    {{ $wishlists->links() }}
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <div class="empty-state">
                    <i class="fas fa-heart fa-5x text-muted mb-4" style="opacity: 0.3;"></i>
                    <h3 class="text-muted mb-3">Daftar Favorit Kosong</h3>
                    <p class="text-muted mb-4">
                        Anda belum menambahkan buku ke daftar favorit. 
                        Jelajahi katalog kami dan tambahkan buku yang Anda sukai!
                    </p>
                    <a href="{{ route('books.index') }}" class="btn btn-primary">
                        <i class="fas fa-book-open"></i> Jelajahi Katalog
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
