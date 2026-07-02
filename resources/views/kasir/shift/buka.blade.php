@extends('layouts.app')
@section('title', 'Buka Shift — BOSS KOPI')
@section('page-title', 'Buka Shift Kasir')
@section('page-subtitle', 'Isi modal awal sebelum mulai transaksi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header text-center">
                <i class="bi bi-door-open fa-lg me-2"></i> Buka Shift Baru
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div style="font-size:3rem;color:var(--coffee);"><i class="fa fa-cash-register"></i></div>
                    <h5 class="mt-2" style="color:var(--coffee);">Selamat Bekerja, {{ auth()->user()->name }}!</h5>
                    <p class="text-muted small">{{ now()->format('l, d F Y — H:i') }}</p>
                </div>
                <form action="{{ route('kasir.shift.buka.post') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-600">Modal Awal (Uang Kas di Laci) *</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="modal_awal"
                                class="form-control @error('modal_awal') is-invalid @enderror"
                                placeholder="0" min="0" required autofocus>
                        </div>
                        @error('modal_awal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Hitung dan masukkan uang tunai yang ada di laci kasir.</small>
                    </div>
                    <button type="submit" class="btn btn-coffee w-100 py-3" style="font-size:1.1rem;">
                        <i class="bi bi-door-open me-2"></i> Mulai Shift Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
