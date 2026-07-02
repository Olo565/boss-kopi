@extends('layouts.app')
@section('title', 'Riwayat Pesanan — BOSS KOPI')
@section('page-title', 'Riwayat Pesanan & Poin')

@section('content')
<div class="row g-3">
    <!-- Poin Loyalitas -->
    <div class="col-12">
        <div class="p-4 rounded" style="background:linear-gradient(135deg,var(--coffee),var(--charcoal));color:#fff;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="small opacity-75">Total Poin Loyalitas</div>
                    <div style="font-size:2.5rem;font-weight:700;color:var(--latte);">
                        ⭐ {{ number_format($user->poin_loyalitas) }}
                    </div>
                    <div class="small opacity-75">1 poin = Rp 1.000 pengeluaran</div>
                </div>
                <div class="text-end">
                    <div class="small opacity-75 mb-1">Riwayat Poin Terbaru</div>
                    @foreach($poinHistories as $ph)
                    <div class="small">
                        <span class="{{ $ph->tipe === 'tambah' ? 'text-success' : 'text-danger' }}">
                            {{ $ph->tipe === 'tambah' ? '+' : '-' }}{{ $ph->jumlah_poin }} poin
                        </span>
                        <span class="opacity-50">— {{ $ph->keterangan }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Order -->
    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="bi bi-bag-check me-2"></i>Riwayat Pesanan</div>
            <div class="card-body p-0">
                @forelse($orders as $order)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <code class="small">{{ $order->nomor_struk }}</code>
                            <div class="small text-muted">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            <div class="small mt-1">
                                @foreach($order->items->take(2) as $item)
                                    <span>{{ $item->jumlah }}x {{ $item->nama_menu }}</span>@if(!$loop->last), @endif
                                @endforeach
                                @if($order->items->count() > 2)
                                    <span class="text-muted">+{{ $order->items->count() - 2 }} lagi</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-700" style="color:var(--coffee);">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                            <span class="badge {{ $order->status === 'selesai' ? 'bg-success' : ($order->status === 'dibatalkan' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ $order->getLabelStatus() }}
                            </span>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('pembeli.tracking', $order) }}" class="btn btn-sm btn-latte">
                            <i class="bi bi-eye me-1"></i> Detail
                        </a>
                        @if($order->status === 'selesai')
                        <form action="{{ route('pembeli.reorder', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-coffee">
                                <i class="bi bi-arrow-repeat me-1"></i> Pesan Lagi
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bag-x" style="font-size:3rem;"></i>
                    <div class="mt-2">Belum ada riwayat pesanan</div>
                    <a href="{{ route('pembeli.menu') }}" class="btn btn-coffee mt-3">Pesan Sekarang</a>
                </div>
                @endforelse
            </div>
            @if($orders->hasPages())
            <div class="card-footer bg-white">{{ $orders->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
