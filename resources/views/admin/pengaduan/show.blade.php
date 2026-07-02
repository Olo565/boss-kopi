@extends('layouts.app')
@section('title', 'Detail Pengaduan — BOSS KOPI')
@section('page-title', 'Detail Pengaduan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="mb-3">
            <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-sm btn-latte">
                <i class="fa fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ $pengaduan->judul }}</span>
                @php
                    $statusColor = ['baru' => 'bg-warning text-dark', 'diproses' => 'bg-info text-dark', 'selesai' => 'bg-success'];
                @endphp
                <span class="badge {{ $statusColor[$pengaduan->status] ?? 'bg-secondary' }}">{{ $pengaduan->getLabelStatus() }}</span>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3 small">
                    <div class="col-6"><span class="text-muted">Pengirim:</span> {{ $pengaduan->user->name ?? '-' }}
                        <span class="badge badge-latte" style="font-size:0.65rem;">{{ ucfirst($pengaduan->user->role ?? '-') }}</span>
                    </div>
                    <div class="col-6"><span class="text-muted">No. HP:</span> {{ $pengaduan->user->no_hp ?? '-' }}</div>
                    <div class="col-6"><span class="text-muted">Kategori:</span> {{ $pengaduan->kategori }}</div>
                    <div class="col-6"><span class="text-muted">Waktu:</span> {{ $pengaduan->created_at->format('d M Y, H:i') }}</div>
                    @if($pengaduan->order)
                    <div class="col-12"><span class="text-muted">Pesanan Terkait:</span> <code>{{ $pengaduan->order->nomor_struk }}</code></div>
                    @endif
                </div>
                <hr>
                <p>{{ $pengaduan->isi }}</p>
                @if($pengaduan->foto_lampiran)
                <img src="{{ asset($pengaduan->foto_lampiran) }}" class="img-fluid rounded mt-2" style="max-height:300px;" alt="Lampiran">
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-reply-fill me-2"></i>Tindak Lanjuti & Balas</div>
            <div class="card-body">
                <form action="{{ route('admin.pengaduan.update', $pengaduan) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="baru" {{ $pengaduan->status === 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="diproses" {{ $pengaduan->status === 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="selesai" {{ $pengaduan->status === 'selesai' ? 'selected' : '' }}>Selesai Ditindaklanjuti</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Balasan untuk Pengguna</label>
                        <textarea name="balasan_admin" class="form-control" rows="4"
                            placeholder="Tuliskan balasan/tindak lanjut yang akan dilihat pengguna...">{{ old('balasan_admin', $pengaduan->balasan_admin) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-coffee w-100">
                        <i class="fa fa-save me-2"></i> Simpan & Kirim Balasan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
