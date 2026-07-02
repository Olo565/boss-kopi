<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'nomor_struk', 'user_id', 'kasir_id', 'shift_id', 'driver_id', 'promo_id',
        'tipe_pesanan', 'nomor_meja', 'nama_pelanggan', 'no_hp_pelanggan',
        'alamat_delivery', 'lat_tujuan', 'lng_tujuan', 'lat_toko', 'lng_toko',
        'subtotal', 'diskon', 'ongkir', 'total',
        'metode_pembayaran', 'uang_bayar', 'uang_kembalian',
        'status', 'catatan', 'bukti_pengiriman',
        'rating_driver', 'komentar_driver', 'rating_pelanggan', 'komentar_pelanggan',
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function kasir() { return $this->belongsTo(User::class, 'kasir_id'); }
    public function driver() { return $this->belongsTo(User::class, 'driver_id'); }
    public function shift() { return $this->belongsTo(Shift::class); }
    public function promo() { return $this->belongsTo(Promo::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function pengiriman() { return $this->hasOne(Pengiriman::class); }

    public static function generateNomorStruk(): string
    {
        $prefix = 'BK-' . date('Ymd') . '-';
        $last = self::where('nomor_struk', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getLabelStatus(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'dikonfirmasi' => 'Dikonfirmasi',
            'diproses' => 'Sedang Diproses',
            'siap' => 'Siap',
            'diantar' => 'Sedang Diantar',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
