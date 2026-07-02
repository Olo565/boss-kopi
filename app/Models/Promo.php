<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'nama', 'kode_kupon', 'tipe', 'nilai_diskon', 'min_transaksi',
        'max_penggunaan', 'sudah_digunakan', 'tanggal_mulai',
        'tanggal_selesai', 'is_active', 'deskripsi',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        $today = now()->toDateString();
        $masaAktif = $today >= $this->tanggal_mulai->toDateString()
                  && $today <= $this->tanggal_selesai->toDateString();
        $masihBisa = is_null($this->max_penggunaan)
                  || $this->sudah_digunakan < $this->max_penggunaan;
        return $this->is_active && $masaAktif && $masihBisa;
    }

    public function hitungDiskon($subtotal): float
    {
        if ($subtotal < $this->min_transaksi) return 0;

        return match($this->tipe) {
            'persentase' => $subtotal * ($this->nilai_diskon / 100),
            'nominal' => min($this->nilai_diskon, $subtotal),
            default => 0,
        };
    }
}
