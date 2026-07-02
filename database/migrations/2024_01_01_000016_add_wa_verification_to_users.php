<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kode_verifikasi_wa', 6)->nullable()->after('status_akun');
            $table->boolean('no_hp_terverifikasi')->default(false)->after('kode_verifikasi_wa');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kode_verifikasi_wa', 'no_hp_terverifikasi']);
        });
    }
};
