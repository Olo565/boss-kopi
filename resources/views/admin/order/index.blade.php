@extends('layouts.app')
@section('title', 'Pesanan Online — BOSS KOPI')
@section('page-title', 'Pesanan Online dari Pembeli')
@section('page-subtitle', 'Proses pesanan sebelum bisa diambil driver')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="border-left-color:#D4A017;">
            <div class="stat-icon mb-2" style="background:#fef3cc;"><i class="fa fa-clock" style="color:#D4A017;"></i></div>
            <div class="stat-value" style="color:#D4A017;">{{ $jumlahPending }}</div>
            <div class="stat-label">Menunggu Konfirmasi</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left-color:#0c5460;">
            <div class="stat-icon mb-2" style="background:#daedfb;"><i class="fa fa-mug-hot" style="color:#0c5460;"></i></div>
            <div class="stat-value" style="color:#0c5460;">{{ $jumlahDiproses }}</div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left-color:#2D6A4F;">
            <div class="stat-icon mb-2" style="background:#d1f0e0;"><i class="bi bi-bicycle" style="color:#2D6A4F;"></i></div>
            <div class="stat-value" style="color:#2D6A4F;">{{ $jumlahSiap }}</div>
            <div class="stat-label">Siap / Menunggu Driver</div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mb-3">
    <a href="?" class="btn btn-sm {{ !request('status') ? 'btn-coffee' : 'btn-latte' }}">Semua</a>
    <a href="?status=pending" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-coffee' : 'btn-latte' }}">Menunggu</a>
    <a href="?status=dikonfirmasi" class="btn btn-sm {{ request('status') === 'dikonfirmasi' ? 'btn-coffee' : 'btn-latte' }}">Dikonfirmasi</a>
    <a href="?status=diproses" class="btn btn-sm {{ request('status') === 'diproses' ? 'btn-coffee' : 'btn-latte' }}">Diproses</a>
    <a href="?status=siap" class="btn btn-sm {{ request('status') === 'siap' ? 'btn-coffee' : 'btn-latte' }}">Siap</a>
    <a href="?status=selesai" class="btn btn-sm {{ request('status') === 'selesai' ? 'btn-coffee' : 'btn-latte' }}">Selesai</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>No. Struk</th>
                        <th>Pelanggan</th>
                        <th>Tipe</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Driver</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="small">{{ $order->created_at->format('d/m H:i') }}</td>
                        <td><code class="small">{{ $order->nomor_struk }}</code></td>
                        <td class="small">{{ $order->nama_pelanggan ?? $order->user->name ?? '-' }}</td>
                        <td>
                            @php $tipeLabel = ['dine_in' => 'Dine-in', 'takeaway' => 'Takeaway', 'delivery' => 'Delivery']; @endphp
                            <span class="badge badge-latte small">{{ $tipeLabel[$order->tipe_pesanan] ?? $order->tipe_pesanan }}</span>
                        </td>
                        <td class="small">{{ $order->items->count() }} item</td>
                        <td class="fw-600 small" style="color:var(--coffee);">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $statusColor = [
                                    'pending' => 'bg-warning text-dark', 'dikonfirmasi' => 'bg-info text-dark',
                                    'diproses' => 'bg-primary', 'siap' => 'bg-success',
                                    'diantar' => 'bg-success', 'selesai' => 'bg-secondary', 'dibatalkan' => 'bg-danger',
                                ];
                            @endphp
                            <span class="badge {{ $statusColor[$order->status] ?? 'bg-secondary' }}">{{ $order->getLabelStatus() }}</span>
                        </td>
                        <td class="small">{{ $order->driver->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.order.show', $order) }}" class="btn btn-sm btn-latte">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada pesanan online</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-white">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
