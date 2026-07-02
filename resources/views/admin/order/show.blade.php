@extends('layouts.app')
@section('title', 'Detail Pesanan — BOSS KOPI')
@section('page-title', 'Detail Pesanan Online')
@section('page-subtitle', $order->nomor_struk)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="mb-3">
            <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-latte">
                <i class="fa fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Info Pesanan</span>
                @php
                    $statusColor = [
                        'pending' => 'bg-warning text-dark', 'dikonfirmasi' => 'bg-info text-dark',
                        'diproses' => 'bg-primary', 'siap' => 'bg-success',
                        'diantar' => 'bg-success', 'selesai' => 'bg-secondary', 'dibatalkan' => 'bg-danger',
                    ];
                @endphp
                <span class="badge {{ $statusColor[$order->status] ?? 'bg-secondary' }}">{{ $order->getLabelStatus() }}</span>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-2 small">
                    <div class="col-6"><span class="text-muted">No. Struk:</span> <code>{{ $order->nomor_struk }}</code></div>
                    <div class="col-6"><span class="text-muted">Waktu:</span> {{ $order->created_at->format('d/m/Y H:i') }}</div>
                    <div class="col-6"><span class="text-muted">Pelanggan:</span> {{ $order->nama_pelanggan ?? $order->user->name ?? '-' }}</div>
                    <div class="col-6"><span class="text-muted">No. HP:</span> {{ $order->no_hp_pelanggan ?? $order->user->no_hp ?? '-' }}</div>
                    <div class="col-6"><span class="text-muted">Tipe:</span>
                        {{ ['dine_in' => 'Dine-in', 'takeaway' => 'Takeaway', 'delivery' => 'Delivery'][$order->tipe_pesanan] ?? $order->tipe_pesanan }}
                    </div>
                    <div class="col-6"><span class="text-muted">Pembayaran:</span> {{ strtoupper($order->metode_pembayaran ?? '-') }}</div>
                    @if($order->tipe_pesanan === 'delivery')
                    <div class="col-12"><span class="text-muted">Alamat:</span> {{ $order->alamat_delivery }}</div>
                    @endif
                    @if($order->driver)
                    <div class="col-12"><span class="text-muted">Driver:</span> {{ $order->driver->name }}</div>
                    @endif
                </div>
                <hr>
                @foreach($order->items as $item)
                <div class="d-flex justify-content-between py-1 border-bottom small">
                    <div>{{ $item->jumlah }}x {{ $item->nama_menu }}
                        @if($item->catatan)<div class="text-muted" style="font-size:0.7rem;">{{ $item->catatan }}</div>@endif
                    </div>
                    <div class="fw-600" style="color:var(--coffee);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
                <div class="mt-2 d-flex justify-content-between fw-700" style="color:var(--coffee);">
                    <span>TOTAL</span><span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if(!in_array($order->status, ['selesai', 'dibatalkan']))
        <div class="card">
            <div class="card-header"><i class="bi bi-arrow-repeat me-2"></i>Ubah Status Pesanan</div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Alur: <strong>Pending</strong> → <strong>Dikonfirmasi</strong> → <strong>Diproses (Barista)</strong> → <strong>Siap</strong> (baru bisa diambil driver) → Diantar → Selesai.
                </p>
                <form action="{{ route('admin.order.update-status', $order) }}" method="POST" class="d-flex gap-2 flex-wrap">
                    @csrf
                    @if($order->status === 'pending')
                        <button type="submit" name="status" value="dikonfirmasi" class="btn btn-info text-white">
                            <i class="fa fa-check me-1"></i> Konfirmasi Pesanan
                        </button>
                    @elseif($order->status === 'dikonfirmasi')
                        <button type="submit" name="status" value="diproses" class="btn btn-primary">
                            <i class="fa fa-mug-hot me-1"></i> Mulai Diproses Barista
                        </button>
                    @elseif($order->status === 'diproses')
                        <button type="submit" name="status" value="siap" class="btn btn-success">
                            <i class="fa fa-check-double me-1"></i> Tandai Siap
                            @if($order->tipe_pesanan === 'delivery') (Siap Diantar Driver) @else (Siap Diambil) @endif
                        </button>
                    @elseif($order->status === 'siap' && $order->tipe_pesanan !== 'delivery')
                        <button type="submit" name="status" value="selesai" class="btn btn-success">
                            <i class="fa fa-bag-shopping me-1"></i> Sudah Diambil Pelanggan (Selesai)
                        </button>
                    @elseif($order->status === 'siap' && $order->tipe_pesanan === 'delivery')
                        <span class="badge bg-info text-dark py-2 px-3">
                            <i class="bi bi-bicycle me-1"></i> Menunggu driver mengambil pesanan ini
                        </span>
                    @endif
                    <button type="submit" name="status" value="dibatalkan" class="btn btn-outline-danger"
                        onclick="return confirm('Batalkan pesanan ini?')">
                        <i class="fa fa-xmark me-1"></i> Batalkan
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
