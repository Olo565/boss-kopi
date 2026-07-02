<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::whereNotNull('user_id')->with(['user', 'items', 'driver']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        $jumlahPending = Order::whereNotNull('user_id')->where('status', 'pending')->count();
        $jumlahDiproses = Order::whereNotNull('user_id')->where('status', 'diproses')->count();
        $jumlahSiap = Order::whereNotNull('user_id')->where('status', 'siap')->count();

        return view('admin.order.index', compact('orders', 'jumlahPending', 'jumlahDiproses', 'jumlahSiap'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.menu', 'driver', 'pengiriman']);
        return view('admin.order.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:dikonfirmasi,diproses,siap,selesai,dibatalkan',
        ], [
            'status.required' => 'Status wajib dipilih.',
        ]);

        $order->update(['status' => $request->status]);

        // Kalau dibatalkan dan ini pesanan delivery dari akun terdaftar
        if ($request->status === 'dibatalkan' && $order->tipe_pesanan === 'delivery' && $order->user_id) {
            $pelanggan = $order->user;
            $jumlahCancel = $pelanggan->jumlah_cancel_delivery + 1;
            $diblokir = $jumlahCancel >= 3; // Blokir setelah 3x cancel

            $pelanggan->update([
                'jumlah_cancel_delivery' => $jumlahCancel,
                'diblokir_delivery' => $diblokir,
            ]);

            if ($diblokir) {
                return back()->with('warning',
                    "Status diubah ke Dibatalkan. ⚠️ Akun {$pelanggan->name} otomatis DIBLOKIR dari delivery karena sudah {$jumlahCancel}x cancel. Admin bisa buka blokir di menu Pengguna."
                );
            }

            $sisaPercobaan = 3 - $jumlahCancel;
            return back()->with('success',
                "Status diubah ke Dibatalkan. Peringatan: {$pelanggan->name} sudah {$jumlahCancel}x cancel delivery. Tersisa {$sisaPercobaan}x sebelum diblokir otomatis."
            );
        }

        $label = $order->getLabelStatus();
        return back()->with('success', "Status pesanan {$order->nomor_struk} diubah menjadi: {$label}");
    }
}
