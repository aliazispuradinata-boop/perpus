@extends('layouts.app')

@section('title', 'Tambah Petugas Baru')

@section('content')
<div class="page-title" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; padding: 2rem; margin: -2rem -2rem 1rem -2rem; border-radius: 0 0 12px 12px;">
    <h1 style="font-weight:700;"><i class="fas fa-user-plus"></i> Tambah Petugas Baru</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.petugas.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" placeholder="08123456789">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-bold">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" placeholder="Jl. Perpustakaan No. 1, Kota">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info" style="background-color: #FFF8DC; border-color: #D2691E; color: #2C1810;">
                        <i class="fas fa-info-circle"></i> Petugas baru akan otomatis menerima email notifikasi dengan informasi akun mereka.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Petugas
                        </button>
                        <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card" style="background: linear-gradient(135deg, rgba(210, 105, 30, 0.1) 0%, rgba(244, 164, 96, 0.1) 100%); border: 2px solid #E8D5C4;">
            <div class="card-body">
                <h5 class="card-title" style="color: #8B4513;">📋 Informasi Penting</h5>
                <ul class="list-unstyled text-sm">
                    <li class="mb-2">
                        <strong style="color: #8B4513;">✓ Email Unik</strong>
                        <small>Pastikan email belum terdaftar</small>
                    </li>
                    <li class="mb-2">
                        <strong style="color: #8B4513;">✓ Password Aman</strong>
                        <small>Minimal 6 karakter, gunakan kombinasi karakter</small>
                    </li>
                    <li class="mb-2">
                        <strong style="color: #8B4513;">✓ Data Lengkap</strong>
                        <small>Nama dan email wajib diisi</small>
                    </li>
                    <li class="mb-2">
                        <strong style="color: #8B4513;">✓ Notifikasi Email</strong>
                        <small>Petugas akan menerima email konfirmasi</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
