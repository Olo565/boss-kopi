<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function bukaForm()
    {
        $shiftAktif = Shift::where('kasir_id', auth()->id())
            ->where('status', 'buka')->latest()->first();

        if ($shiftAktif) {
            return redirect()->route('kasir.pos')
                ->with('info', 'Shift sudah dibuka sejak ' . $shiftAktif->waktu_buka->format('H:i'));
        }

        return view('kasir.shift.buka');
    }

    public function buka(Request $request)
    {
        $request->validate([
            'modal_awal' => 'required|numeric|min:0',
        ], [
            'modal_awal.required' => 'Modal awal wajib diisi.',
            'modal_awal.min' => 'Modal awal tidak boleh negatif.',
        ]);

        Shift::create([
            'kasir_id' => auth()->id(),
            'modal_awal' => $request->modal_awal,
            'waktu_buka' => now(),
            'status' => 'buka',
        ]);

        return redirect()->route('kasir.pos')
            ->with('success', 'Shift berhasil dibuka. Selamat bekerja!');
    }

    public function tutupForm()
    {
        $shift = Shift::where('kasir_id', auth()->id())
            ->where('status', 'buka')->latest()->firstOrFail();

        $shift->load(['orders' => function ($q) {
            $q->where('status', 'selesai');
        }]);

        return view('kasir.shift.tutup', compact('shift'));
    }

    public function tutup(Request $request)
    {
        $request->validate([
            'uang_kas_akhir' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
        ], [
            'uang_kas_akhir.required' => 'Jumlah uang kas akhir wajib diisi.',
        ]);

        $shift = Shift::where('kasir_id', auth()->id())
            ->where('status', 'buka')->latest()->firstOrFail();

        $shift->update([
            'uang_kas_akhir' => $request->uang_kas_akhir,
            'waktu_tutup' => now(),
            'status' => 'tutup',
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('kasir.shift.ringkasan', $shift->id)
            ->with('success', 'Shift berhasil ditutup.');
    }

    public function ringkasan(Shift $shift)
    {
        if ($shift->kasir_id !== auth()->id()) {
            abort(403);
        }
        $shift->load(['orders.items', 'kasir']);
        return view('kasir.shift.ringkasan', compact('shift'));
    }
}
