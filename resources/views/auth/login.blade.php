<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($mode) && $mode === 'register' ? 'Daftar Akun' : 'Masuk' }} — BOSS KOPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body {
            background: #1A1A1A;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
            background-image: radial-gradient(ellipse at 20% 50%, rgba(201,168,76,0.08) 0%, transparent 60%),
                              radial-gradient(ellipse at 80% 20%, rgba(201,168,76,0.05) 0%, transparent 50%);
        }
        .auth-card {
            background: #242424;
            border-radius: 24px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.5), 0 0 0 1px rgba(201,168,76,0.15);
            overflow: hidden;
            max-width: 440px;
            width: 100%;
        }
        .auth-header {
            background: linear-gradient(135deg, #1A1A1A, #2D2D2D);
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(201,168,76,0.2);
        }
        .auth-logo {
            width: 90px; height: 90px;
            background: rgba(201,168,76,0.1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
            border: 2px solid rgba(201,168,76,0.3);
        }
        .auth-title { color: #C9A84C; font-size: 1.6rem; font-weight: 800; margin: 0; letter-spacing: 4px; }
        .auth-subtitle { color: rgba(255,255,255,0.35); font-size: 0.65rem; letter-spacing: 3px; text-transform: uppercase; margin-top: 4px; }
        .auth-body { padding: 2rem; }
        .tab-switcher {
            display: flex;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 1.75rem;
            border: 1px solid rgba(201,168,76,0.15);
        }
        .tab-btn {
            flex: 1; padding: 0.55rem;
            border: none; background: transparent;
            border-radius: 9px; font-weight: 600;
            font-size: 0.85rem; cursor: pointer;
            transition: all 0.2s; color: rgba(255,255,255,0.4);
        }
        .tab-btn.active {
            background: var(--gold, #C9A84C);
            color: #1A1A1A;
            box-shadow: 0 2px 12px rgba(201,168,76,0.4);
        }
        .form-control {
            border-radius: 10px;
            border: 1.5px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            padding: 0.65rem 1rem;
            font-size: 0.875rem;
            color: #fff;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #C9A84C;
            box-shadow: 0 0 0 3px rgba(201,168,76,0.15);
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.25); }
        .form-label { font-weight: 600; color: rgba(255,255,255,0.7); font-size: 0.82rem; margin-bottom: 5px; }
        .input-group-text {
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: rgba(255,255,255,0.3);
        }
        .input-group .form-control { border-radius: 0 10px 10px 0; border-left: none; }
        .btn-masuk {
            background: linear-gradient(135deg, #C9A84C, #E8C96A);
            color: #1A1A1A;
            border: none;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 800;
            font-size: 0.9rem;
            width: 100%;
            transition: all 0.2s;
            letter-spacing: 0.5px;
        }
        .btn-masuk:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(201,168,76,0.5);
            color: #1A1A1A;
        }
        .is-invalid { border-color: #ff4d4d !important; }
        .invalid-feedback { font-size: 0.78rem; color: #ff6b6b; }
        .auth-footer { text-align: center; margin-top: 1rem; font-size: 0.78rem; color: rgba(255,255,255,0.3); }
        .role-card {
            border: 1.5px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.5);
            transition: all 0.2s; border-radius: 10px;
            background: rgba(255,255,255,0.03);
        }
        .role-card i { font-size: 1.2rem; }
        .role-card.active {
            background: rgba(201,168,76,0.15);
            color: #C9A84C;
            border-color: #C9A84C;
        }
        .info-roles {
            background: rgba(201,168,76,0.08);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
            margin-bottom: 1.25rem;
            border: 1px solid rgba(201,168,76,0.15);
        }
        .form-check-input:checked { background-color: #C9A84C; border-color: #C9A84C; }
        a { color: #C9A84C; }
        a:hover { color: #E8C96A; }
    </style>
</head>
<body>
<div class="auth-card">
    <!-- Header -->
    <div class="auth-header">
        <div class="auth-logo">
            <svg width="58" height="58" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="46" stroke="#C9A84C" stroke-width="2.5" fill="none"/>
                <path d="M36 22 Q33 16 36 11" stroke="#C9A84C" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <path d="M50 20 Q47 13 50 8" stroke="#C9A84C" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <path d="M64 22 Q61 16 64 11" stroke="#C9A84C" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <path d="M28 38 L34 72 Q50 78 66 72 L72 38 Z" fill="#C9A84C"/>
                <path d="M72 45 Q85 45 85 55 Q85 65 72 65" stroke="#C9A84C" stroke-width="3.5" fill="none" stroke-linecap="round"/>
                <ellipse cx="50" cy="56" rx="10" ry="7" fill="#1A1A1A"/>
                <path d="M50 49 Q53 56 50 63" stroke="#C9A84C" stroke-width="2" fill="none" stroke-linecap="round"/>
                <ellipse cx="50" cy="74" rx="24" ry="3.5" fill="#C9A84C" opacity="0.2"/>
            </svg>
        </div>
        <div class="auth-title">BOSS KOPI</div>
        <div class="auth-subtitle">Sunggal · Sistem Manajemen</div>
    </div>

    <!-- Body -->
    <div class="auth-body">

        <!-- Tab Switcher -->
        <div class="tab-switcher">
            <button class="tab-btn {{ !isset($mode) || $mode !== 'register' ? 'active' : '' }}"
                onclick="switchTab('login')">
                <i class="fa fa-right-to-bracket me-1"></i> Masuk
            </button>
            <button class="tab-btn {{ isset($mode) && $mode === 'register' ? 'active' : '' }}"
                onclick="switchTab('register')">
                <i class="fa fa-user-plus me-1"></i> Daftar
            </button>
        </div>

        @include('components.alerts')

        <!-- FORM LOGIN -->
        <div id="formLogin" style="{{ isset($mode) && $mode === 'register' ? 'display:none' : '' }}">
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Masukkan email Anda"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" name="password" id="passwordLogin"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan kata sandi" required>
                        <button type="button" class="btn btn-outline-secondary border-start-0"
                            onclick="togglePass('passwordLogin', this)"
                            style="border-radius:0 10px 10px 0;border-color:#E6D5C3;">
                            <i class="fa fa-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:0.8rem;">Ingat saya</label>
                    </div>
                    <a href="{{ route('lupa-password.form') }}" style="font-size:0.78rem;color:#9c7c5e;text-decoration:none;">
                        Lupa kata sandi?
                    </a>
                </div>

                <button type="submit" class="btn-masuk">
                    <i class="fa fa-right-to-bracket me-2"></i> Masuk Sekarang
                </button>
            </form>
        </div>

        <!-- FORM REGISTER -->
        <div id="formRegister" style="{{ !isset($mode) || $mode !== 'register' ? 'display:none' : '' }}">
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Daftar Sebagai</label>
                    <div class="d-flex gap-2">
                        <label class="flex-fill" style="cursor:pointer;">
                            <input type="radio" name="role" value="pembeli" class="d-none role-radio" checked onchange="pilihRole(this)">
                            <div class="role-card active text-center py-2 rounded" data-role="pembeli">
                                <i class="fa fa-mug-hot d-block mb-1"></i>
                                <span style="font-size:0.8rem;">Pembeli</span>
                            </div>
                        </label>
                        <label class="flex-fill" style="cursor:pointer;">
                            <input type="radio" name="role" value="driver" class="d-none role-radio" onchange="pilihRole(this)">
                            <div class="role-card text-center py-2 rounded" data-role="driver">
                                <i class="bi bi-bicycle d-block mb-1"></i>
                                <span style="font-size:0.8rem;">Driver</span>
                            </div>
                        </label>
                    </div>
                    @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nama lengkap Anda"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Alamat email aktif"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                            placeholder="Contoh: 08123456789"
                            value="{{ old('no_hp') }}" required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Field khusus Pembeli -->
                <div id="fieldPembeli" class="mb-3">
                    <label class="form-label">Alamat Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-map-pin"></i></span>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                            rows="2" placeholder="Jalan, No. Rumah, Kelurahan, Kecamatan...">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted">Memudahkan pengiriman delivery nantinya, bisa diubah lagi di Profil.</small>
                </div>

                <!-- Field khusus Driver -->
                <div id="fieldDriver" class="mb-3" style="display:none;">
                    <label class="form-label">Jenis Kendaraan</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="bi bi-bicycle"></i></span>
                        <input type="text" name="jenis_kendaraan" class="form-control @error('jenis_kendaraan') is-invalid @enderror"
                            placeholder="Contoh: Honda Beat, Yamaha NMAX"
                            value="{{ old('jenis_kendaraan') }}">
                        @error('jenis_kendaraan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <label class="form-label">Plat Nomor</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                        <input type="text" name="plat_nomor" class="form-control @error('plat_nomor') is-invalid @enderror"
                            placeholder="Contoh: BK 1234 ABC"
                            value="{{ old('plat_nomor') }}">
                        @error('plat_nomor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" name="password" id="passwordReg"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimal 6 karakter" required>
                        <button type="button" class="btn btn-outline-secondary border-start-0"
                            onclick="togglePass('passwordReg', this)"
                            style="border-radius:0 10px 10px 0;border-color:#E6D5C3;">
                            <i class="fa fa-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Konfirmasi Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" name="password_confirmation" id="passwordConf"
                            class="form-control"
                            placeholder="Ulangi kata sandi" required>
                        <button type="button" class="btn btn-outline-secondary border-start-0"
                            onclick="togglePass('passwordConf', this)"
                            style="border-radius:0 10px 10px 0;border-color:#E6D5C3;">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-masuk">
                    <i class="fa fa-user-plus me-2"></i> Buat Akun Baru
                </button>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function switchTab(tab) {
    const loginForm = document.getElementById('formLogin');
    const regForm = document.getElementById('formRegister');
    const tabs = document.querySelectorAll('.tab-btn');

    if (tab === 'login') {
        loginForm.style.display = '';
        regForm.style.display = 'none';
        tabs[0].classList.add('active');
        tabs[1].classList.remove('active');
    } else {
        loginForm.style.display = 'none';
        regForm.style.display = '';
        tabs[0].classList.remove('active');
        tabs[1].classList.add('active');
    }
}

function pilihRole(input) {
    document.querySelectorAll('.role-card').forEach(c => c.classList.remove('active'));
    input.closest('label').querySelector('.role-card').classList.add('active');

    const fieldPembeli = document.getElementById('fieldPembeli');
    const fieldDriver = document.getElementById('fieldDriver');
    if (input.value === 'driver') {
        fieldPembeli.style.display = 'none';
        fieldDriver.style.display = '';
    } else {
        fieldPembeli.style.display = '';
        fieldDriver.style.display = 'none';
    }
}

function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Jika ada error register, otomatis buka tab register
@if(isset($mode) && $mode === 'register')
switchTab('register');
@endif
</script>
</body>
</html>
