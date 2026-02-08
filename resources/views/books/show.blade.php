@extends('layouts.app')

@section('title', $book->title)

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/books-show.css') }}">
    <style>
        .book-cover-container {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .book-cover-img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        .book-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #6c757d;
        }

        .book-placeholder i {
            font-size: 80px;
            opacity: 0.3;
        }

        .action-buttons-group {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .action-buttons-group .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-borrow {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
        }

        .btn-borrow:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .btn-borrow:disabled {
            background: linear-gradient(135deg, #d1d5db 0%, #9ca3af 100%);
            cursor: not-allowed;
            transform: none;
        }

        .btn-wishlist {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
        }

        .btn-wishlist:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            color: white;
        }

        .btn-wishlist.added {
            background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
        }

        .btn-wishlist.added:hover {
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .btn-upgrade {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            border: none;
        }

        .btn-upgrade:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
            color: white;
        }

        .btn-login {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .book-info-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .book-info-card h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .book-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .meta-value {
            font-size: 1rem;
            color: #1f2937;
        }

        .book-description {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .book-description h5 {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.75rem;
        }

        @media (max-width: 768px) {
            .book-meta {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
<div class="container my-4">
    <div class="row gap-4">
        <!-- Book Cover Section -->
        <div class="col-lg-4">
            <div class="book-cover-container">
                @if($book->cover_image)
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="book-cover-img">
                @else
                    <div class="book-placeholder">
                        <i class="fas fa-book"></i>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons-group">
                @if(auth()->check() && auth()->user()->isPetugas())
                    @if($book->available_copies > 0)
                        <form method="POST" action="{{ route('borrowings.store') }}" style="width: 100%;">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button type="submit" class="btn btn-borrow w-100">
                                <i class="fas fa-hand-holding-heart"></i> Pinjam Buku
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100" disabled>
                            <i class="fas fa-times-circle"></i> Tidak Tersedia
                        </button>
                    @endif
                @elseif(auth()->check() && auth()->user()->isUser())
                    <a href="{{ route('borrowings.history') }}" class="btn btn-upgrade w-100">
                        <i class="fas fa-star"></i> Upgrade ke Petugas
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login w-100">
                        <i class="fas fa-sign-in-alt"></i> Login untuk Pinjam
                    </a>
                @endif

                @auth
                    <button type="button" class="btn btn-wishlist w-100" id="wishlistBtn" onclick="toggleWishlist({{ $book->id }}, this)">
                        <i class="fas fa-heart" id="wishlistIcon"></i>
                        <span id="wishlistText">
                            @if($is_in_wishlist)
                                Hapus dari Favorit
                            @else
                                Tambah ke Favorit
                            @endif
                        </span>
                    </button>
                @endauth
            </div>
        </div>

        <!-- Book Info Section -->
        <div class="col-lg-8">
            <div class="book-info-card">
                <h1>{{ $book->title }}</h1>

                <div class="book-meta">
                    <div class="meta-item">
                        <span class="meta-label">Penulis</span>
                        <span class="meta-value">{{ $book->author ?? '-' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Penerbit</span>
                        <span class="meta-value">{{ $book->publisher ?? '-' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Tahun Terbit</span>
                        <span class="meta-value">{{ $book->published_year ?? '-' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Halaman</span>
                        <span class="meta-value">{{ $book->pages ?? '-' }}</span>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem;">
                    <span class="badge bg-warning text-dark" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                        <i class="fas fa-star"></i> {{ number_format($book->rating, 1) }} ({{ $book->review_count }} review)
                    </span>
                    <span class="badge" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 0.5rem 1rem; font-size: 0.9rem;">
                        {{ $book->category->name }}
                    </span>
                </div>

                <div style="background: #f3f4f6; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <strong>📊 Ketersediaan:</strong>
                    <p style="margin: 0.5rem 0 0 0;">
                        @if($book->available_copies > 0)
                            <span style="background: #d1fae5; color: #065f46; padding: 0.4rem 0.8rem; border-radius: 6px; display: inline-block; font-weight: 600;">
                                ✓ {{ $book->available_copies }} dari {{ $book->total_copies }} tersedia
                            </span>
                        @else
                            <span style="background: #fee2e2; color: #7f1d1d; padding: 0.4rem 0.8rem; border-radius: 6px; display: inline-block; font-weight: 600;">
                                ✕ Semua salinan sedang dipinjam
                            </span>
                        @endif
                    </p>
                </div>

                @if($book->description)
                    <div class="book-description">
                        <h5>📖 Deskripsi</h5>
                        <p style="line-height: 1.6; color: #374151;">{{ $book->description }}</p>
                    </div>
                @endif

                @if($book->content_preview)
                    <div class="book-description">
                        <h5>👀 Preview Konten</h5>
                        <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; border-left: 4px solid #8b5cf6; line-height: 1.6; color: #374151;">
                            {{ $book->content_preview }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-lg-8">
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
    <script>
        function toggleWishlist(bookId, button) {
            const isInWishlist = button.classList.contains('added');
            const url = isInWishlist 
                ? `/books/${bookId}/wishlist` 
                : `/books/${bookId}/wishlist`;
            const method = isInWishlist ? 'DELETE' : 'POST';

            // Disable button during request
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // Toggle button state
                    button.classList.toggle('added');
                    
                    if (isInWishlist) {
                        // Removed from wishlist
                        document.getElementById('wishlistIcon').className = 'fas fa-heart';
                        document.getElementById('wishlistText').textContent = 'Tambah ke Favorit';
                        showNotification('Buku dihapus dari favorit', 'info');
                    } else {
                        // Added to wishlist
                        document.getElementById('wishlistIcon').className = 'fas fa-heart';
                        document.getElementById('wishlistText').textContent = 'Hapus dari Favorit';
                        showNotification('✓ Buku ditambahkan ke favorit!', 'success');
                        
                        // Redirect to wishlist after 1.5 seconds
                        setTimeout(() => {
                            window.location.href = '{{ route("wishlist.index") }}';
                        }, 1500);
                    }
                } else if (data.error) {
                    showNotification(data.error, 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                font-weight: 600;
                z-index: 9999;
                animation: slideInRight 0.3s ease;
                ${type === 'success' ? 'background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;' : ''}
                ${type === 'error' ? 'background: linear-gradient(135deg, #ef5350 0%, #e53935 100%); color: white;' : ''}
                ${type === 'info' ? 'background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;' : ''}
            `;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Initialize button state
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('wishlistBtn');
            const text = document.getElementById('wishlistText');
            if (text && text.textContent.includes('Hapus')) {
                btn.classList.add('added');
            }
        });
    </script>
@endsection
