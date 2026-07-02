@extends('layouts.app')
@section('title', 'Buat Pengaduan — BOSS KOPI')
@section('page-title', 'Buat Pengaduan / Laporan Masalah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="mb-3">
            <a href="{{ route('pengaduan.index') }}" class="btn btn-sm btn-latte">
                <i class="fa fa-arrow-left me-1"></i> Riwayat Pengaduan Saya
            </a>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-megaphone me-2"></i>Form Pengaduan</div>
            <div class="card-body">
                <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Pesanan/Pengiriman" {{ old('kategori') === 'Pesanan/Pengiriman' ? 'selected' : '' }}>Masalah Pesanan / Pengiriman</option>
                            <option value="Pembayaran" {{ old('kategori') === 'Pembayaran' ? 'selected' : '' }}>Masalah Pembayaran</option>
                            <option value="Aplikasi/Teknis" {{ old('kategori') === 'Aplikasi/Teknis' ? 'selected' : '' }}>Masalah Aplikasi / Teknis</option>
                            <option value="Perilaku Pengguna" {{ old('kategori') === 'Perilaku Pengguna' ? 'selected' : '' }}>Perilaku Driver/Pelanggan</option>
                            <option value="Lainnya" {{ old('kategori') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    @if($orders->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Terkait Pesanan (Opsional)</label>
                        <select name="order_id" class="form-control">
                            <option value="">-- Tidak terkait pesanan tertentu --</option>
                            @foreach($orders as $order)
                            <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                {{ $order->nomor_struk }} — {{ $order->created_at->format('d M Y') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                            placeholder="Ringkasan singkat masalah Anda" value="{{ old('judul') }}" required>
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Detail Pengaduan</label>
                        <textarea name="isi" class="form-control @error('isi') is-invalid @enderror" rows="5"
                            placeholder="Jelaskan masalah Anda secara detail..." required>{{ old('isi') }}</textarea>
                        @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Lampiran Foto (Opsional)</label>
                        <input type="file" name="foto_lampiran" class="form-control @error('foto_lampiran') is-invalid @enderror" accept="image/*">
                        @error('foto_lampiran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Maks. 2MB. Bisa berupa screenshot atau foto bukti masalah.</small>
                    </div>

                    <button type="submit" class="btn btn-coffee w-100">
                        <i class="fa fa-paper-plane me-2"></i> Kirim Pengaduan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
