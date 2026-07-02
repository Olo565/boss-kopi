@extends('layouts.app')
@section('title', 'Laporan — BOSS KOPI')
@section('page-title', 'Pusat Laporan')
@section('page-subtitle', 'Unduh dan analisis data bisnis Anda')

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3" style="font-size:3rem;color:var(--coffee);">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h5 class="fw-600" style="color:var(--coffee);">Laporan Penjualan</h5>
                <p class="text-muted small">Detail transaksi, metode pembayaran, dan total pendapatan per periode.</p>
                <a href="{{ route('admin.laporan.penjualan') }}" class="btn btn-coffee w-100">
                    <i class="fa fa-arrow-right me-2"></i> Buka Laporan
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3" style="font-size:3rem;color:#2D6A4F;">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <h5 class="fw-600" style="color:var(--coffee);">Laporan Stok</h5>
                <p class="text-muted small">Kondisi stok bahan baku, riwayat keluar masuk, dan opname gudang.</p>
                <a href="{{ route('admin.laporan.stok') }}" class="btn btn-coffee w-100">
                    <i class="fa fa-arrow-right me-2"></i> Buka Laporan
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3" style="font-size:3rem;color:#D4A017;">
                    <i class="bi bi-person-check"></i>
                </div>
                <h5 class="fw-600" style="color:var(--coffee);">Kinerja Kasir</h5>
                <p class="text-muted small">Rekap shift, total uang yang dikumpulkan per kasir, dan validasi kas.</p>
                <a href="{{ route('admin.laporan.kasir') }}" class="btn btn-coffee w-100">
                    <i class="fa fa-arrow-right me-2"></i> Buka Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
