@extends('layouts.app')
@section('title', 'Driver Menunggu — BOSS KOPI')
@section('page-title', 'Verifikasi Driver Baru')
@section('page-subtitle', 'Proses pendaftaran driver sebelum akun diaktifkan')

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="small text-muted">Total driver proses: <strong>{{ $drivers->count() }}</strong></div>
    <a href="{{ route('admin.user.format-berkas-driver') }}" target="_blank" class="btn btn-coffee btn-sm">
        <i class="fa fa-file-arrow-down me-2"></i> Download Format Berkas Driver
    </a>
</div>

@if($drivers->count() === 0)
<div class="card">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-check-circle" style="font-size:3rem;color:#2D6A4F;"></i>
        <div class="mt-2">Tidak ada driver yang sedang dalam proses verifikasi.</div>
    </div>
</div>
@else

{{-- Alur tahapan --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-2 small">
            <span class="badge bg-warning text-dark px-3 py-2">1. Menunggu</span>
            <i class="fa fa-arrow-right text-muted"></i>
            <span class="badge bg-info text-dark px-3 py-2">2. Dipanggil (Lengkapi Berkas)</span>
            <i class="fa fa-arrow-right text-muted"></i>
            <span class="badge bg-success px-3 py-2">3. Aktif (Bisa Login)</span>
            <i class="fa fa-arrow-right text-muted"></i>
            <span class="badge bg-danger px-3 py-2">Ditolak</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @foreach($drivers as $driver)
        @php
            $noHp = preg_replace('/[^0-9]/', '', $driver->no_hp);
            if (str_starts_with($noHp, '0')) $noHp = '62' . substr($noHp, 1);
        @endphp
        <div class="p-3 border-bottom">
            <div class="d-flex align-items-start gap-3 mb-3">
                <img src="{{ $driver->foto ? asset($driver->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($driver->name) . '&background=4A3525&color=fff&size=60' }}"
                    style="width:55px;height:55px;border-radius:50%;object-fit:cover;border:2px solid var(--latte);flex-shrink:0;">
                <div class="flex-fill">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-700" style="color:var(--coffee);">{{ $driver->name }}</span>
                        @if($driver->status_akun === 'menunggu')
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @elseif($driver->status_akun === 'dipanggil')
                            <span class="badge bg-info text-dark">Dipanggil — Tunggu Berkas</span>
                        @endif
                    </div>
                    <div class="small text-muted">{{ $driver->email }}</div>
                    <div class="small text-muted"><i class="fa fa-phone me-1"></i>{{ $driver->no_hp }}</div>
                    <div class="small text-muted">
                        <i class="bi bi-bicycle me-1"></i>{{ $driver->jenis_kendaraan ?? '-' }}
                        &middot; Plat: <strong>{{ $driver->plat_nomor ?? '-' }}</strong>
                    </div>
                    <div class="small text-muted"><i class="fa fa-clock me-1"></i>Daftar: {{ $driver->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>

            @if($driver->status_akun === 'menunggu')
            {{-- Tahap 1: Belum dipanggil --}}
            <div class="alert alert-warning py-2 small mb-3">
                <i class="fa fa-info-circle me-1"></i>
                Driver baru mendaftar. Klik "Panggil via WA" untuk menghubungi driver agar datang melengkapi berkas.
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.user.panggil-driver', $driver) }}"
                    class="btn btn-success btn-sm flex-fill"
                    onclick="return confirm('Buka WhatsApp untuk panggil {{ $driver->name }} lengkapi berkas?')">
                    <i class="bi bi-whatsapp me-1"></i> Panggil via WA (Minta Lengkapi Berkas)
                </a>
                <form action="{{ route('admin.user.approve-driver', $driver) }}" method="POST" class="flex-fill"
                    onsubmit="return confirm('Tolak pendaftaran {{ $driver->name }}?')">
                    @csrf <input type="hidden" name="aksi" value="tolak">
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fa fa-xmark me-1"></i> Tolak
                    </button>
                </form>
            </div>

            @elseif($driver->status_akun === 'dipanggil')
            {{-- Tahap 2: Sudah dipanggil, tunggu berkas datang --}}
            <div class="alert alert-info py-2 small mb-3">
                <i class="fa fa-clock me-1"></i>
                Driver sudah dihubungi via WA. Tunggu driver datang membawa berkas lengkap, lalu aktifkan akun.
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.user.approve-driver', $driver) }}" method="POST" class="flex-fill">
                    @csrf <input type="hidden" name="aksi" value="aktifkan">
                    <button type="submit" class="btn btn-success btn-sm w-100"
                        onclick="return confirm('Aktifkan akun driver {{ $driver->name }}? Pastikan semua berkas sudah lengkap.')">
                        <i class="fa fa-check me-1"></i> Berkas Lengkap — Aktifkan Akun
                    </button>
                </form>
                <a href="{{ route('admin.user.panggil-driver', $driver) }}"
                    class="btn btn-latte btn-sm"
                    title="Ingatkan lagi via WA">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <form action="{{ route('admin.user.approve-driver', $driver) }}" method="POST"
                    onsubmit="return confirm('Tolak pendaftaran {{ $driver->name }}?')">
                    @csrf <input type="hidden" name="aksi" value="tolak">
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-xmark"></i>
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
