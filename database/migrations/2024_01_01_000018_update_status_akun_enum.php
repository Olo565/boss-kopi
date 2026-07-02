<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: ubah enum dengan ALTER TABLE
        DB::statement("ALTER TABLE users MODIFY COLUMN status_akun ENUM('aktif', 'menunggu', 'dipanggil', 'ditolak') DEFAULT 'aktif'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN status_akun ENUM('aktif', 'menunggu', 'ditolak') DEFAULT 'aktif'");
    }
};
