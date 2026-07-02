<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifikasiWaController;
use App\Http\Controllers\Auth\LupaPasswordController;
use App\Http\Controllers\Admin\{DashboardController, MenuController, UserController, StokController, LaporanController, PromoController, OrderController as AdminOrderController, PengaduanController as AdminPengaduanController, PengaturanController};
use App\Http\Controllers\Kasir\{PosController, ShiftController};
use App\Http\Controllers\Pembeli\HomeController;
use App\Http\Controllers\Pembeli\UlasanController;
use App\Http\Controllers\Driver\OrderController;
use App\Http\Controllers\Shared\PengaduanController;

// ==========================================
// AUTH ROUTES
// ==========================================
Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Lupa Password
Route::middleware('guest')->group(function () {
    Route::get('/lupa-password', [LupaPasswordController::class, 'showForm'])->name('lupa-password.form');
    Route::post('/lupa-password', [LupaPasswordController::class, 'kirimKode'])->name('lupa-password.kirim');
    Route::get('/lupa-password/verifikasi', [LupaPasswordController::class, 'showVerifikasi'])->name('lupa-password.verifikasi');
    Route::post('/lupa-password/reset', [LupaPasswordController::class, 'resetPassword'])->name('lupa-password.reset');
});

// Verifikasi WA untuk Pembeli
Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/verifikasi-wa', [VerifikasiWaController::class, 'show'])->name('verifikasi-wa.show');
    Route::post('/verifikasi-wa', [VerifikasiWaController::class, 'verify'])->name('verifikasi-wa.verify');
});

// Admin generate kode verifikasi untuk pelanggan
Route::get('/admin/pengguna/{user}/kirim-kode-wa', [VerifikasiWaController::class, 'generateKode'])
    ->name('admin.user.kirim-kode-wa')
    ->middleware(['auth', 'role:admin']);

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifikasi', [DashboardController::class, 'notifikasi'])->name('notifikasi');
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    // Pesanan Online
    Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('order.index');
    Route::get('/pesanan/{order}', [AdminOrderController::class, 'show'])->name('order.show');
    Route::post('/pesanan/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('order.update-status');

    // Pengaduan
    Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{pengaduan}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::post('/pengaduan/{pengaduan}', [AdminPengaduanController::class, 'update'])->name('pengaduan.update');

    // Menu Management
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/tambah', [MenuController::class, 'create'])->name('menu.create');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{menu}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');
    Route::patch('/menu/{menu}/toggle', [MenuController::class, 'toggleTersedia'])->name('menu.toggle');

    // User Management
    Route::get('/pengguna/driver-menunggu', [UserController::class, 'driverMenunggu'])->name('user.driver-menunggu');
    Route::get('/pengguna/format-berkas-driver', [UserController::class, 'formatBerkasDriver'])->name('user.format-berkas-driver');
    Route::get('/pengguna/{user}/panggil-driver', [UserController::class, 'panggilDriver'])->name('user.panggil-driver');
    Route::post('/pengguna/{user}/approve', [UserController::class, 'approveDriver'])->name('user.approve-driver');
    Route::post('/pengguna/{user}/buka-blokir', [UserController::class, 'bukaBlokir'])->name('user.buka-blokir');
    Route::post('/pengguna/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
    Route::get('/pengguna', [UserController::class, 'index'])->name('user.index');
    Route::get('/pengguna/tambah', [UserController::class, 'create'])->name('user.create');
    Route::post('/pengguna', [UserController::class, 'store'])->name('user.store');
    Route::get('/pengguna/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/pengguna/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::patch('/pengguna/{user}/toggle', [UserController::class, 'toggleStatus'])->name('user.toggle');

    // Stok Management
    Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
    Route::get('/stok/tambah', [StokController::class, 'create'])->name('stok.create');
    Route::post('/stok', [StokController::class, 'store'])->name('stok.store');
    Route::get('/stok/{stok}/edit', [StokController::class, 'edit'])->name('stok.edit');
    Route::put('/stok/{stok}', [StokController::class, 'update'])->name('stok.update');
    Route::post('/stok/{stok}/restock', [StokController::class, 'restock'])->name('stok.restock');
    Route::get('/stok/{stok}/riwayat', [StokController::class, 'history'])->name('stok.history');

    // Promo Management
    Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');
    Route::get('/promo/tambah', [PromoController::class, 'create'])->name('promo.create');
    Route::post('/promo', [PromoController::class, 'store'])->name('promo.store');
    Route::get('/promo/{promo}/edit', [PromoController::class, 'edit'])->name('promo.edit');
    Route::put('/promo/{promo}', [PromoController::class, 'update'])->name('promo.update');
    Route::delete('/promo/{promo}', [PromoController::class, 'destroy'])->name('promo.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/penjualan/export-excel', [LaporanController::class, 'exportPenjualanExcel'])->name('laporan.penjualan.excel');
    Route::get('/laporan/penjualan/export-pdf', [LaporanController::class, 'exportPenjualanPdf'])->name('laporan.penjualan.pdf');
    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    Route::get('/laporan/kasir', [LaporanController::class, 'kinerjaKasir'])->name('laporan.kasir');
});

