<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoinHistory extends Model
{
    protected $fillable = ['user_id', 'order_id', 'tipe', 'jumlah_poin', 'keterangan'];

    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
}
