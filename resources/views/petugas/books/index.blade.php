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
<div class="btn-toolbar mb-4">
    <a href="{{ route('petugas.books.export-csv', array_merge(request()->all())) }}" class="btn btn-info" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; color: white;">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

<!-- Books Table -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 35%;">📖 Judul Buku</th>
                    <th style="width: 15%;">✍️ Penulis</th>
                    <th style="width: 15%;">📂 Kategori</th>
                    <th style="width: 12%;">📊 Stok</th>
                    <th style="width: 12%;">⚙️ Status</th>
                    <th style="width: 11%;">🎯 Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td>
                            <strong>{{ Str::limit($book->title, 50) }}</strong>
                            @if($book->cover_image)
                                <br><small class="text-success"><i class="fas fa-image"></i> Ada cover</small>
                            @else
                                <br><small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Tidak ada cover</small>
                            @endif
                        </td>
                        <td>{{ Str::limit($book->author, 30) }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $book->category->name ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="stok-info">
                                <strong>{{ $book->available_copies }}/{{ $book->total_copies }}</strong>
                                @if($book->available_copies < 3 && $book->available_copies > 0)
                                    <br><small class="text-warning"><i class="fas fa-exclamation"></i> Stok terbatas</small>
                                @elseif($book->available_copies <= 0)
                                    <br><small class="text-danger"><i class="fas fa-times"></i> Habis</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($book->is_active)
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Aktif</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Nonaktif</span>
                            @endif
                            @if($book->is_featured)
                                <br><small class="text-primary"><i class="fas fa-star"></i> Featured</small>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('petugas.books.show', $book) }}" class="btn btn-sm btn-info" title="Lihat detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('books.show', $book->slug) }}" target="_blank" class="btn btn-sm btn-primary" title="Lihat di katalog">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <p class="text-muted">Tidak ada buku ditemukan</p>
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
