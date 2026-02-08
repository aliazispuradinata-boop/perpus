@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="page-title">
                <i class="fas fa-tachometer-alt"></i> Dashboard Petugas
            </h1>
            <p class="text-muted">Selamat datang kembali, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0">Menunggu Persetujuan</p>
                            <h3 class="mb-0">{{ $stats['pending'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0">Peminjaman Aktif</p>
                            <h3 class="mb-0">{{ $stats['active'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0">Terlambat</p>
                            <h3 class="mb-0">{{ $stats['overdue'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-secondary">
                            <i class="fas fa-undo"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0">Dikembalikan</p>
                            <h3 class="mb-0">{{ $stats['returned'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-lightning-bolt"></i> Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="{{ route('petugas.books.index') }}" class="btn btn-outline-info w-100 mb-2">
                                <i class="fas fa-book"></i> Kelola Buku
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('petugas.borrowings.index') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-list"></i> Semua Peminjaman
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('petugas.borrowings.index', ['status' => 'pending']) }}" class="btn btn-outline-warning w-100 mb-2">
                                <i class="fas fa-hourglass-start"></i> Menunggu
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('petugas.borrowings.index', ['status' => 'active']) }}" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-check-circle"></i> Aktif
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('petugas.borrowings.index', ['status' => 'overdue']) }}" class="btn btn-outline-danger w-100 mb-2">
                                <i class="fas fa-calendar-times"></i> Terlambat
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('petugas.borrowings.export') }}" class="btn btn-outline-secondary w-100 mb-2">
                                <i class="fas fa-download"></i> Export
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approvals Table -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list"></i> Peminjaman Menunggu Persetujuan Anda
                    </h5>
                    @if(($stats['pending'] ?? 0) > 0)
                        <span class="badge bg-warning">{{ $stats['pending'] }} Menunggu</span>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Buku</th>
                                <th>Durasi</th>
                                <th>Tanggal Pinjam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pending_approvals as $borrowing)
                                <tr>
                                    <td>
                                        <strong>{{ $borrowing->user->name }}</strong><br>
                                        <small class="text-muted">{{ $borrowing->user->email }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $borrowing->book->title }}</strong><br>
                                        <small class="text-muted">{{ $borrowing->book->author ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $borrowing->duration_days ?? '-' }} hari</span>
                                    </td>
                                    <td>
                                        {{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('petugas.borrowings.show', $borrowing) }}" 
                                               class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <form action="{{ route('petugas.borrowings.approve-pending', $borrowing) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm"
                                                        onclick="return confirm('Setujui peminjaman ini?')">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('petugas.borrowings.reject-pending', $borrowing) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('Tolak peminjaman ini?')">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-check-circle" style="font-size: 1.5rem; color: #28a745;"></i>
                                        <p class="mt-2">Tidak ada peminjaman yang menunggu persetujuan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i> Aktivitas Terbaru
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Buku</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_borrowings as $borrowing)
                                <tr>
                                    <td>
                                        <strong>{{ $borrowing->user->name }}</strong><br>
                                        <small class="text-muted">{{ $borrowing->user->email }}</small>
                                    </td>
                                    <td>{{ $borrowing->book->title }}</td>
                                    <td>
                                        @if($borrowing->status === 'pending')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($borrowing->status === 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($borrowing->status === 'returned')
                                            <span class="badge bg-secondary">Dikembalikan</span>
                                        @elseif($borrowing->status === 'overdue')
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                    <td>{{ $borrowing->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('petugas.borrowings.show', $borrowing) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox" style="font-size: 1.5rem;"></i>
                                        <p>Tidak ada aktivitas</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tasks / Reminders -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks"></i> Tugas Hari Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @if(($stats['pending'] ?? 0) > 0)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Verifikasi Peminjaman Menunggu</h6>
                                        <small class="text-muted">{{ $stats['pending'] }} peminjaman menunggu</small>
                                    </div>
                                    <span class="badge bg-warning rounded-pill">{{ $stats['pending'] }}</span>
                                </div>
                            </div>
                        @endif

                        @if(($stats['overdue'] ?? 0) > 0)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Peminjaman Terlambat</h6>
                                        <small class="text-muted">Perlu ditindaklanjuti</small>
                                    </div>
                                    <span class="badge bg-danger rounded-pill">{{ $stats['overdue'] }}</span>
                                </div>
                            </div>
                        @endif

                        @if(($stats['pending'] ?? 0) == 0 && ($stats['overdue'] ?? 0) == 0)
                            <div class="list-group-item">
                                <p class="text-muted mb-0">
                                    <i class="fas fa-check-circle text-success"></i> Semua tugas selesai!
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle"></i> Informasi
                    </h6>
                    <p class="small text-muted">
                        Sebagai petugas, Anda bertanggung jawab untuk:
                    </p>
                    <ul class="small text-muted ps-3 mb-0">
                        <li>Mengkonfirmasi pengambilan buku</li>
                        <li>Memverifikasi pengembalian buku</li>
                        <li>Mencatat denda jika ada keterlambatan</li>
                        <li>Menjaga integritas data peminjaman</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-title {
        color: #8B4513;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .stat-icon.bg-warning {
        background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
    }

    .stat-icon.bg-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .stat-icon.bg-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .stat-icon.bg-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    }

    .stat-card h3 {
        color: #8B4513;
        font-weight: 700;
    }

    .card-header {
        border-bottom: 2px solid #e9ecef;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(139, 69, 19, 0.05);
    }

    .list-group-item {
        border-left: 4px solid #8B4513;
        border-radius: 4px;
        margin-bottom: 0.5rem;
    }
</style>
@endsection
