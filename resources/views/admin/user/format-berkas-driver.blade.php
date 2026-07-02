<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Format Kelengkapan Berkas Driver — BOSS KOPI</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Arial, sans-serif; }
        body { background:#F5F0E8; padding:2rem; }
        .dokumen { background:#fff; max-width:800px; margin:0 auto; padding:2.5rem; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1); }
        .header { text-align:center; border-bottom:3px solid #1A1A1A; padding-bottom:1.5rem; margin-bottom:1.5rem; }
        .logo-text { font-size:1.8rem; font-weight:900; color:#1A1A1A; letter-spacing:3px; }
        .logo-sub { color:#9c7c5e; font-size:0.8rem; letter-spacing:2px; }
        h2 { color:#1A1A1A; font-size:1.1rem; margin:1.5rem 0 0.75rem; }
        .info-box { background:#F5F0E8; border-left:4px solid #1A1A1A; padding:1rem 1.25rem; border-radius:0 8px 8px 0; margin-bottom:1.25rem; }
        .info-box p { color:#1A1A1A; font-size:0.9rem; line-height:1.7; }
        table { width:100%; border-collapse:collapse; margin-bottom:1.5rem; }
        th { background:#1A1A1A; color:#fff; padding:0.65rem 1rem; text-align:left; font-size:0.85rem; }
        td { padding:0.65rem 1rem; border-bottom:1px solid #E6D5C3; font-size:0.85rem; color:#333; vertical-align:top; }
        tr:nth-child(even) { background:#fdfbf7; }
        .checklist { width:40px; text-align:center; font-size:1.1rem; }
        .badge { display:inline-block; background:#1A1A1A; color:#fff; border-radius:20px; padding:0.2rem 0.75rem; font-size:0.75rem; font-weight:700; }
        .badge-warning { background:#D4A017; }
        .ttd-section { margin-top:2rem; display:flex; justify-content:space-between; }
        .ttd-box { text-align:center; width:200px; }
        .ttd-line { border-top:1.5px solid #333; margin-top:3rem; padding-top:0.5rem; font-size:0.8rem; color:#555; }
        .footer { text-align:center; margin-top:2rem; padding-top:1rem; border-top:1px solid #E6D5C3; color:#999; font-size:0.75rem; }
        .print-btn { text-align:center; margin-bottom:1.5rem; }
        .print-btn button { background:#1A1A1A; color:#fff; border:none; border-radius:10px; padding:0.75rem 2rem; font-weight:700; cursor:pointer; font-size:0.95rem; }
        .print-btn button:hover { background:#3a2a1e; }
        @media print {
            body { background:#fff; padding:0; }
            .dokumen { box-shadow:none; border-radius:0; padding:1.5rem; }
            .print-btn { display:none; }
        }
    </style>
</head>
<body>
<div class="print-btn">
    <button onclick="window.print()">🖨️ Cetak / Download PDF</button>
    <a href="{{ url()->previous() }}" style="margin-left:1rem;color:#1A1A1A;font-size:0.9rem;">← Kembali</a>
</div>

<div class="dokumen">
    <!-- Header -->
    <div class="header">
        <div class="logo-text">☕ BOSS KOPI</div>
        <div class="logo-sub">Jl. Pinang Baris Elok No.37, Sunggal, Medan Sunggal</div>
        <div class="logo-sub">WA: 0895-3333-01223</div>
        <h1 style="color:#1A1A1A;margin-top:1rem;font-size:1.2rem;">FORMULIR KELENGKAPAN BERKAS DRIVER</h1>
        <p style="color:#999;font-size:0.8rem;margin-top:0.25rem;">No. Berkas: BK-DRV-_______</p>
    </div>

    <!-- Data Driver -->
    <h2>📋 Data Calon Driver</h2>
    <table>
        <tr><td style="width:200px;color:#666;">Nama Lengkap</td><td>: ___________________________________</td></tr>
        <tr><td style="color:#666;">Nomor HP / WA</td><td>: ___________________________________</td></tr>
        <tr><td style="color:#666;">Email Akun</td><td>: ___________________________________</td></tr>
        <tr><td style="color:#666;">Jenis Kendaraan</td><td>: ___________________________________</td></tr>
        <tr><td style="color:#666;">Warna Kendaraan</td><td>: ___________________________________</td></tr>
        <tr><td style="color:#666;">Plat Nomor</td><td>: ___________________________________</td></tr>
        <tr><td style="color:#666;">Alamat Lengkap</td><td>: ___________________________________</td></tr>
        <tr><td style="color:#666;">Tanggal Daftar</td><td>: ___________________________________</td></tr>
    </table>

    <!-- Checklist Berkas -->
    <h2>📄 Checklist Kelengkapan Berkas</h2>
    <div class="info-box">
        <p>Harap bawa semua dokumen berikut saat datang ke kedai. Admin akan mencentang setiap berkas yang sudah lengkap.</p>
    </div>
    <table>
        <thead>
            <tr>
                <th class="checklist">✓</th>
                <th>Dokumen</th>
                <th>Keterangan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="checklist">☐</td>
                <td>KTP (Kartu Tanda Penduduk)</td>
                <td>Asli + fotocopy 1 lembar</td>
                <td><span class="badge badge-warning">Wajib</span></td>
            </tr>
            <tr>
                <td class="checklist">☐</td>
                <td>SIM C (Surat Izin Mengemudi)</td>
                <td>Asli + fotocopy 1 lembar, masih berlaku</td>
                <td><span class="badge badge-warning">Wajib</span></td>
            </tr>
            <tr>
                <td class="checklist">☐</td>
                <td>STNK Kendaraan</td>
                <td>Asli + fotocopy 1 lembar, atas nama sendiri/keluarga</td>
                <td><span class="badge badge-warning">Wajib</span></td>
            </tr>
            <tr>
                <td class="checklist">☐</td>
                <td>Pas Foto Terbaru</td>
                <td>Ukuran 3×4, latar merah, 2 lembar</td>
                <td><span class="badge badge-warning">Wajib</span></td>
            </tr>
            <tr>
                <td class="checklist">☐</td>
                <td>Foto Kendaraan</td>
                <td>Tampak depan & samping (bisa via WA/print)</td>
                <td><span class="badge badge-warning">Wajib</span></td>
            </tr>
            <tr>
                <td class="checklist">☐</td>
                <td>Nomor Rekening / E-Wallet</td>
                <td>Untuk pencairan komisi (BCA/BNI/OVO/GoPay/Dana)</td>
                <td><span class="badge">Opsional</span></td>
            </tr>
        </tbody>
    </table>

    <!-- Perlengkapan yang Diterima -->
    <h2>🎽 Perlengkapan yang Diterima Driver</h2>
    <table>
        <thead>
            <tr>
                <th class="checklist">✓</th>
                <th>Perlengkapan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="checklist">☐</td>
                <td>Jaket BOSS KOPI</td>
                <td>Ukuran: _______ (S / M / L / XL / XXL)</td>
            </tr>
            <tr>
                <td class="checklist">☐</td>
                <td>Helm BOSS KOPI</td>
                <td>Ukuran standar, wajib dipakai saat bertugas</td>
            </tr>
            <tr>
                <td class="checklist">☐</td>
                <td>ID Card Driver</td>
                <td>Dipakai saat mengambil & mengantar pesanan</td>
            </tr>
        </tbody>
    </table>

    <!-- Peraturan -->
    <h2>📜 Peraturan Driver BOSS KOPI</h2>
    <div class="info-box">
        <p>
            1. Driver wajib menggunakan jaket dan helm BOSS KOPI saat bertugas.<br>
            2. Driver wajib menjaga sopan santun dan profesionalisme kepada pelanggan.<br>
            3. Driver dilarang membatalkan pesanan lebih dari 2 kali dalam sebulan.<br>
            4. Driver wajib menjaga kondisi makanan/minuman agar tetap baik saat diantar.<br>
            5. Pelanggaran berat dapat menyebabkan penonaktifan akun secara permanen.<br>
            6. Komisi driver adalah 40% dari ongkos kirim setiap pengantaran selesai.<br>
            7. Driver wajib mengupload foto bukti setelah pesanan diterima pelanggan.
        </p>
    </div>

    <!-- Pernyataan -->
    <h2>✍️ Pernyataan</h2>
    <div class="info-box">
        <p>
            Saya yang bertanda tangan di bawah ini menyatakan bahwa semua data dan dokumen yang saya berikan
            adalah benar dan sah. Saya bersedia mematuhi semua peraturan yang berlaku di BOSS KOPI sebagai Driver
            dan bertanggung jawab atas perlengkapan yang diterima.
        </p>
    </div>

    <!-- TTD -->
    <div class="ttd-section">
        <div class="ttd-box">
            <p style="font-size:0.8rem;color:#666;">Medan, _________________ 2026</p>
            <div class="ttd-line">Calon Driver</div>
            <p style="font-size:0.8rem;color:#666;margin-top:0.25rem;">( ________________________ )</p>
        </div>
        <div class="ttd-box">
            <p style="font-size:0.8rem;color:#666;">Diverifikasi oleh:</p>
            <div class="ttd-line">Admin BOSS KOPI</div>
            <p style="font-size:0.8rem;color:#666;margin-top:0.25rem;">( ________________________ )</p>
        </div>
    </div>

    <div class="footer">
        <p>BOSS KOPI — Jl. Pinang Baris Elok No.37, Sunggal, Kec. Medan Sunggal, Kota Medan, Sumatera Utara</p>
        <p>WA: 0895-3333-01223 | Dokumen ini dicetak dari Sistem Manajemen BOSS KOPI</p>
    </div>
</div>
</body>
</html>
