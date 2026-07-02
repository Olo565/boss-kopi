<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriMenu;

class KategoriMenuSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Mie Aceh', 'jenis' => 'makanan', 'ikon' => 'fa-bowl-food'],
            ['nama' => 'Mie Hun', 'jenis' => 'makanan', 'ikon' => 'fa-bowl-food'],
            ['nama' => 'Mie Tiaw', 'jenis' => 'makanan', 'ikon' => 'fa-bowl-food'],
            ['nama' => 'Indomie', 'jenis' => 'makanan', 'ikon' => 'fa-bowl-food'],
            ['nama' => 'Ifomie', 'jenis' => 'makanan', 'ikon' => 'fa-bowl-food'],
            ['nama' => 'Nasi Goreng', 'jenis' => 'makanan', 'ikon' => 'fa-bowl-rice'],
            ['nama' => 'Ayam & Lele', 'jenis' => 'makanan', 'ikon' => 'fa-drumstick-bite'],
            ['nama' => 'Seafood Olahan', 'jenis' => 'makanan', 'ikon' => 'fa-fish'],
            ['nama' => 'Cemilan', 'jenis' => 'cemilan', 'ikon' => 'fa-cookie-bite'],
            ['nama' => 'Kopi & Teh', 'jenis' => 'minuman', 'ikon' => 'fa-mug-hot'],
            ['nama' => 'Bandrek & TST', 'jenis' => 'minuman', 'ikon' => 'fa-mug-hot'],
            ['nama' => 'Botol & Soda', 'jenis' => 'minuman', 'ikon' => 'fa-bottle-water'],
            ['nama' => 'Sachet', 'jenis' => 'minuman', 'ikon' => 'fa-glass-water'],
            ['nama' => 'Aneka Jus', 'jenis' => 'minuman', 'ikon' => 'fa-blender'],
            ['nama' => 'Paket Promo', 'jenis' => 'paket', 'ikon' => 'fa-tags'],
        ];

        foreach ($kategoris as $k) {
            KategoriMenu::create($k);
        }
    }
}
