<?php $__env->startSection('title', 'Riwayat Pesanan — BOSS KOPI'); ?>
<?php $__env->startSection('page-title', 'Riwayat Pesanan & Poin'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <!-- Poin Loyalitas -->
    <div class="col-12">
        <div class="p-4 rounded" style="background:linear-gradient(135deg,var(--coffee),var(--charcoal));color:#fff;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="small opacity-75">Total Poin Loyalitas</div>
                    <div style="font-size:2.5rem;font-weight:700;color:var(--latte);">
                        ⭐ <?php echo e(number_format($user->poin_loyalitas)); ?>

                    </div>
                    <div class="small opacity-75">1 poin = Rp 1.000 pengeluaran</div>
                </div>
                <div class="text-end">
                    <div class="small opacity-75 mb-1">Riwayat Poin Terbaru</div>
                    <?php $__currentLoopData = $poinHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="small">
                        <span class="<?php echo e($ph->tipe === 'tambah' ? 'text-success' : 'text-danger'); ?>">
                            <?php echo e($ph->tipe === 'tambah' ? '+' : '-'); ?><?php echo e($ph->jumlah_poin); ?> poin
                        </span>
                        <span class="opacity-50">— <?php echo e($ph->keterangan); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Order -->
    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="bi bi-bag-check me-2"></i>Riwayat Pesanan</div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <code class="small"><?php echo e($order->nomor_struk); ?></code>
                            <div class="small text-muted"><?php echo e($order->created_at->format('d M Y, H:i')); ?></div>
                            <div class="small mt-1">
                                <?php $__currentLoopData = $order->items->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span><?php echo e($item->jumlah); ?>x <?php echo e($item->nama_menu); ?></span><?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($order->items->count() > 2): ?>
                                    <span class="text-muted">+<?php echo e($order->items->count() - 2); ?> lagi</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-700" style="color:var(--coffee);">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></div>
                            <span class="badge <?php echo e($order->status === 'selesai' ? 'bg-success' : ($order->status === 'dibatalkan' ? 'bg-danger' : 'bg-warning text-dark')); ?>">
                                <?php echo e($order->getLabelStatus()); ?>

                            </span>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <a href="<?php echo e(route('pembeli.tracking', $order)); ?>" class="btn btn-sm btn-latte">
                            <i class="bi bi-eye me-1"></i> Detail
                        </a>
                        <?php if($order->status === 'selesai'): ?>
                        <form action="<?php echo e(route('pembeli.reorder', $order)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-coffee">
                                <i class="bi bi-arrow-repeat me-1"></i> Pesan Lagi
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bag-x" style="font-size:3rem;"></i>
                    <div class="mt-2">Belum ada riwayat pesanan</div>
                    <a href="<?php echo e(route('pembeli.menu')); ?>" class="btn btn-coffee mt-3">Pesan Sekarang</a>
                </div>
                <?php endif; ?>
            </div>
            <?php if($orders->hasPages()): ?>
            <div class="card-footer bg-white"><?php echo e($orders->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\boss-kopi\resources\views/pembeli/riwayat.blade.php ENDPATH**/ ?>