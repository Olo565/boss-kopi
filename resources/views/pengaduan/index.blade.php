@extends('layouts.app')
@section('title', 'Pengaduan Saya — BOSS KOPI')
@section('page-title', 'Riwayat Pengaduan Saya')

@section('content')
<div class="mb-3">
    <a href="{{ route('pengaduan.create') }}" class="btn btn-coffee btn-sm">
        <i class="fa fa-plus me-1"></i> Buat Pengaduan Baru
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @forelse($pengaduans as $p)
        <a href="{{ route('pengaduan.show', $p) }}" class="text-decoration-none text-dark">
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-600 small">{{ $p->judul }}</span>
                    @php
                        $statusColor = ['baru' => 'bg-warning text-dark', 'diproses' => 'bg-info text-dark', 'selesai' => 'bg-success'];
                    @endphp
                    <span class="badge {{ $statusColor[$p->status] ?? 'bg-secondary' }}">{{ $p->getLabelStatus() }}</span>
                </div>
                <div class="small text-muted mb-1">{{ $p->kategori }} &middot; {{ $p->created_at->format('d M Y, H:i') }}</div>
                <div class="small text-muted">{{ Str::limit($p->isi, 80) }}</div>
            </div>
        </a>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-megaphone" style="font-size:3rem;"></i>
            <div class="mt-2">Belum ada pengaduan yang dikirim</div>
        </div>
        @endforelse
    </div>
    @if($pengaduans->hasPages())
    <div class="card-footer bg-white">{{ $pengaduans->links() }}</div>
    @endif
</div>
@endsection
