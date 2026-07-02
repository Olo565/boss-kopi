<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';
    protected $fillable = ['nama', 'satuan', 'stok_saat_ini', 'stok_minimum', 'harga_per_satuan'];

    public function recipeMappings() { return $this->hasMany(RecipeMapping::class); }
    public function stokHistories() { return $this->hasMany(StokHistory::class); }

    public function isStokKritis(): bool
    {
        return $this->stok_saat_ini <= $this->stok_minimum;
    }
}
