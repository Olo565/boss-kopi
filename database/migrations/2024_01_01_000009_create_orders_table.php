<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_struk')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('kasir_id')->nullable()->constrained('users');
            $table->foreignId('shift_id')->nullable()->constrained('shifts');
            $table->foreignId('driver_id')->nullable()->constrained('users');
            $table->foreignId('promo_id')->nullable()->constrained('promos');
            $table->enum('tipe_pesanan', ['dine_in', 'takeaway', 'delivery']);
            $table->string('nomor_meja')->nullable();
            $table->string('nama_pelanggan')->nullable();
            $table->string('no_hp_pelanggan')->nullable();
            $table->text('alamat_delivery')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('diskon', 10, 2)->default(0);
            $table->decimal('ongkir', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('metode_pembayaran', ['tunai', 'qris', 'debit'])->nullable();
            $table->decimal('uang_bayar', 10, 2)->nullable();
            $table->decimal('uang_kembalian', 10, 2)->nullable();
            $table->enum('status', ['pending', 'dikonfirmasi', 'diproses', 'siap', 'diantar', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('catatan')->nullable();
            $table->string('bukti_pengiriman')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