// ==========================================
// KASIR ROUTES
// ==========================================
Route::prefix('kasir')->name('kasir.')->middleware(['auth', 'role:kasir,admin'])->group(function () {

    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos');
    Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
    Route::get('/struk/{order}', [PosController::class, 'struk'])->name('struk');
    Route::get('/riwayat', [PosController::class, 'riwayat'])->name('riwayat');

    // Shift
    Route::get('/shift/buka', [ShiftController::class, 'bukaForm'])->name('shift.buka');
    Route::post('/shift/buka', [ShiftController::class, 'buka'])->name('shift.buka.post');
    Route::get('/shift/tutup', [ShiftController::class, 'tutupForm'])->name('shift.tutup');
    Route::post('/shift/tutup', [ShiftController::class, 'tutup'])->name('shift.tutup.post');
    Route::get('/shift/{shift}/ringkasan', [ShiftController::class, 'ringkasan'])->name('shift.ringkasan');
});

// ==========================================
// PEMBELI ROUTES
// ==========================================
Route::prefix('menu-online')->name('pembeli.')->middleware(['auth', 'role:pembeli'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [HomeController::class, 'menu'])->name('menu');
    Route::get('/menu/{menu}', [HomeController::class, 'detailMenu'])->name('menu.detail');
    Route::get('/keranjang', [HomeController::class, 'keranjang'])->name('keranjang');
    Route::post('/checkout', [HomeController::class, 'checkout'])->name('checkout');
    Route::get('/tracking/{order}', [HomeController::class, 'tracking'])->name('tracking');
    Route::get('/tracking/{order}/lokasi-driver', [HomeController::class, 'lokasiDriver'])->name('tracking.lokasi-driver');
    Route::get('/riwayat', [HomeController::class, 'riwayat'])->name('riwayat');
    Route::post('/reorder/{order}', [HomeController::class, 'reorder'])->name('reorder');
    Route::post('/cek-promo', [HomeController::class, 'cekPromo'])->name('cek-promo');
    Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
    Route::put('/profil', [HomeController::class, 'updateProfil'])->name('profil.update');
    Route::post('/rating-driver/{order}', [HomeController::class, 'ratingDriver'])->name('rating-driver');
    Route::post('/pesanan/{order}/cancel', [HomeController::class, 'cancelOrder'])->name('cancel-order');
    Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
    Route::get('/notifikasi', [HomeController::class, 'notifikasi'])->name('notifikasi');
});

// ==========================================
// DRIVER ROUTES
// ==========================================
Route::prefix('driver')->name('driver.')->middleware(['auth', 'role:driver'])->group(function () {

    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders');
    Route::post('/pesanan/{order}/ambil', [OrderController::class, 'ambil'])->name('ambil');
    Route::get('/pesanan/{order}', [OrderController::class, 'detail'])->name('detail');
    Route::post('/pesanan/{order}/lokasi', [OrderController::class, 'updateLocation'])->name('update-location');
    Route::post('/pesanan/{order}/selesai', [OrderController::class, 'selesai'])->name('selesai');
    Route::post('/pesanan/{order}/rating-pelanggan', [OrderController::class, 'ratingPelanggan'])->name('rating-pelanggan');
    Route::post('/pesanan/{order}/cancel', [OrderController::class, 'cancelPengantaran'])->name('cancel-pengantaran');
    Route::get('/riwayat', [OrderController::class, 'riwayat'])->name('riwayat');
    Route::get('/profil', [OrderController::class, 'profil'])->name('profil');
    Route::put('/profil', [OrderController::class, 'updateProfil'])->name('profil.update');
});

// ==========================================
// PENGADUAN ROUTES (Pembeli & Driver)
// ==========================================
Route::prefix('pengaduan')->name('pengaduan.')->middleware(['auth', 'role:pembeli,driver'])->group(function () {
    Route::get('/', [PengaduanController::class, 'index'])->name('index');
    Route::get('/buat', [PengaduanController::class, 'create'])->name('create');
    Route::post('/', [PengaduanController::class, 'store'])->name('store');
    Route::get('/{pengaduan}', [PengaduanController::class, 'show'])->name('show');
});
