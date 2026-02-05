@extends('layouts.app')

@section('title', 'Edit Petugas')

@section('content')
<div class="page-title" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; padding: 2rem; margin: -2rem -2rem 1rem -2rem; border-radius: 0 0 12px 12px;">
    <h1 style="font-weight:700;"><i class="fas fa-user-edit"></i> Edit Petugas - {{ $user->name }}</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.petugas.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08123456789">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-bold">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" placeholder="Jl. Perpustakaan No. 1, Kota">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong style="color: #8B4513;">Petugas Aktif</strong>
                                <small class="d-block text-muted">Jika dicentang, petugas dapat login ke sistem</small>
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-info" style="background-color: #FFF8DC; border-color: #D2691E; color: #2C1810;">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Terdaftar:</strong> {{ $user->created_at->format('d F Y H:i') }} | 
                        <strong>Terakhir Login:</strong> {{ optional($user->last_login)->format('d F Y H:i') ?? 'Belum pernah login' }}
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card" style="background: linear-gradient(135deg, rgba(210, 105, 30, 0.1) 0%, rgba(244, 164, 96, 0.1) 100%); border: 2px solid #E8D5C4;">
            <div class="card-body">
                <h5 class="card-title" style="color: #8B4513;">⚙️ Aksi Lainnya</h5>
                
                <div class="mb-3">
                    <form method="POST" action="{{ route('admin.petugas.destroy', $user) }}" 
                          onsubmit="return confirm('Yakin ingin menghapus petugas ini? Data tidak dapat dikembalikan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Hapus Petugas
                        </button>
                    </form>
                </div>

                <hr>

                <h6 style="color: #8B4513;" class="fw-bold mt-3">📊 Informasi Petugas</h6>
                <div class="mb-2">
                    <small class="text-muted">ID:</small><br>
                    <code>{{ $user->id }}</code>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Role:</small><br>
                    <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Status:</small><br>
                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
