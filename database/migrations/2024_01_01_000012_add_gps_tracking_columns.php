<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->decimal('lat_driver', 10, 7)->nullable()->after('komisi');
            $table->decimal('lng_driver', 10, 7)->nullable()->after('lat_driver');
            $table->timestamp('lokasi_updated_at')->nullable()->after('lng_driver');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('lat_tujuan', 10, 7)->nullable()->after('alamat_delivery');
            $table->decimal('lng_tujuan', 10, 7)->nullable()->after('lat_tujuan');
            $table->decimal('lat_toko', 10, 7)->nullable()->after('lng_tujuan');
            $table->decimal('lng_toko', 10, 7)->nullable()->after('lat_toko');
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->dropColumn(['lat_driver', 'lng_driver', 'lokasi_updated_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['lat_tujuan', 'lng_tujuan', 'lat_toko', 'lng_toko']);
        });
    }
};
