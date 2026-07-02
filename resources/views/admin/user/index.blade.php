@extends('layouts.app')
@section('title', 'Pengguna — BOSS KOPI')
@section('page-title', 'Pengguna & Notifikasi')
@section('page-subtitle', 'Semua yang perlu ditindaklanjuti ada di sini')

@section('content')

{{-- ===== PANEL NOTIFIKASI ===== --}}
@php
    $totalNotif = $driverMenunggu->count() + $pembeliBeluVerifikasi->count() + $pesananPending->count() + $pengaduanBaru->count();
@endphp

@if($totalNotif > 0)
<div class="card mb-4" style="border:2px solid #4A3525;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background:#4A3525;color:#fff;border-radius:14px 14px 0 0;">
        <span><i class="fa fa-bell me-2"></i>Perlu Ditindaklanjuti</span>
        <span class="badge bg-danger">{{ $totalNotif }} tugas</span>
    </div>
    <div class="card-body p-0">

        {{-- 1. Driver Menunggu --}}
        @if($driverMenunggu->count() > 0)
        <div class="border-bottom p-3">
            <div class="fw-700 small mb-2" style="color:#4A3525;">
                🛵 Driver Menunggu Verifikasi ({{ $driverMenunggu->count() }})
            </div>
            @foreach($driverMenunggu as $driver)
            <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#fdfbf7;">
                <img src="{{ $driver->foto ? asset($driver->foto) : 'https://ui-avatars.com/api/?name='.urlencode($driver->name).'&background=4A3525&color=fff&size=40' }}"
                    style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                <div class="flex-fill">
                    <div class="fw-600 small">{{ $driver->name }}</div>
                    <div class="small text-muted">{{ $driver->no_hp }} &middot; {{ $driver->jenis_kendaraan ?? '-' }}</div>
                </div>
                @if($driver->status_akun === 'menunggu')
                <span class="badge bg-warning text-dark me-1">Baru</span>
                <a href="{{ route('admin.user.panggil-driver', $driver) }}" class="btn btn-success btn-sm"
                    onclick="return confirm('Buka WA untuk panggil {{ $driver->name }}?')">
                    <i class="bi bi-whatsapp me-1"></i> Panggil
                </a>
                @else
                <span class="badge bg-info text-dark me-1">Dipanggil</span>
                <form action="{{ route('admin.user.approve-driver', $driver) }}" method="POST" class="d-inline">
                    @csrf <input type="hidden" name="aksi" value="aktifkan">
                    <button type="submit" class="btn btn-success btn-sm"
                        onclick="return confirm('Aktifkan akun {{ $driver->name }}?')">
                        <i class="fa fa-check me-1"></i> Aktifkan
                    </button>
                </form>
                @endif
                <form action="{{ route('admin.user.approve-driver', $driver) }}" method="POST" class="d-inline ms-1">
                    @csrf <input type="hidden" name="aksi" value="tolak">
                    <button type="submit" class="btn btn-outline-danger btn-sm"
                        onclick="return confirm('Tolak {{ $driver->name }}?')">
                        <i class="fa fa-xmark"></i>
                    </button>
                </form>
            </div>
            @endforeach
            <a href="{{ route('admin.user.format-berkas-driver') }}" target="_blank" class="btn btn-sm btn-latte mt-1">
                <i class="fa fa-file-arrow-down me-1"></i> Download Format Berkas Driver
            </a>
        </div>
        @endif

        {{-- 2. Verifikasi WA Pembeli --}}
        @if($pembeliBeluVerifikasi->count() > 0)
        <div class="border-bottom p-3">
            <div class="fw-700 small mb-2" style="color:#4A3525;">
                📱 Pembeli Belum Verifikasi WA ({{ $pembeliBeluVerifikasi->count() }})
            </div>
            @foreach($pembeliBeluVerifikasi as $pembeli)
            <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#fdfbf7;">
                <div class="flex-fill">
                    <div class="fw-600 small">{{ $pembeli->name }}</div>
                    <div class="small text-muted">{{ $pembeli->no_hp }} &middot; {{ $pembeli->email }}</div>
                </div>
                <a href="{{ route('admin.user.kirim-kode-wa', $pembeli) }}" class="btn btn-success btn-sm"
                    onclick="return confirm('Kirim kode WA ke {{ $pembeli->name }}?')">
                    <i class="bi bi-whatsapp me-1"></i> Kirim Kode
                </a>
            </div>
            @endforeach
        </div>
        @endif

        {{-- 3. Pesanan Online Pending --}}
        @if($pesananPending->count() > 0)
        <div class="border-bottom p-3">
            <div class="fw-700 small mb-2" style="color:#4A3525;">
                🛒 Pesanan Online Menunggu Konfirmasi ({{ $pesananPending->count() }})
            </div>
            @foreach($pesananPending as $order)
            <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#fdfbf7;">
                <div class="flex-fill">
                    <div class="fw-600 small">{{ $order->nomor_struk }}</div>
                    <div class="small text-muted">{{ $order->user->name ?? '-' }} &middot; Rp {{ number_format($order->total,0,',','.') }} &middot; {{ $order->created_at->diffForHumans() }}</div>
                </div>
                <a href="{{ route('admin.order.show', $order) }}" class="btn btn-coffee btn-sm">
                    Proses →
                </a>
            </div>
            @endforeach
            <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-latte mt-1">Lihat Semua Pesanan →</a>
        </div>
        @endif

        {{-- 4. Pengaduan Baru --}}
        @if($pengaduanBaru->count() > 0)
        <div class="p-3">
            <div class="fw-700 small mb-2" style="color:#4A3525;">
                📣 Pengaduan Baru Belum Ditindaklanjuti ({{ $pengaduanBaru->count() }})
            </div>
            @foreach($pengaduanBaru as $p)
            <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#fdfbf7;">
                <div class="flex-fill">
                    <div class="fw-600 small">{{ $p->judul }}</div>
                    <div class="small text-muted">{{ $p->user->name ?? '-' }} &middot; {{ $p->kategori }} &middot; {{ $p->created_at->diffForHumans() }}</div>
                </div>
                <a href="{{ route('admin.pengaduan.show', $p) }}" class="btn btn-coffee btn-sm">
                    Balas →
                </a>
            </div>
            @endforeach
            <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-sm btn-latte mt-1">Lihat Semua Pengaduan →</a>
        </div>
        @endif

    </div>
