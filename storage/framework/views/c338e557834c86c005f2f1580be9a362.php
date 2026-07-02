<?php $__env->startSection('title', 'Pengaduan Saya — BOSS KOPI'); ?>
<?php $__env->startSection('page-title', 'Riwayat Pengaduan Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <a href="<?php echo e(route('pengaduan.create')); ?>" class="btn btn-coffee btn-sm">
        <i class="fa fa-plus me-1"></i> Buat Pengaduan Baru
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php $__empty_1 = true; $__currentLoopData = $pengaduans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a href="<?php echo e(route('pengaduan.show', $p)); ?>" class="text-decoration-none text-dark">
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-600 small"><?php echo e($p->judul); ?></span>
                    <?php
                        $statusColor = ['baru' => 'bg-warning text-dark', 'diproses' => 'bg-info text-dark', 'selesai' => 'bg-success'];
                    ?>
                    <span class="badge <?php echo e($statusColor[$p->status] ?? 'bg-secondary'); ?>"><?php echo e($p->getLabelStatus()); ?></span>
                </div>
                <div class="small text-muted mb-1"><?php echo e($p->kategori); ?> &middot; <?php echo e($p->created_at->format('d M Y, H:i')); ?></div>
                <div class="small text-muted"><?php echo e(Str::limit($p->isi, 80)); ?></div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-megaphone" style="font-size:3rem;"></i>
            <div class="mt-2">Belum ada pengaduan yang dikirim</div>
        </div>
        <?php endif; ?>
    </div>
    <?php if($pengaduans->hasPages()): ?>
    <div class="card-footer bg-white"><?php echo e($pengaduans->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\boss-kopi\resources\views/pengaduan/index.blade.php ENDPATH**/ ?>