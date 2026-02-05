@extends('layouts.app')

@section('title', 'Kelola Petugas')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/admin-common.css') }}">
@endsection

@section('content')
<div class="books-header">
    <h1><i class="fas fa-user-shield"></i> Kelola Petugas</h1>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="btn-toolbar">
    <a href="{{ route('admin.petugas.create') }}" class="btn btn-success" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%); color:white; border:none;">
        <i class="fas fa-user-plus"></i> Tambah Petugas
    </a>
    <a href="{{ route('admin.petugas.export-csv') }}" class="btn btn-info" style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%); color:white; border:none;">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

<div class="table-card mb-4">
    <div class="card-body p-0">
        <p class="px-4 py-3">Daftar pengguna. Gunakan tombol untuk menetapkan atau mencabut role <strong>petugas</strong>.</p>

        <div class="table-responsive px-3 pb-3">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ optional($user->created_at)->format('d M Y') }}</td>
                            <td>
                                @if($user->role !== 'petugas')
                                    <form action="{{ route('admin.petugas.promote', $user->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button class="btn btn-sm btn-success" title="Jadikan Petugas">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.petugas.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.petugas.revoke', $user->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button class="btn btn-sm btn-secondary" title="Cabut Role Petugas">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.petugas.destroy', $user->id) }}" method="POST" style="display:inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus petugas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex justify-content-end">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
