<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PengaturanController extends Controller
{
    public function index()
    {
        $grupKedai = Pengaturan::where('grup', 'kedai')->get();
        $grupOrder = Pengaturan::where('grup', 'order')->get();
        $grupTampilan = Pengaturan::where('grup', 'tampilan')->get();
        $grupFitur = Pengaturan::where('grup', 'fitur')->get();

        return view('admin.pengaturan.index', compact(
            'grupKedai', 'grupOrder', 'grupTampilan', 'grupFitur'
        ));
    }

    public function update(Request $request)
    {
        $settings = Pengaturan::all();

        foreach ($settings as $setting) {
            $key = $setting->kunci;

            if ($setting->tipe === 'image') {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $filename = $key . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/pengaturan'), $filename);
                    Pengaturan::set($key, 'images/pengaturan/' . $filename);
                }
            } elseif ($setting->tipe === 'boolean') {
                Pengaturan::set($key, $request->has($key) ? '1' : '0');
            } else {
                if ($request->has($key)) {
                    Pengaturan::set($key, $request->input($key));
                }
            }
        }

        // Clear semua cache pengaturan
        Pengaturan::clearCache();

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
