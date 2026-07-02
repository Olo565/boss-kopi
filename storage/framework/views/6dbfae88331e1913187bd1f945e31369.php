<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="fa fa-circle-check me-2"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="fa fa-circle-xmark me-2"></i> <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('warning')): ?>
    <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
        <i class="fa fa-triangle-exclamation me-2"></i> <?php echo e(session('warning')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('info')): ?>
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <i class="fa fa-circle-info me-2"></i> <?php echo e(session('info')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="fa fa-circle-exclamation me-2"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-1">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\boss-kopi\resources\views/components/alerts.blade.php ENDPATH**/ ?>