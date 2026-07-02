@extends('layouts.app')
@section('title', 'Profil Driver — BOSS KOPI')
@section('page-title', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-3">
            <div class="card-body text-center p-4">
                <img src="{{ $user->foto ? asset($user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4A3525&color=fff&size=128' }}"
                    alt="Foto Profil" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--latte);">
                <h5 class="fw-700 mt-3" style="color:var(--coffee);">{{ $user->name }}</h5>
                <span class="badge badge-coffee mb-2">Driver</span>
                <div>
                    <i class="fa fa-star text-warning"></i>
                    <span class="fw-600">{{ number_format($user->rating_rata, 1) }}</span>
                    <span class="text-muted small">({{ $user->jumlah_rating }} rating dari pelanggan)</span>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Edit Profil & Kendaraan</div>
            <div class="card-body">
                <form action="{{ route('driver.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Foto Profil</label>
                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                        @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Pelanggan akan melihat foto ini saat pesanan diantar.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $user->no_hp) }}" required>
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kendaraan</label>
                        <input type="text" name="jenis_kendaraan" class="form-control @error('jenis_kendaraan') is-invalid @enderror"
                            placeholder="Contoh: Honda Beat" value="{{ old('jenis_kendaraan', $user->jenis_kendaraan) }}" required>
                        @error('jenis_kendaraan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna Kendaraan</label>
                        <input type="text" name="warna_kendaraan" class="form-control @error('warna_kendaraan') is-invalid @enderror"
                            placeholder="Contoh: Hitam" value="{{ old('warna_kendaraan', $user->warna_kendaraan) }}">
                        @error('warna_kendaraan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control @error('plat_nomor') is-invalid @enderror"
                            placeholder="Contoh: BK 1234 ABC" value="{{ old('plat_nomor', $user->plat_nomor) }}" required>
                        @error('plat_nomor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-coffee w-100">
                        <i class="fa fa-save me-2"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <a href="{{ route('pengaduan.create') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-megaphone me-2"></i> Buat Pengaduan / Laporan Masalah
                </a>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100">
                <i class="fa fa-right-from-bracket me-2"></i> Keluar
            </button>
        </form>
    </div>
</div>
@endsection
