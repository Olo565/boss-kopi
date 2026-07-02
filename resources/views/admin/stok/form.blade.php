@extends('layouts.app')
@php($bahan = $bahan ?? null)
@section('title', ($bahan ? 'Edit' : 'Tambah') . ' Bahan Baku — BOSS KOPI')
@section('page-title', $bahan ? 'Edit Bahan Baku' : 'Tambah Bahan Baku')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i>Form Bahan Baku</div>
            <div class="card-body">
                <form action="{{ $bahan ? route('admin.stok.update', $bahan) : route('admin.stok.store') }}"
                    method="POST">
                    @csrf
                    @if($bahan) @method('PUT') @endif
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Bahan Baku *</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $bahan?->nama ?? '') }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Satuan *</label>
                            <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror"
                                value="{{ old('satuan', $bahan?->satuan ?? '') }}"
                                placeholder="gram / ml / pcs / liter" required>
                        </div>
                        @if(!$bahan)
                        <div class="col-md-6">
                            <label class="form-label">Stok Awal *</label>
                            <input type="number" name="stok_saat_ini" class="form-control"
                                value="{{ old('stok_saat_ini', 0) }}" min="0" step="0.01" required>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label">Stok Minimum (Peringatan) *</label>
                            <input type="number" name="stok_minimum" class="form-control"
                                value="{{ old('stok_minimum', $bahan?->stok_minimum ?? 0) }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga per Satuan *</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_per_satuan" class="form-control"
                                    value="{{ old('harga_per_satuan', $bahan?->harga_per_satuan ?? 0) }}" min="0">
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.stok.index') }}" class="btn btn-latte">
                                <i class="fa fa-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-coffee">
                                <i class="fa fa-save me-1"></i>
                                {{ $bahan ? 'Simpan' : 'Tambah' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
