<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi WhatsApp — BOSS KOPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background:#FDFBF7; display:flex; align-items:center; justify-content:center; min-height:100vh; font-family:'Segoe UI',sans-serif; }
        .card { border:none; border-radius:18px; box-shadow:0 4px 24px rgba(74,53,37,0.12); max-width:420px; width:100%; }
        .btn-coffee { background:linear-gradient(135deg,#C9A84C,#E8C96A); color:#fff; border-radius:10px; font-weight:600; border:none; }
        .btn-coffee:hover { background:#3a2a1e; color:#fff; }
        .btn-wa { background:#25D366; color:#fff; border-radius:10px; font-weight:600; border:none; }
        .btn-wa:hover { background:#1ebe5d; color:#fff; }
        .wa-icon { width:70px; height:70px; background:#25D366; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto; }
        .kode-input { letter-spacing:0.4rem; font-size:1.6rem; text-align:center; font-weight:700; border-radius:12px; border:2px solid #C9A84C33; }
        .kode-input:focus { border-color:#C9A84C; box-shadow:0 0 0 3px rgba(74,53,37,0.1); }
        .step-badge { width:24px; height:24px; background:linear-gradient(135deg,#C9A84C,#E8C96A); color:#fff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; flex-shrink:0; }
    </style>
</head>
<body>
<div class="container px-3 py-4">
    <div class="card p-4 mx-auto">

        {{-- Logo WA --}}
        <div class="text-center mb-4">
            <div class="wa-icon mb-3">
                <i class="bi bi-whatsapp" style="font-size:2.2rem;color:#fff;"></i>
            </div>
            <h5 class="fw-bold mb-1" style="color:#C9A84C;">Verifikasi WhatsApp</h5>
            <p class="text-muted small mb-0">
                Verifikasi nomor WA Anda cukup <strong>sekali saja</strong> —<br>
                setelah itu bisa langsung pesan delivery kapanpun.
            </p>
        </div>

        @if(session('info'))
        <div class="alert alert-info small py-2 mb-3">{{ session('info') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger small py-2 mb-3">{{ $errors->first() }}</div>
        @endif

        {{-- Langkah-langkah --}}
        <div class="p-3 rounded mb-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
            <div class="fw-bold small mb-2" style="color:#166534;">Cara verifikasi:</div>
            <div class="d-flex align-items-start gap-2 mb-2">
                <span class="step-badge">1</span>
                <small class="text-muted">Klik tombol "Hubungi Admin" di bawah → WA Admin langsung terbuka</small>
            </div>
            <div class="d-flex align-items-start gap-2 mb-2">
                <span class="step-badge">2</span>
                <small class="text-muted">Kirim pesan ke Admin, minta kode verifikasi untuk nomor <strong>{{ $user->no_hp }}</strong></small>
            </div>
            <div class="d-flex align-items-start gap-2 mb-2">
                <span class="step-badge">3</span>
                <small class="text-muted">Admin akan kirim kode 6 digit ke WA Anda</small>
            </div>
            <div class="d-flex align-items-start gap-2">
                <span class="step-badge">4</span>
                <small class="text-muted">Masukkan kode di bawah → klik Verifikasi → selesai!</small>
            </div>
        </div>

        {{-- Tombol Hubungi Admin --}}
        @php
            $admin = \App\Models\User::where('role', 'admin')->first();
            $noAdmin = $admin ? preg_replace('/[^0-9]/', '', $admin->no_hp) : '62895333301223';
            if (str_starts_with($noAdmin, '0')) $noAdmin = '62' . substr($noAdmin, 1);
            $pesanAdmin = urlencode("Halo Admin BOSS KOPI! 👋\nSaya " . $user->name . " ingin meminta kode verifikasi WhatsApp untuk nomor " . $user->no_hp . ".\nMohon bantuannya. Terima kasih! 🙏");
        @endphp
        <a href="https://wa.me/{{ $noAdmin }}?text={{ $pesanAdmin }}" target="_blank"
            class="btn btn-wa w-100 py-2 mb-3">
            <i class="bi bi-whatsapp me-2"></i> Hubungi Admin untuk Dapat Kode
        </a>

        {{-- Form input kode --}}
        <form action="{{ route('verifikasi-wa.verify') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold small">Masukkan Kode Verifikasi (6 digit)</label>
                <input type="text" name="kode" inputmode="numeric" pattern="[0-9]*"
                    class="form-control kode-input @error('kode') is-invalid @enderror"
                    maxlength="6" placeholder="_ _ _ _ _ _"
                    autocomplete="one-time-code" autofocus>
                @error('kode') <div class="invalid-feedback text-center">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-coffee w-100 py-2">
                <i class="bi bi-check-circle me-2"></i> Verifikasi Sekarang
            </button>
        </form>

        <hr class="my-3">

        <div class="text-center">
            <a href="{{ route('pembeli.home') }}" class="text-decoration-none small text-muted">
                Lewati dulu — tapi tidak bisa pesan delivery →
            </a>
        </div>
    </div>
</div>
</body>
</html>
