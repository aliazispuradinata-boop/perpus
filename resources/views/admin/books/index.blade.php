@extends('layouts.app')

@section('title', 'Kelola Buku - Admin')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/admin-common.css') }}">
    <style>
        .books-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.1);
            overflow: hidden;
        }

        .books-header-section {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .books-title h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .books-title p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .stats-group {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: center;
            min-width: 100px;
        }

        .stat-card .value {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
        }

        .stat-card .label {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .filter-area {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .filter-form {
            display: grid;
            grid-template-columns: 2fr 1.5fr 1fr 1fr;
            gap: 1rem;
            align-items: end;
        }

        .filter-form input,
        .filter-form select,
        .filter-form button {
            padding: 0.7rem 1rem;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            font-size: 0.95rem;
        }

        .filter-form input:focus,
        .filter-form select:focus {
            outline: none;
            border-color: #D2691E;
            box-shadow: 0 0 0 3px rgba(210, 105, 30, 0.1);
        }

        .filter-form button {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
        }

        .action-buttons-top {
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-add,
        .btn-export {
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-export {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .table-wrapper {
            padding: 1.5rem;
            overflow-x: auto;
        }

        .books-table {
            width: 100%;
            border-collapse: collapse;
        }

        .books-table thead {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            color: white;
        }

        .books-table thead th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            border: none;
        }

        .books-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .books-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .books-table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .book-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .book-cover-img {
            width: 50px;
            height: 68px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            flex-shrink: 0;
            border: 1px solid #e9ecef;
        }

        .book-details h4 {
            margin: 0 0 0.25rem 0;
            font-size: 0.95rem;
            color: #2c1810;
            font-weight: 600;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-details p {
            margin: 0;
            font-size: 0.8rem;
            color: #6c757d;
        }

        .badge-status {
            display: inline-block;
            padding: 0.4rem 0.7rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid;
        }

        .badge-active {
            background: #c8e6c9;
            color: #1b5e20;
            border-color: #81c784;
        }

        .badge-inactive {
            background: #ffccbc;
            color: #bf360c;
            border-color: #ff7043;
        }

        .badge-featured {
            background: #fff9c4;
            color: #f57f17;
            border-color: #fdd835;
        }

        .stock-info {
            background: #e3f2fd;
            color: #1565c0;
            padding: 0.4rem 0.7rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid #90caf9;
            text-align: center;
            min-width: 70px;
        }

        .category-info {
            background: #f3e5f5;
            color: #6a1b9a;
            padding: 0.4rem 0.7rem;
            border-radius: 6px;
            font-size: 0.85rem;
            border: 1px solid #ce93d8;
            display: inline-block;
        }

        .action-buttons-row {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.5rem 0.9rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 167, 38, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
        }

        .empty-message {
            text-align: center;
            padding: 3rem 1.5rem;
            color: #6c757d;
        }

        .empty-message i {
            font-size: 3rem;
            color: #D2691E;
            opacity: 0.5;
            margin-bottom: 1rem;
            display: block;
        }

        @media (max-width: 1024px) {
            .filter-form {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .books-header-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            .book-details h4 {
                max-width: 150px;
            }

            .action-buttons-row {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
<!-- Main Container -->
<div class="books-container">
    <!-- Header Section -->
    <div class="books-header-section">
        <div class="books-title">
            <h1><i class="fas fa-book-open"></i> Kelola Buku</h1>
            <p>Kelola semua buku dalam perpustakaan</p>
        </div>
        <div class="stats-group">
            <div class="stat-card">
                <span class="value">{{ $books->total() }}</span>
                <span class="label">Total Buku</span>
            </div>
            <div class="stat-card">
                <span class="value">{{ $categories->count() }}</span>
                <span class="label">Kategori</span>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-area">
        <form method="GET" action="{{ route('admin.books.index') }}" class="filter-form">
            <input type="text" name="search" placeholder="🔍 Cari buku atau penulis..." value="{{ request('search') }}">
            <select name="category">
                <option value="">📚 Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if(request('category') == $category->id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit"><i class="fas fa-search"></i> Cari</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal" style="background: linear-gradient(135deg, #8a5c3a 0%, #b8860b 100%);"><i class="fas fa-plus"></i> Kategori</button>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons-top">
        <a href="{{ route('admin.books.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i> Tambah Buku Baru
        </a>
        <a href="{{ route('admin.books.export-csv', array_merge(request()->all())) }}" class="btn-export">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </div>

    <!-- Table Section -->
    <div class="table-wrapper">
        @if($books->count() > 0)
            <table class="books-table">
                <thead>
                    <tr>
                        <th style="width: 35%;">📖 Judul Buku</th>
                        <th style="width: 15%;">✍️ Penulis</th>
                        <th style="width: 12%;">📂 Kategori</th>
                        <th style="width: 12%;">📊 Stok</th>
                        <th style="width: 15%;">⚙️ Status</th>
                        <th style="width: 11%;">🎯 Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <!-- Title with Cover -->
                            <td>
                                <div class="book-item">
                                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="book-cover-img">
                                    <div class="book-details">
                                        <h4 title="{{ $book->title }}">{{ Str::limit($book->title, 30) }}</h4>
                                        <p>ID: #{{ $book->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Author -->
                            <td>
                                <small>{{ $book->author ?? '-' }}</small>
                            </td>

                            <!-- Category -->
                            <td>
                                <span class="category-info">{{ $book->category->name ?? '-' }}</span>
                            </td>

                            <!-- Stock -->
                            <td>
                                <div class="stock-info">{{ $book->available_copies }}/{{ $book->total_copies }}</div>
                            </td>

                            <!-- Status -->
                            <td>
                                <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                                    @if($book->is_active)
                                        <span class="badge-status badge-active">✓ Aktif</span>
                                    @else
                                        <span class="badge-status badge-inactive">✕ Nonaktif</span>
                                    @endif
                                    @if($book->is_featured)
                                        <span class="badge-status badge-featured">⭐ Featured</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="action-buttons-row">
                                    <a href="{{ route('admin.books.edit', $book) }}" class="btn-action btn-edit" title="Edit buku">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.books.destroy', $book) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus buku ini?')" title="Hapus buku">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div style="padding: 1.5rem; border-top: 1px solid #e9ecef;">
                {{ $books->links() }}
            </div>
        @else
            <div class="empty-message">
                <i class="fas fa-book"></i>
                <h3>Belum ada buku</h3>
                <p>Klik tombol "Tambah Buku Baru" untuk menambahkan buku pertama Anda</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; border: none;">
                <h5 class="modal-title" id="addCategoryLabel">
                    <i class="fas fa-plus"></i> Tambah Kategori Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
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
                    const select = document.querySelector('select[name="category"]');
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
