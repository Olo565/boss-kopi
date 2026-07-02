<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BOSS KOPI')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        /* ============================================
           BOSS KOPI — BLACK GOLD MINIMALIST THEME
           ============================================ */
        :root {
            --gold: #C9A84C;
            --gold-light: #E8C96A;
            --gold-dark: #A8852A;
            --black: #1A1A1A;
            --black-soft: #2D2D2D;
            --black-card: #242424;
            --bg: #F5F0E8;
            --bg-card: #FFFFFF;
            --text: #1A1A1A;
            --text-muted: #8A8A8A;
            --border: #E8E0D0;
            --sidebar-w: 260px;

            /* Legacy aliases supaya kode lama tetap jalan */
            --bg-ivory: #F5F0E8;
            --latte: #E8E0D0;
            --coffee: #1A1A1A;
            --charcoal: #2D2D2D;
            --coffee-hover: #2D2D2D;
        }

        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
        body { background: var(--bg); color: var(--text); font-size: 0.875rem; }
        a { color: var(--gold); text-decoration: none; }
        a:hover { color: var(--gold-dark); }

        /* ---- SCROLLBAR ---- */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 10px; }

        /* ---- SIDEBAR ---- */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--black);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            overflow-y: auto;
            border-right: 1px solid rgba(201,168,76,0.15);
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(201,168,76,0.2);
            background: rgba(201,168,76,0.05);
        }
        .logo-text {
            color: var(--gold);
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: 3px;
        }
        .logo-sub {
            color: rgba(255,255,255,0.4);
            font-size: 0.65rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .sidebar-section-title {
            color: rgba(201,168,76,0.5);
            font-size: 0.6rem;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            font-weight: 700;
            padding: 1.25rem 1.25rem 0.4rem;
        }
        .nav-link {
            color: rgba(255,255,255,0.65) !important;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            margin: 1px 0.5rem;
            font-size: 0.82rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.2s;
        }
        .nav-link:hover {
            background: rgba(201,168,76,0.12);
            color: var(--gold) !important;
        }
        .nav-link.active {
            background: linear-gradient(135deg, rgba(201,168,76,0.25), rgba(201,168,76,0.1));
            color: var(--gold) !important;
            font-weight: 600;
            border-left: 3px solid var(--gold);
            border-radius: 0 8px 8px 0;
        }
        .nav-link i, .nav-link svg { width: 18px; text-align: center; font-size: 0.9rem; }

        /* ---- MAIN CONTENT ---- */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }
        .page-content { padding: 1.5rem; }

        /* ---- TOPBAR ---- */
        .topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* ---- CARDS ---- */
        .card {
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
            background: var(--bg-card);
            transition: all 0.2s;
        }
        .card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .card-header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            border-radius: 16px 16px 0 0 !important;
            font-weight: 700;
            color: var(--black);
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
        }
        .card-body { padding: 1.25rem; }
        .card-footer { background: var(--bg-card); border-top: 1px solid var(--border); border-radius: 0 0 16px 16px !important; }

        /* ---- STAT CARDS ---- */
        .stat-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.25rem;
            border: 1px solid var(--border);
            border-left: 4px solid var(--gold);
            transition: all 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(201,168,76,0.15); }
        .stat-icon {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.05));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: var(--gold);
        }
        .stat-value { font-size: 1.6rem; font-weight: 800; color: var(--black); }
        .stat-label { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; }

        /* ---- BUTTONS ---- */
        .btn-coffee, .btn-primary-custom {
            background: var(--gold);
            color: var(--black) !important;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s;
            font-size: 0.85rem;
        }
        .btn-coffee:hover {
            background: var(--gold-dark);
            color: var(--black) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(201,168,76,0.4);
        }
        .btn-latte {
            background: var(--bg);
            color: var(--black);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-latte:hover {
            background: var(--border);
            color: var(--black);
            border-color: var(--gold);
        }

        /* ---- BADGES ---- */
        .badge-coffee, .badge-gold {
            background: var(--gold);
            color: var(--black);
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.7rem;
        }
        .badge-latte {
            background: var(--bg);
            color: var(--black);
            border: 1px solid var(--border);
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.7rem;
        }

        /* ---- FORMS ---- */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--bg);
            font-size: 0.875rem;
            padding: 0.6rem 1rem;
            color: var(--black);
            transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,0.15);
            background: #fff;
        }
        .form-label { font-weight: 600; font-size: 0.82rem; color: var(--black); margin-bottom: 5px; }
        .input-group-text {
            background: var(--bg);
            border: 1.5px solid var(--border);
            color: var(--text-muted);
            border-radius: 10px 0 0 10px;
        }
        .input-group .form-control { border-radius: 0 10px 10px 0; border-left: none; }

        /* ---- TABLES ---- */
        .table { font-size: 0.83rem; }
        .table thead th {
            background: var(--bg);
            color: var(--text-muted);
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border);
            padding: 0.85rem 1rem;
        }
        .table tbody td { padding: 0.85rem 1rem; border-color: var(--border); vertical-align: middle; }
        .table-hover tbody tr:hover { background: rgba(201,168,76,0.04); }

        /* ---- PAGINATION ---- */
        .page-link {
            color: var(--black);
            border-color: var(--border);
            border-radius: 8px !important;
            margin: 0 2px;
            font-size: 0.8rem;
            padding: 0.35rem 0.65rem;
        }
        .page-link:hover { background: var(--bg); border-color: var(--gold); color: var(--gold); }
        .page-item.active .page-link { background: var(--gold); border-color: var(--gold); color: var(--black); font-weight: 700; }

        /* ---- ALERTS ---- */
        .alert { border-radius: 12px; border: none; font-size: 0.85rem; }
        .alert-success { background: rgba(45,106,79,0.1); color: #2D6A4F; }
        .alert-danger { background: rgba(220,53,69,0.1); color: #dc3545; }
        .alert-warning { background: rgba(201,168,76,0.15); color: #8a6c1a; }
        .alert-info { background: rgba(13,110,253,0.08); color: #0d6efd; }

        /* ---- GOLD DIVIDER ---- */
        .gold-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            border: none;
            margin: 1.5rem 0;
        }

        /* ---- UTILITY ---- */
        .fw-600 { font-weight: 600 !important; }
        .fw-700 { font-weight: 700 !important; }
        .fw-800 { font-weight: 800 !important; }
        .text-gold { color: var(--gold) !important; }
        .bg-gold { background: var(--gold) !important; color: var(--black) !important; }
        .border-gold { border-color: var(--gold) !important; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

@auth
@include('layouts.sidebar')
<div class="main-content">
    @include('layouts.topbar')
    <div class="page-content">
        @include('components.alerts')
        @yield('content')
    </div>
</div>
@else
@yield('content')
@endauth

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle sidebar mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });

    // Auto close alert
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            if (a.classList.contains('alert-dismissible')) {
                new bootstrap.Alert(a).close();
            }
        });
    }, 4000);
