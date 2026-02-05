@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">
    <style>
        body { background: linear-gradient(135deg, #FFF8DC 0%, #f5f5f5 100%) !important; }
        main { padding: 0 !important; background: linear-gradient(135deg, #FFF8DC 0%, #f5f5f5 100%) !important; }
    </style>
@endsection

@section('content')
<div class="auth-container" style="background: linear-gradient(135deg, #FFF8DC 0%, #f5f5f5 100%); min-height: calc(100vh - 150px); display: flex; align-items: center; justify-content: center; flex-direction: column; padding: 3rem 1rem;">
    <div class="auth-card" style="background: white; border-radius: 12px; box-shadow: 0 8px 30px rgba(139, 69, 19, 0.2); overflow: hidden; max-width: 500px; width: 100%; margin-bottom: 2rem;">
        <div class="auth-header" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; padding: 2.5rem 2rem; text-align: center;">
            <h2 style="margin: 0; font-family: 'Merriweather', serif; font-size: 2rem; display: flex; align-items: center; justify-content: center; gap: 12px; font-weight: 700;"><i class="fas fa-user-plus"></i> Daftar Akun</h2>
        </div>
        <div class="auth-body" style="padding: 2.5rem 2rem;">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="name" class="form-label" style="font-weight: 600; color: #2C1810; margin-bottom: 0.7rem; display: block; font-size: 0.95rem;">Nama Lengkap</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required autofocus
                           style="background-color: #f9f9f9 !important; border: 2px solid #E8D5C4 !important; border-radius: 8px !important; padding: 0.85rem 1rem !important; transition: all 0.3s ease !important; font-size: 0.95rem;">
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="email" class="form-label" style="font-weight: 600; color: #2C1810; margin-bottom: 0.7rem; display: block; font-size: 0.95rem;">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required
                           style="background-color: #f9f9f9 !important; border: 2px solid #E8D5C4 !important; border-radius: 8px !important; padding: 0.85rem 1rem !important; transition: all 0.3s ease !important; font-size: 0.95rem;">
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="phone" class="form-label" style="font-weight: 600; color: #2C1810; margin-bottom: 0.7rem; display: block; font-size: 0.95rem;">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="phone" name="phone" 
                           value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx"
                           style="background-color: #f9f9f9 !important; border: 2px solid #E8D5C4 !important; border-radius: 8px !important; padding: 0.85rem 1rem !important; transition: all 0.3s ease !important; font-size: 0.95rem;">
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="address" class="form-label" style="font-weight: 600; color: #2C1810; margin-bottom: 0.7rem; display: block; font-size: 0.95rem;">Alamat</label>
                    <textarea class="form-control" id="address" name="address" rows="3" 
                              placeholder="Masukkan alamat Anda"
                              style="background-color: #f9f9f9 !important; border: 2px solid #E8D5C4 !important; border-radius: 8px !important; padding: 0.85rem 1rem !important; transition: all 0.3s ease !important; font-size: 0.95rem; resize: vertical;">{{ old('address') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="password" class="form-label" style="font-weight: 600; color: #2C1810; margin-bottom: 0.7rem; display: block; font-size: 0.95rem;">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required
                           style="background-color: #f9f9f9 !important; border: 2px solid #E8D5C4 !important; border-radius: 8px !important; padding: 0.85rem 1rem !important; transition: all 0.3s ease !important; font-size: 0.95rem;">
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted d-block mt-1" style="color: #2C1810 !important; font-size: 0.85rem;">
                        Minimal 8 karakter, harus mengandung huruf besar, huruf kecil, dan angka.
                    </small>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="password_confirmation" class="form-label" style="font-weight: 600; color: #2C1810; margin-bottom: 0.7rem; display: block; font-size: 0.95rem;">Konfirmasi Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                           id="password_confirmation" name="password_confirmation" required
                           style="background-color: #f9f9f9 !important; border: 2px solid #E8D5C4 !important; border-radius: 8px !important; padding: 0.85rem 1rem !important; transition: all 0.3s ease !important; font-size: 0.95rem;">
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login w-100" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%) !important; color: white !important; border: none !important; padding: 0.95rem 1rem !important; font-weight: 600 !important; border-radius: 8px !important; font-size: 1rem !important; cursor: pointer !important; transition: all 0.3s ease !important;">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>

            <hr class="divider" style="border: none; border-top: 1px solid #E8D5C4; margin: 2rem 0; opacity: 0.5;">

            <div class="auth-footer" style="text-align: center; color: #2C1810;">
                <p style="margin: 0; font-size: 0.95rem;">Sudah punya akun? 
                    <a href="{{ route('login') }}" style="color: #D2691E; font-weight: 600; text-decoration: none; transition: color 0.3s ease;">Login di sini</a>
                </p>
            </div>
        </div>
    </div>

    <div class="auth-card demo-card" style="background: linear-gradient(135deg, rgba(210, 105, 30, 0.1) 0%, rgba(244, 164, 96, 0.1) 100%); border: 2px solid #E8D5C4; max-width: 500px; margin-top: 2rem; padding: 1.5rem 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(139, 69, 19, 0.1);">
        <p style="margin: 0 0 1rem 0; font-weight: 600; color: #8B4513;"><small>Demo Akun (untuk testing):</small></p>
        <p class="mb-2" style="margin: 0 0 0.8rem 0; color: #2C1810;"><small><strong>Admin:</strong> admin@retrolib.test / password</small></p>
        <p class="mb-2" style="margin: 0 0 0.8rem 0; color: #2C1810;"><small><strong>Petugas:</strong> budi@retrolib.test / password</small></p>
        <p style="margin: 0; color: #2C1810;"><small><strong>User:</strong> andi@retrolib.test / password</small></p>
    </div>
</div>
@endsection

@section('extra-js')
    <script src="{{ asset('js/pages/auth.js') }}"></script>
@endsection
