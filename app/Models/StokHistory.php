<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokHistory extends Model
{
    protected $fillable = [
        'bahan_baku_id', 'tipe', 'jumlah',
        'stok_sebelum', 'stok_sesudah', 'keterangan', 'user_id',
    ];

    public function bahanBaku() { return $this->belongsTo(BahanBaku::class); }
    public function user() { return $this->belongsTo(User::class); }
}
