<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\{Ulasan, Menu, Order};
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ], [
            'rating.required' => 'Mohon beri rating bintang.',
        ]);

        // Cek apakah sudah pernah ulasan menu ini dari order yang sama
        $sudahUlasan = Ulasan::where('user_id', auth()->id())
            ->where('menu_id', $request->menu_id)
            ->when($request->order_id, fn($q) => $q->where('order_id', $request->order_id))
            ->exists();

        if ($sudahUlasan) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk menu ini.');
        }

        Ulasan::create([
            'user_id' => auth()->id(),
            'menu_id' => $request->menu_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }

    public function destroy(Ulasan $ulasan)
    {
        if ($ulasan->user_id !== auth()->id()) abort(403);
        $ulasan->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
