@extends('layouts.app')

@section('title', 'Detail Peminjaman - Admin')

@section('extra-css')
    @vite(['resources/css/pages/admin.css'])
@endsection

@section('content')
<div class="admin-header">
    <div class="header-content">
        <h1><i class="fas fa-eye"></i> Detail Peminjaman</h1>
        <p>Informasi lengkap peminjaman buku</p>
    </div>
    <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Detail Peminjam -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Peminjam</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><small class="text-muted">Nama</small></p>
                        <h6 class="text-primary">{{ $borrowing->user->name }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><small class="text-muted">Email</small></p>
                        <h6 class="text-primary">{{ $borrowing->user->email }}</h6>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p class="mb-1"><small class="text-muted">No. Telepon</small></p>
                        <h6>{{ $borrowing->user->phone ?? '-' }}</h6>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p class="mb-1"><small class="text-muted">Alamat</small></p>
                        <h6>{{ $borrowing->user->address ?? '-' }}</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Buku -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-book"></i> Informasi Buku</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><small class="text-muted">Judul</small></p>
                        <h6 class="text-primary">{{ $borrowing->book->title }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><small class="text-muted">Penulis</small></p>
                        <h6 class="text-primary">{{ $borrowing->book->author }}</h6>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p class="mb-1"><small class="text-muted">Kategori</small></p>
                        <h6>{{ $borrowing->book->category->name ?? '-' }}</h6>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p class="mb-1"><small class="text-muted">Penerbit</small></p>
                        <h6>{{ $borrowing->book->publisher ?? '-' }}</h6>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p class="mb-1"><small class="text-muted">ISBN</small></p>
                        <h6>{{ $borrowing->book->isbn ?? '-' }}</h6>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p class="mb-1"><small class="text-muted">Tahun Terbit</small></p>
                        <h6>{{ $borrowing->book->published_year ?? '-' }}</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Peminjaman -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-calendar"></i> Informasi Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><small class="text-muted">Tanggal Pinjam</small></p>
                        <h6>{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d M Y') : '-' }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><small class="text-muted">Tanggal Jatuh Tempo</small></p>
                        <h6>{{ $borrowing->due_date ? $borrowing->due_date->format('d M Y') : '-' }}</h6>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p class="mb-1"><small class="text-muted">Tanggal Kembali</small></p>
                        <h6>
                            @if($borrowing->returned_at)
                                {{ $borrowing->returned_at->format('d M Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </h6>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p class="mb-1"><small class="text-muted">Durasi Peminjaman</small></p>
                        <h6>
                                @php
                                if($borrowing->borrow_date) {
                                    $start = $borrowing->borrow_date;
                                    $end = $borrowing->returned_at ? $borrowing->returned_at : now();
                                    $days = $end->diffInDays($start);
                                } else {
                                    $days = '-';
                                }
                            @endphp
                            @if($days !== '-')
                                {{ $days }} hari
                            @else
                                -
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tag"></i> Status Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="p-3 rounded" style="background-color: #f8f9fa;">
                    @if($borrowing->status == 'active')
                        <span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-check-circle"></i> Aktif
                        </span>
                    @elseif($borrowing->status == 'returned')
                        <span class="badge bg-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-undo"></i> Dikembalikan
                        </span>
                    @elseif($borrowing->status == 'overdue')
                        <span class="badge bg-danger" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-exclamation-circle"></i> Terlambat
                        </span>
                    @endif

                    @if($borrowing->status != 'returned' && $borrowing->due_date && $borrowing->due_date < now())
                        <p class="mt-3 mb-0 text-danger">
                            <i class="fas fa-warning"></i> 
                            <strong>Perhatian: Peminjaman ini sudah melewati tanggal jatuh tempo!</strong>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Catatan -->
        @if($borrowing->notes)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Catatan</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $borrowing->notes }}</p>
                </div>
            </div>
        @endif

        <!-- Aksi -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cog"></i> Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    @if($borrowing->status == 'pending_petugas')
                        <!-- Approval from admin -->
                        <form method="POST" action="{{ route('admin.borrowings.approve-pending', $borrowing) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Setujui peminjaman buku ini?')">
                                <i class="fas fa-check-circle"></i> Setujui Peminjaman
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.borrowings.reject-pending', $borrowing) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak peminjaman buku ini?')">
                                <i class="fas fa-times-circle"></i> Tolak Peminjaman
                            </button>
                        </form>
                    @elseif($borrowing->status == 'pending')
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i> Menunggu persetujuan dari petugas.
                        </div>
                    @elseif($borrowing->status != 'returned')
                        <a href="{{ route('admin.borrowings.edit', $borrowing) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.borrowings.approve-return', $borrowing) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Setujui pengembalian buku ini?')">
                                <i class="fas fa-check"></i> Setujui Pengembalian
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle"></i> Peminjaman telah selesai.
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.borrowings.destroy', $borrowing) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus peminjaman ini? Jika belum dikembalikan, stok buku akan ditambah.')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
    @vite(['resources/js/pages/admin.js'])
@endsection
