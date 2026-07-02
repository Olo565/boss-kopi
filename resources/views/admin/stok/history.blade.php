@extends('layouts.app')
@section('title', 'Riwayat Stok — BOSS KOPI')
@section('page-title', 'Riwayat Stok: ' . $stok->nama)
@section('page-subtitle', 'Stok saat ini: ' . number_format($stok->stok_saat_ini, 1) . ' ' . $stok->satuan)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.stok.index') }}" class="btn btn-sm btn-latte">
        <i class="fa fa-arrow-left me-1"></i> Kembali
    </a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Keterangan</th>
                    <th>Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $h)
                <tr>
                    <td class="small">{{ $h->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($h->tipe === 'masuk')
                            <span class="badge bg-success">Masuk</span>
                        @elseif($h->tipe === 'keluar')
                            <span class="badge bg-danger">Keluar</span>
                        @else
                            <span class="badge bg-secondary">Opname</span>
                        @endif
                    </td>
                    <td class="{{ $h->tipe === 'masuk' ? 'text-success' : 'text-danger' }} fw-600">
                        {{ $h->tipe === 'masuk' ? '+' : '-' }}{{ number_format($h->jumlah, 1) }} {{ $stok->satuan }}
                    </td>
                    <td class="small">{{ number_format($h->stok_sebelum, 1) }}</td>
                    <td class="small">{{ number_format($h->stok_sesudah, 1) }}</td>
                    <td class="small text-muted">{{ $h->keterangan ?? '-' }}</td>
                    <td class="small">{{ $h->user->name ?? 'Sistem' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($histories->hasPages())
    <div class="card-footer bg-white">{{ $histories->links() }}</div>
    @endif
</div>
@endsection
