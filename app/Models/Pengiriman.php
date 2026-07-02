<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $fillable = [
        'order_id', 'driver_id', 'status', 'waktu_ambil',
        'waktu_tiba', 'bukti_foto', 'catatan', 'komisi',
        'lat_driver', 'lng_driver', 'lokasi_updated_at',
    ];

    protected $casts = [
        'waktu_ambil' => 'datetime',
        'waktu_tiba' => 'datetime',
        'lokasi_updated_at' => 'datetime',
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function driver() { return $this->belongsTo(User::class, 'driver_id'); }
}
