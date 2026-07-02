@extends('layouts.app')
@section('title', 'Manajemen Promo — BOSS KOPI')
@section('page-title', 'Promo & Diskon')
@section('page-subtitle', 'Kelola kode kupon dan promo aktif')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.promo.create') }}" class="btn btn-sm btn-coffee">
        <i class="fa fa-plus me-1"></i> Buat Promo Baru
    </a>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nama Promo</th>
                        <th>Kode Kupon</th>
                        <th>Tipe</th>
                        <th>Nilai</th>
                        <th>Periode</th>
                        <th>Digunakan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $promo)
                    <tr>
                        <td class="fw-500">{{ $promo->nama }}</td>
                        <td>
                            @if($promo->kode_kupon)
                                <code class="bg-light px-2 py-1 rounded">{{ $promo->kode_kupon }}</code>
                            @else <span class="text-muted">-</span> @endif
                        </td>
                        <td class="small">
                            @php $tipeLabel = ['persentase' => 'Persentase', 'nominal' => 'Nominal', 'buy1get1' => 'Buy 1 Get 1', 'paket' => 'Paket']; @endphp
                            {{ $tipeLabel[$promo->tipe] ?? $promo->tipe }}
                        </td>
                        <td class="fw-600" style="color:var(--coffee);">
                            @if($promo->tipe === 'persentase')
                                {{ $promo->nilai_diskon }}%
                            @elseif($promo->tipe === 'nominal')
                                Rp {{ number_format($promo->nilai_diskon, 0, ',', '.') }}
                            @else -
                            @endif
                        </td>
                        <td class="small">
                            {{ $promo->tanggal_mulai->format('d/m/Y') }} -
                            {{ $promo->tanggal_selesai->format('d/m/Y') }}
                        </td>
                        <td class="small">
                            {{ $promo->sudah_digunakan }}
                            @if($promo->max_penggunaan) / {{ $promo->max_penggunaan }} @endif
                        </td>
                        <td>
                            @if($promo->isValid())
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.promo.edit', $promo) }}" class="btn btn-sm btn-latte">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.promo.destroy', $promo) }}" method="POST"
                                    onsubmit="return confirm('Hapus promo ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada promo</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
