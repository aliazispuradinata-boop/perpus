@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="page-title">
                <i class="fas fa-tasks"></i> Verifikasi Peminjaman
            </h1>
            <p class="text-muted">Konfirmasi dan verifikasi peminjaman buku dari user</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('petugas.borrowings.export', request()->query()) }}" class="btn btn-secondary" target="_blank">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('petugas.borrowings.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cari User atau Buku</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nama user atau judul buku..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('petugas.borrowings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Borrowings Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 20%;">User</th>
                        <th style="width: 25%;">Buku</th>
                        <th style="width: 12%;">Durasi</th>
                        <th style="width: 15%;">Tgl Pinjam</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 8%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $borrowing)
                        <tr>
                            <td>
                                <code>#{{ $borrowing->id }}</code>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <strong>{{ $borrowing->user->name }}</strong><br>
                                        <small class="text-muted">{{ $borrowing->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $borrowing->book->title }}</strong><br>
                                <small class="text-muted">
                                    {{ $borrowing->book->author ?? 'Penulis tidak diketahui' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $borrowing->duration_days ?? '-' }} hari</span>
                            </td>
                            <td>
                                <div>
                                    <small>Mulai: {{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</small><br>
                                    <small class="text-muted">Kembali: {{ $borrowing->due_date ? $borrowing->due_date->format('d/m/Y') : '-' }}</small>
                                </div>
                            </td>
                            <td>
                                @if($borrowing->status === 'pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> Menunggu
                                    </span>
                                @elseif($borrowing->status === 'pending_petugas')
                                    <span class="badge bg-info">
                                        <i class="fas fa-hourglass-half"></i> Menunggu Admin
                                    </span>
                                @elseif($borrowing->status === 'active')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> Aktif
                                    </span>
                                @elseif($borrowing->status === 'returned')
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-undo"></i> Dikembalikan
                                    </span>
                                @elseif($borrowing->status === 'overdue')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation"></i> Terlambat
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('petugas.borrowings.show', $borrowing) }}" 
                                       class="btn btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($borrowing->status === 'pending')
                                        <form action="{{ route('petugas.borrowings.approve-pending', $borrowing) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm" 
                                                    title="Setujui Peminjaman"
                                                    onclick="return confirm('Setujui peminjaman ini?')">
                                                <i class="fas fa-thumbs-up"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('petugas.borrowings.reject-pending', $borrowing) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                    title="Tolak Peminjaman"
                                                    onclick="return confirm('Tolak peminjaman ini?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @elseif($borrowing->status === 'active' && !$borrowing->confirmed_at)
                                        <form action="{{ route('petugas.borrowings.confirm', $borrowing) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm" 
                                                    title="Konfirmasi Pengambilan"
                                                    onclick="return confirm('Konfirmasi pengambilan buku oleh user?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @elseif($borrowing->status === 'active' && $borrowing->confirmed_at)
                                        <form action="{{ route('petugas.borrowings.verify-return', $borrowing) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-info btn-sm" 
                                                    title="Verifikasi Pengembalian"
                                                    onclick="return confirm('Verifikasi pengembalian buku?')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox" style="font-size: 2rem; color: #ddd;"></i>
                                <p class="text-muted mt-2">Tidak ada data peminjaman</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($borrowings->hasPages())
            <div class="card-footer">
                {{ $borrowings->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .page-title {
        color: #8B4513;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(139, 69, 19, 0.05);
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection
