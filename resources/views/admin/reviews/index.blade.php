@extends('layouts.app')

@section('title', 'Moderasi Ulasan')

@section('content')
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/admin-common.css') }}">
@endsection

<div class="books-header">
    <h1><i class="fas fa-comments"></i> Moderasi Ulasan</h1>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="btn-toolbar">
    <a href="{{ route('admin.reviews.export-csv') }}" class="btn btn-info" style="background: linear-gradient(135deg,#3b82f6 0%,#2563eb 100%); color:white; border:none;">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <p class="px-4 py-3">Daftar ulasan yang menunggu persetujuan.</p>

        <form method="POST" id="bulkForm" onsubmit="return false;">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;"><input type="checkbox" id="selectAll"></th>
                            <th>User</th>
                            <th>Book</th>
                            <th>Rating</th>
                            <th>Isi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $review->id }}"></td>
                                <td>{{ $review->user->name }}</td>
                                <td>{{ Str::limit($review->book->title, 40) }}</td>
                                <td>{{ $review->rating }}</td>
                                <td>{{ Str::limit($review->content, 120) }}</td>
                                <td>{{ $review->created_at->format('d M Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" style="display:inline-block; margin-left:6px;">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">Tolak</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Tidak ada ulasan menunggu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" id="bulkApproveBtn" class="btn btn-success btn-sm">Setujui Terpilih</button>
                    <button type="button" id="bulkRejectBtn" class="btn btn-danger btn-sm ms-2">Tolak Terpilih</button>
                </div>
                <div>
                    {{ $reviews->links() }}
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('input[name="ids[]"]');
        selectAll && selectAll.addEventListener('change', function(){
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });

        const bulkForm = document.getElementById('bulkForm');
        document.getElementById('bulkApproveBtn').addEventListener('click', function(){
            bulkForm.action = '{{ route('admin.reviews.bulk-approve') }}';
            bulkForm.method = 'POST';
            bulkForm.submit();
        });
        document.getElementById('bulkRejectBtn').addEventListener('click', function(){
            bulkForm.action = '{{ route('admin.reviews.bulk-reject') }}';
            bulkForm.method = 'POST';
            bulkForm.submit();
        });
    });
</script>
@endsection
