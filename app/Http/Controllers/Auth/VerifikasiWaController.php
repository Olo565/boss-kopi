<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerifikasiWaController extends Controller
{
    // Tampilkan halaman input kode verifikasi
    public function show()
    {
        $user = auth()->user();
        if ($user->no_hp_terverifikasi) {
            return redirect()->route('pembeli.home')->with('info', 'Nomor HP Anda sudah terverifikasi.');
        }
        return view('auth.verifikasi-wa', compact('user'));
    }

    // Proses kode verifikasi yang diinput pelanggan
    public function verify(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|size:6',
        ], [
            'kode.required' => 'Kode verifikasi wajib diisi.',
            'kode.size' => 'Kode verifikasi harus 6 digit.',
        ]);

        $user = auth()->user();

        if ($user->kode_verifikasi_wa !== $request->kode) {
            return back()->withErrors(['kode' => 'Kode verifikasi salah. Pastikan kode yang Anda masukkan benar.']);
        }

        $user->update([
            'no_hp_terverifikasi' => true,
            'kode_verifikasi_wa' => null,
        ]);

        return redirect()->route('pembeli.home')
            ->with('success', 'Nomor WA berhasil diverifikasi! Sekarang Anda bisa memesan delivery.');
    }

    // Admin generate kode - tampilkan kode dulu sebelum buka WA
    public function generateKode(User $user)
    {
        $kode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'kode_verifikasi_wa' => $kode,
            'no_hp_terverifikasi' => false,
        ]);

        // Redirect balik ke halaman pengguna dengan kode yang tampil
        return redirect()->route('admin.user.index')
            ->with('kode_verifikasi', $kode)
            ->with('nama_pelanggan', $user->name)
            ->with('no_hp_pelanggan', $user->no_hp)
            ->with('user_id_verifikasi', $user->id);
    }
}
