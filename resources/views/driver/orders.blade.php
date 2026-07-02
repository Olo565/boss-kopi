@extends('layouts.app')
@section('title', 'Pesanan Driver — BOSS KOPI')
@section('page-title', 'Pesanan Masuk')
@section('page-subtitle', 'Ambil dan antar pesanan delivery')

@section('content')
<!-- Statistik Hari Ini -->
<div class="row g-3 mb-4">
    <div class="col-6">
        <div class="stat-card">
            <div class="stat-icon mb-2"><i class="bi bi-bicycle"></i></div>
            <div class="stat-value">{{ $totalHariIni }}</div>
            <div class="stat-label">Antar Hari Ini</div>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card" style="border-left-color:#2D6A4F;">
            <div class="stat-icon mb-2" style="background:#d1f0e0;"><i class="fa fa-money-bill" style="color:#2D6A4F;"></i></div>
            <div class="stat-value" style="color:#2D6A4F;">Rp {{ number_format($komisiHariIni, 0, ',', '.') }}</div>
            <div class="stat-label">Komisi Hari Ini</div>
        </div>
    </div>
</div>

<!-- Pesanan Sedang Diantar -->
@if($ordersSaya->count() > 0)
<h6 class="fw-600 mb-2" style="color:var(--coffee);">
    <i class="bi bi-bicycle me-2"></i>Sedang Diantar
</h6>
@foreach($ordersSaya as $order)
<div class="card mb-3 border-warning">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
            <code>{{ $order->nomor_struk }}</code>
            <span class="badge bg-warning text-dark">Sedang Diantar</span>
        </div>
        <div class="small mb-2">
            <i class="fa fa-user me-1"></i> {{ $order->nama_pelanggan ?? $order->user->name ?? '-' }}<br>
            <i class="fa fa-phone me-1"></i> {{ $order->no_hp_pelanggan ?? $order->user->no_hp ?? '-' }}<br>
            <i class="fa fa-location-dot me-1"></i> {{ $order->alamat_delivery }}
        </div>
        <div class="small mb-2">
            @foreach($order->items as $item)
            <div>{{ $item->jumlah }}x {{ $item->nama_menu }}</div>
            @endforeach
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('driver.detail', $order) }}" class="btn btn-sm btn-coffee flex-fill">
                <i class="fa fa-location-arrow me-1"></i> Lihat Detail & Navigasi
            </a>
            @if($order->no_hp_pelanggan || ($order->user && $order->user->no_hp))
            <a href="tel:{{ $order->no_hp_pelanggan ?? $order->user->no_hp }}" class="btn btn-sm btn-latte">
                <i class="fa fa-phone"></i>
            </a>
            @endif
        </div>
    </div>
</div>
@endforeach
@endif

<!-- Pesanan Menunggu -->
<h6 class="fw-600 mb-2" style="color:var(--coffee);">
    <i class="bi bi-clock me-2"></i>Pesanan Menunggu Driver
    @if($ordersMenunggu->count() > 0)
    <span class="badge bg-danger ms-1">{{ $ordersMenunggu->count() }}</span>
    @endif
</h6>

@forelse($ordersMenunggu as $order)
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
            <code>{{ $order->nomor_struk }}</code>
            <span class="badge badge-latte">{{ $order->created_at->diffForHumans() }}</span>
        </div>
        <div class="small mb-1">
            <i class="fa fa-user me-1"></i> {{ $order->nama_pelanggan ?? $order->user->name ?? '-' }}<br>
            <i class="fa fa-location-dot me-1"></i> {{ $order->alamat_delivery }}
        </div>
        <div class="small mb-2 text-muted">
            {{ $order->items->count() }} item •
            Rp {{ number_format($order->total, 0, ',', '.') }}
        </div>
        <form action="{{ route('driver.ambil', $order) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-coffee btn-sm w-100"
                onclick="return confirm('Ambil pesanan ini?')">
                <i class="bi bi-bicycle me-2"></i> Ambil Pesanan Ini
            </button>
        </form>
    </div>
</div>
@empty
<div class="card">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-inbox" style="font-size:3rem;"></i>
        <div class="mt-2">Tidak ada pesanan delivery saat ini</div>
        <button onclick="location.reload()" class="btn btn-sm btn-latte mt-2">
            <i class="bi bi-arrow-clockwise me-1"></i> Perbarui
        </button>
    </div>
</div>
@endforelse
@endsection
