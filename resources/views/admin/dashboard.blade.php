@extends('layouts.app')
@section('title', 'Dasbor Admin — BOSS KOPI')
@section('page-title', 'Dasbor Utama')
@section('page-subtitle', 'Ringkasan performa BOSS KOPI hari ini')

@section('content')
<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="stat-icon"><i class="fa fa-money-bill-wave"></i></div>
            </div>
            <div class="stat-value">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            <div class="stat-label">Pendapatan Hari Ini</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#2D6A4F;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="stat-icon" style="background:#d1f0e0;"><i class="bi bi-receipt" style="color:#2D6A4F;"></i></div>
            </div>
            <div class="stat-value" style="color:#2D6A4F;">{{ $transaksiHariIni }}</div>
            <div class="stat-label">Transaksi Hari Ini</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#D4A017;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="stat-icon" style="background:#fef3cc;"><i class="fa fa-users" style="color:#D4A017;"></i></div>
            </div>
            <div class="stat-value" style="color:#D4A017;">{{ $totalPelanggan }}</div>
            <div class="stat-label">Total Pelanggan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#6B4F35;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="stat-icon" style="background:#f0e6d3;"><i class="fa fa-mug-hot" style="color:#6B4F35;"></i></div>
            </div>
            <div class="stat-value" style="color:#6B4F35;">{{ $totalMenu }}</div>
            <div class="stat-label">Menu Aktif</div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-graph-up me-2"></i>Pendapatan 7 Hari Terakhir</span>
            </div>
            <div class="card-body">
                <canvas id="grafikHarian" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-trophy me-2"></i>Menu Terlaris
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($menuTerlaris as $i => $menu)
                    <li class="list-group-item d-flex align-items-center gap-2 py-2 px-3">
                        <span class="badge badge-coffee rounded-pill">{{ $i + 1 }}</span>
                        <div class="flex-grow-1">
                            <div style="font-size:0.825rem;font-weight:500;">{{ $menu->nama }}</div>
                            <div style="font-size:0.75rem;color:#888;">{{ $menu->total_order ?? 0 }} terjual</div>
                        </div>
                        <span class="text-success small fw-600">
                            Rp {{ number_format($menu->harga_dine_in, 0, ',', '.') }}
                        </span>
                    </li>
                    @empty
                    <li class="list-group-item text-muted text-center py-3">Belum ada data penjualan</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Stok Kritis & Grafik Bulanan -->
<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart me-2"></i>Pendapatan 6 Bulan Terakhir</span>
            </div>
            <div class="card-body">
                <canvas id="grafikBulanan" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle me-2" style="color:#D4A017;"></i>Stok Kritis</span>
                @if($stokKritis->count() > 0)
                    <span class="badge bg-danger">{{ $stokKritis->count() }}</span>
                @endif
            </div>
            <div class="card-body p-0">
                @forelse($stokKritis as $bahan)
                <div class="px-3 py-2 border-bottom stok-kritis">
                    <div class="d-flex justify-content-between">
                        <span style="font-size:0.825rem;font-weight:500;">{{ $bahan->nama }}</span>
                        <span class="badge bg-danger">{{ $bahan->stok_saat_ini }} {{ $bahan->satuan }}</span>
                    </div>
                    <small class="text-muted">Min: {{ $bahan->stok_minimum }} {{ $bahan->satuan }}</small>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-check-circle text-success fs-3"></i>
                    <div class="mt-1 small">Semua stok aman</div>
                </div>
                @endforelse
                @if($stokKritis->count() > 0)
                <div class="p-2">
                    <a href="{{ route('admin.stok.index') }}?filter=kritis" class="btn btn-sm btn-coffee w-100">
                        Kelola Stok
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Data dari PHP
const labelHarian = @json($pendapatanHarian->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->format('d M')));
const dataHarian = @json($pendapatanHarian->pluck('total'));

const labelBulanan = @json($pendapatanBulanan->map(fn($p) => \Carbon\Carbon::createFromDate($p->tahun, $p->bulan, 1)->format('M Y')));
const dataBulanan = @json($pendapatanBulanan->pluck('total'));

const coffeeColor = '#4A3525';
const latteColor = '#E6D5C3';

// Grafik Harian
new Chart(document.getElementById('grafikHarian'), {
    type: 'line',
    data: {
        labels: labelHarian,
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: dataHarian,
            borderColor: coffeeColor,
            backgroundColor: 'rgba(74,53,37,0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: coffeeColor,
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                }
            }
        }
    }
});

// Grafik Bulanan
new Chart(document.getElementById('grafikBulanan'), {
    type: 'bar',
    data: {
        labels: labelBulanan,
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: dataBulanan,
            backgroundColor: coffeeColor,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                }
            }
        }
    }
});
</script>
@endpush
