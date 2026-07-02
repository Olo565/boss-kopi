<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Pengaturan extends Model
{
    protected $table = 'pengaturans';

    protected $fillable = ['kunci', 'nilai', 'tipe', 'label', 'grup'];

    // Ambil nilai pengaturan berdasarkan kunci
    public static function get(string $kunci, $default = null)
    {
        return Cache::remember("setting_{$kunci}", 60, function () use ($kunci, $default) {
            $setting = static::where('kunci', $kunci)->first();
            return $setting ? $setting->nilai : $default;
        });
    }

    // Set nilai pengaturan
    public static function set(string $kunci, $nilai)
    {
        static::where('kunci', $kunci)->update(['nilai' => $nilai]);
        Cache::forget("setting_{$kunci}");
    }

    // Ambil semua pengaturan per grup
    public static function getGrup(string $grup): array
    {
        return static::where('grup', $grup)->pluck('nilai', 'kunci')->toArray();
    }

    // Clear semua cache pengaturan
    public static function clearCache()
    {
        $keys = static::pluck('kunci');
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
    }
}
