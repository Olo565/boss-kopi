<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-latte d-md-none" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <h6 class="mb-0 fw-600" style="color:var(--gold);"><?php echo $__env->yieldContent('page-title', 'BOSS KOPI'); ?></h6>
            <small class="text-muted"><?php echo $__env->yieldContent('page-subtitle', ''); ?></small>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted small d-none d-md-inline">
            <i class="bi bi-calendar3 me-1"></i>
            <?php echo e(now()->translatedFormat('l, d F Y')); ?>

        </span>

        
        <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->role === 'pembeli'): ?>
        <div class="dropdown" id="bellNotif">
            <button class="btn btn-sm btn-latte position-relative" data-bs-toggle="dropdown" id="btnBell">
                <i class="fa fa-bell"></i>
                <span id="bellBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size:0.6rem;display:none;"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end p-0" style="min-width:300px;max-height:400px;overflow-y:auto;">
                <div class="p-2 fw-600 small border-bottom" style="color:var(--gold);">
                    <i class="fa fa-bell me-1"></i> Notifikasi Pesanan
                </div>
                <div id="notifList">
                    <div class="p-3 text-center text-muted small">Memuat...</div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <div class="dropdown">
            <button class="btn btn-sm btn-latte dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <i class="fa fa-user-circle"></i>
                <span class="d-none d-md-inline"><?php echo e(auth()->user()->name); ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header"><?php echo e(ucfirst(auth()->user()->role)); ?></h6></li>
                <li><hr class="dropdown-divider"></li>
                <?php if(auth()->user()->role === 'pembeli'): ?>
                <li><a class="dropdown-item" href="<?php echo e(route('pembeli.profil')); ?>"><i class="fa fa-user me-2"></i>Profil Saya</a></li>
                <li><hr class="dropdown-divider"></li>
                <?php elseif(auth()->user()->role === 'driver'): ?>
                <li><a class="dropdown-item" href="<?php echo e(route('driver.profil')); ?>"><i class="fa fa-user me-2"></i>Profil Saya</a></li>
                <li><hr class="dropdown-divider"></li>
                <?php endif; ?>
                <li>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fa fa-right-from-bracket me-2"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>


<?php $sedangTutup = \App\Models\Pengaturan::get('sedang_tutup', '0'); ?>
<?php if($sedangTutup === '1'): ?>
<div class="alert alert-danger mb-0 rounded-0 text-center py-2" style="font-size:0.85rem;">
    <i class="fa fa-door-closed me-2"></i>
    <strong>Kedai sedang tutup.</strong> Pemesanan tidak dapat dilakukan saat ini. Silakan coba lagi nanti.
</div>
<?php endif; ?>

<?php if(auth()->guard()->check()): ?>
<?php if(auth()->user()->role === 'pembeli'): ?>
<script>
let lastOrderStatuses = {};

async function cekNotifikasiPesanan() {
    try {
        const res = await fetch('<?php echo e(route("pembeli.notifikasi")); ?>');
        const data = await res.json();
        const list = document.getElementById('notifList');
        const badge = document.getElementById('bellBadge');

        if (!data.pesanan || data.pesanan.length === 0) {
            list.innerHTML = '<div class="p-3 text-center text-muted small">Tidak ada pesanan aktif</div>';
            badge.style.display = 'none';
            return;
        }

        let unread = 0;
        let html = '';
        data.pesanan.forEach(p => {
            const statusLama = lastOrderStatuses[p.id];
            const berubah = statusLama && statusLama !== p.status;
            if (berubah) unread++;

            const warna = {
                pending: '#D4A017', dikonfirmasi: '#0c5460',
                diproses: '#0d6efd', siap: '#2D6A4F',
                diantar: '#2D6A4F', selesai: '#6c757d', dibatalkan: '#dc3545'
            };

            html += `<a href="<?php echo e(url('/menu-online/tracking')); ?>/${p.id}" class="dropdown-item py-2 border-bottom ${berubah ? 'bg-warning bg-opacity-10' : ''}">
                <div class="d-flex justify-content-between">
                    <span class="small fw-600">${p.nomor}</span>
                    <span class="badge" style="background:${warna[p.status] || '#999'};font-size:0.65rem;">${p.label}</span>
                </div>
                <div class="small text-muted">Rp ${p.total} · ${p.waktu}</div>
                ${berubah ? '<div class="small text-warning fw-600">🔔 Status berubah!</div>' : ''}
            </a>`;

            lastOrderStatuses[p.id] = p.status;
        });

        list.innerHTML = html;
        if (unread > 0) {
            badge.textContent = unread;
            badge.style.display = '';
        } else {
            badge.style.display = 'none';
        }
    } catch(e) {}
}

// Cek notifikasi saat bell diklik dan setiap 15 detik
document.getElementById('btnBell')?.addEventListener('click', () => {
    document.getElementById('bellBadge').style.display = 'none';
    cekNotifikasiPesanan();
});
cekNotifikasiPesanan();
setInterval(cekNotifikasiPesanan, 15000);
</script>
<?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\boss-kopi\resources\views/layouts/topbar.blade.php ENDPATH**/ ?>