<?php $__env->startSection('title', 'Laporan Penjualan — BOSS KOPI'); ?>
<?php $__env->startSection('page-title', 'Laporan Penjualan'); ?>
<?php $__env->startSection('page-subtitle', 'Periode: ' . \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y')); ?>

<?php $__env->startSection('content'); ?>
<!-- Filter -->
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
            <div class="col-md-4 d-flex gap-2 justify-content-end">
                <a href="<?php echo e(route('admin.laporan.penjualan.excel', request()->all())); ?>"
                    class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </a>
                <a href="<?php echo e(route('admin.laporan.penjualan.pdf', request()->all())); ?>"
                    class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2"><i class="fa fa-money-bill-wave"></i></div>
            <div class="stat-value">Rp <?php echo e(number_format($totalPenjualan, 0, ',', '.')); ?></div>
            <div class="stat-label">Total Penjualan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left-color:#2D6A4F;">
            <div class="stat-icon mb-2" style="background:#d1f0e0;"><i class="bi bi-receipt" style="color:#2D6A4F;"></i></div>
            <div class="stat-value" style="color:#2D6A4F;"><?php echo e($orders->count()); ?></div>
            <div class="stat-label">Total Transaksi</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left-color:#8B2020;">
            <div class="stat-icon mb-2" style="background:#fde8e8;"><i class="fa fa-tags" style="color:#8B2020;"></i></div>
            <div class="stat-value" style="color:#8B2020;">Rp <?php echo e(number_format($totalDiskon, 0, ',', '.')); ?></div>
            <div class="stat-label">Total Diskon</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left-color:#D4A017;">
            <div class="stat-icon mb-2" style="background:#fef3cc;"><i class="fa fa-chart-bar" style="color:#D4A017;"></i></div>
            <div class="stat-value" style="color:#D4A017;">
                Rp <?php echo e($orders->count() > 0 ? number_format($totalPenjualan / $orders->count(), 0, ',', '.') : 0); ?>

            </div>
            <div class="stat-label">Rata-rata per Transaksi</div>
        </div>
    </div>
</div>

<!-- Metode Pembayaran -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fa fa-money-bill me-2"></i>Per Metode Pembayaran</div>
            <div class="card-body">
                <?php $__currentLoopData = ['tunai' => 'Tunai', 'qris' => 'QRIS', 'debit' => 'Debit/EDC']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="small"><?php echo e($label); ?></span>
                    <span class="fw-600" style="color:var(--coffee);">
                        Rp <?php echo e(number_format($byMetode[$key] ?? 0, 0, ',', '.')); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Transaksi -->
<div class="card">
    <div class="card-header"><i class="bi bi-table me-2"></i>Detail Transaksi</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 small">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>No. Struk</th>
                        <th>Kasir</th>
                        <th>Tipe</th>
                        <th>Item</th>
                        <th>Subtotal</th>
                        <th>Diskon</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($order->created_at->format('d/m H:i')); ?></td>
                        <td><code><?php echo e($order->nomor_struk); ?></code></td>
                        <td><?php echo e($order->kasir->name ?? 'Online'); ?></td>
                        <td>
                            <?php $tipeLabel = ['dine_in' => 'Dine-in', 'takeaway' => 'Takeaway', 'delivery' => 'Delivery']; ?>
                            <span class="badge badge-latte"><?php echo e($tipeLabel[$order->tipe_pesanan] ?? $order->tipe_pesanan); ?></span>
                        </td>
                        <td><?php echo e($order->items->count()); ?> item</td>
                        <td>Rp <?php echo e(number_format($order->subtotal, 0, ',', '.')); ?></td>
                        <td class="text-danger">
                            <?php echo e($order->diskon > 0 ? '-Rp '.number_format($order->diskon, 0, ',', '.') : '-'); ?>

                        </td>
                        <td class="fw-600" style="color:var(--coffee);">
                            Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?>

                        </td>
                        <td>
                            <?php $pmLabel = ['tunai' => '<i class="fa fa-money-bill"></i> Tunai', 'qris' => '<i class="fa fa-qrcode"></i> QRIS', 'debit' => '<i class="fa fa-credit-card"></i> Debit']; ?>
                            <?php echo $pmLabel[$order->metode_pembayaran] ?? $order->metode_pembayaran; ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada transaksi pada periode ini</td></tr>
                    <?php endif; ?>
                </tbody>
                <?php if($orders->count() > 0): ?>
                <tfoot>
                    <tr style="background:var(--latte);">
                        <td colspan="5" class="fw-600 text-end">TOTAL</td>
                        <td class="fw-600">Rp <?php echo e(number_format($orders->sum('subtotal'), 0, ',', '.')); ?></td>
                        <td class="fw-600 text-danger">-Rp <?php echo e(number_format($totalDiskon, 0, ',', '.')); ?></td>
                        <td class="fw-700" style="color:var(--coffee);">Rp <?php echo e(number_format($totalPenjualan, 0, ',', '.')); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\boss-kopi\resources\views/admin/laporan/penjualan.blade.php ENDPATH**/ ?>