</div>
@else
<div class="alert alert-success mb-4">
    <i class="fa fa-circle-check me-2"></i> Semua beres! Tidak ada tugas yang perlu ditindaklanjuti saat ini.
</div>
@endif

{{-- ===== KODE OTP FLASH ===== --}}
@if(session('kode_verifikasi'))
@php
    $noHpRaw = preg_replace('/[^0-9]/', '', session('no_hp_pelanggan'));
    if (str_starts_with($noHpRaw, '0')) $noHpRaw = '62' . substr($noHpRaw, 1);
    $pesanWA = urlencode("Halo " . session('nama_pelanggan') . "! 👋\n\nKode verifikasi WhatsApp BOSS KOPI Anda:\n\n🔑 *" . session('kode_verifikasi') . "*\n\nMasukkan kode ini di aplikasi. Terima kasih! ☕");
@endphp
<div class="card mb-4" style="border:3px solid #25D366;">
    <div class="card-body text-center py-3">
        <div style="font-size:2rem;">📋</div>
        <h6 class="fw-700 mt-1" style="color:#4A3525;">Kode Verifikasi untuk {{ session('nama_pelanggan') }}</h6>
        <div class="my-2 p-3 rounded" style="background:#f0fdf4;border:2px dashed #25D366;">
            <div style="font-size:2.2rem;font-weight:900;letter-spacing:0.5rem;color:#4A3525;">{{ session('kode_verifikasi') }}</div>
        </div>
        <a href="https://wa.me/{{ $noHpRaw }}?text={{ $pesanWA }}" target="_blank"
            class="btn py-2 px-4 fw-600" style="background:#25D366;color:#fff;border-radius:10px;">
            <i class="bi bi-whatsapp me-2"></i> Buka WhatsApp & Kirim Kode
        </a>
    </div>
</div>
@endif

{{-- ===== FLASH MESSAGES ===== --}}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('warning'))
<div class="alert alert-warning">{{ session('warning') }}</div>
@endif

{{-- ===== TABEL PENGGUNA ===== --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-700 mb-0" style="color:#4A3525;">Semua Pengguna</h6>
    <a href="{{ route('admin.user.create') }}" class="btn btn-coffee btn-sm">
        <i class="fa fa-plus me-1"></i> Tambah Pengguna
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama..." value="{{ request('search') }}">
            <select name="role" class="form-control form-control-sm" style="max-width:130px;">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="kasir" {{ request('role') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                <option value="pembeli" {{ request('role') === 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                <option value="driver" {{ request('role') === 'driver' ? 'selected' : '' }}>Driver</option>
            </select>
            <button type="submit" class="btn btn-latte btn-sm px-3">Cari</button>
            @if(request('search') || request('role'))
            <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Poin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                    <tr>
                        <td class="small">{{ $users->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $user->foto ? asset($user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4A3525&color=fff&size=32' }}"
                                    style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                                <span class="small fw-600">{{ $user->name }}</span>
                                @if($user->role === 'pembeli' && !($user->no_hp_terverifikasi ?? false))
                                    <span class="badge bg-warning text-dark" style="font-size:0.6rem;">WA?</span>
                                @endif
                            </div>
                        </td>
                        <td class="small">{{ $user->email }}</td>
                        <td class="small">{{ $user->no_hp }}</td>
                        <td><span class="badge badge-latte">{{ ucfirst($user->role) }}</span></td>
                        <td>
                            @if(($user->status_akun ?? 'aktif') === 'menunggu')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif(($user->status_akun ?? 'aktif') === 'dipanggil')
                                <span class="badge bg-info text-dark">Dipanggil</span>
                            @elseif(($user->status_akun ?? 'aktif') === 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="small">{{ number_format($user->poin_loyalitas) }}</td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-sm btn-latte">
                                    <i class="fa fa-pen"></i>
                                </a>
                                @if($user->role === 'pembeli' && ($user->diblokir_delivery ?? false))
                                <form action="{{ route('admin.user.buka-blokir', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" title="Buka Blokir Delivery"
                                        onclick="return confirm('Buka blokir delivery {{ $user->name }}?')">
                                        <i class="bi bi-unlock"></i>
                                    </button>
                                </form>
                                @endif
                                @if($user->role === 'pembeli' && !($user->no_hp_terverifikasi ?? false))
                                <a href="{{ route('admin.user.kirim-kode-wa', $user) }}"
                                    class="btn btn-sm btn-success" title="Kirim Kode Verifikasi WA"
                                    onclick="return confirm('Kirim kode WA ke {{ $user->name }}?')">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                @endif
                                <form action="{{ route('admin.user.toggle', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-latte' : 'btn-coffee' }}"
                                        onclick="return confirm('Ubah status {{ $user->name }}?')">
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.user.destroy', $user) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus akun {{ $user->name }}?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada pengguna ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white">{{ $users->links() }}</div>
    @endif
</div>
@endsection
