<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('jumlah_cancel_delivery')->default(0)->after('no_hp_terverifikasi');
            $table->boolean('diblokir_delivery')->default(false)->after('jumlah_cancel_delivery');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jumlah_cancel_delivery', 'diblokir_delivery']);
        });
    }
};
