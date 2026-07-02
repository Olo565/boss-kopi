<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi — BOSS KOPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background:#1A1A1A; min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',sans-serif; padding:1.5rem; }
        .card { border:none; border-radius:20px; box-shadow:0 8px 32px rgba(74,53,37,0.12); max-width:440px; width:100%; }
        .btn-coffee { background:linear-gradient(135deg,#C9A84C,#E8C96A); color:#fff; border:none; border-radius:10px; padding:0.7rem; font-weight:700; width:100%; }
        .btn-coffee:hover { background:#3a2a1e; color:#fff; }
        .btn-wa { background:#25D366; color:#fff; border:none; border-radius:10px; padding:0.65rem; font-weight:700; width:100%; }
        .btn-wa:hover { background:#1ebe5d; color:#fff; }
        .form-control { border-radius:10px; border:1.5px solid #C9A84C33; padding:0.65rem 1rem; }
        .form-control:focus { border-color:#C9A84C; box-shadow:0 0 0 3px rgba(74,53,37,0.08); }
        .form-label { font-weight:600; color:#C9A84C; font-size:0.85rem; }
        .kode-input { letter-spacing:0.5rem; font-size:1.5rem; text-align:center; font-weight:700; }
        .step-badge { width:28px; height:28px; background:linear-gradient(135deg,#C9A84C,#E8C96A); color:#fff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:700; flex-shrink:0; }
    </style>
</head>
<body>
<div class="card p-4">
    <div class="text-center mb-4">
        <div style="font-size:2.5rem;">📱</div>
        <h5 class="fw-700 mt-2" style="color:#C9A84C;">Reset Kata Sandi</h5>
        <p class="text-muted small mb-0">Kode dikirim ke WhatsApp nomor yang terdaftar untuk <strong>{{ session('reset_nama') }}</strong></p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger small py-2 mb-3">{{ $errors->first() }}</div>
    @endif

    {{-- Step 1: Buka WA untuk terima kode --}}
    <div class="p-3 rounded mb-4" style="background:#f0fdf4; border:1px solid #bbf7d0;">
        <div class="d-flex align-items-start gap-2 mb-2">
            <span class="step-badge">1</span>
            <small class="text-muted">Klik tombol di bawah untuk buka WhatsApp dan kirim pesan ke Admin — kode reset akan dikirimkan ke nomor WA Anda</small>
        </div>
        <div class="d-flex align-items-start gap-2 mb-2">
            <span class="step-badge">2</span>
            <small class="text-muted">Tunggu Admin membalas dengan kode 6 digit</small>
        </div>
        <div class="d-flex align-items-start gap-2">
            <span class="step-badge">3</span>
            <small class="text-muted">Masukkan kode dan buat kata sandi baru di bawah</small>
        </div>
    </div>

    <a href="{{ session('reset_wa_url') }}" target="_blank" class="btn btn-wa mb-4">
        <i class="bi bi-whatsapp me-2"></i> Buka WhatsApp — Minta Kode Reset
    </a>

    {{-- Form kode + password baru --}}
    <form action="{{ route('lupa-password.reset') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kode Verifikasi (6 digit dari Admin)</label>
            <input type="text" name="kode" inputmode="numeric" pattern="[0-9]*"
                class="form-control kode-input @error('kode') is-invalid @enderror"
                maxlength="6" placeholder="_ _ _ _ _ _" autocomplete="one-time-code">
            @error('kode') <div class="invalid-feedback text-center">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Kata Sandi Baru</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Minimal 6 karakter" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="form-label">Konfirmasi Kata Sandi Baru</label>
            <input type="password" name="password_confirmation" class="form-control"
                placeholder="Ulangi kata sandi baru" required>
        </div>
        <button type="submit" class="btn-coffee btn mb-3">
            <i class="fa fa-check me-2"></i> Simpan Kata Sandi Baru
        </button>
    </form>

    <div class="text-center">
        <a href="{{ route('lupa-password.form') }}" class="text-decoration-none small" style="color:rgba(255,255,255,0.4);">
            ← Masukkan email lain
        </a>
    </div>
</div>
</body>
</html>
