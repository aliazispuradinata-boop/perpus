@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 style="color: #8B4513; font-weight: bold;">
                    <i class="fas fa-user-circle"></i> Profil Saya
                </h1>
                <a href="{{ route('profile.edit') }}" class="btn btn-warning" style="background-color: #D2691E; border: none;">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Error!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Profile Cards -->
    <div class="row">
        <!-- Info Card -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #8B4513;">
                <div class="card-header" style="background-color: #F4A460; color: white;">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Data Pribadi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #8B4513;">Nama Lengkap</label>
                            <p class="form-control-plaintext text-dark">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #8B4513;">Email</label>
                            <p class="form-control-plaintext text-dark">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #8B4513;">No. Telepon</label>
                            <p class="form-control-plaintext text-dark">{{ $user->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #8B4513;">Peran</label>
                            <p class="form-control-plaintext">
                                <span class="badge" style="background-color: #8B4513;">
                                    @if($user->role === 'admin')
                                        <i class="fas fa-crown"></i> Administrator
                                    @elseif($user->role === 'petugas')
                                        <i class="fas fa-user-tie"></i> Petugas Perpustakaan
                                    @else
                                        <i class="fas fa-user"></i> Anggota
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color: #8B4513;">Alamat</label>
                        <p class="form-control-plaintext text-dark">{{ $user->address ?? '-' }}</p>
                    </div>

                    <hr style="border-color: #D2691E;">

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #8B4513;">Tanggal Pendaftaran</label>
                            <p class="form-control-plaintext text-dark">
                                {{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #8B4513;">Terakhir Diupdate</label>
                            <p class="form-control-plaintext text-dark">
                                {{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #28a745;">
                <div class="card-header" style="background-color: #28a745; color: white;">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> Status Akun</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-user-circle" style="font-size: 72px; color: #8B4513;"></i>
                    </div>
                    <p class="text-muted mb-3">ID Pengguna: <strong>#{{ $user->id }}</strong></p>
                    <div class="alert alert-success mb-3">
                        <i class="fas fa-check"></i> Akun Aktif
                    </div>
                    <a href="{{ route('profile.edit-password') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-key"></i> Ubah Password
                    </a>
                </div>
            </div>

            @if($user->role === 'user')
            <!-- Stats Card for User -->
            <div class="card shadow-sm border-0 mt-4" style="border-left: 4px solid #0d6efd;">
                <div class="card-header" style="background-color: #0d6efd; color: white;">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">Buku Dipinjam (Aktif)</p>
                        <h4 style="color: #8B4513;">
                            {{ $user->borrowings()->where('status', 'active')->count() }}
                        </h4>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Buku Dikembalikan</p>
                        <h4 style="color: #8B4513;">
                            {{ $user->borrowings()->where('status', 'returned')->count() }}
                        </h4>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Wishlist</p>
                        <h4 style="color: #8B4513;">
                            {{ $user->wishlists()->count() }}
                        </h4>
                    </div>
                </div>
            </div>
            @elseif($user->role === 'petugas')
            <!-- Stats Card for Petugas -->
            <div class="card shadow-sm border-0 mt-4" style="border-left: 4px solid #ffc107;">
                <div class="card-header" style="background-color: #ffc107; color: white;">
                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Statistik Verifikasi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">Menunggu Persetujuan</p>
                        <h4 style="color: #8B4513;">
                            {{ $user->petugas_borrowings()->where('status', 'pending')->count() }}
                        </h4>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Peminjaman Aktif</p>
                        <h4 style="color: #8B4513;">
                            {{ $user->petugas_borrowings()->where('status', 'active')->count() }}
                        </h4>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Terlambat</p>
                        <h4 style="color: #8B4513;">
                            {{ $user->petugas_borrowings()->where('status', 'overdue')->count() }}
                        </h4>
                    </div>
                </div>
            </div>
            @elseif($user->role === 'admin')
            <!-- Stats Card for Admin -->
            <div class="card shadow-sm border-0 mt-4" style="border-left: 4px solid #dc3545;">
                <div class="card-header" style="background-color: #dc3545; color: white;">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Overview Sistem</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">Total Pengguna</p>
                        <h4 style="color: #8B4513;">
                            {{ \App\Models\User::count() }}
                        </h4>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Total Buku</p>
                        <h4 style="color: #8B4513;">
                            {{ \App\Models\Book::count() }}
                        </h4>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Peminjaman Aktif</p>
                        <h4 style="color: #8B4513;">
                            {{ \App\Models\Borrowing::where('status', 'active')->count() }}
                        </h4>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background-color: #8B4513; color: white;">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-edit"></i> Edit Data Pribadi
                        </a>
                        <a href="{{ route('profile.edit-password') }}" class="btn btn-outline-primary">
                            <i class="fas fa-lock"></i> Ubah Password
                        </a>
                        @if($user->role === 'user')
                            <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-book"></i> Jelajahi Buku
                            </a>
                            <a href="{{ route('borrowings.history') }}" class="btn btn-outline-primary">
                                <i class="fas fa-history"></i> Riwayat Peminjaman
                            </a>
                        @elseif($user->role === 'petugas')
                            <a href="{{ route('petugas.dashboard') }}" class="btn btn-outline-primary">
                                <i class="fas fa-chart-line"></i> Dashboard Petugas
                            </a>
                            <a href="{{ route('petugas.borrowings.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list"></i> Daftar Verifikasi
                            </a>
                        @elseif($user->role === 'admin')
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                                <i class="fas fa-tachometer-alt"></i> Admin Panel
                            </a>
                            <a href="{{ route('admin.borrowings.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list"></i> Kelola Peminjaman
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }

    .btn-group .btn {
        margin: 4px;
    }

    @media (max-width: 768px) {
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-group .btn {
            margin: 0;
            width: 100%;
        }
    }
</style>
@endsection
