<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_id', 'kategori', 'judul', 'isi',
        'foto_lampiran', 'status', 'balasan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getLabelStatus()
    {
        return match ($this->status) {
            'baru' => 'Baru',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai Ditindaklanjuti',
            default => $this->status,
        };
    }
}
