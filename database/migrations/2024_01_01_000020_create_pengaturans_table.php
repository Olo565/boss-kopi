<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('kunci')->unique();
            $table->text('nilai')->nullable();
            $table->string('tipe')->default('text'); // text, number, boolean, color, image, textarea
            $table->string('label');
            $table->string('grup')->default('umum');
            $table->timestamps();
        });

        // Seed default settings
        $settings = [
            // Grup: Informasi Kedai
            ['kunci' => 'nama_kedai', 'nilai' => 'BOSS KOPI', 'tipe' => 'text', 'label' => 'Nama Kedai', 'grup' => 'kedai'],
            ['kunci' => 'tagline_kedai', 'nilai' => 'Sunggal · Sistem Manajemen', 'tipe' => 'text', 'label' => 'Tagline / Slogan', 'grup' => 'kedai'],
            ['kunci' => 'alamat_kedai', 'nilai' => 'Jl. Pinang Baris Elok No.37, Sunggal, Kec. Medan Sunggal, Kota Medan', 'tipe' => 'textarea', 'label' => 'Alamat Kedai', 'grup' => 'kedai'],
            ['kunci' => 'no_wa_kedai', 'nilai' => '0895333301223', 'tipe' => 'text', 'label' => 'Nomor WA Kedai', 'grup' => 'kedai'],
            ['kunci' => 'jam_buka', 'nilai' => '07:00', 'tipe' => 'text', 'label' => 'Jam Buka', 'grup' => 'kedai'],
            ['kunci' => 'jam_tutup', 'nilai' => '22:00', 'tipe' => 'text', 'label' => 'Jam Tutup', 'grup' => 'kedai'],
            ['kunci' => 'lat_toko', 'nilai' => '3.579026', 'tipe' => 'text', 'label' => 'Koordinat Latitude', 'grup' => 'kedai'],
            ['kunci' => 'lng_toko', 'nilai' => '98.613460', 'tipe' => 'text', 'label' => 'Koordinat Longitude', 'grup' => 'kedai'],

            // Grup: Pengaturan Order
            ['kunci' => 'ongkir', 'nilai' => '5000', 'tipe' => 'number', 'label' => 'Ongkos Kirim (Rp)', 'grup' => 'order'],
            ['kunci' => 'minimum_order', 'nilai' => '0', 'tipe' => 'number', 'label' => 'Minimum Order Delivery (Rp)', 'grup' => 'order'],
            ['kunci' => 'radius_delivery', 'nilai' => '10', 'tipe' => 'number', 'label' => 'Radius Delivery Maksimal (km)', 'grup' => 'order'],
            ['kunci' => 'komisi_driver_persen', 'nilai' => '40', 'tipe' => 'number', 'label' => 'Komisi Driver (%)', 'grup' => 'order'],
            ['kunci' => 'poin_per_rupiah', 'nilai' => '5000', 'tipe' => 'number', 'label' => 'Belanja per 1 Poin (Rp)', 'grup' => 'order'],
            ['kunci' => 'nilai_tukar_poin', 'nilai' => '2000', 'tipe' => 'number', 'label' => 'Nilai 100 Poin (Rp)', 'grup' => 'order'],
            ['kunci' => 'min_poin_redeem', 'nilai' => '100', 'tipe' => 'number', 'label' => 'Minimum Poin untuk Redeem', 'grup' => 'order'],

            // Grup: Tampilan
            ['kunci' => 'warna_utama', 'nilai' => '#4A3525', 'tipe' => 'color', 'label' => 'Warna Utama (Coffee Brown)', 'grup' => 'tampilan'],
            ['kunci' => 'warna_sekunder', 'nilai' => '#E6D5C3', 'tipe' => 'color', 'label' => 'Warna Sekunder (Latte)', 'grup' => 'tampilan'],
            ['kunci' => 'warna_background', 'nilai' => '#f8f1e3', 'tipe' => 'color', 'label' => 'Warna Background', 'grup' => 'tampilan'],
            ['kunci' => 'logo_kedai', 'nilai' => null, 'tipe' => 'image', 'label' => 'Logo Kedai', 'grup' => 'tampilan'],
            ['kunci' => 'banner_pembeli', 'nilai' => null, 'tipe' => 'image', 'label' => 'Banner Halaman Pembeli', 'grup' => 'tampilan'],

            // Grup: Fitur
            ['kunci' => 'delivery_aktif', 'nilai' => '1', 'tipe' => 'boolean', 'label' => 'Layanan Delivery Aktif', 'grup' => 'fitur'],
            ['kunci' => 'takeaway_aktif', 'nilai' => '1', 'tipe' => 'boolean', 'label' => 'Layanan Takeaway Aktif', 'grup' => 'fitur'],
            ['kunci' => 'poin_aktif', 'nilai' => '1', 'tipe' => 'boolean', 'label' => 'Sistem Poin Loyalitas Aktif', 'grup' => 'fitur'],
            ['kunci' => 'ulasan_aktif', 'nilai' => '1', 'tipe' => 'boolean', 'label' => 'Fitur Ulasan Menu Aktif', 'grup' => 'fitur'],
            ['kunci' => 'sedang_tutup', 'nilai' => '0', 'tipe' => 'boolean', 'label' => 'Kedai Sedang Tutup (Non-aktifkan Pesanan)', 'grup' => 'fitur'],
        ];

        foreach ($settings as $s) {
            DB::table('pengaturans')->insert(array_merge($s, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
