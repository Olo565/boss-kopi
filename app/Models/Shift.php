<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'kasir_id', 'modal_awal', 'total_tunai', 'total_qris',
        'total_debit', 'uang_kas_akhir', 'waktu_buka', 'waktu_tutup',
        'status', 'catatan',
    ];

    protected $casts = [
        'waktu_buka' => 'datetime',
        'waktu_tutup' => 'datetime',
    ];

    public function kasir() { return $this->belongsTo(User::class, 'kasir_id'); }
    public function orders() { return $this->hasMany(Order::class); }

    public function getTotalPendapatan(): float
    {
        return $this->total_tunai + $this->total_qris + $this->total_debit;
    }
}
