@extends('layouts.app')
@section('title', 'Tracking Pesanan — BOSS KOPI')
@section('page-title', 'Status Pesanan')
@section('page-subtitle', 'No. ' . $order->nomor_struk)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <!-- Status Tracker -->
        @php
            $steps = [
                'pending' => ['label' => 'Pesanan Diterima', 'icon' => 'fa-circle-check', 'active' => in_array($order->status, ['pending','dikonfirmasi','diproses','siap','diantar','selesai'])],
                'diproses' => ['label' => 'Sedang Dibuat Barista', 'icon' => 'fa-mug-hot', 'active' => in_array($order->status, ['diproses','siap','diantar','selesai'])],
                'siap' => ['label' => $order->tipe_pesanan === 'delivery' ? 'Sedang Diantar Driver' : 'Siap Diambil', 'icon' => $order->tipe_pesanan === 'delivery' ? 'fa-bicycle' : 'fa-bag-shopping', 'active' => in_array($order->status, ['siap','diantar','selesai'])],
                'selesai' => ['label' => 'Pesanan Selesai', 'icon' => 'fa-star', 'active' => $order->status === 'selesai'],
            ];
        @endphp

        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-truck me-2"></i>Status Real-time</div>
            <div class="card-body">
                @if($order->status === 'pending')
                <div class="alert alert-warning d-flex align-items-center gap-2 py-2 mb-3">
                    <i class="fa fa-clock"></i>
                    <small>Pesanan masih bisa dibatalkan selama belum dikonfirmasi kedai.</small>
                </div>
                @endif

                @if($order->status === 'dibatalkan')
                    <div class="text-center py-3 text-danger">
                        <i class="fa fa-circle-xmark" style="font-size:3rem;"></i>
                        <div class="mt-2 fw-600">Pesanan Dibatalkan</div>
                        @if($order->catatan)
                        <div class="small text-muted mt-1">{{ $order->catatan }}</div>
                        @endif
                    </div>
                @else
                <div class="d-flex flex-column gap-3">
                    @foreach($steps as $key => $step)
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;
                            background:{{ $step['active'] ? 'var(--coffee)' : 'var(--latte)' }};
                            color:{{ $step['active'] ? '#fff' : 'var(--coffee)' }};">
                            <i class="fa {{ $step['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="fw-{{ $step['active'] ? '600' : '400' }}"
                                style="color:{{ $step['active'] ? 'var(--coffee)' : '#999' }};">
                                {{ $step['label'] }}
                            </div>
                        </div>
                        @if($step['active'] && !in_array($order->status, ['selesai', 'dibatalkan']))
                            @php $isCurrentStep = ($key === $order->status || ($key === 'siap' && in_array($order->status, ['siap', 'diantar']))); @endphp
                            @if($isCurrentStep)
                            <span class="badge bg-success ms-auto">Sekarang</span>
                            @endif
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Info Driver -->
        @if($order->driver && in_array($order->status, ['siap', 'diantar', 'selesai']))
        <div class="card mb-3">
            <div class="card-body d-flex align-items-center gap-3">
                <img src="{{ $order->driver->foto ? asset($order->driver->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($order->driver->name) . '&background=4A3525&color=fff&size=80' }}"
                    alt="Foto Driver" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid var(--latte);">
                <div class="flex-fill">
                    <div class="fw-700" style="color:var(--coffee);">{{ $order->driver->name }}</div>
                    <div class="small text-muted">
                        <i class="bi bi-bicycle"></i> {{ $order->driver->jenis_kendaraan ?? '-' }}
                        @if($order->driver->warna_kendaraan) ({{ $order->driver->warna_kendaraan }}) @endif
                    </div>
                    <div class="small text-muted">
                        <i class="fa fa-id-card"></i> {{ $order->driver->plat_nomor ?? '-' }}
                    </div>
                </div>
                <div class="text-end">
                    <div><i class="fa fa-star text-warning"></i> {{ number_format($order->driver->rating_rata, 1) }}</div>
                    <small class="text-muted">{{ $order->driver->jumlah_rating }} rating</small>
                </div>
            </div>
        </div>
        @endif

        <!-- Peta Live Tracking Driver -->
        @if($order->tipe_pesanan === 'delivery' && in_array($order->status, ['siap', 'diantar']))
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bicycle me-2"></i>Lokasi Driver Saat Ini</span>
                <span id="estimasiBadge" class="badge badge-coffee" style="display:none;"></span>
            </div>
            <div class="card-body p-0">
                <div id="petaTracking" class="peta-osm" style="border-radius:0 0 12px 12px;"></div>
                <div class="p-2 text-center">
                    <small id="statusLokasi" class="text-muted">Menunggu driver mengaktifkan lokasi...</small>
                </div>
            </div>
        </div>
        @endif

        @if($order->status === 'pending')
        <div class="card mb-3 border-danger">
            <div class="card-body">
                <div class="fw-600 text-danger mb-2"><i class="fa fa-xmark me-2"></i>Batalkan Pesanan</div>
                <form action="{{ route('pembeli.cancel-order', $order) }}" method="POST"
                    onsubmit="return confirm('Yakin mau batalkan pesanan ini?')">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="alasan_cancel" class="form-control form-control-sm @error('alasan_cancel') is-invalid @enderror"
                            placeholder="Alasan pembatalan (wajib diisi)..." required>
                        @error('alasan_cancel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                        Batalkan Pesanan
                    </button>
                </form>
                <small class="text-muted d-block mt-1">⚠️ Setelah pesanan dikonfirmasi kedai, tidak bisa dibatalkan lagi.</small>
            </div>
        </div>
        @endif

        <!-- Detail Pesanan -->
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Detail Pesanan</div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-6 small"><span class="text-muted">No. Struk:</span><br><code>{{ $order->nomor_struk }}</code></div>
                    <div class="col-6 small"><span class="text-muted">Tipe:</span><br>
                        {{ ['dine_in' => 'Dine-in', 'takeaway' => 'Takeaway', 'delivery' => 'Delivery'][$order->tipe_pesanan] }}
                    </div>
                    <div class="col-6 small"><span class="text-muted">Pembayaran:</span><br>{{ strtoupper($order->metode_pembayaran) }}</div>
                    <div class="col-6 small"><span class="text-muted">Waktu Pesan:</span><br>{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @foreach($order->items as $item)
                <div class="d-flex justify-content-between py-1 border-bottom small">
                    <div>{{ $item->jumlah }}x {{ $item->nama_menu }}
                        @if($item->catatan)<div class="text-muted" style="font-size:0.7rem;">{{ $item->catatan }}</div>@endif
                    </div>
                    <div class="fw-600" style="color:var(--coffee);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
                <div class="mt-2 d-flex justify-content-between fw-700" style="color:var(--coffee);">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if($order->status === 'selesai' && $order->driver_id)
            @if(!$order->rating_driver)
            <div class="card mb-3">
                <div class="card-header"><i class="fa fa-star text-warning me-2"></i>Beri Rating untuk Driver</div>
                <div class="card-body">
                    <form action="{{ route('pembeli.rating-driver', $order) }}" method="POST">
                        @csrf
                        <div class="text-center mb-3" id="starPicker">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star star-input" data-value="{{ $i }}" style="font-size:1.8rem;color:#ddd;cursor:pointer;margin:0 2px;"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" required>
                        <textarea name="komentar" class="form-control mb-3" rows="2" placeholder="Komentar (opsional)..."></textarea>
                        <button type="submit" class="btn btn-coffee w-100">Kirim Penilaian</button>
                    </form>
                </div>
            </div>
            <script>
                document.querySelectorAll('.star-input').forEach(star => {
                    star.addEventListener('click', () => {
                        const val = parseInt(star.dataset.value);
                        document.getElementById('ratingValue').value = val;
                        document.querySelectorAll('.star-input').forEach(s => {
                            s.style.color = parseInt(s.dataset.value) <= val ? '#FFC107' : '#ddd';
                        });
                    });
                });
            </script>
            @else
            <div class="card mb-3">
                <div class="card-body text-center">
                    <div class="mb-1">Rating Anda untuk driver:</div>
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa fa-star" style="color:{{ $i <= $order->rating_driver ? '#FFC107' : '#ddd' }};"></i>
                    @endfor
                    @if($order->komentar_driver)
                        <div class="small text-muted mt-1">"{{ $order->komentar_driver }}"</div>
                    @endif
                </div>
            </div>
            @endif
        @endif

        @if($order->status === 'selesai')
        <div class="d-flex gap-2">
            <form action="{{ route('pembeli.reorder', $order) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-coffee">
                    <i class="bi bi-arrow-repeat me-2"></i> Pesan Lagi
                </button>
            </form>
            <a href="{{ route('pembeli.riwayat') }}" class="btn btn-latte">
                <i class="bi bi-clock-history me-2"></i> Riwayat
            </a>
        </div>
        @else
        <div class="text-center">
            <button onclick="location.reload()" class="btn btn-latte">
                <i class="bi bi-arrow-clockwise me-2"></i> Perbarui Status
            </button>
        </div>
        @endif
    </div>
</div>
@endsection

@if($order->tipe_pesanan === 'delivery' && in_array($order->status, ['siap', 'diantar']))
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.peta-osm {
    height: 280px;
    width: 100%;
    z-index: 1;
    background: #ddd;
}
.peta-osm * { touch-action: pan-x pan-y; }
.emoji-marker { font-size: 26px; text-align: center; line-height: 30px; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3)); }
.leaflet-container { font-family: inherit; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const latToko = {{ $order->lat_toko ?? 3.579026 }};
const lngToko = {{ $order->lng_toko ?? 98.613460 }};
const latTujuan = {{ $order->lat_tujuan ?? 'null' }};
const lngTujuan = {{ $order->lng_tujuan ?? 'null' }};

const mapTracking = L.map('petaTracking', {
    dragging: true,
    touchZoom: true,
    scrollWheelZoom: true,
    doubleClickZoom: true,
    boxZoom: true,
    tap: true,
}).setView([latToko, lngToko], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 19,
}).addTo(mapTracking);

