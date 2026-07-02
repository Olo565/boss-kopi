<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        Promo::create([
            'nama' => 'Diskon Grand Opening 10%',
            'kode_kupon' => 'BOSSKOPI10',
            'tipe' => 'persentase',
            'nilai_diskon' => 10,
            'min_transaksi' => 20000,
            'max_penggunaan' => 100,
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(30),
            'is_active' => true,
            'deskripsi' => 'Diskon 10% untuk semua menu dalam rangka grand opening',
        ]);

        Promo::create([
            'nama' => 'Happy Hour Sore',
            'kode_kupon' => 'HAPPYHOUR',
            'tipe' => 'nominal',
            'nilai_diskon' => 5000,
            'min_transaksi' => 30000,
            'max_penggunaan' => null,
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(60),
            'is_active' => true,
            'deskripsi' => 'Potongan Rp 5.000 setiap hari pukul 15.00 - 17.00',
        ]);
    }
}
