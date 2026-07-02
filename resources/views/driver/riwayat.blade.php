@extends('layouts.app')
@section('title', 'Riwayat Pengiriman — BOSS KOPI')
@section('page-title', 'Riwayat Pengiriman')
@section('page-subtitle', 'Total komisi: Rp ' . number_format($totalKomisi, 0, ',', '.'))

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6">
        <div class="stat-card">
            <div class="stat-icon mb-2"><i class="bi bi-bicycle"></i></div>
            <div class="stat-value">{{ $totalAntar }}</div>
            <div class="stat-label">Total Pengiriman</div>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card" style="border-left-color:#2D6A4F;">
            <div class="stat-icon mb-2" style="background:#d1f0e0;"><i class="fa fa-money-bill" style="color:#2D6A4F;"></i></div>
            <div class="stat-value" style="color:#2D6A4F;font-size:1.2rem;">Rp {{ number_format($totalKomisi, 0, ',', '.') }}</div>
            <div class="stat-label">Total Komisi</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-clock-history me-2"></i>Riwayat Pengiriman</div>
    <div class="card-body p-0">
        @forelse($pengiriman as $p)
        <div class="p-3 border-bottom">
            <div class="d-flex justify-content-between mb-1">
                <code class="small">{{ $p->order->nomor_struk }}</code>
                <span class="badge {{ $p->status === 'selesai' ? 'bg-success' : 'bg-secondary' }}">
                    {{ ucfirst($p->status) }}
                </span>
            </div>
            <div class="small text-muted mb-1">
                {{ $p->created_at->format('d M Y, H:i') }}
            </div>
            <div class="small">
                <i class="fa fa-user me-1"></i>
                {{ $p->order->nama_pelanggan ?? $p->order->user->name ?? '-' }}
            </div>
            <div class="small">
                <i class="fa fa-location-dot me-1"></i>
                {{ Str::limit($p->order->alamat_delivery, 60) }}
            </div>
            <div class="d-flex justify-content-between mt-1">
                <span class="small text-muted">{{ $p->order->items->count() }} item</span>
                <span class="fw-600 small" style="color:#2D6A4F;">
                    +Rp {{ number_format($p->komisi, 0, ',', '.') }} komisi
                </span>
            </div>

            @if($p->status === 'selesai' && $p->order->user_id)
                @if(!$p->order->rating_pelanggan)
                <form action="{{ route('driver.rating-pelanggan', $p->order) }}" method="POST" class="mt-2 pt-2 border-top">
                    @csrf
                    <div class="small text-muted mb-1">Beri rating untuk pelanggan ini:</div>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star star-rate-{{ $p->id }}" data-order="{{ $p->id }}" data-value="{{ $i }}"
                                style="color:#ddd;cursor:pointer;" onclick="pilihBintang({{ $p->id }}, {{ $i }})"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingPelanggan{{ $p->id }}" required>
                        <button type="submit" class="btn btn-sm btn-coffee ms-auto">Kirim</button>
                    </div>
                </form>
                @else
                <div class="small text-muted mt-2 pt-2 border-top">
                    Rating pelanggan:
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa fa-star" style="color:{{ $i <= $p->order->rating_pelanggan ? '#FFC107' : '#ddd' }};"></i>
                    @endfor
                </div>
                @endif
            @endif
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox" style="font-size:3rem;"></i>
            <div class="mt-2">Belum ada riwayat pengiriman</div>
        </div>
        @endforelse
    </div>
    @if($pengiriman->hasPages())
    <div class="card-footer bg-white">{{ $pengiriman->links() }}</div>
    @endif
</div>

<script>
function pilihBintang(orderId, value) {
    document.getElementById('ratingPelanggan' + orderId).value = value;
    document.querySelectorAll('.star-rate-' + orderId).forEach(star => {
        star.style.color = parseInt(star.dataset.value) <= value ? '#FFC107' : '#ddd';
    });
}
</script>
@endsection
