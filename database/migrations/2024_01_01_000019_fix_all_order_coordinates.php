<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update semua pesanan yang masih pakai koordinat lama
        // ke koordinat Boss Kopi yang benar: 3.579026, 98.613460
        DB::table('orders')
            ->whereNotNull('lat_toko')
            ->where('lat_toko', '!=', 3.579026)
            ->update([
                'lat_toko' => 3.579026,
                'lng_toko' => 98.613460,
            ]);
    }

    public function down(): void
    {
        // Tidak perlu rollback koordinat
    }
};
