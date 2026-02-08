@extends('layouts.app')

@section('title', 'Kelola Buku - Petugas')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/admin-common.css') }}">
@endsection

@section('content')
<div class="books-header">
    <div>
        <h1><i class="fas fa-book-open"></i> Kelola Buku</h1>
        <p style="margin: 0.5rem 0 0 0; opacity: 0.95;">Kelola semua buku dalam perpustakaan</p>
    </div>
    <div class="header-stats">
        <div class="stat-item">
            <span class="stat-value">{{ $books->total() }}</span>
            <span class="stat-label">Total Buku</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $categories->count() }}</span>
            <span class="stat-label">Kategori</span>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="filter-section">
    <form method="GET" action="{{ route('petugas.books.index') }}" class="row g-3">
        <div class="col-lg-5">
            <input type="text" name="search" class="form-control" placeholder="🔍 Cari buku atau penulis..." 
                   value="{{ request('search') }}" style="border-radius: 8px; padding: 0.6rem 1rem; border: 2px solid #e9ecef;">
        </div>
        <div class="col-lg-4">
            <select name="category" class="form-select" style="border-radius: 8px; border: 2px solid #e9ecef; padding: 0.6rem 1rem;">
                <option value="">📚 Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if(request('category') == $category->id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <button type="submit" class="btn btn-primary w-100" style="border-radius: 8px; padding: 0.6rem 1rem;">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </form>
</div>

<!-- Export Button -->
<div class="btn-toolbar">
    <a href="{{ route('petugas.books.export-csv', array_merge(request()->all())) }}" class="btn btn-info" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; color: white;">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

<!-- Books Table -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white;">
                <tr>
                    <th style="width: 38%; color: white; font-weight: 600; padding: 1rem;">📖 Judul Buku</th>
                    <th style="width: 15%; color: white; font-weight: 600; padding: 1rem;">✍️ Penulis</th>
                    <th style="width: 13%; color: white; font-weight: 600; padding: 1rem;">📂 Kategori</th>
                    <th style="width: 12%; color: white; font-weight: 600; padding: 1rem;">📊 Stok</th>
                    <th style="width: 12%; color: white; font-weight: 600; padding: 1rem;">⚙️ Status</th>
                    <th style="width: 10%; color: white; font-weight: 600; padding: 1rem; text-align: center;">🎯 Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td style="padding: 1rem;">
                            <div class="book-title-cell">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="book-cover">
                                @else
                                    <img src="{{ asset('images/placeholder-book.png') }}" alt="no cover" class="book-cover" style="background: #f0f0f0;">
                                @endif
                                <div class="book-info">
                                    <strong title="{{ $book->title }}">{{ Str::limit($book->title, 35) }}</strong>
                                    <small>ID: #{{ $book->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            <small>{{ $book->author ?? '-' }}</small>
                        </td>
                        <td style="padding: 1rem;">
                            <span class="badge-custom category-badge">{{ $book->category->name ?? '-' }}</span>
                        </td>
                        <td style="padding: 1rem;">
                            <span class="badge-custom stock-badge">
                                {{ $book->available_copies }}/{{ $book->total_copies }}
                                @if($book->available_copies < 3 && $book->available_copies > 0)
                                    <small class="text-warning"> (Terbatas)</small>
                                @elseif($book->available_copies <= 0)
                                    <small class="text-danger"> (Habis)</small>
                                @endif
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                                @if($book->is_active)
                                    <span class="badge-custom status-active">✓ Aktif</span>
                                @else
                                    <span class="badge-custom status-inactive">✕ Nonaktif</span>
                                @endif
                                @if($book->is_featured)
                                    <span class="badge-custom featured-badge">⭐ Featured</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            <div class="action-buttons" style="justify-content: center;">
                                <a href="{{ route('petugas.books.show', $book) }}" class="btn btn-show" title="Lihat detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('books.show', $book->slug) }}" target="_blank" class="btn" title="Lihat di katalog" style="background: linear-gradient(135deg, #26a69a 0%, #009688 100%); color: white;">
                                    <i class="fas fa-external-link-alt"></i> Katalog
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-book"></i>
                                <p style="margin: 0;">Belum ada buku yang ditambahkan</p>
                                <small style="color: #aaa;">Hubungi admin untuk menambahkan buku baru</small>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($books->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
@endif

@endsection
