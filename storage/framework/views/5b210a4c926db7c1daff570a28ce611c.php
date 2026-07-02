<?php $__env->startSection('title', 'Laporan Stok — BOSS KOPI'); ?>
<?php $__env->startSection('page-title', 'Laporan Stok Bahan Baku'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-clipboard-data me-2"></i>Kondisi Stok Saat Ini</span>
        <small class="text-muted"><?php echo e(now()->format('d M Y, H:i')); ?></small>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Bahan Baku</th>
                    <th>Satuan</th>
                    <th>Stok Saat Ini</th>
                    <th>Stok Minimum</th>
                    <th>Harga/Satuan</th>
                    <th>Nilai Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $bahanBaku; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $bahan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="<?php echo e($bahan->isStokKritis() ? 'stok-kritis' : ''); ?>">
                    <td class="small text-muted"><?php echo e($i + 1); ?></td>
                    <td class="fw-500"><?php echo e($bahan->nama); ?></td>
                    <td class="small"><?php echo e($bahan->satuan); ?></td>
                    <td class="<?php echo e($bahan->isStokKritis() ? 'text-danger fw-600' : 'text-success fw-600'); ?>">
                        <?php echo e(number_format($bahan->stok_saat_ini, 1)); ?>

                    </td>
                    <td class="small"><?php echo e(number_format($bahan->stok_minimum, 1)); ?></td>
                    <td class="small">Rp <?php echo e(number_format($bahan->harga_per_satuan, 0, ',', '.')); ?></td>
                    <td class="small">Rp <?php echo e(number_format($bahan->stok_saat_ini * $bahan->harga_per_satuan, 0, ',', '.')); ?></td>
                    <td>
                        <?php if($bahan->isStokKritis()): ?>
                            <span class="badge bg-danger">Kritis</span>
                        <?php else: ?>
                            <span class="badge bg-success">Aman</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr style="background:var(--latte);">
                    <td colspan="6" class="fw-600 text-end">TOTAL NILAI STOK</td>
                    <td class="fw-700" style="color:var(--coffee);">
                        Rp <?php echo e(number_format($bahanBaku->sum(fn($b) => $b->stok_saat_ini * $b->harga_per_satuan), 0, ',', '.')); ?>

                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\boss-kopi\resources\views/admin/laporan/stok.blade.php ENDPATH**/ ?>