@extends('layouts.app')
@section('title', 'Manajemen Menu — BOSS KOPI')
@section('page-title', 'Manajemen Menu')
@section('page-subtitle', 'Tambah, edit, dan kelola seluruh menu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="Cari nama menu..." value="{{ request('search') }}" style="width:200px;">
            <select name="kategori" class="form-select form-select-sm" style="width:160px;">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-coffee">
                <i class="fa fa-search"></i>
            </button>
        </form>
    </div>
    <a href="{{ route('admin.menu.create') }}" class="btn btn-sm btn-coffee">
        <i class="fa fa-plus me-1"></i> Tambah Menu
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Varian</th>
                        <th>Harga Dine-in</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $i => $menu)
                    <tr>
                        <td class="text-muted small">{{ $menus->firstItem() + $i }}</td>
                        <td>
                            @if($menu->foto)
                                <img src="{{ asset($menu->foto) }}" width="44" height="44"
                                    style="object-fit:cover;border-radius:8px;">
                            @else
                                <div style="width:44px;height:44px;background:var(--latte);border-radius:8px;
                                    display:flex;align-items:center;justify-content:center;">
                                    <i class="fa fa-mug-hot" style="color:var(--coffee);"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-500">{{ $menu->nama }}</div>
                            @if($menu->is_best_seller)
                                <span class="badge badge-coffee" style="font-size:0.65rem;">
                                    <i class="fa fa-star me-1"></i>Best Seller
                                </span>
                            @endif
                        </td>
                        <td><span class="badge badge-latte">{{ $menu->kategori->nama ?? '-' }}</span></td>
                        <td class="small text-muted">{{ $menu->varian ?? '-' }}</td>
                        <td class="fw-600" style="color:var(--coffee);">
                            Rp {{ number_format($menu->harga_dine_in, 0, ',', '.') }}
                        </td>
                        <td>
                            <form action="{{ route('admin.menu.toggle', $menu) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="badge border-0 {{ $menu->tersedia ? 'bg-success' : 'bg-secondary' }}"
                                    style="cursor:pointer;">
                                    {{ $menu->tersedia ? 'Tersedia' : 'Tidak Tersedia' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.menu.edit', $menu) }}"
                                    class="btn btn-sm btn-latte" title="Edit">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus menu {{ $menu->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fa fa-bowl-food fs-3 d-block mb-2"></i>
                            Belum ada menu. <a href="{{ route('admin.menu.create') }}">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($menus->hasPages())
    <div class="card-footer bg-white">
        {{ $menus->links() }}
    </div>
    @endif
</div>
@endsection
