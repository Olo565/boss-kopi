<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Shift, BahanBaku, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function penjualan(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?? now()->startOfMonth()->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->toDateString();

        $orders = Order::with(['items', 'kasir', 'promo'])
            ->where('status', 'selesai')
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalMulai, $tanggalSelesai])
            ->latest()->get();

        $totalPenjualan = $orders->sum('total');
        $totalDiskon = $orders->sum('diskon');
        $byMetode = $orders->groupBy('metode_pembayaran')->map->sum('total');

        return view('admin.laporan.penjualan', compact(
            'orders', 'totalPenjualan', 'totalDiskon', 'byMetode',
            'tanggalMulai', 'tanggalSelesai'
        ));
    }

    public function exportPenjualanExcel(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?? now()->startOfMonth()->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->toDateString();

        $orders = Order::with(['items', 'kasir', 'promo'])
            ->where('status', 'selesai')
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalMulai, $tanggalSelesai])
            ->latest()->get();

        $filename = 'laporan-penjualan-' . $tanggalMulai . '-sd-' . $tanggalSelesai . '.xlsx';

        return Excel::download(new \App\Exports\LaporanPenjualanExport($orders), $filename);
    }

    public function exportPenjualanPdf(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?? now()->startOfMonth()->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->toDateString();

        $orders = Order::with(['items', 'kasir', 'promo'])
            ->where('status', 'selesai')
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalMulai, $tanggalSelesai])
            ->latest()->get();

        $totalPenjualan = $orders->sum('total');

        $pdf = Pdf::loadView('admin.laporan.pdf.penjualan', compact(
            'orders', 'totalPenjualan', 'tanggalMulai', 'tanggalSelesai'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . $tanggalMulai . '.pdf');
    }

    public function stok()
    {
        $bahanBaku = BahanBaku::with('stokHistories')->get();
        return view('admin.laporan.stok', compact('bahanBaku'));
    }

    public function kinerjaKasir(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?? now()->startOfMonth()->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->toDateString();

        $shifts = Shift::with(['kasir', 'orders'])
            ->whereBetween(DB::raw('DATE(waktu_buka)'), [$tanggalMulai, $tanggalSelesai])
            ->where('status', 'tutup')
            ->latest('waktu_buka')->get();

        return view('admin.laporan.kasir', compact('shifts', 'tanggalMulai', 'tanggalSelesai'));
    }
}
