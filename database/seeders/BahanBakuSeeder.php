<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BahanBaku;

class BahanBakuSeeder extends Seeder
{
    public function run(): void
    {
        $bahanBaku = [
            ['nama' => 'Biji Kopi Arabika', 'satuan' => 'gram', 'stok_saat_ini' => 5000, 'stok_minimum' => 500, 'harga_per_satuan' => 150],
            ['nama' => 'Biji Kopi Robusta', 'satuan' => 'gram', 'stok_saat_ini' => 5000, 'stok_minimum' => 500, 'harga_per_satuan' => 80],
            ['nama' => 'Susu Full Cream', 'satuan' => 'ml', 'stok_saat_ini' => 10000, 'stok_minimum' => 1000, 'harga_per_satuan' => 15],
            ['nama' => 'Gula Pasir', 'satuan' => 'gram', 'stok_saat_ini' => 10000, 'stok_minimum' => 1000, 'harga_per_satuan' => 15],
            ['nama' => 'Teh Celup', 'satuan' => 'pcs', 'stok_saat_ini' => 500, 'stok_minimum' => 50, 'harga_per_satuan' => 500],
            ['nama' => 'Mie Aceh', 'satuan' => 'porsi', 'stok_saat_ini' => 200, 'stok_minimum' => 20, 'harga_per_satuan' => 5000],
            ['nama' => 'Mie Kuning', 'satuan' => 'porsi', 'stok_saat_ini' => 200, 'stok_minimum' => 20, 'harga_per_satuan' => 3000],
            ['nama' => 'Beras', 'satuan' => 'gram', 'stok_saat_ini' => 20000, 'stok_minimum' => 2000, 'harga_per_satuan' => 13],
            ['nama' => 'Ayam', 'satuan' => 'gram', 'stok_saat_ini' => 5000, 'stok_minimum' => 500, 'harga_per_satuan' => 35],
            ['nama' => 'Udang', 'satuan' => 'gram', 'stok_saat_ini' => 3000, 'stok_minimum' => 300, 'harga_per_satuan' => 60],
            ['nama' => 'Cumi', 'satuan' => 'gram', 'stok_saat_ini' => 3000, 'stok_minimum' => 300, 'harga_per_satuan' => 50],
            ['nama' => 'Lele', 'satuan' => 'gram', 'stok_saat_ini' => 5000, 'stok_minimum' => 500, 'harga_per_satuan' => 25],
            ['nama' => 'Minyak Goreng', 'satuan' => 'ml', 'stok_saat_ini' => 10000, 'stok_minimum' => 1000, 'harga_per_satuan' => 14],
            ['nama' => 'Telur Ayam', 'satuan' => 'pcs', 'stok_saat_ini' => 200, 'stok_minimum' => 30, 'harga_per_satuan' => 2500],
            ['nama' => 'Telur Bebek', 'satuan' => 'pcs', 'stok_saat_ini' => 100, 'stok_minimum' => 20, 'harga_per_satuan' => 3500],
            ['nama' => 'Cup Plastik', 'satuan' => 'pcs', 'stok_saat_ini' => 500, 'stok_minimum' => 100, 'harga_per_satuan' => 800],
            ['nama' => 'Sedotan', 'satuan' => 'pcs', 'stok_saat_ini' => 1000, 'stok_minimum' => 200, 'harga_per_satuan' => 200],
            ['nama' => 'Kantong Plastik', 'satuan' => 'pcs', 'stok_saat_ini' => 500, 'stok_minimum' => 100, 'harga_per_satuan' => 300],
            ['nama' => 'Roti Tawar', 'satuan' => 'lembar', 'stok_saat_ini' => 100, 'stok_minimum' => 20, 'harga_per_satuan' => 1500],
            ['nama' => 'Jahe', 'satuan' => 'gram', 'stok_saat_ini' => 2000, 'stok_minimum' => 200, 'harga_per_satuan' => 25],
        ];

        foreach ($bahanBaku as $b) {
            BahanBaku::create($b);
        }
    }
}
