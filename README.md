# BOSS KOPI - Sistem Manajemen Kedai Kopi

## Persyaratan Sistem
- PHP >= 8.1
- Laravel 10.x
- MySQL 5.7+ / MariaDB
- Laragon (Windows)
- Composer
- Node.js & NPM

## Cara Instalasi

### 1. Clone / Extract Project
```bash
# Extract zip ke folder htdocs Laragon
# Contoh: C:\laragon\www\boss-kopi
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=boss_kopi
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat Database
Buka phpMyAdmin → Buat database bernama `boss_kopi`

### 6. Migrasi & Seed Database
```bash
php artisan migrate --seed
```

### 7. Jalankan Aplikasi
```bash
php artisan serve
```
Atau akses via Laragon: http://boss-kopi.test

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin/Owner | admin@bosskopi.com | password |
| Kasir | kasir@bosskopi.com | password |
| Pembeli | pembeli@bosskopi.com | password |
| Driver | driver@bosskopi.com | password |

## Fitur Utama

### Admin/Owner
- Dashboard dengan grafik pendapatan
- Manajemen produk & menu (CRUD)
- Manajemen stok & bahan baku
- Manajemen pengguna
- Promo & diskon
- Laporan (export Excel & PDF)

### Kasir
- POS (Point of Sale)
- Transaksi & pembayaran
- Buka/tutup shift
- Cetak struk

### Pembeli
- Pesan online
- Tracking pesanan
- Loyalty poin
- Riwayat pesanan

### Driver
- Daftar pesanan delivery
- Navigasi pengantaran
- Status pengiriman
- Riwayat & komisi
