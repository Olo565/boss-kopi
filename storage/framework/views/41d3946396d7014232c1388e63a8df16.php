<?php $__env->startSection('title', 'Kinerja Kasir — BOSS KOPI'); ?>
<?php $__env->startSection('page-title', 'Laporan Kinerja Kasir'); ?>
<?php $__env->startSection('page-subtitle', 'Rekap shift dan validasi kas'); ?>

<?php $__env->startSection('content'); ?>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="<?php echo e($tanggalMulai); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="<?php echo e($tanggalSelesai); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-coffee w-100">
                    <i class="fa fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-person-check me-2"></i>Rekap Shift Kasir</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 small">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Buka Shift</th>
                        <th>Tutup Shift</th>
                        <th>Modal Awal</th>
                        <th>Total Tunai</th>
                        <th>Total QRIS</th>
                        <th>Total Debit</th>
                        <th>Kas Akhir</th>
                        <th>Selisih</th>
                        <th>Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $totalPendapatan = $shift->getTotalPendapatan();
                        $kasSeharusnya = $shift->modal_awal + $shift->total_tunai;
                        $selisih = ($shift->uang_kas_akhir ?? 0) - $kasSeharusnya;
                    ?>
                    <tr>
                        <td><?php echo e($shift->waktu_buka->format('d/m/Y')); ?></td>
                        <td class="fw-600"><?php echo e($shift->kasir->name ?? '-'); ?></td>
                        <td><?php echo e($shift->waktu_buka->format('H:i')); ?></td>
                        <td><?php echo e($shift->waktu_tutup ? $shift->waktu_tutup->format('H:i') : '-'); ?></td>
                        <td>Rp <?php echo e(number_format($shift->modal_awal, 0, ',', '.')); ?></td>
                        <td>Rp <?php echo e(number_format($shift->total_tunai, 0, ',', '.')); ?></td>
                        <td>Rp <?php echo e(number_format($shift->total_qris, 0, ',', '.')); ?></td>
                        <td>Rp <?php echo e(number_format($shift->total_debit, 0, ',', '.')); ?></td>
                        <td>Rp <?php echo e(number_format($shift->uang_kas_akhir ?? 0, 0, ',', '.')); ?></td>
                        <td class="<?php echo e($selisih < 0 ? 'text-danger fw-600' : ($selisih > 0 ? 'text-warning fw-600' : 'text-success fw-600')); ?>">
                            <?php echo e($selisih >= 0 ? '+' : ''); ?>Rp <?php echo e(number_format($selisih, 0, ',', '.')); ?>

                        </td>
                        <td><?php echo e($shift->orders->count()); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="11" class="text-center py-4 text-muted">Tidak ada data shift pada periode ini</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\boss-kopi\resources\views/admin/laporan/kasir.blade.php ENDPATH**/ ?>