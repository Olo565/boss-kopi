@extends('layouts.app')
@section('title', 'Detail Pengiriman — BOSS KOPI')
@section('page-title', 'Detail Pengiriman')
@section('page-subtitle', $order->nomor_struk)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <!-- Info Pelanggan -->
        <div class="card mb-3">
            <div class="card-header"><i class="fa fa-user me-2"></i>Info Pelanggan</div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="fw-600">{{ $order->nama_pelanggan ?? $order->user->name ?? '-' }}</div>
                        <div class="small text-muted">{{ $order->no_hp_pelanggan ?? $order->user->no_hp ?? '-' }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        @php $hp = $order->no_hp_pelanggan ?? $order->user->no_hp ?? null; @endphp
                        @if($hp)
                        <a href="tel:{{ $hp }}" class="btn btn-sm btn-latte" title="Telepon">
                            <i class="fa fa-phone"></i>
                        </a>
                        <a href="https://wa.me/62{{ ltrim($hp, '0') }}" target="_blank"
                            class="btn btn-sm" style="background:#25D366;color:#fff;" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="p-3 rounded" style="background:var(--latte);">
                    <div class="small fw-600 mb-1"><i class="fa fa-location-dot me-2"></i>Alamat Tujuan:</div>
                    <div>{{ $order->alamat_delivery }}</div>
                </div>

                <!-- Peta Navigasi -->
                <div id="petaPengantaran" class="peta-osm"></div>

                <div class="mt-2 d-flex gap-2">
                    @if($order->lat_tujuan && $order->lng_tujuan)
                    <a href="https://www.openstreetmap.org/directions?from={{ $order->lat_toko ?? 3.579026 }}%2C{{ $order->lng_toko ?? 98.613460 }}&to={{ $order->lat_tujuan }}%2C{{ $order->lng_tujuan }}"
                        target="_blank" class="btn btn-coffee btn-sm flex-fill">
                        <i class="fa fa-map me-2"></i> Buka Rute Lengkap
                    </a>
                    @else
                    <a href="https://maps.google.com/?q={{ urlencode($order->alamat_delivery) }}"
                        target="_blank" class="btn btn-coffee btn-sm flex-fill">
                        <i class="fa fa-map me-2"></i> Cari Alamat di Maps
                    </a>
                    @endif
                    <button id="btnShareLokasi" type="button" class="btn btn-success btn-sm flex-fill" onclick="mulaiShareLokasi()">
                        <i class="bi bi-broadcast me-2"></i> Mulai Bagikan Lokasi
                    </button>
                </div>
                <small id="statusShareLokasi" class="text-muted d-block mt-1"></small>
            </div>
        </div>

        <!-- Item Pesanan -->
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-bag me-2"></i>Item yang Dibawa</div>
            <div class="card-body p-0">
                @foreach($order->items as $item)
                <div class="d-flex justify-content-between p-3 border-bottom">
                    <div>
                        <div class="fw-600 small">{{ $item->jumlah }}x {{ $item->nama_menu }}</div>
                        @if($item->catatan)<div class="text-muted" style="font-size:0.75rem;">{{ $item->catatan }}</div>@endif
                    </div>
                    <div class="fw-600 small" style="color:var(--coffee);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
                <div class="p-3 d-flex justify-content-between fw-700" style="color:var(--coffee);">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Bukti Pengiriman -->
        <div class="card">
            <div class="card-header"><i class="fa fa-camera me-2"></i>Bukti Pengiriman</div>
            <div class="card-body">
                <p class="text-muted small">Foto saat menyerahkan pesanan ke pelanggan sebagai bukti pengiriman.</p>
                <form action="{{ route('driver.selesai', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600">Foto Bukti Pengiriman *</label>
                        <input type="file" name="bukti_foto" class="form-control @error('bukti_foto') is-invalid @enderror"
                            accept="image/*" capture="environment" required onchange="previewFoto(this)">
                        @error('bukti_foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <img id="fotoPrev" src="" style="display:none;width:100%;border-radius:8px;margin-top:0.5rem;max-height:200px;object-fit:cover;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <input type="text" name="catatan" class="form-control" placeholder="Opsional...">
                    </div>
                    <button type="submit" class="btn btn-coffee w-100 py-3"
                        onclick="return confirm('Konfirmasi pesanan sudah diterima pelanggan?')"
                        style="font-size:1rem;">
                        <i class="fa fa-circle-check me-2"></i> Konfirmasi Pesanan Terkirim
                    </button>
                </form>

                {{-- Tombol Cancel seperti Gojek --}}
                <div class="mt-3 pt-3 border-top">
                    <button class="btn btn-outline-danger w-100" type="button"
                        onclick="document.getElementById('formCancelDriver').style.display = document.getElementById('formCancelDriver').style.display === 'none' ? '' : 'none'">
                        <i class="fa fa-xmark me-2"></i> Ada Masalah / Batalkan Pengantaran
                    </button>

                    <div id="formCancelDriver" style="display:none;" class="mt-3 p-3 rounded" style="background:#fff5f5;">
                        <div class="fw-600 small text-danger mb-2">Pilih alasan pembatalan:</div>
                        <form action="{{ route('driver.cancel-pengantaran', $order) }}" method="POST"
                            onsubmit="return confirm('Yakin batalkan pengantaran ini? Pesanan akan dikembalikan ke antrian.')">
                            @csrf
                            <div class="d-flex flex-column gap-2 mb-3">
                                @foreach([
                                    'Pelanggan tidak bisa dihubungi',
                                    'Alamat tidak ditemukan / tidak jelas',
                                    'Motor/kendaraan bermasalah',
                                    'Jarak terlalu jauh',
                                    'Pelanggan minta dibatalkan',
                                    'Kondisi cuaca tidak memungkinkan',
                                ] as $alasan)
                                <label class="d-flex align-items-center gap-2" style="cursor:pointer;">
                                    <input type="radio" name="alasan" value="{{ $alasan }}" required>
                                    <span class="small">{{ $alasan }}</span>
                                </label>
                                @endforeach
                                <label class="d-flex align-items-center gap-2" style="cursor:pointer;">
                                    <input type="radio" name="alasan" value="lainnya" id="radioLainnya"
                                        onclick="document.getElementById('alasanLainnya').style.display=''">
                                    <span class="small">Lainnya...</span>
                                </label>
                                <input type="text" id="alasanLainnya" name="alasan_lain" class="form-control form-control-sm"
                                    placeholder="Tulis alasan..." style="display:none;">
                            </div>
                            <button type="submit" class="btn btn-danger w-100 btn-sm">
                                <i class="fa fa-xmark me-1"></i> Konfirmasi Batalkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.peta-osm {
    height: 280px;
    width: 100%;
    border-radius: 10px;
    margin-top: 0.75rem;
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
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('fotoPrev');
            img.src = e.target.result;
            img.style.display = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// === PETA & GPS TRACKING ===
const latToko = {{ $order->lat_toko ?? 3.579026 }};
const lngToko = {{ $order->lng_toko ?? 98.613460 }};
const latTujuan = {{ $order->lat_tujuan ?? 'null' }};
const lngTujuan = {{ $order->lng_tujuan ?? 'null' }};

const map = L.map('petaPengantaran', {
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
}).addTo(map);

// Pastikan peta dirender ulang dengan ukuran yang benar (mengatasi peta tidak bisa digeser/blank saat pertama load)
setTimeout(() => map.invalidateSize(), 300);

const iconToko = L.divIcon({ html: '☕', className: 'emoji-marker', iconSize: [30, 30] });
const iconDriver = L.divIcon({ html: '🛵', className: 'emoji-marker', iconSize: [30, 30] });
const iconTujuan = L.divIcon({ html: '📍', className: 'emoji-marker', iconSize: [30, 30] });

L.marker([latToko, lngToko], { icon: iconToko }).addTo(map).bindPopup('BOSS KOPI (Toko)');

let markerDriver = null;
let markerTujuan = null;
let garisRute = null;
const bounds = [[latToko, lngToko]];

if (latTujuan && lngTujuan) {
    markerTujuan = L.marker([latTujuan, lngTujuan], { icon: iconTujuan }).addTo(map).bindPopup('Tujuan Pelanggan');
    bounds.push([latTujuan, lngTujuan]);
    gambarRuteJalan(latToko, lngToko, latTujuan, lngTujuan);
}

if (bounds.length > 1) {
    map.fitBounds(bounds, { padding: [40, 40] });
}

// Ambil rute jalan asli (mengikuti jalan, bukan garis lurus) via OSRM — gratis, tanpa API key
async function gambarRuteJalan(lat1, lng1, lat2, lng2) {
    try {
        const url = `https://router.project-osrm.org/route/v1/driving/${lng1},${lat1};${lng2},${lat2}?overview=full&geometries=geojson`;
        const res = await fetch(url);
        const data = await res.json();
        if (data.routes && data.routes[0]) {
            const koordinat = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
            if (garisRute) map.removeLayer(garisRute);
            garisRute = L.polyline(koordinat, { color: '#4A3525', weight: 5, opacity: 0.85 }).addTo(map);
            map.fitBounds(garisRute.getBounds(), { padding: [40, 40] });
        }
    } catch (e) {
        console.warn('Gagal memuat rute jalan, menampilkan garis lurus sebagai gantinya.', e);
    }
}

let watchId = null;
function mulaiShareLokasi() {
    const btn = document.getElementById('btnShareLokasi');
    const status = document.getElementById('statusShareLokasi');

    if (!navigator.geolocation) {
        status.textContent = 'GPS tidak didukung di perangkat ini.';
        status.className = 'text-danger d-block mt-1';
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-broadcast me-2"></i> Lokasi Aktif Dibagikan...';
    btn.classList.remove('btn-success');
    btn.classList.add('btn-secondary');
    status.textContent = 'Mengirim lokasi Anda secara berkala ke pelanggan...';
    status.className = 'text-success d-block mt-1';

    watchId = navigator.geolocation.watchPosition(
        (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            if (!markerDriver) {
                markerDriver = L.marker([lat, lng], { icon: iconDriver }).addTo(map).bindPopup('Posisi Anda');
            } else {
                markerDriver.setLatLng([lat, lng]);
            }
            map.panTo([lat, lng]);

            // Perbarui rute jalan dari posisi driver terbaru ke tujuan
            if (latTujuan && lngTujuan) {
                gambarRuteJalan(lat, lng, latTujuan, lngTujuan);
            }

            fetch('{{ route("driver.update-location", $order) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ lat, lng })
            }).catch(() => {});
        },
        (err) => {
            status.textContent = 'Gagal mengakses GPS: ' + err.message;
            status.className = 'text-danger d-block mt-1';
        },
        { enableHighAccuracy: true, maximumAge: 5000, timeout: 10000 }
    );
}

// Hentikan watch GPS saat tinggalkan halaman
window.addEventListener('beforeunload', () => {
    if (watchId !== null) navigator.geolocation.clearWatch(watchId);
});
</script>
@endpush
