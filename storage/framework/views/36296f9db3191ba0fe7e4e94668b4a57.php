<?php $__env->startSection('title', 'Keranjang — BOSS KOPI'); ?>
<?php $__env->startSection('page-title', 'Keranjang Belanja'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-bag me-2"></i>Item Pesanan</span>
                <button onclick="kosongkanKeranjang()" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-trash me-1"></i> Kosongkan
                </button>
            </div>
            <div class="card-body" id="cartContent">
                <div class="text-center py-5 text-muted" id="emptyState">
                    <i class="bi bi-bag-x" style="font-size:3rem;"></i>
                    <div class="mt-2">Keranjang kosong</div>
                    <a href="<?php echo e(route('pembeli.menu')); ?>" class="btn btn-coffee mt-3">Lihat Menu</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" id="summaryCard" style="display:none;">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Ringkasan Pesanan</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-600">Tipe Pesanan</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-coffee btn-sm flex-fill tipe-btn" onclick="pilihTipe('takeaway', this)">
                            <i class="bi bi-bag me-1"></i> Takeaway
                        </button>
                        <button class="btn btn-latte btn-sm flex-fill tipe-btn" onclick="pilihTipe('delivery', this)">
                            <i class="bi bi-bicycle me-1"></i> Delivery
                        </button>
                    </div>
                </div>
                <div id="alamatSection" style="display:none;" class="mb-3">
                    <label class="form-label">Alamat Pengiriman</label>
                    <textarea id="alamatDelivery" class="form-control" rows="2"
                        placeholder="Masukkan alamat lengkap..."></textarea>
                    <button type="button" class="btn btn-sm btn-latte mt-2 w-100" onclick="ambilLokasiSaya()">
                        <i class="bi bi-geo-alt-fill me-1"></i> Gunakan Lokasi Saya Saat Ini (GPS)
                    </button>
                    <small id="lokasiStatus" class="text-muted d-block mt-1"></small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode Promo</label>
                    <div class="input-group input-group-sm">
                        <input type="text" id="inputPromo" class="form-control" placeholder="Masukkan kode...">
                        <button class="btn btn-latte" onclick="cekPromo()">Pakai</button>
                    </div>
                    <small id="promoMsg" class="text-danger mt-1" style="display:none;"></small>
                </div>

                <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->poin_loyalitas >= 100): ?>
                <div class="mb-3 p-3 rounded" style="background:var(--latte);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0 fw-600">
                            <i class="fa fa-coins me-1" style="color:#D4A017;"></i> Gunakan Poin
                        </label>
                        <span class="badge badge-coffee"><?php echo e(auth()->user()->poin_loyalitas); ?> poin tersedia</span>
                    </div>
                    <small class="text-muted d-block mb-2">100 poin = Rp 2.000 diskon &middot; Min. 100 poin</small>
                    <div class="input-group input-group-sm">
                        <input type="number" id="inputPoin" class="form-control"
                            placeholder="Masukkan jumlah poin (min. 100)"
                            min="100" max="<?php echo e(auth()->user()->poin_loyalitas); ?>" step="100"
                            oninput="hitungDiskonPoin()">
                        <button class="btn btn-latte" onclick="pakaiSemuaPoin()">Pakai Semua</button>
                    </div>
                    <small id="poinMsg" class="text-success mt-1" style="display:none;"></small>
                </div>
                <?php else: ?>
                <div class="mb-3 p-2 rounded" style="background:var(--latte);">
                    <small class="text-muted">
                        <i class="fa fa-coins me-1" style="color:#D4A017;"></i>
                        Poin Anda: <strong><?php echo e(auth()->user()->poin_loyalitas); ?></strong>
                        — Butuh 100 poin untuk dapat diskon Rp 2.000
                    </small>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                <hr>
                <div class="d-flex justify-content-between mb-1 small">
                    <span>Subtotal</span><span id="subtotalDisplay">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small" id="rowDiskon" style="display:none!important;">
                    <span>Diskon Promo</span><span id="diskonDisplay" class="text-danger">-Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small" id="rowDiskonPoin" style="display:none!important;">
                    <span>Diskon Poin</span><span id="diskonPoinDisplay" class="text-danger">-Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small" id="rowOngkir" style="display:none!important;">
                    <span>Ongkos Kirim</span><span>Rp 5.000</span>
                </div>
                <div class="d-flex justify-content-between fw-700 mt-2" style="color:var(--coffee);font-size:1.1rem;">
                    <span>TOTAL</span><span id="totalDisplay">Rp 0</span>
                </div>
                <div class="mb-3 mt-3">
                    <label class="form-label fw-600">Metode Pembayaran</label>
                    <select id="metodePembayaran" class="form-select form-select-sm">
                        <option value="tunai">Tunai (saat ambil/terima)</option>
                        <option value="qris">QRIS</option>
                        <option value="debit">Debit/Transfer</option>
                    </select>
                </div>
                <button onclick="prosesCheckout()" class="btn btn-coffee w-100 py-2" id="btnCheckout">
                    <i class="fa fa-shopping-cart me-2"></i> Pesan Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<form id="formCheckout" action="<?php echo e(route('pembeli.checkout')); ?>" method="POST" style="display:none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="tipe_pesanan" id="f_tipe">
    <input type="hidden" name="metode_pembayaran" id="f_pm">
    <input type="hidden" name="alamat_delivery" id="f_alamat">
    <input type="hidden" name="lat_tujuan" id="f_lat">
    <input type="hidden" name="lng_tujuan" id="f_lng">
    <input type="hidden" name="kode_promo" id="f_promo">
    <input type="hidden" name="gunakan_poin" id="f_poin" value="0">
    <div id="f_items"></div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let cart = JSON.parse(localStorage.getItem('bosskopi_cart') || '[]');
