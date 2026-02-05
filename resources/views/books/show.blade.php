@extends('layouts.app')

@section('title', $book->title)

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/books-show.css') }}">
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="ratio ratio-2x3 bg-light">
                @if($book->cover_image)
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-fluid">
                @else
                    <div class="d-flex align-items-center justify-content-center text-muted">
                        <i class="fas fa-book fa-5x"></i>
                    </div>
                @endif
            </div>
            <div class="card-body text-center">
                @if(auth()->check() && auth()->user()->isPetugas())
                    @if($book->available_copies > 0)
                        <form method="POST" action="{{ route('borrowings.store') }}" class="mb-2">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-hand-holding-heart"></i> Pinjam Buku
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100" disabled>
                            <i class="fas fa-times-circle"></i> Tidak Tersedia
                        </button>
                    @endif
                @elseif(auth()->check() && auth()->user()->isUser())
                    <a href="{{ route('borrowings.history') }}" class="btn btn-warning w-100">
                        <i class="fas fa-sign-in-alt"></i> Upgrade ke Petugas
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt"></i> Login untuk Pinjam
                    </a>
                @endif

                @auth
                    <button type="button" class="btn btn-outline-primary w-100 mt-2" onclick="toggleWishlist({{ $book->id }})">
                        @if($is_in_wishlist)
                            <i class="fas fa-heart"></i> Hapus dari Wishlist
                        @else
                            <i class="far fa-heart"></i> Tambah ke Wishlist
                        @endif
                    </button>
                @endauth
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <h1>{{ $book->title }}</h1>
        
        <div class="mb-3">
            <p class="text-muted mb-1"><strong>Penulis:</strong> {{ $book->author }}</p>
            <p class="text-muted mb-1"><strong>Penerbit:</strong> {{ $book->publisher ?? '-' }}</p>
            <p class="text-muted mb-1"><strong>Tahun Terbit:</strong> {{ $book->published_year ?? '-' }}</p>
            <p class="text-muted mb-3"><strong>Halaman:</strong> {{ $book->pages ?? '-' }}</p>
        </div>

        <div class="mb-3">
            <span class="badge bg-warning text-dark me-2">
                <i class="fas fa-star"></i> {{ number_format($book->rating, 1) }} ({{ $book->review_count }} review)
            </span>
            <span class="badge-category">{{ $book->category->name }}</span>
        </div>

        <div class="mb-3">
            <strong>Ketersediaan:</strong>
            <p class="mb-0">
                @if($book->available_copies > 0)
                    <span class="badge bg-success">{{ $book->available_copies }} dari {{ $book->total_copies }} tersedia</span>
                @else
                    <span class="badge bg-danger">Semua salinan sedang dipinjam</span>
                @endif
            </p>
        </div>

        <div class="mb-4">
            <h5>Deskripsi</h5>
            <p>{{ $book->description ?? 'Tidak ada deskripsi tersedia' }}</p>
        </div>

        @if($book->content_preview)
            <div class="mb-4">
                <h5>Preview</h5>
                <p class="bg-light p-3 rounded">{{ $book->content_preview }}</p>
            </div>
        @endif
    </div>
</div>

<!-- Reviews Section -->
<div class="row mt-5">
    <div class="col-md-8">
        <h2 class="section-title">Ulasan & Rating</h2>

        @if(auth()->check() && auth()->user()->isMember() && $can_review)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Tulis Ulasan Anda</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('books.reviews.store', $book) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}"
                                           @checked($user_review?->rating == $i)>
                                    <label for="star{{ $i }}" class="me-2">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Ulasan (opsional)</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="{{ $user_review?->title }}" placeholder="Berikan judul ulasan Anda">
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Ulasan Anda</label>
                            <textarea class="form-control" id="content" name="content" rows="5"
                                      required>{{ $user_review?->content }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Kirim Ulasan
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Reviews List -->
        @if($reviews->count() > 0)
            @foreach($reviews as $review)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="card-title mb-0">{{ $review->user->name }}</h6>
                                @if($review->title)
                                    <p class="text-muted small mb-0">{{ $review->title }}</p>
                                @endif
                            </div>
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i> {{ $review->rating }}
                            </span>
                        </div>
                        <p class="card-text">{{ $review->content }}</p>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        @if($review->is_verified_purchase)
                            <span class="badge bg-success ms-2">Pembelian Terverifikasi</span>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Belum ada ulasan untuk buku ini.
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <h5 class="section-title">Buku Terkait</h5>

        @forelse($related_books as $related)
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-4">
                        <div class="ratio ratio-2x3 bg-light">
                            @if($related->cover_image)
                                <img src="{{ $related->cover_url }}" alt="{{ $related->title }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center text-muted">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <h6 class="card-title">{{ Str::limit($related->title, 30) }}</h6>
                            <p class="card-text small text-muted">{{ $related->author }}</p>
                            <a href="{{ route('books.show', $related->slug) }}" class="btn btn-sm btn-primary">
                                Lihat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Tidak ada buku terkait</p>
        @endforelse
    </div>
</div>
@endsection

@section('extra-css')
<style>
    .rating-input input[type="radio"] {
        display: none;
    }

    .rating-input label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        transition: color 0.2s;
    }

    .rating-input input[type="radio"]:checked ~ label,
    .rating-input label:hover,
    .rating-input label:hover ~ label {
        color: var(--warning);
    }
</style>
@endsection

@section('extra-js')
    <script src="{{ asset('js/pages/books.js') }}"></script>
@endsection
