<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Rate limiting — maks 5 percobaan per menit per IP+email
        $throttleKey = Str::lower($request->email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors(['email' =>
                "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."
            ])->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->withInput();
        }

        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi admin.'])->withInput();
        }

        if (isset($user->status_akun) && $user->status_akun === 'menunggu') {
            return back()->withErrors(['email' =>
                'Akun Driver Anda sedang menunggu verifikasi Admin BOSS KOPI. ' .
                'Kami akan menghubungi Anda via WhatsApp ke nomor ' . $user->no_hp . ' untuk jadwal kelengkapan berkas.'
            ])->withInput();
        }

        if (isset($user->status_akun) && $user->status_akun === 'dipanggil') {
            return back()->withErrors(['email' =>
                'Admin sudah menghubungi Anda untuk kelengkapan berkas. ' .
                'Silakan datang ke kedai BOSS KOPI dengan membawa berkas yang diperlukan. ' .
                'Akun akan diaktifkan setelah berkas lengkap.'
            ])->withInput();
        }

        if (isset($user->status_akun) && $user->status_akun === 'ditolak') {
            return back()->withErrors(['email' =>
                'Maaf, pendaftaran Driver Anda belum dapat disetujui. ' .
                'Silakan hubungi Admin BOSS KOPI untuk informasi lebih lanjut.'
            ])->withInput();
        }

        if (!Auth::attempt($request->only('email', 'password'), $request->remember)) {
            RateLimiter::hit($throttleKey, 60);
            return back()->withErrors(['password' => 'Kata sandi salah.'])->withInput();
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        return $this->redirectByRole(Auth::user()->role);
    }

    public function showRegister()
    {
        return view('auth.login', ['mode' => 'register']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|min:10|max:15',
            'role' => 'required|in:pembeli,driver',
            'alamat' => 'required_if:role,pembeli|nullable|string|max:255',
            'jenis_kendaraan' => 'required_if:role,driver|nullable|string|max:100',
            'plat_nomor' => 'required_if:role,driver|nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.min' => 'Nomor HP minimal 10 digit.',
            'role.required' => 'Silakan pilih jenis akun.',
            'role.in' => 'Jenis akun tidak valid.',
            'alamat.required_if' => 'Alamat wajib diisi untuk akun Pembeli.',
            'jenis_kendaraan.required_if' => 'Jenis kendaraan wajib diisi untuk akun Driver.',
            'plat_nomor.required_if' => 'Plat nomor wajib diisi untuk akun Driver.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'alamat' => $request->role === 'pembeli' ? $request->alamat : null,
            'jenis_kendaraan' => $request->role === 'driver' ? $request->jenis_kendaraan : null,
            'plat_nomor' => $request->role === 'driver' ? $request->plat_nomor : null,
            'status_akun' => $request->role === 'driver' ? 'menunggu' : 'aktif',
        ]);

        if ($request->role === 'driver') {
            // Driver belum bisa login langsung, harus tunggu persetujuan Admin
            return redirect()->route('login')->with('info',
                'Pendaftaran Driver berhasil! Akun Anda sedang menunggu persetujuan Admin. Kami akan mengabari Anda setelah disetujui.'
            );
        }

        Auth::login($user);

        if ($user->role === 'driver') {
            return redirect()->route('driver.orders')->with('success', 'Selamat datang di BOSS KOPI, ' . $user->name . '! Akun Driver Anda siap digunakan.');
        }

        return redirect()->route('pembeli.home')->with('success', 'Selamat datang di BOSS KOPI, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }

    private function redirectByRole(string $role)
    {
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kasir' => redirect()->route('kasir.pos'),
            'driver' => redirect()->route('driver.orders'),
            default => redirect()->route('pembeli.home'),
        };
    }
}
