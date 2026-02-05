@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
@endsection

@section('content')
<div class="page-title" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; padding: 3rem 2rem; margin: -2rem -2rem 2rem -2rem; border-radius: 0 0 12px 12px;">
    <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0; font-family: 'Merriweather', serif;"><i class="fas fa-home"></i> Dashboard Admin</h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center" style="border: none; box-shadow: 0 4px 15px rgba(139, 69, 19, 0.15); border-top: 4px solid #8B4513;">
            <div class="card-body">
                <i class="fas fa-book fa-3x mb-2" style="color: #8B4513;"></i>
                <h6 class="card-title" style="color: #2C1810; font-weight: 600;">Total Buku</h6>
                <h2 style="color: #8B4513; font-weight: 700;">{{ $stats['total_books'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center" style="border: none; box-shadow: 0 4px 15px rgba(210, 105, 30, 0.15); border-top: 4px solid #D2691E;">
            <div class="card-body">
                <i class="fas fa-users fa-3x mb-2" style="color: #D2691E;"></i>
                <h6 class="card-title" style="color: #2C1810; font-weight: 600;">Total Member</h6>
                <h2 style="color: #D2691E; font-weight: 700;">{{ $stats['total_users'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center" style="border: none; box-shadow: 0 4px 15px rgba(244, 164, 96, 0.15); border-top: 4px solid #F4A460;">
            <div class="card-body">
                <i class="fas fa-hand-holding-heart fa-3x mb-2" style="color: #F4A460;"></i>
                <h6 class="card-title" style="color: #2C1810; font-weight: 600;">Peminjaman Aktif</h6>
                <h2 style="color: #F4A460; font-weight: 700;">{{ $stats['active_borrowings'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center" style="border: none; box-shadow: 0 4px 15px rgba(139, 69, 19, 0.15); border-top: 4px solid #8B4513; opacity: 0.8;">
            <div class="card-body">
                <i class="fas fa-exclamation-circle fa-3x mb-2" style="color: #8B4513;"></i>
                <h6 class="card-title" style="color: #2C1810; font-weight: 600;">Terlambat</h6>
                <h2 style="color: #8B4513; font-weight: 700;">{{ $stats['overdue_borrowings'] }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-md-12">
   
        <a href="{{ route('admin.books.index') }}" class="btn btn-lg me-2" style="background: #E8D5C4; color: #8B4513; border: none; padding: 0.75rem 1.5rem;">
            <i class="fas fa-book"></i> Kelola Buku
        </a>
        <a href="{{ route('admin.borrowings.index') }}" class="btn btn-lg me-2" style="background: #E8D5C4; color: #8B4513; border: none; padding: 0.75rem 1.5rem;">
            <i class="fas fa-history"></i> Kelola Peminjaman
        </a>
        <a href="{{ route('admin.petugas.index') }}" class="btn btn-lg me-2" style="background: #E8D5C4; color: #8B4513; border: none; padding: 0.75rem 1.5rem;">
            <i class="fas fa-user-shield"></i> Kelola Petugas
        </a>
        <a href="{{ route('admin.reviews.pending') }}" class="btn btn-lg" style="background: #E8D5C4; color: #8B4513; border: none; padding: 0.75rem 1.5rem;">
            <i class="fas fa-comments"></i> Moderasi Ulasan
        </a>
    </div>
</div>

<!-- Featured Books -->
<h2 class="section-title mb-3" style="font-size: 1.8rem; font-weight: 700; color: #8B4513; border-bottom: 3px solid #D2691E; padding-bottom: 0.5rem;">Buku Unggulan</h2>
<div class="row mb-4">
    @foreach($featured_books as $book)
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card h-100">
                <div class="ratio ratio-2x3 bg-light">
                    @if($book->cover_image)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center text-muted">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <h6 class="card-title">{{ Str::limit($book->title, 30) }}</h6>
                    <span class="badge bg-warning text-dark">{{ number_format($book->rating, 1) }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Recent Borrowings -->
<h2 class="section-title mb-3" style="font-size: 1.8rem; font-weight: 700; color: #8B4513; border-bottom: 3px solid #D2691E; padding-bottom: 0.5rem;">Peminjaman Terakhir</h2>
<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th>Member</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recent_borrowings as $borrowing)
                <tr>
                    <td>{{ $borrowing->user->name }}</td>
                    <td>{{ Str::limit($borrowing->book->title, 30) }}</td>
                    <td>{{ $borrowing->borrowed_at->format('d M Y') }}</td>
                    <td>{{ $borrowing->due_date->format('d M Y') }}</td>
                    <td>
                        @switch($borrowing->status)
                            @case('active')
                                @if($borrowing->isOverdue())
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-success">Aktif</span>
                                @endif
                                @break
                            @case('returned')
                                <span class="badge bg-secondary">Dikembalikan</span>
                                @break
                            @case('overdue')
                                <span class="badge bg-danger">Terlambat</span>
                                @break
                            @default
                                <span class="badge bg-warning">{{ ucfirst($borrowing->status) }}</span>
                        @endswitch
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="btn btn-info" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.borrowings.edit', $borrowing) }}" class="btn btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
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
                    <td colspan="6" class="text-center text-muted py-3">Tidak ada data peminjaman</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Registered Users -->
<h2 class="section-title mb-3 mt-4" style="font-size: 1.8rem; font-weight: 700; color: #8B4513; border-bottom: 3px solid #D2691E; padding-bottom: 0.5rem;">Data Pengguna Terdaftar</h2>
<div class="table-responsive mb-4">
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Terdaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>{{ Str::limit($user->address, 40) }}</td>
                    <td>{{ optional($user->created_at)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">Tidak ada pengguna</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        {{ $users->links() }}
    </div>
</div>

@endsection

@section('extra-js')
    <script src="{{ asset('js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('js/pages/admin.js') }}"></script>
@endsection
