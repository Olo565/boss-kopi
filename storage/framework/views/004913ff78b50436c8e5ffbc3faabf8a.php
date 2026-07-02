<?php $__env->startSection('title', 'Pesanan Online — BOSS KOPI'); ?>
<?php $__env->startSection('page-title', 'Pesanan Online dari Pembeli'); ?>
<?php $__env->startSection('page-subtitle', 'Proses pesanan sebelum bisa diambil driver'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="border-left-color:#D4A017;">
            <div class="stat-icon mb-2" style="background:#fef3cc;"><i class="fa fa-clock" style="color:#D4A017;"></i></div>
            <div class="stat-value" style="color:#D4A017;"><?php echo e($jumlahPending); ?></div>
            <div class="stat-label">Menunggu Konfirmasi</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left-color:#0c5460;">
            <div class="stat-icon mb-2" style="background:#daedfb;"><i class="fa fa-mug-hot" style="color:#0c5460;"></i></div>
            <div class="stat-value" style="color:#0c5460;"><?php echo e($jumlahDiproses); ?></div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left-color:#2D6A4F;">
            <div class="stat-icon mb-2" style="background:#d1f0e0;"><i class="bi bi-bicycle" style="color:#2D6A4F;"></i></div>
            <div class="stat-value" style="color:#2D6A4F;"><?php echo e($jumlahSiap); ?></div>
            <div class="stat-label">Siap / Menunggu Driver</div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mb-3">
    <a href="?" class="btn btn-sm <?php echo e(!request('status') ? 'btn-coffee' : 'btn-latte'); ?>">Semua</a>
    <a href="?status=pending" class="btn btn-sm <?php echo e(request('status') === 'pending' ? 'btn-coffee' : 'btn-latte'); ?>">Menunggu</a>
    <a href="?status=dikonfirmasi" class="btn btn-sm <?php echo e(request('status') === 'dikonfirmasi' ? 'btn-coffee' : 'btn-latte'); ?>">Dikonfirmasi</a>
    <a href="?status=diproses" class="btn btn-sm <?php echo e(request('status') === 'diproses' ? 'btn-coffee' : 'btn-latte'); ?>">Diproses</a>
    <a href="?status=siap" class="btn btn-sm <?php echo e(request('status') === 'siap' ? 'btn-coffee' : 'btn-latte'); ?>">Siap</a>
    <a href="?status=selesai" class="btn btn-sm <?php echo e(request('status') === 'selesai' ? 'btn-coffee' : 'btn-latte'); ?>">Selesai</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>No. Struk</th>
                        <th>Pelanggan</th>
                        <th>Tipe</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Driver</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="small"><?php echo e($order->created_at->format('d/m H:i')); ?></td>
                        <td><code class="small"><?php echo e($order->nomor_struk); ?></code></td>
                        <td class="small"><?php echo e($order->nama_pelanggan ?? $order->user->name ?? '-'); ?></td>
                        <td>
                            <?php $tipeLabel = ['dine_in' => 'Dine-in', 'takeaway' => 'Takeaway', 'delivery' => 'Delivery']; ?>
                            <span class="badge badge-latte small"><?php echo e($tipeLabel[$order->tipe_pesanan] ?? $order->tipe_pesanan); ?></span>
                        </td>
                        <td class="small"><?php echo e($order->items->count()); ?> item</td>
                        <td class="fw-600 small" style="color:var(--coffee);">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></td>
                        <td>
                            <?php
                                $statusColor = [
                                    'pending' => 'bg-warning text-dark', 'dikonfirmasi' => 'bg-info text-dark',
                                    'diproses' => 'bg-primary', 'siap' => 'bg-success',
                                    'diantar' => 'bg-success', 'selesai' => 'bg-secondary', 'dibatalkan' => 'bg-danger',
                                ];
                            ?>
                            <span class="badge <?php echo e($statusColor[$order->status] ?? 'bg-secondary'); ?>"><?php echo e($order->getLabelStatus()); ?></span>
                        </td>
                        <td class="small"><?php echo e($order->driver->name ?? '-'); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.order.show', $order)); ?>" class="btn btn-sm btn-latte">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada pesanan online</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($orders->hasPages()): ?>
    <div class="card-footer bg-white"><?php echo e($orders->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\boss-kopi\resources\views/admin/order/index.blade.php ENDPATH**/ ?>