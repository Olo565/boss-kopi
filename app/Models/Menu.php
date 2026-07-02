<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'kategori_menu_id', 'nama', 'deskripsi', 'varian',
        'harga_dine_in', 'harga_takeaway', 'harga_delivery',
        'harga_pokok', 'foto', 'tersedia', 'is_best_seller', 'total_terjual',
    ];

    protected $casts = [
        'tersedia' => 'boolean',
        'is_best_seller' => 'boolean',
    ];

    public function kategori() { return $this->belongsTo(KategoriMenu::class, 'kategori_menu_id'); }
    public function recipeMappings() { return $this->hasMany(RecipeMapping::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }

    public function getHargaByTipe($tipe)
    {
        return match($tipe) {
            'takeaway' => $this->harga_takeaway,
            'delivery' => $this->harga_delivery,
            default => $this->harga_dine_in,
        };
    }
}