let tipePesanan = 'takeaway';
let promoData = null;
let lokasiLat = null;
let lokasiLng = null;
let poinDipakai = 0;
const MAX_POIN = <?php echo e(auth()->check() ? auth()->user()->poin_loyalitas : 0); ?>;

function hitungDiskonPoin() {
    const input = document.getElementById('inputPoin');
    if (!input) return;
    let poin = parseInt(input.value) || 0;
    // Bulatkan ke kelipatan 100
    poin = Math.floor(poin / 100) * 100;
    // Tidak boleh melebihi poin yang dimiliki
    if (poin > MAX_POIN) poin = Math.floor(MAX_POIN / 100) * 100;
    poinDipakai = poin;

    const msg = document.getElementById('poinMsg');
    const rowDiskonPoin = document.getElementById('rowDiskonPoin');
    if (poin >= 100) {
        const diskonRp = (poin / 100) * 2000;
        msg.textContent = `✓ ${poin} poin akan digunakan → diskon Rp ${diskonRp.toLocaleString('id-ID')}`;
        msg.style.display = '';
        rowDiskonPoin.style.display = 'flex';
        document.getElementById('diskonPoinDisplay').textContent = '-Rp ' + diskonRp.toLocaleString('id-ID');
    } else {
        msg.style.display = 'none';
        rowDiskonPoin.style.display = 'none';
        poinDipakai = 0;
    }
    updateTotal();
}

function pakaiSemuaPoin() {
    const input = document.getElementById('inputPoin');
    if (!input) return;
    const maxKelipatan = Math.floor(MAX_POIN / 100) * 100;
    input.value = maxKelipatan;
    hitungDiskonPoin();
}

function ambilLokasiSaya() {
    const status = document.getElementById('lokasiStatus');
    if (!navigator.geolocation) {
        status.textContent = 'Browser Anda tidak mendukung GPS. Isi alamat manual saja.';
        status.className = 'text-danger d-block mt-1';
        return;
    }
    status.textContent = 'Mencari lokasi Anda...';
    status.className = 'text-muted d-block mt-1';
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            lokasiLat = pos.coords.latitude;
            lokasiLng = pos.coords.longitude;
            status.textContent = '✓ Lokasi GPS berhasil didapat. Pastikan alamat di atas tetap diisi lengkap untuk driver.';
            status.className = 'text-success d-block mt-1';
        },
        (err) => {
            status.textContent = 'Gagal mengambil lokasi: izin GPS ditolak atau tidak tersedia.';
            status.className = 'text-danger d-block mt-1';
        }
    );
}

renderKeranjang();

function renderKeranjang() {
    const content = document.getElementById('cartContent');
    const summary = document.getElementById('summaryCard');
    const emptyState = document.getElementById('emptyState');

    if (cart.length === 0) {
        content.innerHTML = `<div class="text-center py-5 text-muted">
            <i class="bi bi-bag-x" style="font-size:3rem;"></i>
            <div class="mt-2">Keranjang kosong</div>
            <a href="<?php echo e(route('pembeli.menu')); ?>" class="btn btn-coffee mt-3">Lihat Menu</a>
        </div>`;
        summary.style.display = 'none';
        return;
    }

    summary.style.display = '';
    let html = '';
    cart.forEach((item, idx) => {
        html += `<div class="d-flex align-items-center gap-3 py-2 border-bottom">
            <div class="flex-grow-1">
                <div class="fw-600 small">${item.nama}</div>
                <input type="text" class="form-control form-control-sm mt-1" placeholder="Catatan..."
                    value="${item.catatan || ''}" oninput="cart[${idx}].catatan=this.value;save();"
                    style="font-size:0.75rem;height:28px;">
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-latte" onclick="ubahQty(${idx},-1)">−</button>
                <span class="fw-700">${item.jumlah}</span>
                <button class="btn btn-sm btn-latte" onclick="ubahQty(${idx},1)">+</button>
            </div>
            <div class="fw-600 small" style="min-width:80px;text-align:right;color:var(--coffee);">
                Rp ${formatRp(item.harga * item.jumlah)}
            </div>
        </div>`;
    });
    content.innerHTML = html;
    hitungTotal();
}

