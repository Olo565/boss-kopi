<div class="sidebar" id="sidebar">
    <div class="sidebar-brand d-flex align-items-center gap-2">
        <div class="logo-svg">
            <svg width="34" height="34" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="46" stroke="#C9A84C" stroke-width="3" fill="none"/>
                <path d="M36 22 Q33 16 36 11" stroke="#C9A84C" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M50 20 Q47 13 50 8" stroke="#C9A84C" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M64 22 Q61 16 64 11" stroke="#C9A84C" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M28 38 L34 72 Q50 78 66 72 L72 38 Z" fill="#C9A84C"/>
                <path d="M72 45 Q85 45 85 55 Q85 65 72 65" stroke="#C9A84C" stroke-width="4" fill="none" stroke-linecap="round"/>
                <ellipse cx="50" cy="56" rx="10" ry="7" fill="#1A1A1A"/>
                <path d="M50 49 Q53 56 50 63" stroke="#C9A84C" stroke-width="2" fill="none" stroke-linecap="round"/>
            </svg>
        </div>
        <div>
            <div class="logo-text">BOSS KOPI</div>
            <div class="logo-sub">Sunggal</div>
        </div>
    </div>

    <!-- User Info -->
    <div class="px-3 py-2 border-bottom border-white border-opacity-10">
        <div class="d-flex align-items-center gap-2 py-1">
            <div style="width:36px;height:36px;background:var(--latte);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="fa fa-user" style="color:var(--coffee);font-size:0.9rem;"></i>
            </div>
            <div>
                <div style="color:#fff;font-size:0.8rem;font-weight:600;">{{ auth()->user()->name }}</div>
                <div style="color:rgba(255,255,255,0.5);font-size:0.7rem;text-transform:capitalize;">
                    {{ auth()->user()->role }}
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="py-2">
        @if(auth()->user()->role === 'admin')
            <div class="sidebar-section-title">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dasbor
            </a>
            <a href="{{ route('admin.order.index') }}" class="nav-link {{ request()->routeIs('admin.order.*') ? 'active' : '' }}">
                <i class="bi bi-bag-check"></i> Pesanan Online
                <span id="badgePesanan" class="badge bg-danger ms-1" style="display:none;"></span>
            </a>
            <a href="{{ route('admin.pengaduan.index') }}" class="nav-link {{ request()->routeIs('admin.pengaduan.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> Pengaduan
            </a>
            <a href="{{ route('admin.pengaturan.index') }}" class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                <i class="fa fa-gear"></i> Pengaturan
            </a>

            <div class="sidebar-section-title">Manajemen</div>
            <a href="{{ route('admin.menu.index') }}" class="nav-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                <i class="fa fa-utensils"></i> Menu & Produk
            </a>
            <a href="{{ route('admin.stok.index') }}" class="nav-link {{ request()->routeIs('admin.stok.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Stok & Bahan Baku
            </a>
            <a href="{{ route('admin.user.index') }}" class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <i class="fa fa-users"></i> Pengguna
            </a>
            @php $jumlahDriverMenunggu = \App\Models\User::where('role','driver')->where('status_akun','menunggu')->count(); @endphp
            @if($jumlahDriverMenunggu > 0)
            <a href="{{ route('admin.user.driver-menunggu') }}" class="nav-link" style="color:#dc3545;">
                <i class="bi bi-person-exclamation"></i> Driver Menunggu
                <span class="badge bg-danger ms-1">{{ $jumlahDriverMenunggu }}</span>
            </a>
            @endif
            <a href="{{ route('admin.promo.index') }}" class="nav-link {{ request()->routeIs('admin.promo.*') ? 'active' : '' }}">
                <i class="fa fa-tags"></i> Promo & Diskon
            </a>

            <div class="sidebar-section-title">Laporan</div>
            <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link {{ request()->routeIs('admin.laporan.penjualan*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Laporan Penjualan
            </a>
            <a href="{{ route('admin.laporan.stok') }}" class="nav-link {{ request()->routeIs('admin.laporan.stok') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i> Laporan Stok
            </a>
            <a href="{{ route('admin.laporan.kasir') }}" class="nav-link {{ request()->routeIs('admin.laporan.kasir') ? 'active' : '' }}">
                <i class="bi bi-person-check"></i> Kinerja Kasir
            </a>

            <div class="sidebar-section-title">Akses Cepat</div>
            <a href="{{ route('kasir.pos') }}" class="nav-link">
                <i class="bi bi-cart3"></i> Buka POS Kasir
            </a>

        @elseif(auth()->user()->role === 'kasir')
            <div class="sidebar-section-title">Kasir</div>
            <a href="{{ route('kasir.pos') }}" class="nav-link {{ request()->routeIs('kasir.pos') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i> POS Transaksi
            </a>
            <a href="{{ route('kasir.riwayat') }}" class="nav-link {{ request()->routeIs('kasir.riwayat') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Riwayat Hari Ini
            </a>

            <div class="sidebar-section-title">Shift</div>
            <a href="{{ route('kasir.shift.buka') }}" class="nav-link {{ request()->routeIs('kasir.shift.buka') ? 'active' : '' }}">
                <i class="bi bi-door-open"></i> Buka Shift
            </a>
            <a href="{{ route('kasir.shift.tutup') }}" class="nav-link {{ request()->routeIs('kasir.shift.tutup') ? 'active' : '' }}">
                <i class="bi bi-door-closed"></i> Tutup Shift
            </a>

        @elseif(auth()->user()->role === 'pembeli')
            <div class="sidebar-section-title">Pesan</div>
            <a href="{{ route('pembeli.home') }}" class="nav-link {{ request()->routeIs('pembeli.home') ? 'active' : '' }}">
                <i class="bi bi-house"></i> Beranda
            </a>
            <a href="{{ route('pembeli.menu') }}" class="nav-link {{ request()->routeIs('pembeli.menu*') ? 'active' : '' }}">
                <i class="fa fa-mug-hot"></i> Menu
            </a>
            <a href="{{ route('pembeli.keranjang') }}" class="nav-link {{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}">
                <i class="bi bi-bag"></i> Keranjang
            </a>

            <div class="sidebar-section-title">Akun</div>
            <a href="{{ route('pembeli.riwayat') }}" class="nav-link {{ request()->routeIs('pembeli.riwayat') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Riwayat Pesanan
            </a>
            <a href="{{ route('pembeli.profil') }}" class="nav-link {{ request()->routeIs('pembeli.profil') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profil Saya
            </a>
            @if(auth()->user()->role === 'pembeli' && !auth()->user()->no_hp_terverifikasi)
            <a href="{{ route('verifikasi-wa.show') }}" class="nav-link" style="color:#dc3545;">
                <i class="bi bi-whatsapp"></i> Verifikasi WA
                <span class="badge bg-danger ms-1">!</span>
            </a>
            @endif
            <a href="{{ route('pengaduan.index') }}" class="nav-link {{ request()->routeIs('pengaduan.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> Pengaduan Saya
            </a>
            <div class="nav-link" style="cursor:default;">
                <i class="bi bi-star"></i>
                <span>Poin: <strong style="color:var(--latte);">{{ auth()->user()->poin_loyalitas }}</strong></span>
            </div>

            <div class="sidebar-section-title">Info Kedai</div>
            <div class="mx-2 p-3 rounded" style="background:rgba(230,213,195,0.3);font-size:0.75rem;">
                <div class="fw-600 mb-1" style="color:var(--latte);">☕ BOSS KOPI Sunggal</div>
                <div style="color:rgba(255,255,255,0.7);" class="mb-1"><i class="fa fa-location-dot me-1"></i>Jl. Pinang Baris Elok No.37</div>
                <div style="color:rgba(255,255,255,0.7);" class="mb-2"><i class="fa fa-clock me-1"></i>07.00 – 22.00 WIB</div>
                <a href="https://wa.me/62895333301223" target="_blank"
                    class="d-block text-center py-1 rounded fw-600"
                    style="background:#25D366;color:#fff;font-size:0.75rem;text-decoration:none;">
                    <i class="bi bi-whatsapp me-1"></i> Chat Admin
                </a>
            </div>

        @elseif(auth()->user()->role === 'driver')
            <div class="sidebar-section-title">Pengiriman</div>
            <a href="{{ route('driver.orders') }}" class="nav-link {{ request()->routeIs('driver.orders') ? 'active' : '' }}">
                <i class="bi bi-bicycle"></i> Pesanan Masuk
            </a>
            <a href="{{ route('driver.riwayat') }}" class="nav-link {{ request()->routeIs('driver.riwayat') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Riwayat Antar
            </a>
            <a href="{{ route('driver.profil') }}" class="nav-link {{ request()->routeIs('driver.profil') ? 'active' : '' }}">
                <i class="fa fa-user"></i> Profil Saya
            </a>
            <a href="{{ route('pengaduan.index') }}" class="nav-link {{ request()->routeIs('pengaduan.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> Pengaduan Saya
            </a>
        @endif

        <!-- Logout -->
        <div class="sidebar-section-title">Akun</div>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent"
                style="cursor:pointer;">
                <i class="fa fa-right-from-bracket"></i> Keluar
            </button>
        </form>
    </nav>
</div>
