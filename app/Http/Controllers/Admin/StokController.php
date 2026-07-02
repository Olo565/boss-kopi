<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{BahanBaku, StokHistory};
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanBaku::query();
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filter === 'kritis') {
            $query->whereRaw('stok_saat_ini <= stok_minimum');
        }
        $bahanBaku = $query->latest()->paginate(15);
        $stokKritisCount = BahanBaku::whereRaw('stok_saat_ini <= stok_minimum')->count();
        return view('admin.stok.index', compact('bahanBaku', 'stokKritisCount'));
    }

    public function create()
    {
        return view('admin.stok.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok_saat_ini' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_per_satuan' => 'required|numeric|min:0',
        ], [
            'nama.required' => 'Nama bahan baku wajib diisi.',
            'satuan.required' => 'Satuan wajib diisi.',
            'stok_saat_ini.required' => 'Stok awal wajib diisi.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
            'harga_per_satuan.required' => 'Harga per satuan wajib diisi.',
        ]);

        BahanBaku::create($validated);
        return redirect()->route('admin.stok.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(BahanBaku $stok)
    {
        return view('admin.stok.form', ['bahan' => $stok]);
    }

    public function update(Request $request, BahanBaku $stok)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_per_satuan' => 'required|numeric|min:0',
        ]);
        $stok->update($request->only(['nama', 'satuan', 'stok_minimum', 'harga_per_satuan']));
        return redirect()->route('admin.stok.index')->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function restock(Request $request, BahanBaku $stok)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string',
        ], [
            'jumlah.required' => 'Jumlah restock wajib diisi.',
            'jumlah.min' => 'Jumlah harus lebih dari 0.',
        ]);

        $sebelum = $stok->stok_saat_ini;
        $sesudah = $sebelum + $request->jumlah;

        $stok->update(['stok_saat_ini' => $sesudah]);

        StokHistory::create([
            'bahan_baku_id' => $stok->id,
            'tipe' => 'masuk',
            'jumlah' => $request->jumlah,
            'stok_sebelum' => $sebelum,
            'stok_sesudah' => $sesudah,
            'keterangan' => $request->keterangan ?? 'Restock',
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', "Restock {$stok->nama} berhasil. Stok sekarang: {$sesudah} {$stok->satuan}");
    }

    public function history(BahanBaku $stok)
    {
        $histories = StokHistory::where('bahan_baku_id', $stok->id)
            ->with('user')->latest()->paginate(20);
        return view('admin.stok.history', compact('stok', 'histories'));
    }
}