setTimeout(() => mapTracking.invalidateSize(), 300);

const iconToko = L.divIcon({ html: '☕', className: 'emoji-marker', iconSize: [30, 30] });
const iconDriver = L.divIcon({ html: '🛵', className: 'emoji-marker', iconSize: [30, 30] });
const iconTujuan = L.divIcon({ html: '🏠', className: 'emoji-marker', iconSize: [30, 30] });

L.marker([latToko, lngToko], { icon: iconToko }).addTo(mapTracking).bindPopup('BOSS KOPI');
if (latTujuan && lngTujuan) {
    L.marker([latTujuan, lngTujuan], { icon: iconTujuan }).addTo(mapTracking).bindPopup('Lokasi Anda');
}

let markerDriverTracking = null;
let garisRuteTracking = null;

// Ambil rute jalan asli (mengikuti jalan, seperti tampilan Gojek/Grab) via OSRM — gratis, tanpa API key
async function gambarRuteJalanTracking(lat1, lng1, lat2, lng2) {
    try {
        const url = `https://router.project-osrm.org/route/v1/driving/${lng1},${lat1};${lng2},${lat2}?overview=full&geometries=geojson`;
        const res = await fetch(url);
        const data = await res.json();
        if (data.routes && data.routes[0]) {
            const koordinat = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
            if (garisRuteTracking) mapTracking.removeLayer(garisRuteTracking);
            garisRuteTracking = L.polyline(koordinat, { color: '#4A3525', weight: 5, opacity: 0.85 }).addTo(mapTracking);
        }
    } catch (e) {
        console.warn('Gagal memuat rute jalan.', e);
    }
}

