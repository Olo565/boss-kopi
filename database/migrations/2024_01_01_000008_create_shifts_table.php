<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasir_id')->constrained('users');
            $table->decimal('modal_awal', 10, 2)->default(0);
            $table->decimal('total_tunai', 10, 2)->default(0);
            $table->decimal('total_qris', 10, 2)->default(0);
            $table->decimal('total_debit', 10, 2)->default(0);
            $table->decimal('uang_kas_akhir', 10, 2)->nullable();
            $table->dateTime('waktu_buka');
            $table->dateTime('waktu_tutup')->nullable();
            $table->enum('status', ['buka', 'tutup'])->default('buka');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
