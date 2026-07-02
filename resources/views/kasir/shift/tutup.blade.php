@extends('layouts.app')
@section('title', 'Tutup Shift — BOSS KOPI')
@section('page-title', 'Tutup Shift')
@section('page-subtitle', 'Rekap transaksi sebelum menutup shift')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <!-- Ringkasan Shift -->
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-clipboard-check me-2"></i>Ringkasan Shift Hari Ini</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background:var(--latte);">
                            <div class="small text-muted">Waktu Buka</div>
                            <div class="fw-600">{{ $shift->waktu_buka->format('H:i') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background:var(--latte);">
                            <div class="small text-muted">Modal Awal</div>
                            <div class="fw-600">Rp {{ number_format($shift->modal_awal, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background:#d1f0e0;">
                            <div class="small text-muted">Tunai</div>
                            <div class="fw-700 text-success">Rp {{ number_format($shift->total_tunai, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background:#daedfb;">
                            <div class="small text-muted">QRIS</div>
                            <div class="fw-700" style="color:#0c5460;">Rp {{ number_format($shift->total_qris, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background:#fef3cc;">
                            <div class="small text-muted">Debit/EDC</div>
                            <div class="fw-700" style="color:#7d5a00;">Rp {{ number_format($shift->total_debit, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded text-center" style="background:var(--coffee);color:#fff;">
                            <div class="small">Total Pendapatan Shift Ini</div>
                            <div style="font-size:1.8rem;font-weight:700;">
                                Rp {{ number_format($shift->getTotalPendapatan(), 0, ',', '.') }}
                            </div>
                            <div class="small">{{ $shift->orders->count() }} transaksi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Tutup Shift -->
        <div class="card">
            <div class="card-header"><i class="bi bi-door-closed me-2"></i>Hitung & Tutup Kas</div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fa fa-circle-info me-2"></i>
                    Uang tunai di laci seharusnya:
                    <strong>Rp {{ number_format($shift->modal_awal + $shift->total_tunai, 0, ',', '.') }}</strong>
                    (Modal Awal + Total Tunai)
                </div>
                <form action="{{ route('kasir.shift.tutup.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600">Jumlah Uang Kas di Laci Sekarang *</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="uang_kas_akhir"
                                class="form-control @error('uang_kas_akhir') is-invalid @enderror"
                                placeholder="0" min="0" required>
                        </div>
                        @error('uang_kas_akhir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2"
                            placeholder="Keterangan atau catatan shift..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 py-3"
                        onclick="return confirm('Yakin tutup shift sekarang? Transaksi baru tidak bisa dilakukan.')"
                        style="font-size:1rem;">
                        <i class="bi bi-door-closed me-2"></i> Tutup Shift & Selesai
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
