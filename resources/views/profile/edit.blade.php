@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 style="color: #8B4513; font-weight: bold;">
                <i class="fas fa-edit"></i> Edit Profil
            </h1>
            <p class="text-muted">Perbarui informasi data pribadi Anda</p>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Oops, ada kesalahan!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #8B4513;">
                <div class="card-header" style="background-color: #F4A460; color: white;">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Data Pribadi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold" style="color: #8B4513;">
                                <i class="fas fa-user"></i> Nama Lengkap
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Minimal 3 karakter, maksimal 255 karakter</small>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold" style="color: #8B4513;">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input 
                                type="email" 
                                class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Email tidak boleh sama dengan email pengguna lain</small>
                        </div>

                        <!-- No. Telepon -->
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold" style="color: #8B4513;">
                                <i class="fas fa-phone"></i> No. Telepon
                            </label>
                            <input 
                                type="tel" 
                                class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="08xx-xxxx-xxxx"
                                required
                            >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Masukkan nomor telepon yang aktif</small>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold" style="color: #8B4513;">
                                <i class="fas fa-map-marker-alt"></i> Alamat
                            </label>
                            <textarea 
                                class="form-control form-control-lg @error('address') is-invalid @enderror" 
                                id="address" 
                                name="address"
                                rows="4"
                                placeholder="Jl. ... No. ... Kota ..."
                                required
                            >{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Masukkan alamat lengkap Anda</small>
                        </div>

                        <hr style="border-color: #D2691E;">

                        <!-- Buttons -->
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-lg btn-primary" style="background-color: #8B4513; border: none;">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn btn-lg btn-outline-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4" style="border-left: 4px solid #0d6efd;">
                <div class="card-header" style="background-color: #0d6efd; color: white;">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        <strong>Peran Akun:</strong><br>
                        <span class="badge" style="background-color: #8B4513; font-size: 12px;">
                            @if($user->role === 'admin')
                                <i class="fas fa-crown"></i> Administrator
                            @elseif($user->role === 'petugas')
                                <i class="fas fa-user-tie"></i> Petugas Perpustakaan
                            @else
                                <i class="fas fa-user"></i> Anggota Perpustakaan
                            @endif
                        </span>
                    </p>
                    <hr>
                    <p class="small text-muted">
                        <i class="fas fa-shield-alt"></i> 
                        <strong>Catatan Keamanan:</strong><br>
                        Pastikan email dan nomor telepon Anda selalu aktif. Data ini akan digunakan untuk komunikasi penting dari perpustakaan.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0" style="border-left: 4px solid #28a745;">
                <div class="card-header" style="background-color: #28a745; color: white;">
                    <h5 class="mb-0"><i class="fas fa-lock"></i> Keamanan Password</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Ubah password akun Anda di halaman khusus.
                    </p>
                    <a href="{{ route('profile.edit-password') }}" class="btn btn-sm btn-outline-success w-100">
                        <i class="fas fa-key"></i> Ubah Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control-lg, .form-control-lg:focus {
        border-color: #D2691E;
    }

    .form-control-lg:focus {
        box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25);
    }

    .form-label {
        color: #8B4513;
    }

    .invalid-feedback {
        display: block;
    }
</style>
@endsection