async function ambilLokasiDriver() {
    try {
        const res = await fetch('{{ route("pembeli.tracking.lokasi-driver", $order) }}');
        const data = await res.json();
        const statusEl = document.getElementById('statusLokasi');
        const estimasiEl = document.getElementById('estimasiBadge');

        if (!data.available) {
            statusEl.textContent = 'Menunggu driver mengaktifkan lokasi...';
            return;
        }

        const posisiBaru = !markerDriverTracking;

        if (!markerDriverTracking) {
            markerDriverTracking = L.marker([data.lat, data.lng], { icon: iconDriver }).addTo(mapTracking).bindPopup('Driver Anda');
        } else {
            markerDriverTracking.setLatLng([data.lat, data.lng]);
        }

        if (latTujuan && lngTujuan) {
            await gambarRuteJalanTracking(data.lat, data.lng, latTujuan, lngTujuan);
        }

        if (posisiBaru) {
            const bounds = [[latToko, lngToko], [data.lat, data.lng]];
            if (latTujuan && lngTujuan) bounds.push([latTujuan, lngTujuan]);
            mapTracking.fitBounds(bounds, { padding: [40, 40] });
        }

        statusEl.textContent = 'Update terakhir: ' + (data.updated_at ?? 'baru saja');

        if (data.estimasi_menit) {
            estimasiEl.textContent = '± ' + data.estimasi_menit + ' menit lagi';
            estimasiEl.style.display = '';
        }

        // Hentikan polling kalau pesanan sudah selesai
        if (data.status === 'selesai' || data.status === 'dibatalkan') {
            clearInterval(pollingInterval);
        }
    } catch (e) {
        console.error('Gagal ambil lokasi driver', e);
    }
}

ambilLokasiDriver();
const pollingInterval = setInterval(ambilLokasiDriver, 8000);
</script>
@endpush
@endif
