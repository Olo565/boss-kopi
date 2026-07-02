<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\{Menu, KategoriMenu, Order, OrderItem, Shift, Promo, BahanBaku, StokHistory};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        // Cek apakah ada shift aktif
        $shiftAktif = Shift::where('kasir_id', auth()->id())
            ->where('status', 'buka')->latest()->first();

        if (!$shiftAktif) {
            return redirect()->route('kasir.shift.buka')
                ->with('warning', 'Silakan buka shift terlebih dahulu.');
        }

        $kategoris = KategoriMenu::where('is_active', true)->get();
        $menus = Menu::with('kategori')->where('tersedia', true)->get();

        return view('kasir.pos', compact('kategoris', 'menus', 'shiftAktif'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.catatan' => 'nullable|string',
            'tipe_pesanan' => 'required|in:dine_in,takeaway',
            'metode_pembayaran' => 'required|in:tunai,qris,debit',
            'uang_bayar' => 'required_if:metode_pembayaran,tunai|nullable|numeric|min:0',
            'nama_pelanggan' => 'nullable|string|max:100',
            'nomor_meja' => 'required_if:tipe_pesanan,dine_in|nullable|string',
            'kode_promo' => 'nullable|string',
        ], [
            'items.required' => 'Keranjang belanja tidak boleh kosong.',
            'tipe_pesanan.required' => 'Tipe pesanan wajib dipilih.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'uang_bayar.required_if' => 'Uang bayar wajib diisi untuk pembayaran tunai.',
            'nomor_meja.required_if' => 'Nomor meja wajib diisi untuk dine-in.',
        ]);

        $shiftAktif = Shift::where('kasir_id', auth()->id())
            ->where('status', 'buka')->latest()->firstOrFail();

        DB::transaction(function () use ($request, $shiftAktif) {
            $subtotal = 0;
            $items = [];

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $harga = $menu->getHargaByTipe($request->tipe_pesanan);
                $itemSubtotal = $harga * $item['jumlah'];
                $subtotal += $itemSubtotal;

                $items[] = [
                    'menu' => $menu,
                    'jumlah' => $item['jumlah'],
                    'harga' => $harga,
                    'subtotal' => $itemSubtotal,
                    'catatan' => $item['catatan'] ?? null,
                    'varian' => $item['varian'] ?? null,
                ];
            }

            // Hitung diskon promo
            $diskon = 0;
            $promoId = null;
            if ($request->kode_promo) {
                $promo = Promo::where('kode_kupon', $request->kode_promo)->first();
                if ($promo && $promo->isValid()) {
                    $diskon = $promo->hitungDiskon($subtotal);
                    $promoId = $promo->id;
                    $promo->increment('sudah_digunakan');
                }
            }

            $ongkir = 0;
            $total = $subtotal - $diskon + $ongkir;
            $kembalian = $request->metode_pembayaran === 'tunai'
                ? ($request->uang_bayar - $total) : 0;

            $order = Order::create([
                'nomor_struk' => Order::generateNomorStruk(),
                'kasir_id' => auth()->id(),
                'shift_id' => $shiftAktif->id,
                'promo_id' => $promoId,
                'tipe_pesanan' => $request->tipe_pesanan,
                'nomor_meja' => $request->nomor_meja,
                'nama_pelanggan' => $request->nama_pelanggan,
                'no_hp_pelanggan' => $request->no_hp_pelanggan,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'ongkir' => $ongkir,
                'total' => $total,
                'metode_pembayaran' => $request->metode_pembayaran,
                'uang_bayar' => $request->uang_bayar,
                'uang_kembalian' => $kembalian,
                'status' => 'selesai',
                'catatan' => $request->catatan,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu']->id,
                    'nama_menu' => $item['menu']->nama,
                    'harga_satuan' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                    'varian' => $item['varian'],
                    'catatan' => $item['catatan'],
                ]);

                // Update stok via recipe mapping
                foreach ($item['menu']->recipeMappings as $recipe) {
                    $bahan = $recipe->bahanBaku;
                    $totalDipakai = $recipe->jumlah_digunakan * $item['jumlah'];
                    $sebelum = $bahan->stok_saat_ini;
                    $sesudah = max(0, $sebelum - $totalDipakai);
                    $bahan->update(['stok_saat_ini' => $sesudah]);
                    StokHistory::create([
                        'bahan_baku_id' => $bahan->id,
                        'tipe' => 'keluar',
                        'jumlah' => $totalDipakai,
                        'stok_sebelum' => $sebelum,
                        'stok_sesudah' => $sesudah,
                        'keterangan' => 'Penjualan order ' . $order->nomor_struk,
                        'user_id' => auth()->id(),
                    ]);
                }

                // Update total terjual menu
                $item['menu']->increment('total_terjual', $item['jumlah']);
            }

            // Update shift
            $field = 'total_' . $request->metode_pembayaran;
            $shiftAktif->increment($field, $total);

            session(['last_order_id' => $order->id]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil!',
            'order_id' => session('last_order_id'),
        ]);
    }

    public function struk(Order $order)
    {
        $order->load(['items', 'kasir', 'promo']);
        return view('kasir.struk', compact('order'));
    }

    public function riwayat(Request $request)
    {
        $shiftAktif = Shift::where('kasir_id', auth()->id())
            ->where('status', 'buka')->latest()->first();

        $orders = Order::where('kasir_id', auth()->id())
            ->whereDate('created_at', today())
            ->with(['items'])->latest()->paginate(20);

        return view('kasir.riwayat', compact('orders', 'shiftAktif'));
    }
}
