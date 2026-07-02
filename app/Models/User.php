<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'no_hp', 'alamat', 'password',
        'role', 'is_active', 'status_akun', 'poin_loyalitas', 'foto',
        'jenis_kendaraan', 'warna_kendaraan', 'plat_nomor',
        'rating_rata', 'jumlah_rating',
        'kode_verifikasi_wa', 'no_hp_terverifikasi',
        'jumlah_cancel_delivery', 'diblokir_delivery',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'rating_rata' => 'decimal:2',
    ];

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isKasir(): bool { return $this->role === 'kasir'; }
    public function isPembeli(): bool { return $this->role === 'pembeli'; }
    public function isDriver(): bool { return $this->role === 'driver'; }

    public function orders() { return $this->hasMany(Order::class, 'user_id'); }
    public function kasirOrders() { return $this->hasMany(Order::class, 'kasir_id'); }
    public function shifts() { return $this->hasMany(Shift::class, 'kasir_id'); }
    public function poinHistories() { return $this->hasMany(PoinHistory::class); }
    public function pengiriman() { return $this->hasMany(Pengiriman::class, 'driver_id'); }
    public function pengaduans() { return $this->hasMany(Pengaduan::class); }

    public function tambahRating(float $bintangBaru): void
    {
        $totalBaru = ($this->rating_rata * $this->jumlah_rating) + $bintangBaru;
        $jumlahBaru = $this->jumlah_rating + 1;
        $this->update([
            'rating_rata' => round($totalBaru / $jumlahBaru, 2),
            'jumlah_rating' => $jumlahBaru,
        ]);
    }
}
