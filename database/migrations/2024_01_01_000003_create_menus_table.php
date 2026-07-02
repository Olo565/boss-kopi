<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_menu_id')->constrained('kategori_menus')->onDelete('cascade');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('varian')->nullable();
            $table->decimal('harga_dine_in', 10, 2)->default(0);
            $table->decimal('harga_takeaway', 10, 2)->default(0);
            $table->decimal('harga_delivery', 10, 2)->default(0);
            $table->decimal('harga_pokok', 10, 2)->default(0);
            $table->string('foto')->nullable();
            $table->boolean('tersedia')->default(true);
            $table->boolean('is_best_seller')->default(false);
            $table->integer('total_terjual')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
