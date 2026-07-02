@extends('layouts.app')
@section('title', 'Beranda — BOSS KOPI')
@section('page-title', 'Selamat Datang')
@section('page-subtitle', 'Pesan makanan & minuman favorit Anda')

@section('content')
<!-- Search Bar -->
<div class="mb-4">
    <div class="input-group">
        <span class="input-group-text" style="background:#fff;border-right:none;border-radius:12px 0 0 12px;">
            <i class="fa fa-magnifying-glass" style="color:#9c7c5e;"></i>
        </span>
        <input type="text" id="searchMenu" class="form-control"
            style="border-left:none;border-radius:0 12px 12px 0;background:#fff;"
            placeholder="Cari menu... (contoh: mie aceh, kopi, jus)"
            oninput="filterMenu(this.value)">
    </div>
    <div id="searchNoResult" class="text-center py-3 text-muted small" style="display:none;">
        Tidak ada menu yang cocok dengan pencarian Anda.
    </div>
</div>

<!-- Welcome Banner -->
<div class="p-4 rounded mb-4" style="background:linear-gradient(135deg,var(--coffee),var(--charcoal));color:#fff;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-700 mb-1">Halo, {{ auth()->user()->name }}! 👋</h5>
            <p class="mb-0 small opacity-75">Mau pesan apa hari ini?</p>
        </div>
        <div class="text-end">
            <div class="small opacity-75">Poin Loyalitas Anda</div>
            <div style="font-size:1.8rem;font-weight:700;color:var(--latte);">
                ⭐ {{ number_format($user->poin_loyalitas) }}
            </div>
        </div>
    </div>
</div>

<!-- Promo Banner -->
@if($promoAktif->count() > 0)
<div class="mb-4">
    <h6 class="fw-600 mb-2" style="color:var(--coffee);"><i class="fa fa-tags me-2"></i>Promo Aktif</h6>
    <div class="d-flex gap-3 overflow-auto pb-2" style="scrollbar-width:thin;">
        @foreach($promoAktif as $promo)
        <div class="p-3 rounded flex-shrink-0" style="background:linear-gradient(135deg,#4A3525,#6B4F35);color:#fff;min-width:220px;">
            <div class="fw-600 mb-1">{{ $promo->nama }}</div>
            @if($promo->kode_kupon)
            <div class="mb-1" style="font-size:0.75rem;opacity:0.8;">
                Kode: <code style="background:rgba(255,255,255,0.2);padding:2px 6px;border-radius:4px;color:#E6D5C3;">{{ $promo->kode_kupon }}</code>
            </div>
            @endif
            <div style="font-size:0.75rem;opacity:0.7;">
                Berlaku s/d {{ $promo->tanggal_selesai->format('d M Y') }}
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Kategori Cepat -->
<div class="mb-4">
    <h6 class="fw-600 mb-2" style="color:var(--coffee);"><i class="fa fa-th-large me-2"></i>Kategori Menu</h6>
    <div class="d-flex flex-wrap gap-2">
        @foreach($kategoris as $kat)
        <a href="{{ route('pembeli.menu', ['kategori' => $kat->id]) }}"
            class="btn btn-latte btn-sm" style="border-radius:20px;">
            <i class="{{ $kat->ikon ?? 'fa fa-utensils' }} me-1"></i>
            {{ $kat->nama }}
        </a>
        @endforeach
    </div>
</div>

<!-- Menu Populer -->
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-600 mb-0" style="color:var(--coffee);">
            <i class="bi bi-trophy me-2"></i>Menu Terpopuler
        </h6>
        <a href="{{ route('pembeli.menu') }}" class="btn btn-sm btn-latte">Lihat Semua</a>
    </div>
    <div class="row g-3" id="menuGrid">
        @foreach($menuPopuler as $menu)
        <div class="col-6 col-md-3 menu-item" data-nama="{{ strtolower($menu->nama) }} {{ strtolower($menu->varian ?? '') }} {{ strtolower($menu->kategori->nama ?? '') }}">
            <a href="{{ route('pembeli.menu.detail', $menu) }}" class="text-decoration-none">
                <div class="card h-100" style="transition:all 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
                    <div style="height:120px;background:var(--latte);border-radius:12px 12px 0 0;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                        @if($menu->foto)
                            <img src="{{ asset($menu->foto) }}" style="width:100%;height:120px;object-fit:cover;">
                        @else
                            <i class="fa fa-mug-hot" style="font-size:2.5rem;color:var(--coffee);"></i>
                        @endif
                    </div>
                    <div class="card-body p-2">
                        <div class="fw-600 small" style="color:var(--charcoal);">{{ $menu->nama }}</div>
                        @if($menu->varian)
                        <div style="font-size:0.7rem;color:#999;">{{ $menu->varian }}</div>
                        @endif
                        <div class="fw-700 mt-1" style="color:var(--coffee);font-size:0.85rem;">
                            Rp {{ number_format($menu->harga_dine_in, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterMenu(keyword) {
    const q = keyword.toLowerCase().trim();
    const items = document.querySelectorAll('.menu-item');
    let visible = 0;
    items.forEach(item => {
        const nama = item.dataset.nama || '';
        if (!q || nama.includes(q)) {
            item.style.display = '';
            visible++;
        } else {
            item.style.display = 'none';
        }
    });
    document.getElementById('searchNoResult').style.display = (visible === 0 && q) ? '' : 'none';
}
</script>
@endpush
