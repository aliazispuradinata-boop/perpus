@extends('layouts.app')

@section('title', 'Edit Buku - Admin')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
@endsection

@section('content')
<div class="page-title">
    <h1><i class="fas fa-edit"></i> Edit Buku</h1>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @if($book->category_id == $category->id) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Tambah Kategori Baru">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ $book->title }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Penulis <span class="text-danger">*</span></label>
                        <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" 
                               value="{{ $book->author }}" required>
                        @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" name="isbn" id="isbn" class="form-control @error('isbn') is-invalid @enderror" 
                                   value="{{ $book->isbn }}">
                            @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="publisher" class="form-label">Penerbit</label>
                            <input type="text" name="publisher" id="publisher" class="form-control @error('publisher') is-invalid @enderror" 
                                   value="{{ $book->publisher }}">
                            @error('publisher')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="published_year" class="form-label">Tahun Terbit</label>
                            <input type="number" name="published_year" id="published_year" class="form-control @error('published_year') is-invalid @enderror" 
                                   value="{{ $book->published_year }}" min="1900" max="{{ date('Y') }}">
                            @error('published_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pages" class="form-label">Jumlah Halaman</label>
                            <input type="number" name="pages" id="pages" class="form-control @error('pages') is-invalid @enderror" 
                                   value="{{ $book->pages }}" min="1">
                            @error('pages')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="language" class="form-label">Bahasa</label>
                        <input type="text" name="language" id="language" class="form-control @error('language') is-invalid @enderror" 
                               value="{{ $book->language }}" placeholder="Contoh: Indonesia, English">
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4">{{ $book->description }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="total_copies" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" name="total_copies" id="total_copies" class="form-control @error('total_copies') is-invalid @enderror" 
                               value="{{ $book->total_copies }}" required min="1">
                        @error('total_copies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" id="is_featured" class="form-check-input" 
                                   @if($book->is_featured) checked @endif>
                            <label class="form-check-label" for="is_featured">
                                <i class="fas fa-star"></i> Jadikan Buku Unggulan
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" @if($book->is_active) checked @endif>
                            <label class="form-check-label" for="is_active">
                                Aktifkan Buku Ini
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Cover Buku (opsional)</label>
                        @if($book->cover_image)
                            <div class="mb-2">
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" style="max-width:120px;" class="img-thumbnail">
                            </div>
                        @endif
                        <div class="input-group">
                            <input type="file" name="cover_image" id="cover_image" class="form-control @error('cover_image') is-invalid @enderror">
                            <button class="btn btn-outline-secondary" type="button" id="generateCoverBtn" data-bs-toggle="tooltip" title="Generate cover menggunakan AI">
                                <i class="fas fa-wand-magic-sparkles"></i> Generate
                            </button>
                        </div>
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Preview Cover -->
                    <div id="coverPreviewContainer" class="mb-3" style="display: none;">
                        <label class="form-label">Preview Cover</label>
                        <div class="border rounded p-3 text-center">
                            <img id="coverPreview" src="" alt="Cover Preview" style="max-height: 300px; object-fit: contain;">
                            <div id="coverActions" class="mt-3">
                                <button type="button" class="btn btn-sm btn-success" id="useCoverBtn">
                                    <i class="fas fa-check"></i> Gunakan Cover Ini
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" id="cancelCoverBtn">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
    <script src="{{ asset('js/pages/admin.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const generateCoverBtn = document.getElementById('generateCoverBtn');
            const useCoverBtn = document.getElementById('useCoverBtn');
            const cancelCoverBtn = document.getElementById('cancelCoverBtn');
            const coverInput = document.getElementById('cover_image');
            const coverPreview = document.getElementById('coverPreview');
            const coverPreviewContainer = document.getElementById('coverPreviewContainer');
            const titleInput = document.getElementById('title');
            const authorInput = document.getElementById('author');

            let generatedImageUrl = null;

            generateCoverBtn.addEventListener('click', async function() {
                const title = titleInput.value;
                const author = authorInput.value;

                if (!title) {
                    alert('Silakan masukkan judul buku terlebih dahulu');
                    return;
                }

                generateCoverBtn.disabled = true;
                generateCoverBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

                try {
                    const response = await fetch('{{ route("admin.books.generate-cover") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            title: title,
                            author: author
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        generatedImageUrl = data.image_url;
                        coverPreview.src = data.image_url;
                        coverPreviewContainer.style.display = 'block';
                        showAlert('success', data.message);
                    } else {
                        showAlert('danger', data.message || 'Gagal generate cover');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('danger', 'Terjadi kesalahan: ' + error.message);
                } finally {
                    generateCoverBtn.disabled = false;
                    generateCoverBtn.innerHTML = '<i class="fas fa-wand-magic-sparkles"></i> Generate';
                }
            });

            useCoverBtn.addEventListener('click', async function() {
                if (!generatedImageUrl) return;

                useCoverBtn.disabled = true;
                useCoverBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                try {
                    const response = await fetch('{{ route("admin.books.save-cover") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            image_url: generatedImageUrl,
                            title: titleInput.value
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Set hidden input untuk file path
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'cover_path';
                        hiddenInput.value = data.path;
                        document.querySelector('form').appendChild(hiddenInput);

                        coverPreviewContainer.style.display = 'none';
                        generatedImageUrl = null;
                        showAlert('success', 'Cover berhasil disimpan!');
                    } else {
                        showAlert('danger', data.message || 'Gagal menyimpan cover');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('danger', 'Terjadi kesalahan: ' + error.message);
                } finally {
                    useCoverBtn.disabled = false;
                    useCoverBtn.innerHTML = '<i class="fas fa-check"></i> Gunakan Cover Ini';
                }
            });

            cancelCoverBtn.addEventListener('click', function() {
                coverPreviewContainer.style.display = 'none';
                generatedImageUrl = null;
            });

            function showAlert(type, message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('form'));
                setTimeout(() => alertDiv.remove(), 5000);
            }
        });
    </script>

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
                    const select = document.getElementById('category_id');
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
