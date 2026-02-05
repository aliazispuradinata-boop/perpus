@extends('layouts.app')

@section('title', 'Login')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">
    <style>
        body { background: linear-gradient(135deg, #FFF8DC 0%, #f5f5f5 100%) !important; }
        main { padding: 0 !important; background: linear-gradient(135deg, #FFF8DC 0%, #f5f5f5 100%) !important; }
    </style>
@endsection

@section('content')
<div class="auth-container" style="background: linear-gradient(135deg, #FFF8DC 0%, #f5f5f5 100%); min-height: calc(100vh - 150px); display: flex; align-items: center; justify-content: center; flex-direction: column; padding: 3rem 1rem;">
    <div class="auth-card" style="background: white; border-radius: 12px; box-shadow: 0 8px 30px rgba(139, 69, 19, 0.2); overflow: hidden; max-width: 450px; width: 100%; margin-bottom: 2rem;">
        <div class="auth-header" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; padding: 2.5rem 2rem; text-align: center;">
            <h2 style="margin: 0; font-family: 'Merriweather', serif; font-size: 2rem; display: flex; align-items: center; justify-content: center; gap: 12px; font-weight: 700;"><i class="fas fa-sign-in-alt" style="font-size: 1.8rem;"></i> Login</h2>
        </div>
        <div class="auth-body" style="padding: 2.5rem 2rem;">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="email" class="form-label" style="font-weight: 600; color: #2C1810; margin-bottom: 0.7rem; display: block; font-size: 0.95rem;">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required autofocus
                           style="background-color: #f9f9f9 !important; border: 2px solid #E8D5C4 !important; border-radius: 8px !important; padding: 0.85rem 1rem !important; transition: all 0.3s ease !important; font-size: 0.95rem;">
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="password" class="form-label" style="font-weight: 600; color: #2C1810; margin-bottom: 0.7rem; display: block; font-size: 0.95rem;">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required
                           style="background-color: #f9f9f9 !important; border: 2px solid #E8D5C4 !important; border-radius: 8px !important; padding: 0.85rem 1rem !important; transition: all 0.3s ease !important; font-size: 0.95rem;">
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check" style="margin-bottom: 1.5rem; display: flex; align-items: center;">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                           style="width: 1.2rem; height: 1.2rem; border: 2px solid #D2691E !important; accent-color: #8B4513 !important; cursor: pointer;">
                    <label class="form-check-label" for="remember" style="margin-left: 0.7rem; color: #2C1810; font-size: 0.95rem; cursor: pointer;">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-login w-100" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%) !important; color: white !important; border: none !important; padding: 0.95rem 1rem !important; font-weight: 600 !important; border-radius: 8px !important; font-size: 1rem !important; cursor: pointer !important; transition: all 0.3s ease !important;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <hr class="divider" style="border: none; border-top: 1px solid #E8D5C4; margin: 2rem 0; opacity: 0.5;">

            <div class="auth-footer" style="text-align: center; color: #2C1810;">
                <p style="margin: 0; font-size: 0.95rem;">Belum punya akun? 
                    <a href="{{ route('register') }}" style="color: #D2691E; font-weight: 600; text-decoration: none; transition: color 0.3s ease;">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>

    <div class="auth-card demo-card" style="background: linear-gradient(135deg, rgba(210, 105, 30, 0.1) 0%, rgba(244, 164, 96, 0.1) 100%); border: 2px solid #E8D5C4; max-width: 450px; margin-top: 2rem; padding: 1.5rem 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(139, 69, 19, 0.1);">
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
