@extends('layouts.app')
@section('title', 'Manajemen Stok — BOSS KOPI')
@section('page-title', 'Stok & Bahan Baku')
@section('page-subtitle', 'Pantau dan kelola stok bahan baku')

@section('content')
@if($stokKritisCount > 0)
<div class="alert alert-warning d-flex align-items-center gap-2">
    <i class="fa fa-triangle-exclamation fa-lg"></i>
    <div>
        <strong>Peringatan!</strong> Terdapat <strong>{{ $stokKritisCount }}</strong> bahan baku dengan stok kritis.
        <a href="?filter=kritis" class="alert-link">Lihat sekarang</a>
    </div>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" class="form-control form-control-sm"
            placeholder="Cari bahan baku..." value="{{ request('search') }}" style="width:200px;">
        <select name="filter" class="form-select form-select-sm" style="width:150px;">
            <option value="">Semua Stok</option>
            <option value="kritis" {{ request('filter') === 'kritis' ? 'selected' : '' }}>Stok Kritis</option>
        </select>
        <button type="submit" class="btn btn-sm btn-coffee"><i class="fa fa-search"></i></button>
    </form>
    <a href="{{ route('admin.stok.create') }}" class="btn btn-sm btn-coffee">
        <i class="fa fa-plus me-1"></i> Tambah Bahan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Bahan Baku</th>
                        <th>Satuan</th>
                        <th>Stok Saat Ini</th>
                        <th>Stok Minimum</th>
                        <th>Harga/Satuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahanBaku as $i => $bahan)
                    <tr class="{{ $bahan->isStokKritis() ? 'stok-kritis' : '' }}">
                        <td class="small text-muted">{{ $bahanBaku->firstItem() + $i }}</td>
                        <td class="fw-500">{{ $bahan->nama }}</td>
                        <td class="small text-muted">{{ $bahan->satuan }}</td>
                        <td>
                            <strong class="{{ $bahan->isStokKritis() ? 'text-danger' : 'text-success' }}">
                                {{ number_format($bahan->stok_saat_ini, 1) }}
                            </strong>
                            {{ $bahan->satuan }}
                        </td>
                        <td class="small">{{ number_format($bahan->stok_minimum, 1) }} {{ $bahan->satuan }}</td>
                        <td class="small">Rp {{ number_format($bahan->harga_per_satuan, 0, ',', '.') }}</td>
                        <td>
                            @if($bahan->isStokKritis())
                                <span class="badge bg-danger"><i class="fa fa-triangle-exclamation me-1"></i>Kritis</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <!-- Restock Modal Button -->
                                <button class="btn btn-sm btn-coffee" title="Restock"
                                    onclick="bukaModalRestock({{ $bahan->id }}, '{{ $bahan->nama }}', '{{ $bahan->satuan }}')">
                                    <i class="bi bi-plus-circle"></i>
                                </button>
                                <a href="{{ route('admin.stok.edit', $bahan) }}" class="btn btn-sm btn-latte" title="Edit">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="{{ route('admin.stok.history', $bahan) }}" class="btn btn-sm btn-outline-secondary" title="Riwayat">
                                    <i class="bi bi-clock-history"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data bahan baku</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bahanBaku->hasPages())
    <div class="card-footer bg-white">{{ $bahanBaku->links() }}</div>
    @endif
</div>

<!-- Modal Restock -->
<div class="modal fade" id="modalRestock" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Restock Bahan Baku</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRestock" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-2">
                        <strong id="namaRestockLabel"></strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Restock <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="jumlah" class="form-control" min="0.01" step="0.01" required>
                            <span class="input-group-text" id="satuanLabel"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-latte" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-coffee">
                        <i class="bi bi-plus-circle me-1"></i> Restock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function bukaModalRestock(id, nama, satuan) {
    document.getElementById('namaRestockLabel').textContent = nama;
    document.getElementById('satuanLabel').textContent = satuan;
    document.getElementById('formRestock').action = '/admin/stok/' + id + '/restock';
    new bootstrap.Modal(document.getElementById('modalRestock')).show();
}
</script>
@endpush
