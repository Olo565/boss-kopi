<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan | BOSS KOPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#1A1A1A; min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',sans-serif; }
        .error-card { background:#242424; border-radius:20px; padding:3rem 2rem; text-align:center; max-width:420px; box-shadow:0 4px 24px rgba(74,53,37,0.1); }
        .error-code { font-size:5rem; font-weight:900; color:#E6D5C3; line-height:1; }
        .error-title { color:#C9A84C; font-weight:700; font-size:1.3rem; }
        .btn-back { background:#C9A84C; color:#1A1A1A; border:none; border-radius:10px; padding:0.6rem 2rem; font-weight:600; }
        .btn-back:hover { background:#3a2a1e; color:#1A1A1A; }
        .coffee-icon { font-size:4rem; margin-bottom:1rem; }
    </style>
</head>
<body>
<div class="error-card">
    <div class="coffee-icon">☕</div>
    <div class="error-code">404</div>
    <div class="error-title mt-2 mb-2">Halaman Tidak Ditemukan</div>
    <p class="text-muted small mb-4">Sepertinya halaman yang Anda cari sudah dipindah atau tidak ada. Coba kembali ke halaman utama.</p>
    <a href="{{ url('/') }}" class="btn btn-back">← Kembali ke Beranda</a>
</div>
</body>
</html>
