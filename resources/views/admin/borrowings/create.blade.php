@extends('layouts.app')

@section('title', 'Tambah Peminjaman - Admin')

@section('extra-css')
    @vite(['resources/css/pages/admin.css'])
@endsection

@section('content')
<div class="admin-header">
    <div class="header-content">
        <h1><i class="fas fa-plus-circle"></i> Tambah Peminjaman</h1>
        <p>Buat peminjaman buku baru untuk pengguna</p>
    </div>
    <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-book"></i> Form Peminjaman Buku</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-circle"></i> Gagal!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.borrowings.store') }}">
                    @csrf

                    <!-- Peminjam -->
                    <div class="mb-3">
                        <label for="user_id" class="form-label">
                            <i class="fas fa-user"></i> Peminjam <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">-- Pilih Peminjam --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buku -->
                    <div class="mb-3">
                        <label for="book_id" class="form-label">
                            <i class="fas fa-book-open"></i> Buku <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
                                    {{ $book->title }} ({{ $book->author }}) - Tersedia: {{ $book->available_copies }}
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Pinjam -->
                    <div class="mb-3">
                        <label for="borrowed_date" class="form-label">
                            <i class="fas fa-calendar"></i> Tanggal Pinjam <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control @error('borrowed_date') is-invalid @enderror" 
                               id="borrowed_date" name="borrowed_date" value="{{ old('borrowed_date', now()->format('Y-m-d')) }}" required>
                        @error('borrowed_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Kembali -->
                    <div class="mb-3">
                        <label for="due_date" class="form-label">
                            <i class="fas fa-calendar-check"></i> Tanggal Kembali <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                               id="due_date" name="due_date" value="{{ old('due_date', now()->addDays(14)->format('Y-m-d')) }}" required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Durasi standar peminjaman adalah 14 hari
                        </small>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note"></i> Catatan (Opsional)
                        </label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Tambahkan catatan khusus jika diperlukan...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
    @vite(['resources/js/pages/admin.js'])
    <script>
        document.getElementById('borrowed_date').addEventListener('change', function() {
            const borrowed = new Date(this.value);
            const due = new Date(borrowed);
            due.setDate(due.getDate() + 14);
            
            const year = due.getFullYear();
            const month = String(due.getMonth() + 1).padStart(2, '0');
            const day = String(due.getDate()).padStart(2, '0');
            
            document.getElementById('due_date').value = `${year}-${month}-${day}`;
        });
    </script>
@endsection
