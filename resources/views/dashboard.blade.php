<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Hidroponik</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px auto;
            width: 95%;
        }
        .chart-box {
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 8px;
        }
        canvas {
            max-height: 350px;
         }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Monitoring Sensor Hidroponik (Live Update)</h2>
<h3>Pengaturan Sistem</h3>
<form method="POST" action="/api/update-batas">
    <label>TDS Minimum (ppm): <input type="number" name="tds_min" value="700"></label><br>
    <label>Air Minimum (cm): <input type="number" name="air_min" value="10"></label><br>
    <label>Interval Simpan Data (ms): <input type="number" name="interval" value="10000"></label><br>
    <button type="submit">Simpan</button>
</form>
    <div class="chart-container">
        <div class="chart-box">
            <h4>Grafik Suhu</h4>
            <canvas id="suhuChart"></canvas>
        </div>
        <div class="chart-box">
            <h4>Grafik TDS</h4>
            <canvas id="tdsChart"></canvas>
        </div>
        <div class="chart-box">
            <h4>Grafik pH</h4>
            <canvas id="phChart"></canvas>
        </div>
        <div class="chart-box">
            <h4>Grafik Suhu Udara</h4>
            <canvas id="suhuUdaraChart"></canvas>
        </div>
    </div>

    <div style="width: 90%; margin: 30px auto;">
        <h4>Gabungan Suhu, TDS & pH</h4>
        <canvas id="combinedChart"></canvas>
    </div>

    <script>
        let suhuChart, tdsChart, phChart, combinedChart,suhuUdaraChart;

        function initCharts(waktu, suhu, tds, ph,suhuUdara) {
            const commonOptions = (label, data, color) => ({
                type: 'line',
                data: {
                    labels: waktu,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        fill: false,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            suhuUdaraChart = new Chart(document.getElementById('suhuUdaraChart'), commonOptions('Suhu Udara (°C)', suhuUdara, 'orange'));
            suhuChart = new Chart(document.getElementById('suhuChart'), commonOptions('Suhu (°C)', suhu, 'red'));
            tdsChart = new Chart(document.getElementById('tdsChart'), commonOptions('TDS (ppm)', tds, 'blue'));
            phChart = new Chart(document.getElementById('phChart'), {
                type: 'line',
                data: {
                    labels: waktu,
                    datasets: [{
                        label: 'pH',
                        data: ph,
                        borderColor: 'green',
                        fill: false,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 14
                        }
                    }
                }
            });

            combinedChart = new Chart(document.getElementById('combinedChart'), {
                type: 'line',
                data: {
                    labels: waktu,
                    datasets: [
                        { label: 'Suhu (°C)', data: suhu, borderColor: 'red', fill: false, tension: 0.3 },
                        { label: 'TDS (ppm)', data: tds, borderColor: 'blue', fill: false, tension: 0.3 },
                        { label: 'pH', data: ph, borderColor: 'green', fill: false, tension: 0.3 }
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

            const { waktu, suhu, tds, ph,suhuUdara } = result;

            const updateChart = (chart, data) => {
                chart.data.labels = waktu;
                chart.data.datasets[0].data = data;
                chart.update();
            };

            updateChart(suhuChart, suhu);
            updateChart(tdsChart, tds);
            updateChart(phChart, ph);
            updateChart(suhuUdaraChart, suhuUdara);

            // Update combined chart
            combinedChart.data.labels = waktu;
            combinedChart.data.datasets[0].data = suhu;
            combinedChart.data.datasets[1].data = tds;
            combinedChart.data.datasets[2].data = ph;
            combinedChart.update();
        }

        // Init once with empty/fetched data
        fetch('/sensor/live')
            .then(res => res.json())
            .then(data => initCharts(data.waktu, data.suhu, data.tds, data.ph, data.suhuUdara));

        // Live update every 5 seconds
        setInterval(fetchDataAndUpdateCharts, 10000);
    </script>
</body>
</html>
