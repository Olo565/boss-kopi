<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode_kupon')->unique()->nullable();
            $table->enum('tipe', ['persentase', 'nominal', 'buy1get1', 'paket']);
            $table->decimal('nilai_diskon', 10, 2)->default(0);
            $table->decimal('min_transaksi', 10, 2)->default(0);
            $table->integer('max_penggunaan')->nullable();
            $table->integer('sudah_digunakan')->default(0);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_active')->default(true);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
