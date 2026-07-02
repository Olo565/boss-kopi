<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan BOSS KOPI</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; }
        body { margin: 20px; color: #2C2520; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #4A3525; padding-bottom: 10px; }
        .header h1 { font-size: 20px; color: #4A3525; margin: 0; letter-spacing: 2px; }
        .header p { margin: 2px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead th { background: #4A3525; color: #fff; padding: 6px 8px; text-align: left; font-size: 10px; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #E6D5C3; }
        tbody tr:nth-child(even) { background: #FDFBF7; }
        tfoot td { background: #E6D5C3; font-weight: bold; padding: 6px 8px; color: #4A3525; }
        .summary { margin-top: 15px; }
        .summary table { width: 300px; margin-left: auto; }
        .summary td { padding: 4px 8px; }
        .summary .label { color: #666; }
        .summary .value { font-weight: bold; color: #4A3525; text-align: right; }
        .footer { margin-top: 20px; text-align: right; color: #888; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BOSS KOPI — SUNGGAL</h1>
        <p>Laporan Penjualan</p>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }}</p>
        <p>Dicetak: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Waktu</th>
                <th>No. Struk</th>
                <th>Kasir</th>
                <th>Tipe</th>
                <th>Item</th>
                <th>Total</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $i => $order)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $order->created_at->format('d/m H:i') }}</td>
                <td>{{ $order->nomor_struk }}</td>
                <td>{{ $order->kasir->name ?? 'Online' }}</td>
                <td>{{ ['dine_in' => 'Dine-in', 'takeaway' => 'Takeaway', 'delivery' => 'Delivery'][$order->tipe_pesanan] }}</td>
                <td>{{ $order->items->count() }} item</td>
                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td>{{ strtoupper($order->metode_pembayaran) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align:right;">TOTAL PENDAPATAN</td>
                <td>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <table>
            <tr><td class="label">Total Transaksi</td><td class="value">{{ $orders->count() }}</td></tr>
            <tr><td class="label">Total Penjualan</td><td class="value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td></tr>
            <tr><td class="label">Rata-rata/Transaksi</td>
                <td class="value">Rp {{ $orders->count() > 0 ? number_format($totalPenjualan / $orders->count(), 0, ',', '.') : 0 }}</td></tr>
        </table>
    </div>

    <div class="footer">
        Laporan ini dibuat otomatis oleh Sistem BOSS KOPI
    </div>
</body>
</html>
