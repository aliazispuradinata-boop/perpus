@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="page-title" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; padding: 2rem; margin: -2rem -2rem 1rem -2rem; border-radius: 0 0 12px 12px;">
    <h1 style="font-weight:700;"><i class="fas fa-user"></i> Detail Pengguna</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>{{ $user->name }}</h5>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        <p><strong>Telepon:</strong> {{ $user->phone ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>
        <p><strong>Terdaftar:</strong> {{ optional($user->created_at)->format('d M Y H:i') }}</p>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
