@extends('layouts.app')
@section('title', 'Detail Pengaduan — BOSS KOPI')
@section('page-title', 'Detail Pengaduan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="mb-3">
            <a href="{{ route('pengaduan.index') }}" class="btn btn-sm btn-latte">
                <i class="fa fa-arrow-left me-1"></i> Kembali
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
                <div class="small text-muted mb-3">
                    {{ $pengaduan->kategori }} &middot; Dikirim {{ $pengaduan->created_at->format('d M Y, H:i') }}
                    @if($pengaduan->order)
                        &middot; Terkait pesanan <code>{{ $pengaduan->order->nomor_struk }}</code>
                    @endif
                </div>
                <p>{{ $pengaduan->isi }}</p>
                @if($pengaduan->foto_lampiran)
                <img src="{{ asset($pengaduan->foto_lampiran) }}" class="img-fluid rounded mt-2" style="max-height:300px;" alt="Lampiran">
                @endif
            </div>
        </div>

        @if($pengaduan->balasan_admin)
        <div class="card" style="background:var(--latte);">
            <div class="card-header bg-transparent"><i class="bi bi-reply-fill me-2"></i>Balasan dari Admin</div>
            <div class="card-body">
                <p class="mb-0">{{ $pengaduan->balasan_admin }}</p>
            </div>
        </div>
        @else
        <div class="alert alert-info">Pengaduan Anda sedang ditinjau oleh tim kami. Mohon ditunggu ya.</div>
        @endif
    </div>
</div>
@endsection
