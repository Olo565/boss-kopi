@extends('layouts.app')
@php($promo = $promo ?? null)
@section('title', ($promo ? 'Edit' : 'Buat') . ' Promo — BOSS KOPI')
@section('page-title', $promo ? 'Edit Promo' : 'Buat Promo Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="fa fa-tags me-2"></i>Form Promo</div>
            <div class="card-body">
                <form action="{{ $promo ? route('admin.promo.update', $promo) : route('admin.promo.store') }}"
                    method="POST">
                    @csrf
                    @if($promo) @method('PUT') @endif
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Promo *</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $promo?->nama ?? '') }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Kupon</label>
                            <input type="text" name="kode_kupon" class="form-control @error('kode_kupon') is-invalid @enderror"
                                value="{{ old('kode_kupon', $promo?->kode_kupon ?? '') }}"
                                placeholder="Contoh: BOSSKOPI10" style="text-transform:uppercase;">
                            @error('kode_kupon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipe Promo *</label>
                            <select name="tipe" class="form-select @error('tipe') is-invalid @enderror">
                                <option value="persentase" {{ old('tipe', $promo?->tipe ?? '') === 'persentase' ? 'selected' : '' }}>Persentase (%)</option>
                                <option value="nominal" {{ old('tipe', $promo?->tipe ?? '') === 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                                <option value="buy1get1" {{ old('tipe', $promo?->tipe ?? '') === 'buy1get1' ? 'selected' : '' }}>Buy 1 Get 1</option>
                                <option value="paket" {{ old('tipe', $promo?->tipe ?? '') === 'paket' ? 'selected' : '' }}>Paket</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nilai Diskon *</label>
                            <input type="number" name="nilai_diskon" class="form-control"
                                value="{{ old('nilai_diskon', $promo?->nilai_diskon ?? 0) }}" min="0" step="0.01" required>
                            <small class="text-muted">Isi 0 jika tidak berlaku</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Min. Transaksi</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="min_transaksi" class="form-control"
                                    value="{{ old('min_transaksi', $promo ? $promo->min_transaksi : 0) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Maks. Penggunaan</label>
                            <input type="number" name="max_penggunaan" class="form-control"
                                value="{{ old('max_penggunaan', $promo ? $promo->max_penggunaan : '') }}"
                                min="1" placeholder="Kosongkan = tidak terbatas">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai *</label>
                            <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai', $promo?->tanggal_mulai?->format('Y-m-d') ?? today()->format('Y-m-d')) }}" required>
                            @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai *</label>
                            <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai', $promo?->tanggal_selesai?->format('Y-m-d') ?? today()->addMonth()->format('Y-m-d')) }}" required>
                            @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"
                                placeholder="Keterangan promo ini...">{{ old('deskripsi', $promo?->deskripsi ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    {{ old('is_active', $promo?->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Promo aktif</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.promo.index') }}" class="btn btn-latte">
                                <i class="fa fa-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-coffee">
                                <i class="fa fa-save me-1"></i>
                                {{ $promo ? 'Simpan' : 'Buat Promo' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
