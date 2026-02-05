@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 style="color: #8B4513; font-weight: bold;">
                <i class="fas fa-key"></i> Ubah Password
            </h1>
            <p class="text-muted">Perbarui password akun Anda untuk keamanan yang lebih baik</p>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Oops, ada kesalahan!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Password Form -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #8B4513;">
                <div class="card-header" style="background-color: #F4A460; color: white;">
                    <h5 class="mb-0"><i class="fas fa-lock"></i> Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-bold" style="color: #8B4513;">
                                <i class="fas fa-lock"></i> Password Saat Ini
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control form-control-lg @error('current_password') is-invalid @enderror" 
                                    id="current_password" 
                                    name="current_password" 
                                    required
                                >
                                <button class="btn btn-outline-secondary" type="button" id="toggleCurrent">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Masukkan password Anda saat ini</small>
                        </div>

                        <hr style="border-color: #D2691E;">

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold" style="color: #8B4513;">
                                <i class="fas fa-key"></i> Password Baru
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    required
                                >
                                <button class="btn btn-outline-secondary" type="button" id="toggleNew">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Minimal 8 karakter, kombinasi huruf dan angka disarankan</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold" style="color: #8B4513;">
                                <i class="fas fa-key"></i> Konfirmasi Password Baru
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    required
                                >
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Ulangi password baru Anda</small>
                        </div>

                        <hr style="border-color: #D2691E;">

                        <!-- Password Strength Indicator -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #8B4513;">Kekuatan Password:</label>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" id="strengthBar" role="progressbar" style="width: 0%; background-color: #8B4513;"></div>
                            </div>
                            <small class="form-text text-muted">
                                <span id="strengthText">Sangat Lemah</span>
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-lg btn-primary" style="background-color: #8B4513; border: none;">
                                <i class="fas fa-save"></i> Ubah Password
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn btn-lg btn-outline-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Safety Tips -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4" style="border-left: 4px solid #dc3545;">
                <div class="card-header" style="background-color: #dc3545; color: white;">
                    <h5 class="mb-0"><i class="fas fa-shield-alt"></i> Tips Keamanan</h5>
                </div>
                <div class="card-body">
                    <h6 style="color: #8B4513;">Password yang kuat harus memiliki:</h6>
                    <ul class="small">
                        <li>
                            <i class="fas fa-check text-success"></i>
                            <strong>Minimal 8 karakter</strong>
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i>
                            <strong>Kombinasi huruf besar & kecil</strong> (A-Z, a-z)
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i>
                            <strong>Angka</strong> (0-9)
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i>
                            <strong>Karakter khusus</strong> (!@#$%^&*)
                        </li>
                    </ul>
                    <hr style="border-color: #D2691E;">
                    <h6 style="color: #8B4513;">Yang HARUS dihindari:</h6>
                    <ul class="small">
                        <li>
                            <i class="fas fa-times text-danger"></i>
                            <strong>Jangan gunakan tanggal lahir</strong>
                        </li>
                        <li>
                            <i class="fas fa-times text-danger"></i>
                            <strong>Jangan gunakan nama pengguna</strong>
                        </li>
                        <li>
                            <i class="fas fa-times text-danger"></i>
                            <strong>Jangan gunakan kata-kata umum</strong>
                        </li>
                        <li>
                            <i class="fas fa-times text-danger"></i>
                            <strong>Jangan bagikan password ke orang lain</strong>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="alert alert-info" style="border-color: #0d6efd;">
                <i class="fas fa-lightbulb"></i>
                <strong>Saran:</strong> Ubah password Anda setiap 3 bulan sekali untuk keamanan maksimal.
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = {
        current: document.getElementById('toggleCurrent'),
        new: document.getElementById('toggleNew'),
        confirm: document.getElementById('toggleConfirm')
    };

    const fields = {
        current: document.getElementById('current_password'),
        new: document.getElementById('password'),
        confirm: document.getElementById('password_confirmation')
    };

    // Toggle visibility
    toggleButtons.current.addEventListener('click', function() {
        togglePassword(fields.current, this);
    });

    toggleButtons.new.addEventListener('click', function() {
        togglePassword(fields.new, this);
    });

    toggleButtons.confirm.addEventListener('click', function() {
        togglePassword(fields.confirm, this);
    });

    // Password strength indicator
    fields.new.addEventListener('input', function() {
        updatePasswordStrength(this.value);
    });

    function togglePassword(field, button) {
        if (field.type === 'password') {
            field.type = 'text';
            button.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            field.type = 'password';
            button.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }

    function updatePasswordStrength(password) {
        let strength = 0;
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        if (password.length >= 8) strength += 20;
        if (password.length >= 12) strength += 10;
        if (/[a-z]/.test(password)) strength += 15;
        if (/[A-Z]/.test(password)) strength += 15;
        if (/[0-9]/.test(password)) strength += 15;
        if (/[!@#$%^&*]/.test(password)) strength += 10;

        strengthBar.style.width = strength + '%';

        if (strength < 30) {
            strengthText.textContent = 'Sangat Lemah';
            strengthBar.style.backgroundColor = '#dc3545';
        } else if (strength < 50) {
            strengthText.textContent = 'Lemah';
            strengthBar.style.backgroundColor = '#ffc107';
        } else if (strength < 70) {
            strengthText.textContent = 'Sedang';
            strengthBar.style.backgroundColor = '#17a2b8';
        } else if (strength < 90) {
            strengthText.textContent = 'Kuat';
            strengthBar.style.backgroundColor = '#28a745';
        } else {
            strengthText.textContent = 'Sangat Kuat';
            strengthBar.style.backgroundColor = '#20c997';
        }
    }
});
</script>

<style>
    .form-control-lg, .form-control-lg:focus {
        border-color: #D2691E;
    }

    .form-control-lg:focus {
        box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25);
    }

    .input-group .form-control:focus {
        border-color: #D2691E;
    }

    .input-group .btn-outline-secondary {
        border-color: #D2691E;
        color: #8B4513;
    }

    .input-group .btn-outline-secondary:hover {
        background-color: #8B4513;
        border-color: #8B4513;
        color: white;
    }

    .form-label {
        color: #8B4513;
    }

    .invalid-feedback {
        display: block;
    }
</style>
@endsection
