<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Menu, KategoriMenu};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('kategori');
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->kategori) {
            $query->where('kategori_menu_id', $request->kategori);
        }
        $menus = $query->latest()->paginate(15);
        $kategoris = KategoriMenu::all();
        return view('admin.menu.index', compact('menus', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriMenu::where('is_active', true)->get();
        return view('admin.menu.form', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_menu_id' => 'required|exists:kategori_menus,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'varian' => 'nullable|string|max:255',
            'harga_dine_in' => 'required|numeric|min:0',
            'harga_takeaway' => 'required|numeric|min:0',
            'harga_delivery' => 'required|numeric|min:0',
            'harga_pokok' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'kategori_menu_id.required' => 'Kategori wajib dipilih.',
            'nama.required' => 'Nama menu wajib diisi.',
            'harga_dine_in.required' => 'Harga dine-in wajib diisi.',
            'harga_takeaway.required' => 'Harga takeaway wajib diisi.',
            'harga_delivery.required' => 'Harga delivery wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/menus'), $filename);
            $validated['foto'] = 'images/menus/' . $filename;
        }

        $validated['tersedia'] = $request->has('tersedia');
        Menu::create($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $kategoris = KategoriMenu::where('is_active', true)->get();
        return view('admin.menu.form', compact('menu', 'kategoris'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'kategori_menu_id' => 'required|exists:kategori_menus,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'varian' => 'nullable|string|max:255',
            'harga_dine_in' => 'required|numeric|min:0',
            'harga_takeaway' => 'required|numeric|min:0',
            'harga_delivery' => 'required|numeric|min:0',
            'harga_pokok' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'kategori_menu_id.required' => 'Kategori wajib dipilih.',
            'nama.required' => 'Nama menu wajib diisi.',
            'harga_dine_in.required' => 'Harga dine-in wajib diisi.',
            'harga_takeaway.required' => 'Harga takeaway wajib diisi.',
            'harga_delivery.required' => 'Harga delivery wajib diisi.',
        ]);

        if ($request->hasFile('foto')) {
            if ($menu->foto && file_exists(public_path($menu->foto))) {
                unlink(public_path($menu->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/menus'), $filename);
            $validated['foto'] = 'images/menus/' . $filename;
        }

        $validated['tersedia'] = $request->has('tersedia');
        $menu->update($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->foto) Storage::disk('public')->delete($menu->foto);
        $menu->delete();
        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    public function toggleTersedia(Menu $menu)
    {
        $menu->update(['tersedia' => !$menu->tersedia]);
        $status = $menu->tersedia ? 'tersedia' : 'tidak tersedia';
        return back()->with('success', "Menu {$menu->nama} sekarang {$status}.");
    }
}
