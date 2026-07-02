<?php $__env->startSection('title', 'Menu — BOSS KOPI'); ?>
<?php $__env->startSection('page-title', 'Daftar Menu'); ?>
<?php $__env->startSection('page-subtitle', 'Pilih menu favorit Anda'); ?>

<?php $__env->startSection('content'); ?>
<!-- Filter -->
<div class="d-flex flex-wrap gap-2 mb-3">
    <form class="d-flex gap-2 w-100" method="GET">
        <input type="text" name="search" class="form-control" placeholder="🔍 Cari menu..." value="<?php echo e(request('search')); ?>">
        <select name="kategori" class="form-select" style="max-width:180px;">
            <option value="">Semua Kategori</option>
            <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($kat->id); ?>" <?php echo e(request('kategori') == $kat->id ? 'selected' : ''); ?>><?php echo e($kat->nama); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button type="submit" class="btn btn-coffee"><i class="fa fa-search"></i></button>
    </form>
</div>

<div class="row g-3">
    <?php $__empty_1 = true; $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-6 col-md-3">
        <div class="card h-100">
            <div style="height:130px;background:var(--latte);border-radius:12px 12px 0 0;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                <?php if($menu->foto): ?>
                    <img src="<?php echo e(asset($menu->foto)); ?>" style="width:100%;height:130px;object-fit:cover;">
                <?php else: ?>
                    <i class="fa fa-mug-hot" style="font-size:2.5rem;color:var(--coffee);"></i>
                <?php endif; ?>
            </div>
            <div class="card-body p-2">
                <div class="fw-600 small" style="color:var(--charcoal);"><?php echo e($menu->nama); ?></div>
                <?php if($menu->varian): ?><div style="font-size:0.7rem;color:#999;"><?php echo e($menu->varian); ?></div><?php endif; ?>
                <div class="fw-700 mt-1" style="color:var(--coffee);font-size:0.85rem;">
                    Rp <?php echo e(number_format($menu->harga_dine_in, 0, ',', '.')); ?>

                </div>
                <span class="badge badge-latte" style="font-size:0.65rem;"><?php echo e($menu->kategori->nama); ?></span>
            </div>
            <div class="card-footer p-2 bg-white border-top-0">
                <button onclick="tambahKeranjang(<?php echo e($menu->id); ?>, '<?php echo e(addslashes($menu->nama)); ?>', <?php echo e($menu->harga_dine_in); ?>)"
                    class="btn btn-coffee btn-sm w-100" <?php echo e(!$menu->tersedia ? 'disabled' : ''); ?>>
                    <?php if($menu->tersedia): ?>
                        <i class="bi bi-cart-plus me-1"></i> Tambah
                    <?php else: ?>
                        Tidak Tersedia
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12 text-center py-5 text-muted">
        <i class="fa fa-bowl-food fs-1 d-block mb-3"></i>
        Menu tidak ditemukan
    </div>
    <?php endif; ?>
</div>

<!-- Floating Cart Button -->
<div style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:999;">
    <a href="<?php echo e(route('pembeli.keranjang')); ?>" class="btn btn-coffee rounded-pill px-4 py-3"
        style="box-shadow:0 4px 20px rgba(74,53,37,0.4);">
        <i class="bi bi-bag me-2"></i>
        Keranjang
        <span id="cartBadge" class="badge bg-danger ms-1" style="display:none;">0</span>
    </a>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let cart = JSON.parse(localStorage.getItem('bosskopi_cart') || '[]');
updateBadge();

function tambahKeranjang(id, nama, harga) {
    const existing = cart.find(i => i.id == id);
    if (existing) existing.jumlah++;
    else cart.push({ id, nama, harga, jumlah: 1, catatan: '' });
    localStorage.setItem('bosskopi_cart', JSON.stringify(cart));
    updateBadge();

    // Toast notifikasi
    const toast = document.createElement('div');
    toast.innerHTML = `<div style="position:fixed;bottom:5rem;right:1.5rem;background:var(--coffee);color:#fff;padding:0.5rem 1rem;border-radius:8px;z-index:9999;font-size:0.85rem;">
        ✓ ${nama} ditambahkan</div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}

function updateBadge() {
    const total = cart.reduce((s, i) => s + i.jumlah, 0);
    const badge = document.getElementById('cartBadge');
    if (total > 0) { badge.textContent = total; badge.style.display = ''; }
    else badge.style.display = 'none';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\boss-kopi\resources\views/pembeli/menu.blade.php ENDPATH**/ ?>