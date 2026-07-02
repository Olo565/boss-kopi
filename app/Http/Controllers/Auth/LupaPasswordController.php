<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LupaPasswordController extends Controller
{
    // Langkah 1: Form input email
    public function showForm()
    {
        return view('auth.lupa-password');
    }

    // Langkah 2: Generate kode reset, tampilkan ke pengguna
    public function kirimKode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar di sistem.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate kode 6 digit
        $kode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update(['kode_verifikasi_wa' => $kode]);

        // Format nomor WA
        $noHp = preg_replace('/[^0-9]/', '', $user->no_hp);
        if (str_starts_with($noHp, '0')) {
            $noHp = '62' . substr($noHp, 1);
        }

        $pesan = urlencode(
            "Halo " . $user->name . "! 👋\n\n" .
            "Anda meminta reset kata sandi untuk akun BOSS KOPI.\n\n" .
            "🔑 Kode Reset: *" . $kode . "*\n\n" .
            "Masukkan kode ini di halaman reset kata sandi.\n" .
            "Jika bukan Anda yang meminta, abaikan pesan ini.\n\n" .
            "BOSS KOPI Sunggal ☕"
        );

        $urlWA = "https://wa.me/{$noHp}?text={$pesan}";

        // Simpan email di session untuk langkah berikutnya
        session(['reset_email' => $request->email, 'reset_wa_url' => $urlWA, 'reset_nama' => $user->name]);

        return redirect()->route('lupa-password.verifikasi');
    }

    // Langkah 3: Tampilkan halaman input kode + form password baru
    public function showVerifikasi()
    {
        if (!session('reset_email')) {
            return redirect()->route('lupa-password.form');
        }
        return view('auth.lupa-password-verifikasi');
    }

    // Langkah 4: Verifikasi kode dan set password baru
    public function resetPassword(Request $request)
    {
        if (!session('reset_email')) {
            return redirect()->route('lupa-password.form');
        }

        $request->validate([
            'kode' => 'required|string|size:6',
            'password' => 'required|min:6|confirmed',
        ], [
            'kode.required' => 'Kode verifikasi wajib diisi.',
            'kode.size' => 'Kode harus 6 digit.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (!$user || $user->kode_verifikasi_wa !== $request->kode) {
            return back()->withErrors(['kode' => 'Kode verifikasi salah. Periksa kembali kode yang dikirim ke WA Anda.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'kode_verifikasi_wa' => null,
        ]);

        session()->forget(['reset_email', 'reset_wa_url', 'reset_nama']);

        return redirect()->route('login')
            ->with('success', 'Kata sandi berhasil direset! Silakan login dengan kata sandi baru Anda.');
    }
}
