<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\KategoriMenu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // MIE ACEH (kategori_id = 1)
            ['kategori' => 'Mie Aceh', 'nama' => 'Mie Aceh', 'varian' => 'Basah / Goreng / Kuah', 'harga' => 15000],
            ['kategori' => 'Mie Aceh', 'nama' => 'Mie Aceh Jamur', 'varian' => 'Basah / Goreng / Kuah', 'harga' => 20000],
            ['kategori' => 'Mie Aceh', 'nama' => 'Mie Aceh Daging Spesial', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 28000],
            ['kategori' => 'Mie Aceh', 'nama' => 'Mie Aceh Cumi', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 28000],
            ['kategori' => 'Mie Aceh', 'nama' => 'Mie Aceh Udang', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 28000],
            ['kategori' => 'Mie Aceh', 'nama' => 'Mie Aceh Seafood', 'varian' => 'Basah / Goreng / Kuah', 'harga' => 30000],

            // MIE HUN (kategori_id = 2)
            ['kategori' => 'Mie Hun', 'nama' => 'Mie Hun Biasa', 'varian' => 'Basah / Goreng / Kuah', 'harga' => 15000],
            ['kategori' => 'Mie Hun', 'nama' => 'Mie Hun Jamur', 'varian' => 'Basah / Goreng / Kuah', 'harga' => 20000],
            ['kategori' => 'Mie Hun', 'nama' => 'Mie Hun Udang', 'varian' => 'Goreng / Basah', 'harga' => 28000],
            ['kategori' => 'Mie Hun', 'nama' => 'Mie Hun Daging', 'varian' => 'Goreng / Basah', 'harga' => 28000],
            ['kategori' => 'Mie Hun', 'nama' => 'Mie Hun Cumi', 'varian' => 'Basah / Goreng', 'harga' => 28000],
            ['kategori' => 'Mie Hun', 'nama' => 'Mie Hun Seafood', 'varian' => 'Goreng / Basah', 'harga' => 30000],

            // MIE TIAW (kategori_id = 3)
            ['kategori' => 'Mie Tiaw', 'nama' => 'Mie Tiaw Biasa', 'varian' => 'Goreng / Basah', 'harga' => 15000],
            ['kategori' => 'Mie Tiaw', 'nama' => 'Mie Tiaw Jamur', 'varian' => '-', 'harga' => 20000],
            ['kategori' => 'Mie Tiaw', 'nama' => 'Mie Tiaw Cumi', 'varian' => 'Basah / Goreng', 'harga' => 28000],
            ['kategori' => 'Mie Tiaw', 'nama' => 'Mie Tiaw Udang', 'varian' => 'Basah / Goreng', 'harga' => 28000],
            ['kategori' => 'Mie Tiaw', 'nama' => 'Mie Tiaw Daging', 'varian' => 'Basah / Goreng', 'harga' => 28000],
            ['kategori' => 'Mie Tiaw', 'nama' => 'Mie Tiaw Seafood', 'varian' => 'Basah / Goreng', 'harga' => 30000],

            // INDOMIE (kategori_id = 4)
            ['kategori' => 'Indomie', 'nama' => 'Indomie Biasa', 'varian' => 'Kuah / Basah / Goreng', 'harga' => 15000],
            ['kategori' => 'Indomie', 'nama' => 'Indomie Kuah', 'varian' => '-', 'harga' => 13000],
            ['kategori' => 'Indomie', 'nama' => 'Indomie Bangladesh', 'varian' => '-', 'harga' => 15000],
            ['kategori' => 'Indomie', 'nama' => 'Martabak Indomie', 'varian' => '-', 'harga' => 19000],
            ['kategori' => 'Indomie', 'nama' => 'Indomie Jamur', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 20000],
            ['kategori' => 'Indomie', 'nama' => 'Indomie Daging Spesial', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 28000],
            ['kategori' => 'Indomie', 'nama' => 'Indomie Cumi', 'varian' => 'Basah / Goreng / Kuah', 'harga' => 28000],
            ['kategori' => 'Indomie', 'nama' => 'Indomie Udang', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 28000],
            ['kategori' => 'Indomie', 'nama' => 'Indomie Seafood', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 28000],

            // IFOMIE (kategori_id = 5)
            ['kategori' => 'Ifomie', 'nama' => 'Ifomie', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 15000],
            ['kategori' => 'Ifomie', 'nama' => 'Ifomie Jamur', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 20000],
            ['kategori' => 'Ifomie', 'nama' => 'Ifomie Udang', 'varian' => 'Goreng / Basah / Kuah', 'harga' => 28000],
            ['kategori' => 'Ifomie', 'nama' => 'Ifomie Cumi', 'varian' => 'Kuah / Goreng / Basah', 'harga' => 28000],
            ['kategori' => 'Ifomie', 'nama' => 'Ifomie Daging', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 28000],
            ['kategori' => 'Ifomie', 'nama' => 'Ifomie Seafood', 'varian' => 'Basah / Kuah / Goreng', 'harga' => 30000],

            // NASI GORENG (kategori_id = 6)
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Kampung', 'varian' => '-', 'harga' => 15000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Biasa', 'varian' => '-', 'harga' => 15000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Ayam', 'varian' => '-', 'harga' => 23000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Kampung Ayam', 'varian' => '-', 'harga' => 25000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Udang', 'varian' => '-', 'harga' => 28000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Ayam Penyet', 'varian' => '-', 'harga' => 28000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Cumi', 'varian' => '-', 'harga' => 28000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Kampung Ayam Penyet', 'varian' => '-', 'harga' => 29000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Kampung Cumi', 'varian' => '-', 'harga' => 29000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Kampung Daging', 'varian' => '-', 'harga' => 29000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Kampung Udang', 'varian' => '-', 'harga' => 29000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Seafood', 'varian' => '-', 'harga' => 30000],
            ['kategori' => 'Nasi Goreng', 'nama' => 'Nasi Goreng Kampung Seafood', 'varian' => '-', 'harga' => 30000],

            // AYAM & LELE (kategori_id = 7)
            ['kategori' => 'Ayam & Lele', 'nama' => 'Pecal Lele + Nasi', 'varian' => 'Sambal khas', 'harga' => 20000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Lele Lada Hitam + Nasi', 'varian' => '-', 'harga' => 20000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Ayam Geprek + Nasi', 'varian' => '-', 'harga' => 20000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Lele Asam Manis + Nasi', 'varian' => '-', 'harga' => 22000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Lele Pedas Manis + Nasi', 'varian' => '-', 'harga' => 22000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Lele Saus Tiram + Nasi', 'varian' => '-', 'harga' => 22000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Lele Sambal Ijo + Nasi', 'varian' => '-', 'harga' => 22000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Ayam Penyet + Nasi', 'varian' => 'Sambal khas', 'harga' => 25000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Ayam Saus Tiram + Nasi', 'varian' => '-', 'harga' => 28000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Ayam Asam Manis + Nasi', 'varian' => '-', 'harga' => 28000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Ayam Sambal Ijo + Nasi', 'varian' => '-', 'harga' => 28000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Ayam Pedas Manis + Nasi', 'varian' => '-', 'harga' => 28000],
            ['kategori' => 'Ayam & Lele', 'nama' => 'Ayam Lada Hitam + Nasi', 'varian' => '-', 'harga' => 28000],

            // SEAFOOD OLAHAN (kategori_id = 8)
            ['kategori' => 'Seafood Olahan', 'nama' => 'Cumi Asam Manis + Nasi', 'varian' => '-', 'harga' => 27000],
            ['kategori' => 'Seafood Olahan', 'nama' => 'Cumi Goreng Tepung + Nasi', 'varian' => '-', 'harga' => 27000],
            ['kategori' => 'Seafood Olahan', 'nama' => 'Udang Goreng Tepung + Nasi', 'varian' => '-', 'harga' => 27000],
            ['kategori' => 'Seafood Olahan', 'nama' => 'Cumi Pedas Manis + Nasi', 'varian' => '-', 'harga' => 30000],
            ['kategori' => 'Seafood Olahan', 'nama' => 'Cumi Lada Hitam + Nasi', 'varian' => '-', 'harga' => 30000],
            ['kategori' => 'Seafood Olahan', 'nama' => 'Udang Pedas Manis + Nasi', 'varian' => '-', 'harga' => 30000],
            ['kategori' => 'Seafood Olahan', 'nama' => 'Udang Asam Manis + Nasi', 'varian' => '-', 'harga' => 30000],
            ['kategori' => 'Seafood Olahan', 'nama' => 'Udang Lada Hitam + Nasi', 'varian' => '-', 'harga' => 30000],

            // CEMILAN (kategori_id = 9)
            ['kategori' => 'Cemilan', 'nama' => 'Roti Bakar', 'varian' => 'Coklat/Srikaya/Pandan/Stroberi', 'harga' => 7000],
            ['kategori' => 'Cemilan', 'nama' => 'Roti Cane', 'varian' => 'Gula atau Susu Coklat', 'harga' => 10000],
            ['kategori' => 'Cemilan', 'nama' => 'Kentang Goreng', 'varian' => '-', 'harga' => 12000],
            ['kategori' => 'Cemilan', 'nama' => 'Martabak Telor Ayam', 'varian' => '-', 'harga' => 13000],
            ['kategori' => 'Cemilan', 'nama' => 'Burger', 'varian' => '-', 'harga' => 13000],
            ['kategori' => 'Cemilan', 'nama' => 'Martabak Telor Bebek', 'varian' => '-', 'harga' => 15000],
            ['kategori' => 'Cemilan', 'nama' => 'Roti Bandung', 'varian' => 'Coklat/Srikaya/Pandan/Stroberi', 'harga' => 20000],
            ['kategori' => 'Cemilan', 'nama' => 'Roti Bandung Remix', 'varian' => 'Kombinasi 2 Rasa', 'harga' => 23000],

            // KOPI & TEH (kategori_id = 10)
            ['kategori' => 'Kopi & Teh', 'nama' => 'Teh Manis', 'varian' => 'Panas / Dingin', 'harga' => 7000],
            ['kategori' => 'Kopi & Teh', 'nama' => 'Kopi Saree', 'varian' => 'Asli Khas Aceh', 'harga' => 7000],
            ['kategori' => 'Kopi & Teh', 'nama' => 'Kopi Susu', 'varian' => 'Panas / Dingin', 'harga' => 13000],
            ['kategori' => 'Kopi & Teh', 'nama' => 'Sanger', 'varian' => 'Panas / Dingin', 'harga' => 13000],
            ['kategori' => 'Kopi & Teh', 'nama' => 'Teh Tarek Aceh', 'varian' => 'Panas / Dingin', 'harga' => 13000],
            ['kategori' => 'Kopi & Teh', 'nama' => 'Kopi Mix Susu', 'varian' => 'Panas / Dingin', 'harga' => 13000],
            ['kategori' => 'Kopi & Teh', 'nama' => 'Capucino Susu', 'varian' => 'Panas / Dingin', 'harga' => 13000],
            ['kategori' => 'Kopi & Teh', 'nama' => 'Milo Susu', 'varian' => 'Panas / Dingin', 'harga' => 13000],
            ['kategori' => 'Kopi & Teh', 'nama' => 'TST (Susu Coklat)', 'varian' => 'Panas / Dingin', 'harga' => 15000],

            // BANDREK & TST (kategori_id = 11)
            ['kategori' => 'Bandrek & TST', 'nama' => 'Bandrek Gula', 'varian' => 'Bahan Alami Jahe', 'harga' => 9500],
            ['kategori' => 'Bandrek & TST', 'nama' => 'Bandrek + Susu', 'varian' => 'Bahan Alami Jahe', 'harga' => 12000],
            ['kategori' => 'Bandrek & TST', 'nama' => 'BST (Bandrek Susu Telor)', 'varian' => 'STMJ Alami', 'harga' => 16000],
            ['kategori' => 'Bandrek & TST', 'nama' => 'TST (Teh Susu Telor)', 'varian' => '-', 'harga' => 17500],
            ['kategori' => 'Bandrek & TST', 'nama' => 'TST Pinang Muda', 'varian' => '-', 'harga' => 23000],

            // BOTOL & SODA (kategori_id = 12)
            ['kategori' => 'Botol & Soda', 'nama' => 'Aneka Minuman Botol', 'varian' => 'Aqua, Teh Botol, Frutea', 'harga' => 7000],
            ['kategori' => 'Botol & Soda', 'nama' => 'Sprite', 'varian' => 'Biasa / Dingin', 'harga' => 7000],
            ['kategori' => 'Botol & Soda', 'nama' => 'Fanta Biasa', 'varian' => 'Dingin / Biasa', 'harga' => 8000],
            ['kategori' => 'Botol & Soda', 'nama' => 'Fanta + Susu', 'varian' => 'Soda Gembira', 'harga' => 13000],
            ['kategori' => 'Botol & Soda', 'nama' => 'Sprite + Susu', 'varian' => 'Soda Gembira', 'harga' => 13000],
            ['kategori' => 'Botol & Soda', 'nama' => 'Minuman Badak', 'varian' => 'Khas Sumatra', 'harga' => 13000],
            ['kategori' => 'Botol & Soda', 'nama' => 'Minuman Badak + Susu', 'varian' => 'Badak Gembira', 'harga' => 17000],

            // SACHET (kategori_id = 13)
            ['kategori' => 'Sachet', 'nama' => 'Nutri Sari', 'varian' => 'Jeruk, J. Nipis, Jambu, Mangga', 'harga' => 7000],

            // ANEKA JUS (kategori_id = 14)
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Jambu', 'varian' => 'Biasa / Dingin', 'harga' => 12500],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Belimbing', 'varian' => 'Biasa / Dingin', 'harga' => 12500],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Jeruk', 'varian' => 'Biasa / Dingin', 'harga' => 12500],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Naga', 'varian' => 'Biasa / Dingin', 'harga' => 12500],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Wortel', 'varian' => 'Biasa / Dingin', 'harga' => 12500],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Markisa', 'varian' => 'Biasa / Dingin', 'harga' => 12500],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Sirsak', 'varian' => 'Biasa / Dingin', 'harga' => 12500],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Kuini', 'varian' => 'Biasa / Dingin', 'harga' => 12500],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Mangga', 'varian' => 'Biasa / Dingin', 'harga' => 13000],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Worjer', 'varian' => 'Wortel + Jeruk', 'harga' => 13000],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Terong Belanda', 'varian' => 'Biasa / Dingin', 'harga' => 16000],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Martabe', 'varian' => 'Terong Belanda + Markisa', 'harga' => 16000],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Pokat', 'varian' => 'Biasa / Dingin', 'harga' => 16000],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Naga + Sirsak', 'varian' => 'Mix Varian', 'harga' => 16000],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Apel', 'varian' => 'Biasa / Dingin', 'harga' => 16000],
            ['kategori' => 'Aneka Jus', 'nama' => 'Jus Pinang Muda', 'varian' => 'Berenergi', 'harga' => 23000],

            // PAKET PROMO (kategori_id = 15)
            ['kategori' => 'Paket Promo', 'nama' => 'Mie Hun + Aqua Botol', 'varian' => 'Paket Hemat', 'harga' => 25000],
            ['kategori' => 'Paket Promo', 'nama' => 'Mie Aceh + Aqua Botol', 'varian' => 'Paket Hemat', 'harga' => 25000],
            ['kategori' => 'Paket Promo', 'nama' => 'Nasi Goreng Telur + Aqua Botol', 'varian' => 'Paket Hemat', 'harga' => 25000],
            ['kategori' => 'Paket Promo', 'nama' => 'Mie Tiaw + Aqua Botol', 'varian' => 'Paket Hemat', 'harga' => 25000],
            ['kategori' => 'Paket Promo', 'nama' => 'Ifomie + Aqua Botol', 'varian' => 'Paket Hemat', 'harga' => 25000],
            ['kategori' => 'Paket Promo', 'nama' => 'Nasi Goreng Kampung + Teh Manis', 'varian' => 'Paket Hemat', 'harga' => 25000],
            ['kategori' => 'Paket Promo', 'nama' => 'Mie Hun + Teh Manis Dingin', 'varian' => 'Paket Hemat', 'harga' => 24000],
            ['kategori' => 'Paket Promo', 'nama' => 'Nasi Goreng + Teh Manis Dingin', 'varian' => 'Paket Hemat', 'harga' => 24000],
            ['kategori' => 'Paket Promo', 'nama' => 'Mie Tiaw + Teh Manis Dingin', 'varian' => 'Paket Hemat', 'harga' => 24000],
            ['kategori' => 'Paket Promo', 'nama' => 'Ifomie + Teh Manis Dingin', 'varian' => 'Paket Hemat', 'harga' => 24000],
            ['kategori' => 'Paket Promo', 'nama' => 'Indomie Bangladesh + Teh Manis Dingin', 'varian' => 'Paket Hemat', 'harga' => 24000],
            ['kategori' => 'Paket Promo', 'nama' => 'Mie Aceh + Teh Manis Dingin', 'varian' => 'Paket Hemat', 'harga' => 24000],
            ['kategori' => 'Paket Promo', 'nama' => 'Ayam Geprek + Teh Manis Dingin', 'varian' => 'Paket Hemat', 'harga' => 26000],
            ['kategori' => 'Paket Promo', 'nama' => 'Mie Aceh + Jus Jeruk', 'varian' => 'Paket Hemat', 'harga' => 30000],
            ['kategori' => 'Paket Promo', 'nama' => 'Nasi Goreng + Jus Jeruk', 'varian' => 'Paket Hemat', 'harga' => 30000],
            ['kategori' => 'Paket Promo', 'nama' => 'Ayam Penyet + Teh Manis Dingin', 'varian' => 'Paket Hemat', 'harga' => 30000],
            ['kategori' => 'Paket Promo', 'nama' => 'Ayam Penyet + Nutri Sari', 'varian' => 'Paket Hemat', 'harga' => 31000],
            ['kategori' => 'Paket Promo', 'nama' => 'Roti Bandung + TST', 'varian' => 'Paket Hemat', 'harga' => 38000],
            ['kategori' => 'Paket Promo', 'nama' => '[PROMO 2 PORSI] 2 Mie Aceh + Teh Manis', 'varian' => 'Promo 2 Porsi', 'harga' => 39000],
            ['kategori' => 'Paket Promo', 'nama' => '[PROMO 2 PORSI] 2 Nasi Goreng + Teh Manis', 'varian' => 'Promo 2 Porsi', 'harga' => 39000],
            ['kategori' => 'Paket Promo', 'nama' => '[PROMO 2 PORSI] 2 Nasi Goreng Kampung + Teh Manis', 'varian' => 'Promo 2 Porsi', 'harga' => 39000],
            ['kategori' => 'Paket Promo', 'nama' => '[PROMO 2 PORSI] 2 Mie Aceh + Nutri Sari', 'varian' => 'Promo 2 Porsi', 'harga' => 39000],
            ['kategori' => 'Paket Promo', 'nama' => '[PROMO 2 PORSI] 2 Nasi Goreng + Nutri Sari', 'varian' => 'Promo 2 Porsi', 'harga' => 39000],
        ];

        foreach ($menus as $m) {
            $kategori = KategoriMenu::where('nama', $m['kategori'])->first();
            if ($kategori) {
                Menu::create([
                    'kategori_menu_id' => $kategori->id,
                    'nama' => $m['nama'],
                    'varian' => $m['varian'] !== '-' ? $m['varian'] : null,
                    'harga_dine_in' => $m['harga'],
                    'harga_takeaway' => $m['harga'],
                    'harga_delivery' => $m['harga'] + 2000,
                    'harga_pokok' => $m['harga'] * 0.4,
                    'tersedia' => true,
                ]);
            }
        }
    }
}
