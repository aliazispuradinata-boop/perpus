@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('extra-css')
    @vite(['resources/css/pages/admin.css'])
    <link rel="stylesheet" href="{{ asset('css/pages/admin-common.css') }}">
@endsection

@section('content')
<div class="books-header">
    <div>
        <h1><i class="fas fa-history"></i> Kelola Peminjaman</h1>
        <p style="margin:4px 0 0 0; opacity:0.92;">Kelola semua data peminjaman buku di perpustakaan</p>
    </div>
    <div class="header-stats">
        <div class="stat-item" style="background:rgba(255,255,255,0.12); padding:0.6rem 0.9rem; border-radius:8px; color:white;">
            <span style="font-weight:700; display:block;">{{ $borrowings->total() }}</span>
            <small>Jumlah Peminjaman</small>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Search and Filter -->
<div class="card card-search mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.borrowings.index') }}" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari nama pengguna atau judul buku..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') == 'pending')>Menunggu Persetujuan Petugas</option>
                    <option value="pending_petugas" @selected(request('status') == 'pending_petugas')>Menunggu Persetujuan Admin</option>
                    <option value="active" @selected(request('status') == 'active')>Sedang Dipinjam</option>
                    <option value="returned" @selected(request('status') == 'returned')>Sudah Dikembalikan</option>
                    <option value="overdue" @selected(request('status') == 'overdue')>Terlambat</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toolbar -->
<div class="btn-toolbar">
    <a href="{{ route('admin.borrowings.create') }}" class="btn btn-success" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%); color:white; border:none;">
        <i class="fas fa-plus-circle"></i> Tambah Peminjaman
    </a>
    <a href="{{ route('admin.borrowings.export-csv', array_merge(request()->all(), ['status' => request('status')])) }}" class="btn btn-info" style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%); color:white; border:none;">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

<!-- Borrowings Table -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $key => $borrowing)
                    <tr>
                        <td>{{ $borrowings->firstItem() + $key }}</td>
                        <td>
                            <strong>{{ $borrowing->user->name }}</strong><br>
                            <small class="text-muted">{{ $borrowing->user->email }}</small>
                        </td>
                        <td>
                            <strong>{{ Str::limit($borrowing->book->title, 30) }}</strong><br>
                            <small class="text-muted">{{ $borrowing->book->author }}</small>
                        </td>
                        <td>{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <strong>{{ $borrowing->due_date ? $borrowing->due_date->format('d/m/Y') : '-' }}</strong>
                            @if($borrowing->due_date && $borrowing->due_date < now() && $borrowing->status == 'active')
                                <br><small class="text-danger"><i class="fas fa-exclamation"></i> Terlambat</small>
                            @endif
                        </td>
                        <td>
                            @if($borrowing->status == 'pending')
                                <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half"></i> Menunggu Petugas</span>
                            @elseif($borrowing->status == 'pending_petugas')
                                <span class="badge bg-info"><i class="fas fa-hourglass-half"></i> Menunggu Admin</span>
                            @elseif($borrowing->status == 'active')
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Sedang Dipinjam</span>
                            @elseif($borrowing->status == 'returned')
                                <span class="badge bg-secondary"><i class="fas fa-check"></i> Sudah Dikembalikan</span>
                            @elseif($borrowing->status == 'overdue')
                                <span class="badge bg-danger"><i class="fas fa-exclamation-triangle"></i> Terlambat</span>
                            @else
                                <span class="badge bg-info">{{ ucfirst($borrowing->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.borrowings.show', $borrowing) }}" 
                                   class="btn btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                
                                <a href="{{ route('admin.borrowings.print-proof', $borrowing) }}" 
                                   class="btn btn-secondary" title="Cetak Bukti" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                
                                @if($borrowing->status == 'pending_petugas')
                                    <form method="POST" action="{{ route('admin.borrowings.approve-pending', $borrowing) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" 
                                                title="Setujui Peminjaman"
                                                onclick="return confirm('Setujui peminjaman buku ini?')">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.borrowings.reject-pending', $borrowing) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                title="Tolak Peminjaman"
                                                onclick="return confirm('Tolak peminjaman buku ini?')">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                @elseif($borrowing->status == 'pending')
                                    <!-- Pending status (waiting for petugas) - no action for admin -->
                                    <span class="badge bg-secondary">Menunggu Petugas</span>
                                @else
                                    <a href="{{ route('admin.borrowings.edit', $borrowing) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($borrowing->status != 'returned')
                                        <form method="POST" action="{{ route('admin.borrowings.approve-return', $borrowing) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                    title="Setujui Pengembalian"
                                                    onclick="return confirm('Setujui pengembalian buku ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                
                                <form method="POST" action="{{ route('admin.borrowings.destroy', $borrowing) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            title="Hapus"
                                            onclick="return confirm('Hapus data peminjaman ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Tidak ada data peminjaman</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
{{ $borrowings->links() }}

@endsection

@section('extra-js')
    @vite(['resources/js/pages/admin.js'])
@endsection
