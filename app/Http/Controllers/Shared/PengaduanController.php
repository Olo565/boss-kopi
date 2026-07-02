<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\{Pengaduan, Order};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::where('user_id', auth()->id())->latest()->paginate(10);
        return view('pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        $orders = Order::where('user_id', auth()->id())
            ->orWhere('driver_id', auth()->id())
            ->latest()->limit(20)->get();

        return view('pengaduan.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:100',
            'judul' => 'required|string|max:150',
            'isi' => 'required|string|max:1000',
            'order_id' => 'nullable|exists:orders,id',
            'foto_lampiran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'kategori.required' => 'Kategori pengaduan wajib dipilih.',
            'judul.required' => 'Judul pengaduan wajib diisi.',
            'isi.required' => 'Isi pengaduan wajib diisi.',
            'foto_lampiran.image' => 'Lampiran harus berupa gambar.',
            'foto_lampiran.max' => 'Ukuran lampiran maksimal 2MB.',
        ]);

        $data = $request->only(['kategori', 'judul', 'isi', 'order_id']);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('foto_lampiran')) {
            $file = $request->file('foto_lampiran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/pengaduan'), $filename);
            $data['foto_lampiran'] = 'images/pengaduan/' . $filename;
        }

        Pengaduan::create($data);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan Anda berhasil dikirim. Tim kami akan segera menindaklanjuti.');
    }

    public function show(Pengaduan $pengaduan)
    {
        if ($pengaduan->user_id !== auth()->id()) abort(403);
        return view('pengaduan.show', compact('pengaduan'));
    }
}
