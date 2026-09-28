{{-- CARD UTAMA GRAFIK SENSOR --}}
<div class="glass-card p-6 mt-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Realtime Analytics</span>
            <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2 mt-0.5">
                <i class="fas fa-chart-line text-emerald-600"></i>
                Grafik Performance Sensor
            </h3>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Auto update tiap 10 detik
        </div>
    </div>

    {{-- Grid Grafik Individu (1 kolom di Mobile, 2 kolom di Medium/Desktop) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- Suhu Air --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-xs">
                        <i class="fas fa-temperature-high"></i>
                    </span>
                    Suhu Air Nutrisi
                </h4>
                <span class="text-xs font-semibold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-md">°C</span>
            </div>
            <div class="relative w-full h-60">
                <canvas id="suhuChart"></canvas>
            </div>
        </div>

        {{-- Suhu Udara --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xs">
                        <i class="fas fa-sun"></i>
                    </span>
                    Suhu Udara
                </h4>
                <span class="text-xs font-semibold text-teal-600 bg-teal-50 px-2 py-0.5 rounded-md">°C</span>
            </div>
            <div class="relative w-full h-60">
                <canvas id="suhuUdaraChart"></canvas>
            </div>
        </div>

        {{-- TDS Nutrisi --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fas fa-seedling"></i>
                    </span>
                    TDS Nutrisi
                </h4>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">PPM</span>
            </div>
            <div class="relative w-full h-60">
                <canvas id="tdsChart"></canvas>
            </div>
        </div>

        {{-- Kelembaban Udara --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-xs">
                        <i class="fas fa-wind"></i>
                    </span>
                    Kelembaban Udara
                </h4>
                <span class="text-xs font-semibold text-violet-600 bg-violet-50 px-2 py-0.5 rounded-md">% RH</span>
            </div>
            <div class="relative w-full h-60">
                <canvas id="kelembabanUdaraChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik Gabungan Sensor --}}
    <div class="bg-white rounded-2xl p-5 md:p-6 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                    <i class="fas fa-chart-area"></i>
                </span>
                Grafik Monitoring Gabungan Sensor
            </h4>
        </div>
        <div class="relative w-full h-80 md:h-96">
            <canvas id="combinedChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    let suhuChart, tdsChart, phChart, combinedChart, suhuUdaraChart, kelembabanUdaraChart;

    // Helper untuk membuat opsi chart standar yang bersih
    function createChartConfig(label, data, waktu, colorHex, fillBgHex) {
        return {
            type: 'line',
            data: {
                labels: waktu || [],
                datasets: [{
                    label: label,
                    data: data || [],
                    borderColor: colorHex,
                    backgroundColor: fillBgHex || 'transparent',
                    fill: !!fillBgHex,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointRadius: 2,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                    },
                    y: {
                        beginAtZero: false,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                    }
                }
            }
        };
    }

    function initCharts(waktu, suhu, tds, ph, suhuudara, kelembaban) {
        // Safe Canvas Initialization Check
        const ctxSuhu = document.getElementById('suhuChart');
        const ctxTds = document.getElementById('tdsChart');
        const ctxPh = document.getElementById('phChart');
        const ctxSuhuUdara = document.getElementById('suhuUdaraChart');
        const ctxKelembaban = document.getElementById('kelembabanUdaraChart');
        const ctxCombined = document.getElementById('combinedChart');

        if (ctxSuhu) suhuChart = new Chart(ctxSuhu, createChartConfig('Suhu Air (°C)', suhu, waktu, '#ef4444', 'rgba(239, 68, 68, 0.05)'));
        if (ctxTds) tdsChart = new Chart(ctxTds, createChartConfig('TDS (ppm)', tds, waktu, '#10b981', 'rgba(16, 185, 129, 0.05)'));
        if (ctxPh) phChart = new Chart(ctxPh, createChartConfig('pH', ph, waktu, '#f59e0b', 'rgba(245, 158, 11, 0.05)'));
        if (ctxSuhuUdara) suhuUdaraChart = new Chart(ctxSuhuUdara, createChartConfig('Suhu Udara (°C)', suhuudara, waktu, '#0d9488', 'rgba(13, 148, 136, 0.05)'));
        if (ctxKelembaban) kelembabanUdaraChart = new Chart(ctxKelembaban, createChartConfig('Kelembaban Udara (%)', kelembaban, waktu, '#8b5cf6', 'rgba(139, 92, 246, 0.05)'));

        if (ctxCombined) {
            combinedChart = new Chart(ctxCombined, {
                type: 'line',
                data: {
                    labels: waktu || [],
                    datasets: [
                        { label: 'Suhu Air (°C)', data: suhu || [], borderColor: '#ef4444', tension: 0.4, borderWidth: 2, pointRadius: 0 },
                        { label: 'TDS (ppm)', data: tds || [], borderColor: '#10b981', tension: 0.4, borderWidth: 2, pointRadius: 0 },
                        { label: 'Suhu Udara (°C)', data: suhuudara || [], borderColor: '#0d9488', tension: 0.4, borderWidth: 2, pointRadius: 0 },
                        { label: 'Kelembaban (%)', data: kelembaban || [], borderColor: '#8b5cf6', tension: 0.4, borderWidth: 2, pointRadius: 0 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { font: { size: 11 }, usePointStyle: true } }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#94a3b8' } },
                        y: { beginAtZero: false, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, color: '#94a3b8' } }
                    }
                }
            });
        }
    }

    async function fetchDataAndUpdateCharts() {
        try {
            const response = await fetch('{{ url('/sensor/live') }}', { cache: 'no-store' });
            if (!response.ok) return;
            const result = await response.json();
            
            const { waktu = [], suhu = [], tds = [], ph = [], suhuudara = [], kelembaban = [] } = result;

            const updateSingleChart = (chart, data) => {
                if (!chart) return;
                chart.data.labels = waktu;
                chart.data.datasets[0].data = data;
                chart.update('none'); // Update smooth tanpa re-animasi penuh
            };

            updateSingleChart(suhuChart, suhu);
            updateSingleChart(tdsChart, tds);
            updateSingleChart(phChart, ph);
            updateSingleChart(suhuUdaraChart, suhuudara);
            updateSingleChart(kelembabanUdaraChart, kelembaban);

            if (combinedChart) {
                combinedChart.data.labels = waktu;
                if (combinedChart.data.datasets[0]) combinedChart.data.datasets[0].data = suhu;
                if (combinedChart.data.datasets[1]) combinedChart.data.datasets[1].data = tds;
                if (combinedChart.data.datasets[2]) combinedChart.data.datasets[2].data = suhuudara;
                if (combinedChart.data.datasets[3]) combinedChart.data.datasets[3].data = kelembaban;
                combinedChart.update('none');
            }
        } catch (e) {
            console.debug('Chart fetch update error:', e);
        }
    }

    // Inisialisasi Pertama
    fetch('{{ url('/sensor/live') }}', { cache: 'no-store' })
        .then(res => res.json())
        .then(data => {
            initCharts(data.waktu, data.suhu, data.tds, data.ph, data.suhuudara, data.kelembaban);
        })
        .catch(err => console.debug('Chart init error:', err));

    // Interval Update Realtime
    setInterval(fetchDataAndUpdateCharts, 10000);
})();
</script>
@endpush