function ubahQty(idx, delta) {
    cart[idx].jumlah += delta;
    if (cart[idx].jumlah <= 0) cart.splice(idx, 1);
    save(); renderKeranjang();
}

function kosongkanKeranjang() {
    if (!confirm('Kosongkan keranjang?')) return;
    cart = []; save(); renderKeranjang();
}

function save() {
    localStorage.setItem('bosskopi_cart', JSON.stringify(cart));
}

function pilihTipe(tipe, btn) {
    tipePesanan = tipe;
    document.querySelectorAll('.tipe-btn').forEach(b => {
        b.classList.remove('btn-coffee'); b.classList.add('btn-latte');
    });
    btn.classList.add('btn-coffee'); btn.classList.remove('btn-latte');
    document.getElementById('alamatSection').style.display = tipe === 'delivery' ? '' : 'none';
    hitungTotal();
}

function hitungTotal() {
    const subtotal = cart.reduce((s, i) => s + i.harga * i.jumlah, 0);
    const diskon = promoData ? Math.min(promoData.nilai, subtotal) : 0;
    const diskonPoin = poinDipakai >= 100 ? (poinDipakai / 100) * 2000 : 0;
    const ongkir = tipePesanan === 'delivery' ? 5000 : 0;
    const total = Math.max(0, subtotal - diskon - diskonPoin + ongkir);

    document.getElementById('subtotalDisplay').textContent = 'Rp ' + formatRp(subtotal);
    document.getElementById('totalDisplay').textContent = 'Rp ' + formatRp(total);
    document.getElementById('rowDiskon').style.display = promoData ? 'flex' : 'none';
    document.getElementById('diskonDisplay').textContent = '-Rp ' + formatRp(diskon);
    document.getElementById('rowOngkir').style.display = ongkir > 0 ? 'flex' : 'none';
}

function updateTotal() { hitungTotal(); }

async function cekPromo() {
    const kode = document.getElementById('inputPromo').value.trim();
    if (!kode) return;
    const res = await fetch('<?php echo e(route("pembeli.cek-promo")); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
        body: JSON.stringify({ kode })
    });
    const data = await res.json();
    const msg = document.getElementById('promoMsg');
    if (data.valid) {
        promoData = data;
        msg.textContent = '✓ Promo diterapkan: ' + data.nama;
        msg.className = 'text-success mt-1';
    } else {
        promoData = null;
        msg.textContent = data.pesan;
        msg.className = 'text-danger mt-1';
    }
    msg.style.display = 'block';
    hitungTotal();
}

function prosesCheckout() {
    if (cart.length === 0) { alert('Keranjang kosong!'); return; }
    if (tipePesanan === 'delivery' && !document.getElementById('alamatDelivery').value.trim()) {
        alert('Alamat pengiriman wajib diisi!'); return;
    }
    if (tipePesanan === 'delivery' && (lokasiLat === null || lokasiLng === null)) {
        alert('Mohon klik tombol "Gunakan Lokasi Saya Saat Ini (GPS)" terlebih dahulu, supaya driver bisa menemukan lokasi Anda dengan akurat di peta.');
        return;
    }

    document.getElementById('f_tipe').value = tipePesanan;
    document.getElementById('f_pm').value = document.getElementById('metodePembayaran').value;
    document.getElementById('f_alamat').value = document.getElementById('alamatDelivery').value;
    document.getElementById('f_lat').value = lokasiLat ?? '';
    document.getElementById('f_lng').value = lokasiLng ?? '';
    document.getElementById('f_poin').value = poinDipakai;
    document.getElementById('f_promo').value = document.getElementById('inputPromo').value;

    const itemsDiv = document.getElementById('f_items');
    itemsDiv.innerHTML = '';
    cart.forEach((item, idx) => {
        itemsDiv.innerHTML += `
            <input type="hidden" name="items[${idx}][menu_id]" value="${item.id}">
            <input type="hidden" name="items[${idx}][jumlah]" value="${item.jumlah}">
            <input type="hidden" name="items[${idx}][catatan]" value="${item.catatan || ''}">
        `;
    });

    localStorage.removeItem('bosskopi_cart');
    document.getElementById('formCheckout').submit();
}

function formatRp(n) { return new Intl.NumberFormat('id-ID').format(n); }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\boss-kopi\resources\views/pembeli/keranjang.blade.php ENDPATH**/ ?>