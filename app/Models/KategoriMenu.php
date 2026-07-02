<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriMenu extends Model
{
    protected $fillable = ['nama', 'jenis', 'ikon', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function menus() { return $this->hasMany(Menu::class, 'kategori_menu_id'); }
}
