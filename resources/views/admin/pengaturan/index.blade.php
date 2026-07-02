@extends('layouts.app')
@section('title', 'Pengaturan — BOSS KOPI')
@section('page-title', 'Pengaturan Aplikasi')
@section('page-subtitle', 'Sesuaikan semua konfigurasi tanpa perlu edit kode')

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

{{-- TAB NAVIGATION --}}
<ul class="nav nav-tabs mb-4" id="settingTab">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#kedai">🏪 Info Kedai</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#order">🛒 Pesanan & Poin</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tampilan">🎨 Tampilan</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#fitur">⚙️ Fitur</a></li>
</ul>

<div class="tab-content">

    {{-- TAB 1: INFO KEDAI --}}
    <div class="tab-pane fade show active" id="kedai">
        <div class="card">
            <div class="card-header">🏪 Informasi Kedai</div>
            <div class="card-body">
                <div class="row g-3">
                @foreach($grupKedai as $s)
                <div class="col-md-{{ in_array($s->tipe, ['textarea']) ? '12' : '6' }}">
                    <label class="form-label fw-600">{{ $s->label }}</label>
                    @if($s->tipe === 'textarea')
                        <textarea name="{{ $s->kunci }}" class="form-control" rows="3">{{ $s->nilai }}</textarea>
                    @else
                        <input type="{{ $s->tipe === 'number' ? 'number' : 'text' }}"
                            name="{{ $s->kunci }}" class="form-control" value="{{ $s->nilai }}">
                    @endif
                    @if($s->kunci === 'no_wa_kedai')
                        <small class="text-muted">Format: 0895xxxxxxxx (tanpa +62)</small>
                    @endif
                    @if($s->kunci === 'lat_toko' || $s->kunci === 'lng_toko')
                        <small class="text-muted">Ambil dari Google Maps, tekan & tahan di lokasi kedai</small>
                    @endif
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: PESANAN & POIN --}}
    <div class="tab-pane fade" id="order">
        <div class="card">
            <div class="card-header">🛒 Pengaturan Pesanan & Poin</div>
            <div class="card-body">
                <div class="row g-3">
                @foreach($grupOrder as $s)
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ $s->label }}</label>
                    <div class="input-group">
                        @if(str_contains($s->kunci, 'persen'))
                            <input type="number" name="{{ $s->kunci }}" class="form-control" value="{{ $s->nilai }}" min="0" max="100">
                            <span class="input-group-text">%</span>
                        @elseif(str_contains($s->kunci, 'km') || str_contains($s->kunci, 'radius'))
                            <input type="number" name="{{ $s->kunci }}" class="form-control" value="{{ $s->nilai }}" min="0">
                            <span class="input-group-text">km</span>
                        @elseif(str_contains($s->kunci, 'poin') || str_contains($s->kunci, 'min_poin'))
                            <input type="number" name="{{ $s->kunci }}" class="form-control" value="{{ $s->nilai }}" min="0">
                            <span class="input-group-text">poin</span>
                        @else
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="{{ $s->kunci }}" class="form-control" value="{{ $s->nilai }}" min="0">
                        @endif
                    </div>
                </div>
                @endforeach
                </div>
                <div class="alert alert-info small mt-3">
                    <strong>Catatan Poin:</strong> Contoh dengan pengaturan saat ini —
                    Belanja Rp {{ number_format(\App\Models\Pengaturan::get('poin_per_rupiah', 5000), 0, ',', '.') }} = 1 poin.
                    100 poin = Rp {{ number_format(\App\Models\Pengaturan::get('nilai_tukar_poin', 2000), 0, ',', '.') }} diskon.
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 3: TAMPILAN --}}
    <div class="tab-pane fade" id="tampilan">
        <div class="card">
            <div class="card-header">🎨 Pengaturan Tampilan</div>
            <div class="card-body">
                <div class="row g-3">
                @foreach($grupTampilan as $s)
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ $s->label }}</label>
                    @if($s->tipe === 'color')
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" name="{{ $s->kunci }}" value="{{ $s->nilai }}" class="form-control form-control-color" style="width:60px;height:40px;">
                            <input type="text" name="{{ $s->kunci }}_hex" class="form-control" value="{{ $s->nilai }}" readonly
                                id="hex_{{ $s->kunci }}">
                        </div>
                    @elseif($s->tipe === 'image')
                        <div>
                            @if($s->nilai)
                                <img src="{{ asset($s->nilai) }}" style="height:60px;border-radius:8px;margin-bottom:0.5rem;display:block;">
                            @endif
                            <input type="file" name="{{ $s->kunci }}" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
                        </div>
                    @endif
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 4: FITUR --}}
    <div class="tab-pane fade" id="fitur">
        <div class="card">
            <div class="card-header">⚙️ Aktif / Nonaktif Fitur</div>
            <div class="card-body">
                <div class="row g-3">
                @foreach($grupFitur as $s)
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background:#fdfbf7;border:1.5px solid #E6D5C3;">
                        <div>
                            <div class="fw-600 small">{{ $s->label }}</div>
                            @if($s->kunci === 'sedang_tutup')
                                <div class="text-danger" style="font-size:0.72rem;">⚠️ Aktifkan ini saat kedai tutup — pelanggan tidak bisa pesan</div>
                            @endif
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="{{ $s->kunci }}"
                                {{ $s->nilai == '1' ? 'checked' : '' }} style="width:2.5rem;height:1.25rem;cursor:pointer;">
                        </div>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-coffee px-4">
        <i class="fa fa-save me-2"></i> Simpan Semua Pengaturan
    </button>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-latte">Batal</a>
</div>
</form>

@push('scripts')
<script>
// Sync color picker dengan hex input
document.querySelectorAll('input[type="color"]').forEach(picker => {
    picker.addEventListener('input', function() {
        const key = this.name;
        const hexInput = document.getElementById('hex_' + key);
        if (hexInput) hexInput.value = this.value;
    });
});
</script>
@endpush
@endsection
