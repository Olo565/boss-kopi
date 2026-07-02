@extends('layouts.app')
@section('title', 'Pengaduan — BOSS KOPI')
@section('page-title', 'Pengaduan Pengguna')
@section('page-subtitle', 'Komplain dari pembeli & driver')

@section('content')
<div class="d-flex gap-2 mb-3">
    <a href="?" class="btn btn-sm {{ !request('status') ? 'btn-coffee' : 'btn-latte' }}">Semua</a>
    <a href="?status=baru" class="btn btn-sm {{ request('status') === 'baru' ? 'btn-coffee' : 'btn-latte' }}">
        Baru @if($jumlahBaru > 0)<span class="badge bg-danger ms-1">{{ $jumlahBaru }}</span>@endif
    </a>
    <a href="?status=diproses" class="btn btn-sm {{ request('status') === 'diproses' ? 'btn-coffee' : 'btn-latte' }}">Diproses</a>
    <a href="?status=selesai" class="btn btn-sm {{ request('status') === 'selesai' ? 'btn-coffee' : 'btn-latte' }}">Selesai</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pengirim</th>
                        <th>Kategori</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduans as $p)
                    <tr>
                        <td class="small">{{ $p->created_at->format('d/m H:i') }}</td>
                        <td class="small">
                            {{ $p->user->name ?? '-' }}
                            <span class="badge badge-latte" style="font-size:0.65rem;">{{ ucfirst($p->user->role ?? '-') }}</span>
                        </td>
                        <td class="small">{{ $p->kategori }}</td>
                        <td class="small">{{ Str::limit($p->judul, 40) }}</td>
                        <td>
                            @php
                                $statusColor = ['baru' => 'bg-warning text-dark', 'diproses' => 'bg-info text-dark', 'selesai' => 'bg-success'];
                            @endphp
                            <span class="badge {{ $statusColor[$p->status] ?? 'bg-secondary' }}">{{ $p->getLabelStatus() }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.pengaduan.show', $p) }}" class="btn btn-sm btn-latte">
                                <i class="bi bi-eye"></i> Tindak Lanjuti
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada pengaduan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pengaduans->hasPages())
    <div class="card-footer bg-white">{{ $pengaduans->links() }}</div>
    @endif
</div>
@endsection
