@extends('layouts.app')
@section('title', 'Riwayat Transaksi — BOSS KOPI')
@section('page-title', 'Riwayat Transaksi Hari Ini')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-clock-history me-2"></i>Transaksi {{ now()->format('d M Y') }}</span>
        <span class="badge badge-coffee">{{ $orders->total() }} transaksi</span>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>No. Struk</th>
                    <th>Pelanggan</th>
                    <th>Tipe</th>
                    <th>Item</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Struk</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="small">{{ $order->created_at->format('H:i') }}</td>
                    <td><code class="small">{{ $order->nomor_struk }}</code></td>
                    <td class="small">{{ $order->nama_pelanggan ?? '-' }}</td>
                    <td>
                        @php $tipeLabel = ['dine_in' => 'Dine-in', 'takeaway' => 'Takeaway', 'delivery' => 'Delivery']; @endphp
                        <span class="badge badge-latte small">{{ $tipeLabel[$order->tipe_pesanan] ?? $order->tipe_pesanan }}</span>
                    </td>
                    <td class="small">{{ $order->items->count() }} item</td>
                    <td class="fw-600" style="color:var(--coffee);">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="small">{{ strtoupper($order->metode_pembayaran) }}</td>
                    <td>
                        <a href="{{ route('kasir.struk', $order) }}" target="_blank"
                            class="btn btn-sm btn-latte" title="Cetak Ulang Struk">
                            <i class="bi bi-printer"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada transaksi hari ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-white">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
