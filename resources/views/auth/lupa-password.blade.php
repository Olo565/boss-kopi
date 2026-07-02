<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi — BOSS KOPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background:#1A1A1A; min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',sans-serif; padding:1.5rem; }
        .card { border:none; border-radius:20px; box-shadow:0 8px 32px rgba(74,53,37,0.12); max-width:420px; width:100%; }
        .btn-coffee { background:linear-gradient(135deg,#C9A84C,#E8C96A); color:#fff; border:none; border-radius:10px; padding:0.7rem; font-weight:700; width:100%; }
        .btn-coffee:hover { background:#3a2a1e; color:#fff; }
        .form-control { border-radius:10px; border:1.5px solid #C9A84C33; padding:0.65rem 1rem; }
        .form-control:focus { border-color:#C9A84C; box-shadow:0 0 0 3px rgba(74,53,37,0.08); }
        .form-label { font-weight:600; color:#C9A84C; font-size:0.85rem; }
    </style>
</head>
<body>
<div class="card p-4">
    <div class="text-center mb-4">
        <div style="font-size:3rem;">🔑</div>
        <h5 class="fw-700 mt-2" style="color:#C9A84C;">Lupa Kata Sandi?</h5>
        <p class="text-muted small">Masukkan email akun Anda. Kami akan kirim kode reset ke WhatsApp Anda.</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger small py-2 mb-3">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('lupa-password.kirim') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                placeholder="email@contoh.com" value="{{ old('email') }}" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn-coffee btn mb-3">
            <i class="bi bi-whatsapp me-2"></i> Kirim Kode Reset via WhatsApp
        </button>
    </form>

    <div class="text-center">
        <a href="{{ route('login') }}" class="text-decoration-none small" style="color:rgba(255,255,255,0.4);">
            ← Kembali ke Halaman Login
        </a>
    </div>
</div>
</body>
</html>
