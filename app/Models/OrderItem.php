<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'menu_id', 'nama_menu',
        'harga_satuan', 'jumlah', 'subtotal', 'varian', 'catatan',
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function menu() { return $this->belongsTo(Menu::class); }
}
