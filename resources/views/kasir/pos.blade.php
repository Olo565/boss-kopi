@extends('layouts.app')
@section('title', 'POS Kasir — BOSS KOPI')
@section('page-title', 'Point of Sale')
@section('page-subtitle', 'Shift: ' . $shiftAktif->waktu_buka->format('d M Y, H:i'))

@push('styles')
<style>
.pos-wrapper { display: flex; gap: 1rem; height: calc(100vh - 130px); }
.pos-menu { flex: 1; overflow-y: auto; }
.pos-cart { width: 360px; min-width: 360px; display: flex; flex-direction: column; }
.kategori-tab { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; }
.kategori-tab .btn { font-size: 0.78rem; padding: 0.35rem 0.85rem; border-radius: 20px; }
.menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.75rem; }
.menu-card {
    background: #fff; border-radius: 10px; padding: 0.75rem;
    cursor: pointer; transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(74,53,37,0.08);
    border: 2px solid transparent;
    user-select: none;
}
.menu-card:hover { border-color: var(--coffee); transform: translateY(-2px); }
.menu-card.tidak-tersedia { opacity: 0.4; cursor: not-allowed; }
.menu-card-img {
    width: 100%; height: 80px; object-fit: cover;
    border-radius: 6px; margin-bottom: 0.5rem;
    background: var(--latte); display: flex; align-items: center; justify-content: center;
}
.menu-card-nama { font-size: 0.8rem; font-weight: 600; color: var(--charcoal); line-height: 1.3; }
.menu-card-harga { font-size: 0.78rem; color: var(--coffee); font-weight: 700; margin-top: 0.25rem; }
.menu-card-varian { font-size: 0.68rem; color: #999; }
.cart-header {
    background: var(--coffee); color: #fff; padding: 0.75rem 1rem;
    border-radius: 10px 10px 0 0; font-weight: 600; font-size: 0.9rem;
}
.cart-body { flex: 1; overflow-y: auto; background: #fff; padding: 0.75rem; }
.cart-item {
    display: flex; align-items: center; gap: 0.5rem;
    padding: 0.5rem 0; border-bottom: 1px solid var(--latte);
}
.cart-item-nama { flex: 1; font-size: 0.8rem; font-weight: 500; }
.cart-item-harga { font-size: 0.78rem; color: var(--coffee); font-weight: 600; min-width: 70px; text-align: right; }
.qty-control { display: flex; align-items: center; gap: 0.35rem; }
.qty-btn { width: 24px; height: 24px; border: none; border-radius: 6px; background: var(--latte); color: var(--coffee); font-weight: 700; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; }
.qty-btn:hover { background: var(--coffee); color: #fff; }
.qty-num { font-size: 0.85rem; font-weight: 700; min-width: 20px; text-align: center; }
.cart-footer { background: var(--latte); padding: 0.75rem 1rem; border-radius: 0 0 10px 10px; }
.cart-total-row { display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 0.35rem; }
.cart-total-row.total { font-weight: 700; font-size: 1rem; color: var(--coffee); }
.btn-bayar { width: 100%; padding: 0.75rem; background: var(--coffee); color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 1rem; margin-top: 0.5rem; cursor: pointer; transition: all 0.2s; }
.btn-bayar:hover { background: var(--coffee-hover); }
.btn-bayar:disabled { background: #999; cursor: not-allowed; }
.empty-cart { text-align: center; padding: 2rem; color: #bbb; }
</style>
@endpush

@section('content')
<div class="pos-wrapper">
    <!-- KIRI: Menu Grid -->
    <div class="pos-menu">
        <!-- Search -->
        <div class="mb-3">
            <input type="text" id="searchMenu" class="form-control" placeholder="🔍 Cari nama menu..." oninput="filterMenu(this.value)">
        </div>

        <!-- Kategori Filter -->
        <div class="kategori-tab mb-3">
            <button class="btn btn-coffee btn-sm aktif-kat" onclick="filterKategori(0, this)">
                Semua
            </button>
            @foreach($kategoris as $kat)
            <button class="btn btn-latte btn-sm" onclick="filterKategori({{ $kat->id }}, this)">
                {{ $kat->nama }}
            </button>
            @endforeach
        </div>

        <!-- Menu Grid -->
        <div class="menu-grid" id="menuGrid">
            @foreach($menus as $menu)
            <div class="menu-card {{ !$menu->tersedia ? 'tidak-tersedia' : '' }}"
                 data-kategori="{{ $menu->kategori_menu_id }}"
                 data-nama="{{ strtolower($menu->nama) }}"
                 data-id="{{ $menu->id }}"
                 data-nama-display="{{ $menu->nama }}"
                 data-harga="{{ $menu->harga_dine_in }}"
                 data-varian="{{ $menu->varian }}"
                 onclick="{{ $menu->tersedia ? 'tambahKeKeranjang(this)' : '' }}">

                <div class="menu-card-img">
                    @if($menu->foto)
                        <img src="{{ asset($menu->foto) }}" style="width:100%;height:80px;object-fit:cover;border-radius:6px;">
                    @else
                        <i class="fa fa-mug-hot" style="font-size:2rem;color:var(--coffee);"></i>
                    @endif
                </div>
                <div class="menu-card-nama">{{ $menu->nama }}</div>
                @if($menu->varian)
                <div class="menu-card-varian">{{ $menu->varian }}</div>
                @endif
                <div class="menu-card-harga">Rp {{ number_format($menu->harga_dine_in, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- KANAN: Keranjang -->
    <div class="pos-cart">
        <div class="cart-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-cart3 me-2"></i>Pesanan</span>
            <button onclick="kosongkanKeranjang()" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:#fff;font-size:0.75rem;">
                <i class="fa fa-trash me-1"></i> Kosongkan
            </button>
        </div>

        <!-- Tipe Pesanan -->
        <div style="background:#f8f4f0;padding:0.6rem 0.75rem;border-bottom:1px solid var(--latte);">
            <div class="d-flex gap-1">
                <button class="btn btn-sm btn-coffee flex-fill tipe-btn active" onclick="pilihTipe('dine_in', this)">
                    <i class="bi bi-cup-hot me-1"></i> Dine-in
                </button>
                <button class="btn btn-sm btn-latte flex-fill tipe-btn" onclick="pilihTipe('takeaway', this)">
                    <i class="bi bi-bag me-1"></i> Takeaway
                </button>
            </div>
            <input type="text" id="nomorMeja" class="form-control form-control-sm mt-2" placeholder="Nomor Meja (Dine-in)">
        </div>

        <!-- Cart Items -->
        <div class="cart-body" id="cartBody">
            <div class="empty-cart" id="emptyCart">
                <i class="bi bi-cart-x" style="font-size:2.5rem;"></i>
                <div class="mt-2 small">Keranjang kosong</div>
                <div class="small">Klik menu untuk menambahkan</div>
            </div>
        </div>

        <!-- Footer / Total -->
        <div class="cart-footer">
            <div class="cart-total-row">
                <span>Subtotal</span>
                <span id="subtotalDisplay">Rp 0</span>
            </div>
            <div class="cart-total-row" id="rowPromo" style="display:none!important;">
                <span>Diskon Promo</span>
                <span id="diskonDisplay" class="text-danger">-Rp 0</span>
            </div>
            <div class="cart-total-row" id="rowOngkir" style="display:none!important;">
                <span>Ongkos Kirim</span>
                <span>Rp 5.000</span>
            </div>
            <hr class="my-1">
            <div class="cart-total-row total">
                <span>TOTAL</span>
                <span id="totalDisplay">Rp 0</span>
            </div>

            <!-- Kode Promo -->
            <div class="input-group input-group-sm mt-2">
                <input type="text" id="inputPromo" class="form-control" placeholder="Kode promo...">
                <button class="btn btn-latte" onclick="cekPromo()">Pakai</button>
            </div>
            <small id="promoMsg" class="text-danger" style="display:none;"></small>

            <!-- Tombol Bayar -->
            <button class="btn-bayar mt-2" id="btnBayar" onclick="bukaPembayaran()" disabled>
                <i class="fa fa-cash-register me-2"></i> Proses Pembayaran
            </button>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="modalBayar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--coffee);color:#fff;">
                <h5 class="modal-title"><i class="fa fa-cash-register me-2"></i>Proses Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggan (Opsional)</label>
                    <input type="text" id="namaPelanggan" class="form-control" placeholder="Kosongkan jika tidak perlu">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-600">Metode Pembayaran *</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-latte flex-fill pm-btn active" id="pmTunai" onclick="pilihPM('tunai')">
                            <i class="fa fa-money-bill me-1"></i> Tunai
                        </button>
                        <button class="btn btn-latte flex-fill pm-btn" id="pmQris" onclick="pilihPM('qris')">
                            <i class="fa fa-qrcode me-1"></i> QRIS
                        </button>
                        <button class="btn btn-latte flex-fill pm-btn" id="pmDebit" onclick="pilihPM('debit')">
                            <i class="fa fa-credit-card me-1"></i> Debit
                        </button>
                    </div>
                </div>
                <div id="sectionTunai">
                    <div class="mb-3">
                        <label class="form-label">Uang Diterima *</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="uangBayar" class="form-control" placeholder="0" oninput="hitungKembalian()">
                        </div>
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            @foreach([5000, 10000, 20000, 50000, 100000] as $nominal)
                            <button class="btn btn-sm btn-latte" onclick="isiUangBayar({{ $nominal }})">
                                +{{ number_format($nominal/1000, 0) }}rb
                            </button>
                            @endforeach
                            <button class="btn btn-sm btn-coffee" onclick="isiUangBayar(totalFinal)">Pas</button>
                        </div>
                    </div>
                    <div class="p-3 rounded" style="background:var(--latte);">
                        <div class="d-flex justify-content-between fw-600">
                            <span>Kembalian</span>
                            <span id="kembalianDisplay" style="color:var(--coffee);font-size:1.2rem;">Rp 0</span>
                        </div>
                    </div>
                </div>
                <div id="sectionQris" style="display:none;">
                    <div class="text-center p-3" style="background:var(--latte);border-radius:10px;">
                        @if(file_exists(public_path('images/qris.png')) || file_exists(public_path('images/qris.jpg')))
                            <img src="{{ asset(file_exists(public_path('images/qris.png')) ? 'images/qris.png' : 'images/qris.jpg') }}"
                                alt="QRIS Boss Kopi" style="max-width:220px;border-radius:8px;">
                            <div class="mt-2 small text-muted fw-600">Scan QR ini untuk bayar via semua bank & e-wallet</div>
                        @else
                            <i class="fa fa-qrcode" style="font-size:5rem;color:var(--coffee);"></i>
                            <div class="mt-2 small text-danger">Upload file QR code QRIS Anda ke folder <code>public/images/qris.png</code></div>
                        @endif
                    </div>
                </div>
                <div id="sectionDebit" style="display:none;">
                    <div class="text-center p-3" style="background:var(--latte);border-radius:10px;">
                        <i class="fa fa-credit-card" style="font-size:3rem;color:var(--coffee);"></i>
                        <div class="mt-2 fw-600">Gesek/Tap Kartu di Mesin EDC</div>
                    </div>
                </div>
                <div class="mt-3 p-3 rounded text-center" style="background:#f8f4f0;">
                    <div class="small text-muted">Total Pembayaran</div>
                    <div class="fw-700" id="totalBayarModal" style="font-size:1.5rem;color:var(--coffee);">Rp 0</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-latte" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-coffee" id="btnKonfirmasiBayar" onclick="prosesCheckout()">
                    <i class="fa fa-check me-2"></i> Konfirmasi Bayar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let keranjang = [];
let tipePesanan = 'dine_in';
let metodePembayaran = 'tunai';
let promoData = null;
let totalFinal = 0;

// Filter Kategori
function filterKategori(id, btn) {
    document.querySelectorAll('.aktif-kat').forEach(b => {
        b.classList.remove('btn-coffee');
        b.classList.add('btn-latte');
        b.classList.remove('aktif-kat');
    });
    btn.classList.add('btn-coffee', 'aktif-kat');
    btn.classList.remove('btn-latte');

    document.querySelectorAll('.menu-card').forEach(card => {
        card.style.display = (id === 0 || parseInt(card.dataset.kategori) === id) ? '' : 'none';
    });
}

// Cari Menu
function filterMenu(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.menu-card').forEach(card => {
        card.style.display = card.dataset.nama.includes(q) ? '' : 'none';
    });
}

// Tambah ke Keranjang
function tambahKeKeranjang(card) {
    const id = card.dataset.id;
    const nama = card.dataset.namaDisplay;
    const harga = parseInt(card.dataset.harga);
    const varian = card.dataset.varian;

    const existing = keranjang.find(i => i.id === id);
    if (existing) {
        existing.jumlah++;
    } else {
        keranjang.push({ id, nama, harga, jumlah: 1, catatan: '', varian });
    }
    updateKeranjang();
}

// Update tampilan keranjang
function updateKeranjang() {
    const body = document.getElementById('cartBody');
    const emptyCart = document.getElementById('emptyCart');

    if (keranjang.length === 0) {
        body.innerHTML = `<div class="empty-cart" id="emptyCart">
            <i class="bi bi-cart-x" style="font-size:2.5rem;"></i>
            <div class="mt-2 small">Keranjang kosong</div>
        </div>`;
        document.getElementById('btnBayar').disabled = true;
        hitungTotal();
        return;
    }

    let html = '';
    keranjang.forEach((item, idx) => {
        html += `<div class="cart-item">
            <div class="flex-grow-1">
                <div class="cart-item-nama">${item.nama}</div>
                ${item.varian ? `<div style="font-size:0.7rem;color:#999;">${item.varian}</div>` : ''}
                <input type="text" class="form-control form-control-sm mt-1" placeholder="Catatan..." 
                    value="${item.catatan}" oninput="setCatatan(${idx}, this.value)"
                    style="font-size:0.72rem;padding:2px 6px;height:24px;">
            </div>
            <div class="qty-control">
                <button class="qty-btn" onclick="ubahQty(${idx}, -1)">−</button>
                <span class="qty-num">${item.jumlah}</span>
                <button class="qty-btn" onclick="ubahQty(${idx}, 1)">+</button>
            </div>
            <div class="cart-item-harga">Rp ${formatRp(item.harga * item.jumlah)}</div>
        </div>`;
    });
    body.innerHTML = html;
    document.getElementById('btnBayar').disabled = false;
    hitungTotal();
}

function ubahQty(idx, delta) {
    keranjang[idx].jumlah += delta;
    if (keranjang[idx].jumlah <= 0) keranjang.splice(idx, 1);
    updateKeranjang();
}

function setCatatan(idx, val) { keranjang[idx].catatan = val; }

function kosongkanKeranjang() {
    if (keranjang.length === 0) return;
    if (!confirm('Kosongkan semua item dari keranjang?')) return;
    keranjang = [];
    promoData = null;
    document.getElementById('inputPromo').value = '';
    document.getElementById('promoMsg').style.display = 'none';
    updateKeranjang();
}

function pilihTipe(tipe, btn) {
    tipePesanan = tipe;
    document.querySelectorAll('.tipe-btn').forEach(b => {
        b.classList.remove('btn-coffee'); b.classList.add('btn-latte');
    });
    btn.classList.add('btn-coffee'); btn.classList.remove('btn-latte');
    document.getElementById('nomorMeja').style.display = tipe === 'dine_in' ? '' : 'none';
    // Update harga sesuai tipe
    keranjang.forEach(item => {
        const card = document.querySelector(`.menu-card[data-id="${item.id}"]`);
        if (card) {
            // harga sudah di-set saat tambah, idealnya dari data-harga-tipe
        }
    });
    hitungTotal();
}

function hitungTotal() {
    const subtotal = keranjang.reduce((s, i) => s + i.harga * i.jumlah, 0);
    const diskon = promoData ? Math.min(promoData.nilai, subtotal) : 0;
    const ongkir = 0;
    totalFinal = subtotal - diskon + ongkir;

    document.getElementById('subtotalDisplay').textContent = 'Rp ' + formatRp(subtotal);
    document.getElementById('totalDisplay').textContent = 'Rp ' + formatRp(totalFinal);
    document.getElementById('totalBayarModal').textContent = 'Rp ' + formatRp(totalFinal);
    document.getElementById('rowPromo').style.display = promoData ? 'flex' : 'none';
    document.getElementById('diskonDisplay').textContent = '-Rp ' + formatRp(diskon);
    document.getElementById('rowOngkir').style.display = ongkir > 0 ? 'flex' : 'none';
}

async function cekPromo() {
    const kode = document.getElementById('inputPromo').value.trim();
    if (!kode) return;
    const res = await fetch('/menu-online/cek-promo', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ kode })
    });
    const data = await res.json();
    const msg = document.getElementById('promoMsg');
    if (data.valid) {
        promoData = data;
        msg.textContent = '✓ Promo ' + data.nama + ' berhasil diterapkan!';
        msg.className = 'text-success';
    } else {
        promoData = null;
        msg.textContent = data.pesan;
        msg.className = 'text-danger';
    }
    msg.style.display = 'block';
    hitungTotal();
}

function pilihPM(pm) {
    metodePembayaran = pm;
    document.querySelectorAll('.pm-btn').forEach(b => {
        b.classList.remove('btn-coffee'); b.classList.add('btn-latte');
    });
    document.getElementById('pm' + pm.charAt(0).toUpperCase() + pm.slice(1)).classList.add('btn-coffee');
    document.getElementById('sectionTunai').style.display = pm === 'tunai' ? '' : 'none';
    document.getElementById('sectionQris').style.display = pm === 'qris' ? '' : 'none';
    document.getElementById('sectionDebit').style.display = pm === 'debit' ? '' : 'none';
}

function bukaPembayaran() {
    document.getElementById('totalBayarModal').textContent = 'Rp ' + formatRp(totalFinal);
    document.getElementById('uangBayar').value = '';
    document.getElementById('kembalianDisplay').textContent = 'Rp 0';
    new bootstrap.Modal(document.getElementById('modalBayar')).show();
}

function isiUangBayar(nominal) {
    const el = document.getElementById('uangBayar');
    el.value = parseInt(el.value || 0) + nominal;
    hitungKembalian();
}

function hitungKembalian() {
    const bayar = parseInt(document.getElementById('uangBayar').value) || 0;
    const kembalian = bayar - totalFinal;
    document.getElementById('kembalianDisplay').textContent = 'Rp ' + formatRp(Math.max(0, kembalian));
    document.getElementById('kembalianDisplay').style.color = kembalian < 0 ? '#8B2020' : 'var(--coffee)';
}

async function prosesCheckout() {
    const uangBayar = parseInt(document.getElementById('uangBayar').value) || 0;
    if (metodePembayaran === 'tunai' && uangBayar < totalFinal) {
        alert('Uang bayar kurang dari total!'); return;
    }

    document.getElementById('btnKonfirmasiBayar').disabled = true;
    document.getElementById('btnKonfirmasiBayar').innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Memproses...';

    const payload = {
        items: keranjang.map(i => ({ menu_id: i.id, jumlah: i.jumlah, catatan: i.catatan, varian: i.varian })),
        tipe_pesanan: tipePesanan,
        metode_pembayaran: metodePembayaran,
        uang_bayar: uangBayar,
        nama_pelanggan: document.getElementById('namaPelanggan').value,
        nomor_meja: document.getElementById('nomorMeja').value,
        kode_promo: document.getElementById('inputPromo').value,
        _token: '{{ csrf_token() }}'
    };

    try {
        const res = await fetch('{{ route("kasir.checkout") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalBayar')).hide();
            keranjang = [];
            promoData = null;
            updateKeranjang();
            window.open('/kasir/struk/' + data.order_id, '_blank', 'width=400,height=600');
        }
    } catch(e) {
        alert('Terjadi kesalahan. Silakan coba lagi.');
    }
    document.getElementById('btnKonfirmasiBayar').disabled = false;
    document.getElementById('btnKonfirmasiBayar').innerHTML = '<i class="fa fa-check me-2"></i> Konfirmasi Bayar';
}

function formatRp(n) {
    return new Intl.NumberFormat('id-ID').format(n);
}

// Init
pilihTipe('dine_in', document.querySelector('.tipe-btn'));
document.getElementById('nomorMeja').style.display = '';
</script>
@endpush
