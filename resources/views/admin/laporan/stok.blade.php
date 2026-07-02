@extends('layouts.app')
@section('title', 'Laporan Stok — BOSS KOPI')
@section('page-title', 'Laporan Stok Bahan Baku')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-clipboard-data me-2"></i>Kondisi Stok Saat Ini</span>
        <small class="text-muted">{{ now()->format('d M Y, H:i') }}</small>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Bahan Baku</th>
                    <th>Satuan</th>
                    <th>Stok Saat Ini</th>
                    <th>Stok Minimum</th>
                    <th>Harga/Satuan</th>
                    <th>Nilai Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bahanBaku as $i => $bahan)
                <tr class="{{ $bahan->isStokKritis() ? 'stok-kritis' : '' }}">
                    <td class="small text-muted">{{ $i + 1 }}</td>
                    <td class="fw-500">{{ $bahan->nama }}</td>
                    <td class="small">{{ $bahan->satuan }}</td>
                    <td class="{{ $bahan->isStokKritis() ? 'text-danger fw-600' : 'text-success fw-600' }}">
                        {{ number_format($bahan->stok_saat_ini, 1) }}
                    </td>
                    <td class="small">{{ number_format($bahan->stok_minimum, 1) }}</td>
                    <td class="small">Rp {{ number_format($bahan->harga_per_satuan, 0, ',', '.') }}</td>
                    <td class="small">Rp {{ number_format($bahan->stok_saat_ini * $bahan->harga_per_satuan, 0, ',', '.') }}</td>
                    <td>
                        @if($bahan->isStokKritis())
                            <span class="badge bg-danger">Kritis</span>
                        @else
                            <span class="badge bg-success">Aman</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:var(--latte);">
                    <td colspan="6" class="fw-600 text-end">TOTAL NILAI STOK</td>
                    <td class="fw-700" style="color:var(--coffee);">
                        Rp {{ number_format($bahanBaku->sum(fn($b) => $b->stok_saat_ini * $b->harga_per_satuan), 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
