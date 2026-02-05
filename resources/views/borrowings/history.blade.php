@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/admin-common.css') }}">
@endsection

@section('content')
<div class="books-header">
    <h1><i class="fas fa-history"></i> Riwayat Peminjaman Saya</h1>
</div>

<!-- Filter -->
<div class="filter-card">
    <form method="GET" action="{{ route('borrowings.history') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') == 'pending')>Menunggu Konfirmasi</option>
                <option value="active" @selected(request('status') == 'active')>Sedang Dipinjam</option>
                <option value="returned" @selected(request('status') == 'returned')>Sudah Dikembalikan</option>
                <option value="overdue" @selected(request('status') == 'overdue')>Terlambat</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn w-100">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Borrowings Table -->
@if($borrowings->count() > 0)
    <div class="table-card">
        <div class="table-responsive px-3 pb-3">
            <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Tgl Pinjam</th>
                    <th>Harus Dikembalikan</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowings as $borrowing)
                    <tr>
                        <td>
                            <a href="{{ route('books.show', $borrowing->book->slug) }}" class="text-decoration-none">
                                {{ Str::limit($borrowing->book->title, 40) }}
                            </a>
                        </td>
                        <td>{{ $borrowing->book->author }}</td>
                        <td>{{ $borrowing->borrowed_at ? $borrowing->borrowed_at->format('d M Y') : '-' }}</td>
                        <td>{{ $borrowing->due_date ? $borrowing->due_date->format('d M Y') : '-' }}</td>
                        <td>
                            @if($borrowing->returned_at)
                                {{ $borrowing->returned_at->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @switch($borrowing->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi
                                    </span>
                                    @break
                                @case('active')
                                    @if($borrowing->isOverdue())
                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-circle"></i> Terlambat
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Sedang Dipinjam
                                        </span>
                                    @endif
                                    @break
                                @case('returned')
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-check"></i> Sudah Dikembalikan
                                    </span>
                                    @break
                                @case('overdue')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle"></i> Terlambat
                                    </span>
                                    @break
                                @default
                                    <span class="badge bg-info">{{ ucfirst($borrowing->status) }}</span>
                            @endswitch
                        </td>
                        <td>
                            @if($borrowing->status === 'pending')
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('borrowings.proof', $borrowing) }}" class="btn btn-sm btn-secondary" title="Lihat Bukti">
                                        <i class="fas fa-receipt"></i> Bukti
                                    </a>
                                    <a href="{{ route('books.show', $borrowing->book->slug) }}" class="btn btn-sm btn-primary" title="Lihat Buku">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            @elseif($borrowing->status === 'active')
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('borrowings.proof', $borrowing) }}" class="btn btn-sm btn-secondary" title="Lihat Bukti">
                                        <i class="fas fa-receipt"></i> Bukti
                                    </a>
                                    <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" title="Kembalikan">
                                            <i class="fas fa-undo"></i> Kembalikan
                                        </button>
                                    </form>
                                    
                                    @if($borrowing->canRenew())
                                        <form method="POST" action="{{ route('borrowings.renew', $borrowing) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-sm" title="Perpanjang">
                                                <i class="fas fa-sync"></i> Perpanjang
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('borrowings.proof', $borrowing) }}" class="btn btn-sm btn-secondary" title="Lihat Bukti">
                                        <i class="fas fa-receipt"></i> Bukti
                                    </a>
                                    <a href="{{ route('books.show', $borrowing->book->slug) }}" class="btn btn-sm btn-primary" title="Lihat Buku">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    {{ $borrowings->links() }}
@else
    <div class="alert alert-info text-center py-5">
        <i class="fas fa-inbox fa-3x mb-3"></i>
        <h4>Belum ada riwayat peminjaman</h4>
        <p>Mulai jelajahi katalog buku dan pinjam buku favorit Anda</p>
        <a href="{{ route('books.index') }}" class="btn btn-primary mt-3">
            <i class="fas fa-book-open"></i> Jelajahi Katalog
        </a>
    </div>
@endif
@endsection

@section('extra-js')
    <script src="{{ asset('js/pages/dashboard.js') }}"></script>
@endsection
