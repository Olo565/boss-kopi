<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Menu, User, BahanBaku, Shift, Pengaduan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Pendapatan harian 7 hari terakhir
        $pendapatanHarian = Order::where('status', 'selesai')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as tanggal, SUM(total) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Pendapatan bulanan 6 bulan terakhir
        $pendapatanBulanan = Order::where('status', 'selesai')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, SUM(total) as total')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')->orderBy('bulan')
            ->get();

        // Menu terlaris
        $menuTerlaris = Menu::withCount(['orderItems as total_order' => function ($q) {
            $q->whereHas('order', fn($q2) => $q2->where('status', 'selesai'));
        }])->orderByDesc('total_order')->take(5)->get();

        // Stok kritis
        $stokKritis = BahanBaku::whereRaw('stok_saat_ini <= stok_minimum')->get();

        // Statistik hari ini
        $today = now()->toDateString();
        $pendapatanHariIni = Order::where('status', 'selesai')
            ->whereDate('created_at', $today)->sum('total');
        $transaksiHariIni = Order::whereDate('created_at', $today)->count();
        $totalPelanggan = User::where('role', 'pembeli')->count();
        $totalMenu = Menu::where('tersedia', true)->count();

        return view('admin.dashboard', compact(
            'pendapatanHarian', 'pendapatanBulanan', 'menuTerlaris',
            'stokKritis', 'pendapatanHariIni', 'transaksiHariIni',
            'totalPelanggan', 'totalMenu'
        ));
    }

    public function notifikasi()
    {
        $pesananPending = \App\Models\Order::whereNotNull('user_id')
            ->where('status', 'pending')->count();
        $driverMenunggu = User::where('role', 'driver')
            ->where('status_akun', 'menunggu')->count();
        $pengaduanBaru = \App\Models\Pengaduan::where('status', 'baru')->count();

        return response()->json([
            'pesanan_pending' => $pesananPending,
            'driver_menunggu' => $driverMenunggu,
            'pengaduan_baru' => $pengaduanBaru,
            'total' => $pesananPending + $driverMenunggu + $pengaduanBaru,
        ]);
    }
}
