<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Pengaduan, Order};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->role) $query->where('role', $request->role);
        if ($request->search) $query->where('name', 'like', '%' . $request->search . '%');
        $users = $query->latest()->paginate(15);

        // Data notifikasi yang perlu ditindaklanjuti
        $driverMenunggu = User::where('role', 'driver')
            ->whereIn('status_akun', ['menunggu', 'dipanggil'])->get();

        $pembeliBeluVerifikasi = User::where('role', 'pembeli')
            ->where('no_hp_terverifikasi', false)->latest()->limit(10)->get();

        $pesananPending = \App\Models\Order::whereNotNull('user_id')
            ->where('status', 'pending')->with('user')->latest()->limit(5)->get();

        $pengaduanBaru = \App\Models\Pengaduan::where('status', 'baru')
            ->with('user')->latest()->limit(5)->get();

        return view('admin.user.index', compact(
            'users', 'driverMenunggu', 'pembeliBeluVerifikasi',
            'pesananPending', 'pengaduanBaru'
        ));
    }

    public function create()
    {
        return view('admin.user.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|max:15',
            'role' => 'required|in:admin,kasir,pembeli,driver',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'role.required' => 'Role wajib dipilih.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.user.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'required|string|max:15',
            'role' => 'required|in:admin,kasir,pembeli,driver',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.user.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Hapus data terkait dulu sebelum hapus user
        // Hapus poin histories
        \App\Models\PoinHistory::where('user_id', $user->id)->delete();

        // Hapus pengaduan
        \App\Models\Pengaduan::where('user_id', $user->id)->delete();

        // Untuk orders: lepas relasi (set null) daripada hapus riwayat transaksi
        \App\Models\Order::where('user_id', $user->id)->update(['user_id' => null]);
        \App\Models\Order::where('driver_id', $user->id)->update(['driver_id' => null]);

        // Hapus pengiriman milik driver ini
        \App\Models\Pengiriman::where('driver_id', $user->id)->delete();

        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function bukaBlokir(User $user)
    {
        $user->update([
            'diblokir_delivery' => false,
            'jumlah_cancel_delivery' => 0,
        ]);
        return back()->with('success', "Blokir delivery {$user->name} berhasil dibuka. Counter cancel direset ke 0.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password_baru' => ['required', 'min:6', 'confirmed'],
        ], [
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password minimal 6 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', "Password {$user->name} berhasil direset.");
    }

    public function formatBerkasDriver()
    {
        return view('admin.user.format-berkas-driver');
    }

    public function driverMenunggu()
    {
        $drivers = User::where('role', 'driver')
            ->whereIn('status_akun', ['menunggu', 'dipanggil'])
            ->latest()->get();
        $jumlahMenunggu = User::where('role', 'driver')->where('status_akun', 'menunggu')->count();
        return view('admin.user.driver-menunggu', compact('drivers', 'jumlahMenunggu'));
    }

    public function panggilDriver(User $user)
    {
        $user->update(['status_akun' => 'dipanggil']);

        $noHp = preg_replace('/[^0-9]/', '', $user->no_hp);
        if (str_starts_with($noHp, '0')) $noHp = '62' . substr($noHp, 1);

        $pesan = urlencode(
            "Halo " . $user->name . "! 👋\n\n" .
            "Pendaftaran Driver BOSS KOPI Anda sedang diproses.\n\n" .
            "📋 Mohon datang ke kedai kami untuk melengkapi berkas:\n" .
            "*BOSS KOPI — Jl. Pinang Baris Elok No.37, Sunggal, Medan*\n\n" .
            "📄 Berkas yang perlu dibawa:\n" .
            "1. KTP asli + fotocopy\n" .
            "2. SIM C asli + fotocopy\n" .
            "3. STNK kendaraan asli + fotocopy\n" .
            "4. Pas foto 3x4 (2 lembar)\n" .
            "5. Foto kendaraan (tampak depan & samping)\n\n" .
            "Setelah berkas lengkap, akun Anda akan langsung diaktifkan dan " .
            "Anda akan mendapat jaket & helm BOSS KOPI.\n\n" .
            "Hubungi kami jika ada pertanyaan. Terima kasih! ☕"
        );

        $urlWA = "https://wa.me/{$noHp}?text={$pesan}";
        return redirect()->away($urlWA);
    }

    public function approveDriver(Request $request, User $user)
    {
        $request->validate(['aksi' => 'required|in:aktifkan,tolak']);

        if ($request->aksi === 'aktifkan') {
            $user->update(['status_akun' => 'aktif', 'is_active' => true]);
            return back()->with('success', "Driver {$user->name} berhasil diaktifkan dan sudah bisa login.");
        } else {
            $user->update(['status_akun' => 'ditolak', 'is_active' => false]);
            return back()->with('success', "Pendaftaran Driver {$user->name} ditolak.");
        }
    }
}
