@extends('layouts.app')

@section('title', 'Dashboard Hidroponik - SIKECE')

@section('content')
<div class="mb-3 d-flex justify-content-end">
    <a href="{{ url('/disease-analysis') }}" class="btn btn-outline-primary">
        <i class="fa-solid fa-chart-line me-1"></i> Analisis Kondisi & Penyakit
    </a>
</div>
<div class="space-y-6">
    

    {{-- STATUS RINGKAS --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="modern-stat">
            <div class="stat-icon bg-emerald-100 text-emerald-600"><i class="fas fa-droplet"></i></div>
            <div><p class="stat-label">TDS Terakhir</p><p class="stat-value"><span id="dashboardTds">{{ $latestSensor?->tds !== null ? number_format($latestSensor->tds, 0) : '-' }}</span> <small>ppm</small></p></div>
            <span class="stat-dot bg-emerald-500"></span>
        </div>
        <div class="modern-stat">
            <div class="stat-icon bg-sky-100 text-sky-600"><i class="fas fa-temperature-half"></i></div>
            <div><p class="stat-label">Suhu Air</p><p class="stat-value"><span id="dashboardSuhu">{{ $latestSensor?->suhu !== null ? number_format($latestSensor->suhu, 1) : '-' }}</span> <small>°C</small></p></div>
            <span class="stat-dot bg-sky-500"></span>
        </div>
        <div class="modern-stat">
            <div class="stat-icon bg-violet-100 text-violet-600"><i class="fas fa-wind"></i></div>
            <div><p class="stat-label">Kelembaban</p><p class="stat-value"><span id="dashboardHumidity">{{ $latestSensor?->kelembaban !== null ? number_format($latestSensor->kelembaban, 1) : '-' }}</span> <small>%</small></p></div>
            <span class="stat-dot bg-violet-500"></span>
        </div>
        <div class="modern-stat">
            <div class="stat-icon bg-rose-100 text-rose-600"><i class="fas fa-virus"></i></div>
            <div><p class="stat-label">Deteksi Hari Ini</p><p class="stat-value">{{ $diseaseToday }} <small>kasus</small></p></div>
            <span class="stat-dot bg-rose-500"></span>
        </div>
    </section>

    {{-- MONITORING + PENYAKIT --}}
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2">
            @include('dashboard.cards.status-system')
        </div>
        <div class="disease-highlight">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">AI Plant Health</p>
                    <h2 class="text-xl font-extrabold text-gray-900">Deteksi Penyakit</h2>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fas fa-leaf text-lg"></i></div>
            </div>
            @if($latestDisease)
                <div class="flex gap-4 items-center">
                    @if($latestDisease->image_path)
                        <img src="{{ asset($latestDisease->image_path) }}" class="w-24 h-24 rounded-2xl object-cover border border-gray-100" alt="Foto daun">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400"><i class="fas fa-image text-2xl"></i></div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500">Deteksi terakhir</p>
                        <h3 class="text-lg font-extrabold text-gray-900 truncate">{{ ucwords(str_replace(['_', '-'], ' ', $latestDisease->disease)) }}</h3>
                        <p class="text-sm text-gray-500">Confidence <b class="text-emerald-600">{{ number_format((float)$latestDisease->confidence * (abs((float)$latestDisease->confidence) <= 1 ? 100 : 1), 2) }}%</b></p>
                        <p class="text-xs text-gray-400 mt-1">{{ optional($latestDisease->detected_at)->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            @else
                <div class="rounded-2xl bg-gray-50 border border-dashed border-gray-200 p-6 text-center">
                    <i class="fas fa-camera text-3xl text-gray-300 mb-3"></i>
                    <p class="font-semibold text-gray-700">Belum ada deteksi penyakit</p>
                    <p class="text-sm text-gray-500 mt-1">Gunakan aplikasi mobile untuk memotret daun.</p>
                </div>
            @endif
            <div class="grid grid-cols-2 gap-3 mt-5">
                <div class="mini-stat"><span>Total sakit</span><b>{{ $diseaseCount }}</b></div>
                <div class="mini-stat"><span>Healthy</span><b>{{ $healthyCount }}</b></div>
            </div>
            <a href="{{ url('/disease-detections') }}" class="mt-4 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 text-white py-3 font-bold hover:bg-gray-800 transition">
                Lihat Riwayat Deteksi <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </section>

    {{-- TANAMAN --}}
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1">@include('dashboard.cards.kondisi-tanaman')</div>
        <div class="xl:col-span-2">@include('dashboard.metrics.live-sensor')</div>
    </section>

    {{-- FILTER + GRAFIK --}}
    <section class="glass-card p-5 md:p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
            <div><p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Historical Monitoring</p><h2 class="text-xl font-extrabold text-gray-900">Tren Sensor</h2></div>
            <div class="flex items-center gap-2">
                <select id="filterSelector" class="form-input !py-2 !px-3">
                    <option value="hari" {{ $filter === 'hari' ? 'selected' : '' }}>Hari ini</option>
                    <option value="minggu" {{ $filter === 'minggu' ? 'selected' : '' }}>Minggu ini</option>
                    <option value="bulan" {{ $filter === 'bulan' ? 'selected' : '' }}>Bulan ini</option>
                </select>
                <button id="exportBtn" type="button" class="btn-primary !py-2 !px-4"><i class="fas fa-download"></i><span class="hidden sm:inline">Export</span></button>
            </div>
        </div>
        @include('dashboard.cards.chart-sensor')
    </section>

    @include('dashboard.cards.pengaturan-system')
</div>
@endsection

@push('scripts')
@include('layouts.partials.scripts')
<script>
(function () {
    const filter = document.getElementById('filterSelector');
    const exportBtn = document.getElementById('exportBtn');
    if (filter) filter.addEventListener('change', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('filter', this.value);
        window.location.href = url.toString();
    });
    if (exportBtn) exportBtn.addEventListener('click', function () {
        window.location.href = '{{ url('/export-excel') }}?filter=' + encodeURIComponent(filter?.value || 'hari');
    });

    async function refreshDashboardSummary() {
        try {
            const r = await fetch('{{ url('/sensor/live') }}', {cache:'no-store'});
            if (!r.ok) return;
            const d = await r.json();
            const set = (id, value) => { const el=document.getElementById(id); if(el && value !== undefined && value !== null) el.textContent=value; };
            if (Array.isArray(d.tds) && d.tds.length) set('dashboardTds', Number(d.tds[d.tds.length-1]).toFixed(0));
            if (Array.isArray(d.suhu) && d.suhu.length) set('dashboardSuhu', Number(d.suhu[d.suhu.length-1]).toFixed(1));
            if (Array.isArray(d.kelembaban) && d.kelembaban.length) set('dashboardHumidity', Number(d.kelembaban[d.kelembaban.length-1]).toFixed(1));
        } catch(e) { console.debug('Dashboard summary:', e); }
    }
    refreshDashboardSummary();
    setInterval(refreshDashboardSummary, 10000);
})();
</script>
@endpush
