<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('users');
            $table->enum('status', ['menunggu', 'diambil', 'diantar', 'selesai'])->default('menunggu');
            $table->dateTime('waktu_ambil')->nullable();
            $table->dateTime('waktu_tiba')->nullable();
            $table->string('bukti_foto')->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('komisi', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('poin_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->enum('tipe', ['tambah', 'kurang']);
            $table->integer('jumlah_poin');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poin_histories');
        Schema::dropIfExists('pengiriman');
    }
};
