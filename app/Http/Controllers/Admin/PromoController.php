<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::latest()->paginate(15);
        return view('admin.promo.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promo.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode_kupon' => 'nullable|string|max:50|unique:promos,kode_kupon',
            'tipe' => 'required|in:persentase,nominal,buy1get1,paket',
            'nilai_diskon' => 'required|numeric|min:0',
            'min_transaksi' => 'nullable|numeric|min:0',
            'max_penggunaan' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required' => 'Nama promo wajib diisi.',
            'kode_kupon.unique' => 'Kode kupon sudah digunakan.',
            'tipe.required' => 'Tipe promo wajib dipilih.',
            'nilai_diskon.required' => 'Nilai diskon wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sudah_digunakan'] = 0;
        Promo::create($validated);

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil dibuat.');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promo.form', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode_kupon' => 'nullable|string|max:50|unique:promos,kode_kupon,' . $promo->id,
            'tipe' => 'required|in:persentase,nominal,buy1get1,paket',
            'nilai_diskon' => 'required|numeric|min:0',
            'min_transaksi' => 'nullable|numeric|min:0',
            'max_penggunaan' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required' => 'Nama promo wajib diisi.',
            'tipe.required' => 'Tipe promo wajib dipilih.',
            'nilai_diskon.required' => 'Nilai diskon wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $promo->update($validated);

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil dihapus.');
    }
}
