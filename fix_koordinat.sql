-- Script untuk update koordinat toko di semua pesanan lama
-- Jalankan di phpMyAdmin > SQL
-- Koordinat baru: Jl. Pinang Baris Elok No.37, Sunggal, Medan
-- 3.579026, 98.613460

UPDATE orders 
SET 
    lat_toko = 3.579026,
    lng_toko = 98.613460
WHERE 
    lat_toko IS NOT NULL 
    AND lat_toko != 3.579026;

-- Cek hasilnya
SELECT id, nomor_struk, lat_toko, lng_toko 
FROM orders 
WHERE lat_toko IS NOT NULL 
LIMIT 10;
