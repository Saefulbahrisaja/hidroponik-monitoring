<div class="glass-card p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-chart-line mr-2 text-purple-600"></i> Grafik Sensor (TDS & Suhu)
    </h3>
        <div class="grid grid-cols-2 lg:grid-cols-2 gap-8 mb-8">
            <div class="chart-container">
                <h4 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-red-500"></i>
                    Suhu Air Nutrisi
                </h4>
                <canvas id="suhuChart" class="w-full h-64"></canvas>
            </div>
            <div class="chart-container">
                <h4 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-blue-500"></i>
                    Suhu Udara
                </h4>
                <canvas id="suhuUdaraChart" class="w-full h-64"></canvas>
            </div>
            <div class="chart-container">
                <h4 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-green-500"></i>
                    TDS Nutrisi
                </h4>
                <canvas id="tdsChart" class="w-full h-64"></canvas>
            </div>
            <div class="chart-container">
                <h4 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-purple-500"></i>
                    Kelembaban Udara
                </h4>
                <canvas id="kelembabanUdaraChart" class="w-full h-64"></canvas>
            </div>
        </div>
        <div class="chart-container">
            <h4 class="text-xl font-semibold mb-4 text-center">
                <i class="fas fa-chart-area mr-2"></i>
                Grafik Gabungan Sensor
            </h4>
            <canvas id="combinedChart" class="w-full h-80"></canvas>
        </div>
</div>


@push('scripts')
<script>

    let suhuChart, tdsChart, phChart, combinedChart, suhuUdaraChart, kelembabanUdaraChart;
    const filterSelector = document.getElementById('filterSelector');
    const exportBtn = document.getElementById('exportBtn');

    function initCharts(waktu, suhu, tds, ph, suhuudara, kelembaban) {
        const commonOptions = (label, data, color) => ({
            type: 'line',
            data: {
                labels: waktu,
                datasets: [{ label: label, data: data, borderColor: color, fill: false, tension: 0.3 }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        suhuChart = new Chart(document.getElementById('suhuChart'), commonOptions('Suhu Air (°C)', suhu, 'rgb(251, 65, 65)'));
        tdsChart = new Chart(document.getElementById('tdsChart'), commonOptions('TDS (ppm)', tds, 'rgb(120, 200, 65)'));
        phChart = new Chart(document.getElementById('phChart'), commonOptions('pH', ph, 'rgb(255, 155, 47)'));
        suhuUdaraChart = new Chart(document.getElementById('suhuUdaraChart'), commonOptions('Suhu Udara (°C)', suhuudara, 'rgb(3, 167, 145)'));
        kelembabanUdaraChart = new Chart(document.getElementById('kelembabanUdaraChart'), commonOptions('Kelembaban Udara (%)', kelembaban, 'rgb(177, 59, 255)'));

        combinedChart = new Chart(document.getElementById('combinedChart'), {
            type: 'line',
            data: {
                labels: waktu,
                datasets: [
                    { label: 'Suhu Air (°C)', data: suhu, borderColor: 'rgb(251, 65, 65)', fill: false, tension: 0.3 },
                    { label: 'TDS (ppm)', data: tds, borderColor: 'rgb(120, 200, 65)', fill: false, tension: 0.3 },
                    { label: 'pH', data: ph, borderColor: 'rgb(255, 155, 47)', fill: false, tension: 0.3 },
                    { label: 'Suhu Udara (°C)', data: suhuudara, borderColor: 'rgb(3, 167, 145)', fill: false, tension: 0.3 },
                    { label: 'Kelembaban Udara (%)', data: kelembaban, borderColor: 'rgb(177, 59, 255)', fill: false, tension: 0.3 }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Grafik Gabungan' },
                    legend: { position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true },
                    x: { title: { display: true, text: 'Waktu' } }
                }
            }
        });
    }

    async function fetchDataAndUpdateCharts() {
        const response = await fetch('/sensor/live');
        const result = await response.json();
        const { waktu, suhu, tds, ph, suhuudara, kelembaban } = result;

        const updateChart = (chart, data) => {
            chart.data.labels = waktu;
            chart.data.datasets[0].data = data;
            chart.update();
        };

        updateChart(suhuChart, suhu);
        updateChart(tdsChart, tds);
        updateChart(phChart, ph);
        updateChart(suhuUdaraChart, suhuudara);
        updateChart(kelembabanUdaraChart, kelembaban);

        combinedChart.data.labels = waktu;
        combinedChart.data.datasets[0].data = suhu;
        combinedChart.data.datasets[1].data = tds;
        combinedChart.data.datasets[2].data = ph;
        combinedChart.data.datasets[3].data = suhuudara;
        combinedChart.data.datasets[4].data = kelembaban;
        combinedChart.update();
    }

    fetch('/sensor/live')
        .then(res => res.json())
        .then(data => initCharts(data.waktu, data.suhu, data.tds, data.ph, data.suhuudara, data.kelembaban));

    setInterval(fetchDataAndUpdateCharts, 10000);

</script>
@endpush
