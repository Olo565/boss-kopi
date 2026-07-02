<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $order->nomor_struk }}</title>
    <style>
        * { font-family: 'Courier New', monospace; font-size: 12px; margin: 0; padding: 0; }
        body { width: 280px; margin: 0 auto; padding: 10px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; }
        .logo { font-size: 16px; font-weight: bold; letter-spacing: 2px; }
        .small { font-size: 10px; color: #555; }
        @media print {
            body { width: 72mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="center mb-2">
        <div class="logo">BOSS KOPI</div>
        <div class="small">Sunggal, Medan</div>
        <div class="small">Telp: 0895333301223</div>
    </div>
    <div class="line"></div>
    <div class="row"><span>No. Struk</span><span class="bold">{{ $order->nomor_struk }}</span></div>
    <div class="row"><span>Tanggal</span><span>{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
    <div class="row"><span>Kasir</span><span>{{ $order->kasir->name ?? '-' }}</span></div>
    <div class="row"><span>Tipe</span>
        <span>{{ ['dine_in' => 'Dine-in', 'takeaway' => 'Takeaway', 'delivery' => 'Delivery'][$order->tipe_pesanan] }}</span>
    </div>
    @if($order->nomor_meja)
    <div class="row"><span>Meja</span><span>{{ $order->nomor_meja }}</span></div>
    @endif
    @if($order->nama_pelanggan)
    <div class="row"><span>Pelanggan</span><span>{{ $order->nama_pelanggan }}</span></div>
    @endif
    <div class="line"></div>
    @foreach($order->items as $item)
    <div>
        <div class="bold">{{ $item->nama_menu }}</div>
        @if($item->varian) <div class="small">  {{ $item->varian }}</div> @endif
        @if($item->catatan) <div class="small">  Catatan: {{ $item->catatan }}</div> @endif
        <div class="row">
            <span>  {{ $item->jumlah }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach
    <div class="line"></div>
    <div class="row"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
    @if($order->diskon > 0)
    <div class="row"><span>Diskon</span><span>-Rp {{ number_format($order->diskon, 0, ',', '.') }}</span></div>
    @endif
    @if($order->ongkir > 0)
    <div class="row"><span>Ongkir</span><span>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span></div>
    @endif
    <div class="line"></div>
    <div class="row bold" style="font-size:14px;">
        <span>TOTAL</span><span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
    </div>
    @if($order->metode_pembayaran === 'tunai')
    <div class="row"><span>Bayar</span><span>Rp {{ number_format($order->uang_bayar, 0, ',', '.') }}</span></div>
    <div class="row"><span>Kembalian</span><span>Rp {{ number_format($order->uang_kembalian, 0, ',', '.') }}</span></div>
    @else
    <div class="row"><span>Metode</span><span>{{ strtoupper($order->metode_pembayaran) }}</span></div>
    @endif
    <div class="line"></div>
    <div class="center small">
        <div>Terima kasih telah berkunjung!</div>
        <div>Silakan datang kembali 😊</div>
        <div style="margin-top:6px;">— BOSS KOPI Sunggal —</div>
    </div>

    <div class="no-print" style="margin-top:16px;text-align:center;">
        <button onclick="window.print()" style="padding:6px 20px;background:#4A3525;color:#fff;border:none;border-radius:6px;cursor:pointer;">
            🖨️ Cetak Struk
        </button>
        <button onclick="window.close()" style="padding:6px 20px;background:#E6D5C3;color:#4A3525;border:none;border-radius:6px;cursor:pointer;margin-left:6px;">
            Tutup
        </button>
    </div>

    <script>
        // Auto print jika dibuka sebagai popup
        if (window.opener) window.print();
    </script>
</body>
</html>
