<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\{Order, Pengiriman};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        // Pesanan delivery yang belum diambil driver
        $ordersMenunggu = Order::where('tipe_pesanan', 'delivery')
            ->where('status', 'siap')
            ->whereNull('driver_id')
            ->with(['items', 'user'])->latest()->get();

        // Pesanan yang sedang diantar driver ini
        $ordersSaya = Order::where('driver_id', auth()->id())
            ->whereIn('status', ['diantar'])
            ->with(['items', 'user', 'pengiriman'])->latest()->get();

        $totalHariIni = Pengiriman::where('driver_id', auth()->id())
            ->where('status', 'selesai')
            ->whereDate('updated_at', today())->count();

        $komisiHariIni = Pengiriman::where('driver_id', auth()->id())
            ->where('status', 'selesai')
            ->whereDate('updated_at', today())->sum('komisi');

        return view('driver.orders', compact(
            'ordersMenunggu', 'ordersSaya', 'totalHariIni', 'komisiHariIni'
        ));
    }

    public function ambil(Order $order)
    {
        if ($order->status !== 'siap' || $order->driver_id) {
            return back()->with('error', 'Pesanan ini sudah diambil driver lain.');
        }

        $order->update([
            'driver_id' => auth()->id(),
            'status' => 'diantar',
        ]);

        Pengiriman::create([
            'order_id' => $order->id,
            'driver_id' => auth()->id(),
            'status' => 'diambil',
            'waktu_ambil' => now(),
            'komisi' => 0,
        ]);

        return redirect()->route('driver.detail', $order->id)
            ->with('success', 'Pesanan berhasil diambil. Segera antar ke pelanggan!');
    }

    public function detail(Order $order)
    {
        if ($order->driver_id !== auth()->id()) abort(403);
        $order->load(['items.menu', 'user', 'pengiriman']);
        return view('driver.detail', compact('order'));
    }

    public function selesai(Request $request, Order $order)
    {
        if ($order->driver_id !== auth()->id()) abort(403);

        $request->validate([
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'bukti_foto.required' => 'Foto bukti pengiriman wajib diunggah.',
            'bukti_foto.image' => 'File harus berupa foto.',
            'bukti_foto.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        // Simpan bukti foto ke public/images langsung (tanpa symlink)
        $file = $request->file('bukti_foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/bukti-pengiriman'), $filename);
        $fotoPath = 'images/bukti-pengiriman/' . $filename;

        // Komisi 40% dari ongkir pesanan
        $ongkir = $order->ongkir ?? 5000;
        $komisi = round($ongkir * 0.40);

        $order->update([
            'status' => 'selesai',
            'bukti_pengiriman' => $fotoPath,
        ]);

        $pengiriman = $order->pengiriman;
        $pengiriman->update([
            'status' => 'selesai',
            'waktu_tiba' => now(),
            'bukti_foto' => $fotoPath,
            'catatan' => $request->catatan,
            'komisi' => $komisi,
        ]);

        return redirect()->route('driver.orders')
            ->with('success', 'Pengiriman selesai! Komisi Anda: Rp ' . number_format($komisi, 0, ',', '.') . ' (40% dari ongkir).');
    }

    public function updateLocation(Request $request, Order $order)
    {
        if ($order->driver_id !== auth()->id()) {
            return response()->json(['success' => false], 403);
        }

        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $pengiriman = $order->pengiriman;
        if ($pengiriman) {
            $pengiriman->update([
                'lat_driver' => $request->lat,
                'lng_driver' => $request->lng,
                'lokasi_updated_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function cancelPengantaran(Request $request, Order $order)
    {
        if ($order->driver_id !== auth()->id()) abort(403);

        if (!in_array($order->status, ['siap', 'diantar'])) {
            return back()->with('error', 'Pesanan ini tidak bisa dibatalkan.');
        }

        $request->validate([
            'alasan' => 'required|string|max:255',
        ], [
            'alasan.required' => 'Pilih alasan pembatalan.',
        ]);

        // Kalau pilih "lainnya", pakai isi field alasan_lain
        $alasan = $request->alasan === 'lainnya'
            ? ($request->alasan_lain ?: 'Alasan lainnya')
            : $request->alasan;

        // Kembalikan pesanan ke status siap, lepas dari driver ini
        $order->update([
            'status' => 'siap',
            'driver_id' => null,
            'catatan' => 'Driver membatalkan: ' . $alasan,
        ]);

        // Hapus data pengiriman
        $order->pengiriman()->delete();

        return redirect()->route('driver.orders')
            ->with('info', 'Pengantaran dibatalkan. Pesanan dikembalikan ke antrian untuk driver lain.');
    }

    public function ratingPelanggan(Request $request, Order $order)
    {
        if ($order->driver_id !== auth()->id()) abort(403);
        if ($order->status !== 'selesai') {
            return back()->with('error', 'Pesanan ini belum selesai.');
        }
        if (!$order->user_id) {
            return back()->with('error', 'Pesanan ini tidak memiliki akun pelanggan terdaftar.');
        }
        if ($order->rating_pelanggan) {
            return back()->with('error', 'Anda sudah memberi rating untuk pelanggan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:255',
        ], [
            'rating.required' => 'Mohon beri rating bintang.',
        ]);

        $order->update([
            'rating_pelanggan' => $request->rating,
            'komentar_pelanggan' => $request->komentar,
        ]);

        $order->user?->tambahRating($request->rating);

        return back()->with('success', 'Terima kasih atas penilaian Anda untuk pelanggan!');
    }

    public function riwayat()
    {
        $pengiriman = Pengiriman::where('driver_id', auth()->id())
            ->with(['order.items', 'order.user'])
            ->latest()->paginate(15);

        $totalKomisi = Pengiriman::where('driver_id', auth()->id())
            ->where('status', 'selesai')->sum('komisi');

        $totalAntar = Pengiriman::where('driver_id', auth()->id())
            ->where('status', 'selesai')->count();

        return view('driver.riwayat', compact('pengiriman', 'totalKomisi', 'totalAntar'));
    }

    public function profil()
    {
        $user = auth()->user();
        return view('driver.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'no_hp' => 'required|string|min:10|max:15',
            'jenis_kendaraan' => 'required|string|max:100',
            'warna_kendaraan' => 'nullable|string|max:50',
            'plat_nomor' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'no_hp.required' => 'No. HP wajib diisi.',
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib diisi.',
            'plat_nomor.required' => 'Plat nomor wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = $request->only(['name', 'no_hp', 'jenis_kendaraan', 'warna_kendaraan', 'plat_nomor']);

        if ($request->hasFile('foto')) {
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/driver-foto'), $filename);
            $data['foto'] = 'images/driver-foto/' . $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