</script>
@stack('scripts')

@if(auth()->check() && auth()->user()->role === 'admin')
<script>
// Notifikasi pesanan baru untuk Admin
let lastPesananCount = null;

// Buat bunyi notifikasi sederhana pakai Web Audio API (tanpa file MP3)
function bunyiNotif() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.setValueAtTime(880, ctx.currentTime);
        osc.frequency.setValueAtTime(660, ctx.currentTime + 0.1);
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + 0.4);
    } catch(e) {}
}

async function cekNotifikasi() {
    try {
        const res = await fetch('{{ route("admin.notifikasi") }}');
        const data = await res.json();

        // Update badge pesanan
        const badge = document.getElementById('badgePesanan');
        if (badge) {
            if (data.pesanan_pending > 0) {
                badge.textContent = data.pesanan_pending;
                badge.style.display = '';
            } else {
                badge.style.display = 'none';
            }
        }

        // Bunyi + toast notif kalau ada pesanan baru
        if (lastPesananCount !== null && data.pesanan_pending > lastPesananCount) {
            bunyiNotif();
            tampilToast(`🛒 Ada ${data.pesanan_pending - lastPesananCount} pesanan baru masuk!`, 'pesanan');
        }

        lastPesananCount = data.pesanan_pending;
    } catch(e) {}
}

function tampilToast(pesan, tipe) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position:fixed; top:20px; right:20px; z-index:9999;
        background:#4A3525; color:#fff; padding:12px 18px;
        border-radius:10px; font-size:0.875rem; font-weight:600;
        box-shadow:0 4px 15px rgba(0,0,0,0.3);
        animation: slideIn 0.3s ease;
        max-width: 280px;
    `;
    toast.innerHTML = `${pesan} <a href="{{ route('admin.order.index') }}" style="color:#E6D5C3;margin-left:8px;">Lihat →</a>`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.5s'; }, 4000);
    setTimeout(() => { toast.remove(); }, 4500);
}

// Cek pertama kali langsung, lalu tiap 15 detik
cekNotifikasi();
setInterval(cekNotifikasi, 15000);
</script>
@endif
</body>
</html>
