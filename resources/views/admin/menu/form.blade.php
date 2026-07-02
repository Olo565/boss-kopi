@extends('layouts.app')
@php($menu = $menu ?? null)
@section('title', ($menu ? 'Edit' : 'Tambah') . ' Menu — BOSS KOPI')
@section('page-title', $menu ? 'Edit Menu' : 'Tambah Menu Baru')
@section('page-subtitle', 'Isi data menu dengan lengkap')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-utensils me-2"></i>
                {{ $menu ? 'Edit: ' . $menu?->nama : 'Form Menu Baru' }}
            </div>
            <div class="card-body">
                <form action="{{ $menu ? route('admin.menu.update', $menu) : route('admin.menu.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($menu) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $menu?->nama ?? '') }}" placeholder="Contoh: Mie Aceh Seafood">
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_menu_id" class="form-select @error('kategori_menu_id') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}"
                                        {{ old('kategori_menu_id', $menu?->kategori_menu_id ?? '') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_menu_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Varian / Keterangan</label>
                            <input type="text" name="varian" class="form-control"
                                value="{{ old('varian', $menu?->varian ?? '') }}"
                                placeholder="Contoh: Basah / Goreng / Kuah">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"
                                placeholder="Deskripsi singkat menu ini...">{{ old('deskripsi', $menu?->deskripsi ?? '') }}</textarea>
                        </div>

                        <!-- Harga -->
                        <div class="col-12">
                            <label class="form-label fw-600">Harga Jual</label>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Dine-in <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_dine_in" class="form-control @error('harga_dine_in') is-invalid @enderror"
                                    value="{{ old('harga_dine_in', $menu?->harga_dine_in ?? '') }}" min="0" placeholder="0">
                                @error('harga_dine_in') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Takeaway <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_takeaway" class="form-control @error('harga_takeaway') is-invalid @enderror"
                                    value="{{ old('harga_takeaway', $menu?->harga_takeaway ?? '') }}" min="0" placeholder="0">
                                @error('harga_takeaway') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Delivery <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_delivery" class="form-control @error('harga_delivery') is-invalid @enderror"
                                    value="{{ old('harga_delivery', $menu?->harga_delivery ?? '') }}" min="0" placeholder="0">
                                @error('harga_delivery') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Pokok (HPP)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_pokok" class="form-control"
                                    value="{{ old('harga_pokok', $menu?->harga_pokok ?? '') }}" min="0" placeholder="0">
                            </div>
                        </div>

                        <!-- Foto -->
                        <div class="col-md-6">
                            <label class="form-label">Foto Menu</label>
                            @if($menu && $menu?->foto)
                                <div class="mb-2">
                                    <img src="{{ asset($menu?->foto) }}" width="80" height="80"
                                        style="object-fit:cover;border-radius:8px;" id="fotoPreview">
                                </div>
                            @else
                                <div class="mb-2">
                                    <img src="" width="80" height="80"
                                        style="object-fit:cover;border-radius:8px;display:none;" id="fotoPreview">
                                </div>
                            @endif
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                                accept="image/*" onchange="previewFoto(this)">
                            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Toggle -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="tersedia" id="tersedia"
                                    {{ old('tersedia', $menu?->tersedia ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tersedia">Menu ini tersedia / aktif dijual</label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.menu.index') }}" class="btn btn-latte">
                                <i class="fa fa-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-coffee">
                                <i class="fa fa-save me-1"></i>
                                {{ $menu ? 'Simpan Perubahan' : 'Tambah Menu' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewFoto(input) {
    const preview = document.getElementById('fotoPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-fill harga takeaway & delivery dari dine-in
document.querySelector('[name="harga_dine_in"]')?.addEventListener('input', function() {
    const v = parseInt(this.value) || 0;
    if (!document.querySelector('[name="harga_takeaway"]').value)
        document.querySelector('[name="harga_takeaway"]').value = v;
    if (!document.querySelector('[name="harga_delivery"]').value)
        document.querySelector('[name="harga_delivery"]').value = v + 2000;
});
</script>
@endpush
