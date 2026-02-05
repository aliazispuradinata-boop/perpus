@extends('layouts.app')

@section('title', 'Bukti Peminjaman')

@section('content')
<div class="page-title" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; padding: 2rem; margin: -2rem -2rem 1rem -2rem; border-radius: 0 0 12px 12px;">
    <h1 style="font-weight:700;"><i class="fas fa-receipt"></i> Bukti Peminjaman</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h5>{{ $borrowing->book->title }}</h5>
                <p><strong>Penulis:</strong> {{ $borrowing->book->author }}</p>
                <p><strong>Member:</strong> {{ $borrowing->user->name }} ({{ $borrowing->user->email }})</p>
                <p><strong>Tanggal Pinjam:</strong> {{ $borrowing->borrowed_at->format('d M Y') }}</p>
                <p><strong>Durasi:</strong> {{ $borrowing->duration_days }} hari</p>
                <p><strong>Tanggal Kembali:</strong> {{ $borrowing->due_date->format('d M Y') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($borrowing->status) }}</p>
            </div>
            <div class="col-md-4 text-center">
                @if($borrowing->qr_code)
                    <img src="{{ asset('storage/' . $borrowing->qr_code) }}" alt="QR Code" style="width:200px; height:200px; border:1px solid #eee;">
                @else
                    <div class="border p-4">QR code tidak tersedia</div>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Cetak / Simpan PDF</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
