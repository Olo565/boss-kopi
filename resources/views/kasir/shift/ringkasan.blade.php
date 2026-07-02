@extends('layouts.app')
@section('title', 'Ringkasan Shift — BOSS KOPI')
@section('page-title', 'Ringkasan Shift Selesai')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="alert alert-success">
            <i class="fa fa-circle-check me-2"></i>
            <strong>Shift berhasil ditutup!</strong> Terima kasih telah bekerja hari ini, {{ $shift->kasir->name }}.
        </div>

        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-clipboard-check me-2"></i>Ringkasan Shift</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr><td class="text-muted">Kasir</td><td class="fw-600">{{ $shift->kasir->name }}</td></tr>
                            <tr><td class="text-muted">Buka Shift</td><td>{{ $shift->waktu_buka->format('d/m/Y H:i') }}</td></tr>
                            <tr><td class="text-muted">Tutup Shift</td><td>{{ $shift->waktu_tutup->format('d/m/Y H:i') }}</td></tr>
                            <tr><td class="text-muted">Modal Awal</td><td>Rp {{ number_format($shift->modal_awal, 0, ',', '.') }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr><td class="text-muted">Total Tunai</td><td class="text-success fw-600">Rp {{ number_format($shift->total_tunai, 0, ',', '.') }}</td></tr>
                            <tr><td class="text-muted">Total QRIS</td><td class="fw-600">Rp {{ number_format($shift->total_qris, 0, ',', '.') }}</td></tr>
                            <tr><td class="text-muted">Total Debit</td><td class="fw-600">Rp {{ number_format($shift->total_debit, 0, ',', '.') }}</td></tr>
                            <tr><td class="text-muted">Kas Akhir</td><td class="fw-600">Rp {{ number_format($shift->uang_kas_akhir, 0, ',', '.') }}</td></tr>
                        </table>
                    </div>
                    @php
                        $seharusnya = $shift->modal_awal + $shift->total_tunai;
                        $selisih = $shift->uang_kas_akhir - $seharusnya;
                    @endphp
                    <div class="col-12">
                        <div class="p-3 rounded text-center {{ $selisih == 0 ? '' : ($selisih < 0 ? 'bg-danger text-white' : 'bg-warning') }}"
                            style="{{ $selisih == 0 ? 'background:var(--latte);' : '' }}">
                            <div class="small">Selisih Kas</div>
                            <div style="font-size:1.5rem;font-weight:700;">
                                {{ $selisih >= 0 ? '+' : '' }}Rp {{ number_format($selisih, 0, ',', '.') }}
                            </div>
                            <div class="small">{{ $selisih == 0 ? 'Kas sesuai ✓' : ($selisih < 0 ? 'Kas kurang!' : 'Kas lebih') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Transaksi -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-receipt me-2"></i>Transaksi Shift Ini ({{ $shift->orders->count() }} transaksi)
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>Waktu</th><th>Struk</th><th>Item</th><th>Total</th><th>Pembayaran</th></tr></thead>
                    <tbody>
                        @foreach($shift->orders as $order)
                        <tr>
                            <td class="small">{{ $order->created_at->format('H:i') }}</td>
                            <td class="small"><code>{{ $order->nomor_struk }}</code></td>
                            <td class="small">{{ $order->items->count() }}</td>
                            <td class="fw-600" style="color:var(--coffee);">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="small">{{ strtoupper($order->metode_pembayaran) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('kasir.shift.buka') }}" class="btn btn-coffee">
                <i class="bi bi-door-open me-2"></i> Buka Shift Baru
            </a>
        </div>
    </div>
</div>
@endsection
