<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\{Menu, KategoriMenu, Promo, Order, OrderItem, PoinHistory};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $menuPopuler = Menu::where('tersedia', true)
            ->orderByDesc('total_terjual')->take(8)->get();
        $kategoris = KategoriMenu::where('is_active', true)->get();
        $promoAktif = Promo::where('is_active', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())->get();
        $user = auth()->user();
        return view('pembeli.home', compact('menuPopuler', 'kategoris', 'promoAktif', 'user'));
    }

    public function menu(Request $request)
    {
        $query = Menu::with('kategori')->where('tersedia', true);
        if ($request->kategori) {
            $query->where('kategori_menu_id', $request->kategori);
        }
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        $menus = $query->get();
        $kategoris = KategoriMenu::where('is_active', true)->get();
        return view('pembeli.menu', compact('menus', 'kategoris'));
    }

    public function detailMenu(Menu $menu)
    {
        $ulasans = \App\Models\Ulasan::where('menu_id', $menu->id)
            ->with('user')->latest()->get();
        $rataRating = $ulasans->avg('rating');
        $sudahUlasan = auth()->check()
            ? \App\Models\Ulasan::where('user_id', auth()->id())->where('menu_id', $menu->id)->exists()
            : false;
        return view('pembeli.detail-menu', compact('menu', 'ulasans', 'rataRating', 'sudahUlasan'));
    }

    public function keranjang()
    {
        return view('pembeli.keranjang');
    }

    public function checkout(Request $request)
    {
        // Cek verifikasi WA untuk pesanan delivery
        if ($request->tipe_pesanan === 'delivery' && !auth()->user()->no_hp_terverifikasi) {
            return redirect()->route('verifikasi-wa.show')
                ->with('info', 'Nomor WhatsApp Anda perlu diverifikasi terlebih dahulu sebelum bisa memesan delivery. Ini untuk keamanan driver kami.');
        }

        // Cek blacklist delivery
        if ($request->tipe_pesanan === 'delivery' && auth()->user()->diblokir_delivery) {
            return back()->with('error',
                'Maaf, akun Anda diblokir dari layanan delivery karena terlalu sering membatalkan pesanan. ' .
                'Silakan hubungi Admin BOSS KOPI untuk membuka blokir.'
            );
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'tipe_pesanan' => 'required|in:takeaway,delivery',
            'metode_pembayaran' => 'required|in:tunai,qris,debit',
            'alamat_delivery' => 'required_if:tipe_pesanan,delivery|nullable|string',
            'lat_tujuan' => 'required_if:tipe_pesanan,delivery|nullable|numeric',
            'lng_tujuan' => 'required_if:tipe_pesanan,delivery|nullable|numeric',
            'kode_promo' => 'nullable|string',
            'gunakan_poin' => 'nullable|integer|min:0',
        ], [
            'items.required' => 'Keranjang belanja kosong.',
            'tipe_pesanan.required' => 'Pilih tipe pesanan.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
            'alamat_delivery.required_if' => 'Alamat pengiriman wajib diisi untuk delivery.',
            'lat_tujuan.required_if' => 'Mohon aktifkan GPS lokasi Anda untuk pesanan delivery.',
            'lng_tujuan.required_if' => 'Mohon aktifkan GPS lokasi Anda untuk pesanan delivery.',
        ]);

        $subtotal = 0;
        $orderItems = [];
        $menuTidakTersedia = [];

        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);

            // Cek menu masih tersedia
            if (!$menu->tersedia) {
                $menuTidakTersedia[] = $menu->nama;
                continue;
            }

            $harga = $menu->getHargaByTipe($request->tipe_pesanan);
            $itemSubtotal = $harga * $item['jumlah'];
            $subtotal += $itemSubtotal;
            $orderItems[] = [
                'menu' => $menu,
                'jumlah' => $item['jumlah'],
                'harga' => $harga,
                'subtotal' => $itemSubtotal,
                'catatan' => $item['catatan'] ?? null,
            ];
        }

        // Kalau ada menu tidak tersedia, tolak checkout
        if (!empty($menuTidakTersedia)) {
            return back()->with('error',
                'Menu berikut sudah tidak tersedia: ' . implode(', ', $menuTidakTersedia) .
                '. Mohon hapus dari keranjang dan coba lagi.'
            );
        }

        if (empty($orderItems)) {
            return back()->with('error', 'Tidak ada item yang bisa diproses. Periksa kembali keranjang Anda.');
        }

        $diskon = 0;
        $promoId = null;
        if ($request->kode_promo) {
            $promo = Promo::where('kode_kupon', $request->kode_promo)->first();
            if ($promo && $promo->isValid() && $subtotal >= $promo->min_transaksi) {
                $diskon = $promo->hitungDiskon($subtotal);
                $promoId = $promo->id;
                $promo->increment('sudah_digunakan');
            }
        }

        $ongkir = $request->tipe_pesanan === 'delivery'
            ? (int) \App\Models\Pengaturan::get('ongkir', 5000)
            : 0;

        // Koordinat toko dari pengaturan
        $latToko = (float) \App\Models\Pengaturan::get('lat_toko', 3.579026);
        $lngToko = (float) \App\Models\Pengaturan::get('lng_toko', 98.613460);

        // Redeem poin loyalitas (100 poin = Rp 2.000, minimum 100 poin)
        $poinDipakai = 0;
        $diskonPoin = 0;
        $user = auth()->user();
        $poinDiminta = (int) ($request->gunakan_poin ?? 0);

        if ($poinDiminta >= 100 && $user->poin_loyalitas >= $poinDiminta) {
            // Bulatkan ke kelipatan 100
            $poinDipakai = floor($poinDiminta / 100) * 100;
            $diskonPoin = ($poinDipakai / 100) * 2000;
        }

        $total = max(0, $subtotal - $diskon - $diskonPoin + $ongkir);

        $order = Order::create([
            'nomor_struk' => Order::generateNomorStruk(),
            'user_id' => auth()->id(),
            'promo_id' => $promoId,
            'tipe_pesanan' => $request->tipe_pesanan,
            'nama_pelanggan' => auth()->user()->name,
            'no_hp_pelanggan' => auth()->user()->no_hp,
            'alamat_delivery' => $request->alamat_delivery,
            'lat_tujuan' => $request->lat_tujuan,
            'lng_tujuan' => $request->lng_tujuan,
            'lat_toko' => $latToko,
            'lng_toko' => $lngToko,
            'subtotal' => $subtotal,
            'diskon' => $diskon,
            'ongkir' => $ongkir,
            'total' => $total,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => 'pending',
            'catatan' => $request->catatan,
        ]);

        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu']->id,
                'nama_menu' => $item['menu']->nama,
                'harga_satuan' => $item['harga'],
                'jumlah' => $item['jumlah'],
                'subtotal' => $item['subtotal'],
                'catatan' => $item['catatan'],
            ]);
        }

        // Tambah poin loyalitas (dari pengaturan)
        $poinPerRupiah = (int) \App\Models\Pengaturan::get('poin_per_rupiah', 5000);
        $poinDidapat = $poinPerRupiah > 0 ? (int) ($total / $poinPerRupiah) : 0;
        if ($poinDidapat > 0) {
            auth()->user()->increment('poin_loyalitas', $poinDidapat);
            PoinHistory::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'tipe' => 'tambah',
                'jumlah_poin' => $poinDidapat,
                'keterangan' => 'Pembelian order ' . $order->nomor_struk,
            ]);
        }

        // Kurangi poin yang digunakan untuk diskon
        if ($poinDipakai > 0) {
            auth()->user()->decrement('poin_loyalitas', $poinDipakai);
            PoinHistory::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'tipe' => 'kurang',
                'jumlah_poin' => $poinDipakai,
                'keterangan' => 'Redeem poin — diskon Rp ' . number_format($diskonPoin, 0, ',', '.') . ' untuk order ' . $order->nomor_struk,
            ]);
        }

        $pesanSukses = 'Pesanan berhasil dibuat!';
        if ($poinDidapat > 0) $pesanSukses .= ' Anda mendapat ' . $poinDidapat . ' poin.';
        if ($poinDipakai > 0) $pesanSukses .= ' Diskon poin Rp ' . number_format($diskonPoin, 0, ',', '.') . ' berhasil digunakan.';

        return redirect()->route('pembeli.tracking', $order->id)
            ->with('success', $pesanSukses);
    }

    public function tracking(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        $order->load(['items.menu', 'driver', 'pengiriman']);
        return view('pembeli.tracking', compact('order'));
    }

    public function cancelOrder(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses oleh kedai.');
        }

        $request->validate([
            'alasan_cancel' => 'required|string|max:255',
        ], [
            'alasan_cancel.required' => 'Mohon isi alasan pembatalan.',
        ]);

        $order->update([
            'status' => 'dibatalkan',
            'catatan' => 'Dibatalkan oleh pelanggan: ' . $request->alasan_cancel,
        ]);

        // Kembalikan poin yang sudah digunakan (kalau ada)
        // Hapus poin yang didapat dari order ini (belum ada karena pending)

        return redirect()->route('pembeli.riwayat')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function lokasiDriver(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['success' => false], 403);
        }

        $pengiriman = $order->pengiriman;

        if (!$pengiriman || !$pengiriman->lat_driver) {
            return response()->json(['available' => false]);
        }

        // Estimasi waktu tempuh sederhana berdasarkan jarak lurus (haversine), asumsi kecepatan rata-rata 30 km/jam
        $estimasiMenit = null;
        if ($order->lat_tujuan && $order->lng_tujuan) {
            $jarakKm = $this->hitungJarakKm(
                $pengiriman->lat_driver, $pengiriman->lng_driver,
                $order->lat_tujuan, $order->lng_tujuan
            );
            $estimasiMenit = max(1, round(($jarakKm / 30) * 60));
        }

        return response()->json([
            'available' => true,
            'lat' => (float) $pengiriman->lat_driver,
            'lng' => (float) $pengiriman->lng_driver,
            'updated_at' => $pengiriman->lokasi_updated_at?->diffForHumans(),
            'estimasi_menit' => $estimasiMenit,
            'status' => $order->status,
        ]);
    }

    private function hitungJarakKm($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function riwayat()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items'])->latest()->paginate(10);
        $user = auth()->user();
        $poinHistories = PoinHistory::where('user_id', auth()->id())
            ->latest()->take(10)->get();
        return view('pembeli.riwayat', compact('orders', 'user', 'poinHistories'));
    }

    public function reorder(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        session(['reorder_items' => $order->items->map(fn($i) => [
            'menu_id' => $i->menu_id,
            'nama' => $i->nama_menu,
            'harga' => $i->harga_satuan,
            'jumlah' => $i->jumlah,
        ])->toArray()]);
        return redirect()->route('pembeli.keranjang')
            ->with('success', 'Item dari pesanan sebelumnya telah ditambahkan ke keranjang.');
    }

    public function cekPromo(Request $request)
    {
        $promo = Promo::where('kode_kupon', $request->kode)->first();
        if (!$promo || !$promo->isValid()) {
            return response()->json(['valid' => false, 'pesan' => 'Kode promo tidak valid atau sudah kadaluarsa.']);
        }
        return response()->json([
            'valid' => true,
            'nama' => $promo->nama,
            'tipe' => $promo->tipe,
            'nilai' => $promo->nilai_diskon,
            'min_transaksi' => $promo->min_transaksi,
        ]);
    }

    public function notifikasi()
    {
        $pesanan = Order::where('user_id', auth()->id())
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->latest()->limit(5)->get()
            ->map(fn($o) => [
                'id' => $o->id,
                'nomor' => $o->nomor_struk,
                'status' => $o->status,
                'label' => $o->getLabelStatus(),
                'total' => number_format($o->total, 0, ',', '.'),
                'waktu' => $o->updated_at->diffForHumans(),
            ]);

        return response()->json(['pesanan' => $pesanan]);
    }

    public function profil()
    {
        $user = auth()->user();
        return view('pembeli.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'no_hp' => 'required|string|min:10|max:15',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'no_hp.required' => 'No. HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = $request->only(['name', 'no_hp', 'alamat']);

        if ($request->hasFile('foto')) {
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/user-foto'), $filename);
            $data['foto'] = 'images/user-foto/' . $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function ratingDriver(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        if ($order->status !== 'selesai' || !$order->driver_id) {
            return back()->with('error', 'Pesanan ini belum bisa diberi rating.');
        }
        if ($order->rating_driver) {
            return back()->with('error', 'Anda sudah memberi rating untuk pesanan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:255',
        ], [
            'rating.required' => 'Mohon beri rating bintang.',
        ]);

        $order->update([
            'rating_driver' => $request->rating,
            'komentar_driver' => $request->komentar,
        ]);

        $order->driver?->tambahRating($request->rating);

        return back()->with('success', 'Terima kasih atas penilaian Anda untuk driver!');
    }
}
