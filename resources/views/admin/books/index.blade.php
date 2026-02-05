@extends('layouts.app')

@section('title', 'Kelola Buku - Admin')

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
    <form method="GET" action="{{ route('admin.books.index') }}" class="row g-3">
        <div class="col-lg-5">
            <input type="text" name="search" class="form-control" placeholder="🔍 Cari buku atau penulis..." 
                   value="{{ request('search') }}" style="border-radius: 8px; padding: 0.6rem 1rem; border: 2px solid #e9ecef;">
        </div>
        <div class="col-lg-4">
            <div class="input-group" style="border-radius: 8px; overflow: hidden;">
                <select name="category" class="form-select" id="categoryFilter" style="border-radius: 8px 0 0 8px; border: 2px solid #e9ecef;">
                    <option value="">📚 Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if(request('category') == $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Tambah Kategori Baru" style="border-radius: 0 8px 8px 0; border-left: none;">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="col-lg-3">
            <button type="submit" class="btn btn-primary w-100" style="border-radius: 8px; padding: 0.6rem 1rem;">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </form>
</div>

<!-- Add Book Button -->
<div class="btn-toolbar">
    <a href="{{ route('admin.books.create') }}" class="btn btn-success" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: white;">
        <i class="fas fa-plus-circle"></i> Tambah Buku Baru
    </a>
    <a href="{{ route('admin.books.export-csv', array_merge(request()->all())) }}" class="btn btn-info" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; color: white;">
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
                            <div class="book-title-cell">
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="book-cover">
                                <div class="book-info">
                                    <strong title="{{ $book->title }}">{{ Str::limit($book->title, 35) }}</strong>
                                    <small>ID: #{{ $book->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <small>{{ $book->author }}</small>
                        </td>
                        <td>
                            <span class="badge-custom category-badge">{{ $book->category->name }}</span>
                        </td>
                        <td>
                            <span class="badge-custom stock-badge">
                                {{ $book->available_copies }}/{{ $book->total_copies }}
                            </span>
                        </td>
                        <td>
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
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-action btn-edit" title="Edit buku">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.books.destroy', $book) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-delete" 
                                            onclick="return confirm('Yakin ingin menghapus buku ini?')" title="Hapus buku">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-book"></i>
                                <p style="margin: 0;">Belum ada buku yang ditambahkan</p>
                                <small style="color: #aaa;">Klik tombol "Tambah Buku Baru" untuk menambahkan buku pertama Anda</small>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
{{ $books->links() }}

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryLabel">
                    <i class="fas fa-plus"></i> Tambah Kategori Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                        <small class="text-muted">Contoh: Fiksi, Non-Fiksi, Komik, dll</small>
                    </div>
                    <div class="mb-3">
                        <label for="categoryDesc" class="form-label">Deskripsi (opsional)</label>
                        <textarea class="form-control" id="categoryDesc" name="description" rows="3"></textarea>
                    </div>
                    <div id="categoryMessage"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveCategoryBtn">
                    <i class="fas fa-save"></i> Simpan Kategori
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
    <script src="{{ asset('js/pages/admin.js') }}"></script>
    <script>
        document.getElementById('saveCategoryBtn').addEventListener('click', function() {
            const form = document.getElementById('addCategoryForm');
            const name = document.getElementById('categoryName').value.trim();
            const description = document.getElementById('categoryDesc').value.trim();
            const messageDiv = document.getElementById('categoryMessage');

            if (!name) {
                messageDiv.innerHTML = '<div class="alert alert-danger">Nama kategori tidak boleh kosong!</div>';
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            fetch('{{ route("admin.categories.store-ajax") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name, description })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Add new category to select dropdown
                    const select = document.getElementById('categoryFilter');
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.textContent = data.category.name;
                    option.selected = true;
                    select.appendChild(option);

                    messageDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check"></i> ' + data.message + '</div>';
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
                        form.reset();
                        messageDiv.innerHTML = '';
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Kategori';
                    }, 1500);
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation"></i> ' + data.message + '</div>';
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save"></i> Simpan Kategori';
                }
            })
            .catch(err => {
                messageDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation"></i> Terjadi kesalahan: ' + err.message + '</div>';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Simpan Kategori';
            });
        });
    </script>
@endsection

