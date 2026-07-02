<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('jenis_kendaraan')->nullable()->after('alamat');
            $table->string('warna_kendaraan')->nullable()->after('jenis_kendaraan');
            $table->string('plat_nomor')->nullable()->after('warna_kendaraan');
            $table->decimal('rating_rata', 3, 2)->default(5.00)->after('plat_nomor');
            $table->integer('jumlah_rating')->default(0)->after('rating_rata');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating_driver')->nullable()->after('status');
            $table->string('komentar_driver')->nullable()->after('rating_driver');
            $table->unsignedTinyInteger('rating_pelanggan')->nullable()->after('komentar_driver');
            $table->string('komentar_pelanggan')->nullable()->after('rating_pelanggan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jenis_kendaraan', 'warna_kendaraan', 'plat_nomor', 'rating_rata', 'jumlah_rating']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['rating_driver', 'komentar_driver', 'rating_pelanggan', 'komentar_pelanggan']);
        });
    }
};
