<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with('user', 'order');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $pengaduans = $query->latest()->paginate(15);
        $jumlahBaru = Pengaduan::where('status', 'baru')->count();

        return view('admin.pengaduan.index', compact('pengaduans', 'jumlahBaru'));
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load('user', 'order');
        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai',
            'balasan_admin' => 'nullable|string|max:1000',
        ], [
            'status.required' => 'Status wajib dipilih.',
        ]);

        $pengaduan->update($request->only(['status', 'balasan_admin']));

        return back()->with('success', 'Pengaduan berhasil diperbarui.');
    }
}
