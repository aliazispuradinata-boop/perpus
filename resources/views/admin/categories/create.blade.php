@extends('layouts.app')

@section('title', 'Tambah Kategori - Admin')

@section('content')
<div class="page-title">
    <h1><i class="fas fa-folder-plus"></i> Tambah Kategori</h1>
    <p class="text-muted">Halaman khusus untuk menambahkan kategori buku.</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->has('error'))
                    <div class="alert alert-danger">{{ $errors->first('error') }}</div>
                @endif

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (opsional)</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Petunjuk</h5>
                <ul>
                    <li>Gunakan halaman ini jika ingin menambah kategori secara terpisah dari form tambah buku.</li>
                    <li>Setelah disimpan, kategori akan tersedia di dropdown saat menambahkan atau mengedit buku.</li>
                    <li>Untuk menambah kategori langsung saat sedang menambah buku, gunakan tombol "+" pada dropdown kategori di halaman Tambah Buku.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
