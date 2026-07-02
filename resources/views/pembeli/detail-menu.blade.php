@extends('layouts.app')
@section('title', $menu->nama . ' — BOSS KOPI')
@section('page-title', 'Detail Menu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div style="height:220px;background:var(--latte);border-radius:12px 12px 0 0;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                @if($menu->foto)
                    <img src="{{ asset($menu->foto) }}" style="width:100%;height:220px;object-fit:cover;">
                @else
                    <i class="fa fa-mug-hot" style="font-size:4rem;color:var(--coffee);"></i>
                @endif
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="fw-700" style="color:var(--coffee);">{{ $menu->nama }}</h4>
                        <span class="badge badge-latte">{{ $menu->kategori->nama }}</span>
                        @if($menu->is_best_seller)
                        <span class="badge badge-coffee"><i class="fa fa-star me-1"></i>Best Seller</span>
                        @endif
                    </div>
                </div>
                @if($menu->deskripsi)
                <p class="text-muted mt-2">{{ $menu->deskripsi }}</p>
                @endif
                @if($menu->varian)
                <div class="mb-3">
                    <label class="form-label fw-600">Varian Tersedia</label>
                    <div>{{ $menu->varian }}</div>
                </div>
                @endif

                <div class="row g-2 mb-3">
                    <div class="col-4 text-center p-2 rounded" style="background:var(--latte);">
                        <div class="small text-muted">Dine-in</div>
                        <div class="fw-700" style="color:var(--coffee);">Rp {{ number_format($menu->harga_dine_in, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-4 text-center p-2 rounded" style="background:var(--latte);">
                        <div class="small text-muted">Takeaway</div>
                        <div class="fw-700" style="color:var(--coffee);">Rp {{ number_format($menu->harga_takeaway, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-4 text-center p-2 rounded" style="background:var(--latte);">
                        <div class="small text-muted">Delivery</div>
                        <div class="fw-700" style="color:var(--coffee);">Rp {{ number_format($menu->harga_delivery, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-600">Catatan Khusus (Opsional)</label>
                    <textarea id="catatanMenu" class="form-control" rows="2"
                        placeholder="Contoh: Less Sugar, No Ice, dll..."></textarea>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <label class="form-label fw-600 mb-0">Jumlah</label>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-latte btn-sm" onclick="ubahJumlah(-1)">−</button>
                        <span id="jumlahDisplay" class="fw-700" style="min-width:30px;text-align:center;">1</span>
                        <button class="btn btn-latte btn-sm" onclick="ubahJumlah(1)">+</button>
                    </div>
                </div>

                <button onclick="tambahKeKeranjang()" class="btn btn-coffee w-100 py-3" {{ !$menu->tersedia ? 'disabled' : '' }}>
                    <i class="bi bi-cart-plus me-2"></i>
                    @if($menu->tersedia)
                        Tambah ke Keranjang — <span id="totalHargaDisplay">Rp {{ number_format($menu->harga_dine_in, 0, ',', '.') }}</span>
                    @else
                        Menu Tidak Tersedia
                    @endif
                </button>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('pembeli.menu') }}" class="btn btn-latte">
                <i class="fa fa-arrow-left me-2"></i> Kembali ke Menu
            </a>
        </div>
    </div>
</div>
@endsection

{{-- ULASAN SECTION --}}
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fa fa-star text-warning me-2"></i>Ulasan Pembeli</span>
        @if($rataRating)
        <span>
            <span class="fw-700" style="color:var(--coffee);">{{ number_format($rataRating, 1) }}</span>
            <span class="text-muted small">/5 ({{ $ulasans->count() }} ulasan)</span>
        </span>
        @endif
    </div>
    <div class="card-body">
        {{-- Form Tulis Ulasan --}}
        @auth
        @if(!$sudahUlasan)
        <div class="mb-4 p-3 rounded" style="background:#f8f1e3;">
            <div class="fw-600 small mb-2" style="color:var(--coffee);">Tulis Ulasan Anda</div>
            @if(session('success')) <div class="alert alert-success small py-2">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert alert-danger small py-2">{{ session('error') }}</div> @endif
            <form action="{{ route('pembeli.ulasan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                <div class="mb-2 text-center" id="starPickerUlasan">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="fa fa-star star-ulasan" data-value="{{ $i }}"
                        style="font-size:1.8rem;color:#ddd;cursor:pointer;margin:0 2px;"
                        onclick="pilihBintangUlasan({{ $i }})"></i>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingUlasanVal" required>
                <textarea name="komentar" class="form-control form-control-sm mb-2" rows="2"
                    placeholder="Ceritakan pengalaman Anda dengan menu ini... (opsional)"></textarea>
                <button type="submit" class="btn btn-coffee btn-sm w-100">
                    <i class="fa fa-paper-plane me-1"></i> Kirim Ulasan
                </button>
            </form>
        </div>
        @else
        <div class="alert alert-info small py-2 mb-3">Anda sudah memberikan ulasan untuk menu ini.</div>
        @endif
        @endauth

        {{-- Daftar Ulasan --}}
        @forelse($ulasans as $u)
        <div class="d-flex gap-3 py-3 border-bottom">
            <img src="{{ $u->user->foto ? asset($u->user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($u->user->name).'&background=4A3525&color=fff&size=40' }}"
                style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
            <div class="flex-fill">
                <div class="d-flex justify-content-between">
                    <span class="fw-600 small">{{ $u->user->name }}</span>
                    <span class="small text-muted">{{ $u->created_at->diffForHumans() }}</span>
                </div>
                <div class="mb-1">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="fa fa-star" style="font-size:0.75rem;color:{{ $i <= $u->rating ? '#FFC107' : '#ddd' }};"></i>
                    @endfor
                </div>
                @if($u->komentar)
                <div class="small text-muted">{{ $u->komentar }}</div>
                @endif
                @if(auth()->check() && auth()->id() === $u->user_id)
                <form action="{{ route('pembeli.ulasan.destroy', $u) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm p-0 text-danger" style="font-size:0.7rem;"
                        onclick="return confirm('Hapus ulasan ini?')">Hapus</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-3 text-muted small">Belum ada ulasan. Jadilah yang pertama!</div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function pilihBintangUlasan(value) {
    document.getElementById('ratingUlasanVal').value = value;
    document.querySelectorAll('.star-ulasan').forEach(s => {
        s.style.color = parseInt(s.dataset.value) <= value ? '#FFC107' : '#ddd';
    });
}

let jumlah = 1;
const harga = {{ $menu->harga_dine_in }};

function ubahJumlah(delta) {
    jumlah = Math.max(1, jumlah + delta);
    document.getElementById('jumlahDisplay').textContent = jumlah;
    document.getElementById('totalHargaDisplay').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga * jumlah);
}

function tambahKeKeranjang() {
    let cart = JSON.parse(localStorage.getItem('bosskopi_cart') || '[]');
    const catatan = document.getElementById('catatanMenu').value;
    const existing = cart.find(i => i.id == {{ $menu->id }} && i.catatan === catatan);
    if (existing) existing.jumlah += jumlah;
    else cart.push({
        id: {{ $menu->id }},
        nama: @json($menu->nama),
        harga: harga,
        jumlah: jumlah,
        catatan: catatan
    });
    localStorage.setItem('bosskopi_cart', JSON.stringify(cart));
    window.location.href = '{{ route("pembeli.keranjang") }}';
}
</script>
@endpush
