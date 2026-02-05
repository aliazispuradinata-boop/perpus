@extends('layouts.app')

@section('title', 'Edit Peminjaman - Admin')

@section('extra-css')
    @vite(['resources/css/pages/admin.css'])
@endsection

@section('content')
<div class="admin-header">
    <div class="header-content">
        <h1><i class="fas fa-edit"></i> Edit Peminjaman</h1>
        <p>Ubah informasi peminjaman buku</p>
    </div>
    <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle"></i> 
                    Peminjam: <strong>{{ $borrowing->user->name }}</strong> | 
                    Buku: <strong>{{ $borrowing->book->title }}</strong>
                </h5>
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

                <!-- Informasi Peminjaman (Read Only) -->
                <div class="row mb-4 p-3 bg-light rounded">
                    <div class="col-md-6">
                        <p class="mb-1"><small class="text-muted"><i class="fas fa-user"></i> Peminjam</small></p>
                        <h6 class="text-primary">{{ $borrowing->user->name }}</h6>
                        <small class="text-muted">{{ $borrowing->user->email }}</small>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><small class="text-muted"><i class="fas fa-book"></i> Buku</small></p>
                        <h6 class="text-primary">{{ $borrowing->book->title }}</h6>
                        <small class="text-muted">{{ $borrowing->book->author }}</small>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p class="mb-1"><small class="text-muted"><i class="fas fa-calendar"></i> Tanggal Pinjam</small></p>
                        <h6>{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('d M Y') }}</h6>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p class="mb-1"><small class="text-muted"><i class="fas fa-calendar-check"></i> Tanggal Jatuh Tempo</small></p>
                        <h6>{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}</h6>
                    </div>
                    <div class="col-md-12 mt-3">
                        <p class="mb-1"><small class="text-muted"><i class="fas fa-tag"></i> Status</small></p>
                        <h6>
                            @if($borrowing->status === 'active')
                                <span class="badge bg-primary"><i class="fas fa-check-circle"></i> Aktif</span>
                            @elseif($borrowing->status === 'overdue')
                                <span class="badge bg-danger"><i class="fas fa-exclamation-circle"></i> Terlambat</span>
                            @elseif($borrowing->status === 'pending_return')
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Menunggu Konfirmasi</span>
                            @else
                                <span class="badge bg-success"><i class="fas fa-undo"></i> Dikembalikan</span>
                            @endif
                        </h6>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.borrowings.update', $borrowing) }}">
                    @csrf
                    @method('PUT')

                    <!-- Tanggal Pinjam -->
                    <div class="mb-3">
                        <label for="borrowed_date" class="form-label">
                            <i class="fas fa-calendar"></i> Tanggal Pinjam <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control @error('borrowed_date') is-invalid @enderror" 
                               id="borrowed_date" name="borrowed_date" value="{{ old('borrowed_date', \Carbon\Carbon::parse($borrowing->borrowed_date)->format('Y-m-d')) }}" required>
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
                               id="due_date" name="due_date" value="{{ old('due_date', \Carbon\Carbon::parse($borrowing->due_date)->format('Y-m-d')) }}" required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">
                            <i class="fas fa-tag"></i> Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" @selected(old('status', $borrowing->status) == 'active')>Aktif</option>
                            <option value="returned" @selected(old('status', $borrowing->status) == 'returned')>Dikembalikan</option>
                            <option value="overdue" @selected(old('status', $borrowing->status) == 'overdue')>Terlambat</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note"></i> Catatan
                        </label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Tambahkan atau ubah catatan...">{{ old('notes', $borrowing->notes) }}</textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
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
@endsection
