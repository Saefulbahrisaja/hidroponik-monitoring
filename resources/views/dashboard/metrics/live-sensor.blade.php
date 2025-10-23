<div class="glass-card p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-microchip mr-2 text-green-600"></i> Sensor Aktif (Realtime)
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-6 gap-6 text-center">
        <!-- TDS -->
        <div class="p-4 bg-emerald-100 rounded-xl shadow-md border border-emerald-200">
            <p class="text-emerald-700 font-semibold">TDS Air</p>
            <p id="tds" class="text-3xl font-bold text-emerald-800">-- ppm</p>
        </div>

        <!-- Suhu Air -->
        <div class="p-4 bg-sky-100 rounded-xl shadow-md border border-sky-200">
            <p class="text-sky-700 font-semibold">Suhu Air</p>
            <p id="suhuAir" class="text-3xl font-bold text-sky-800">-- °C</p>
        </div>

        <!-- Suhu Udara -->
        <div class="p-4 bg-cyan-100 rounded-xl shadow-md border border-cyan-200">
            <p class="text-cyan-700 font-semibold">Suhu Udara</p>
            <p id="suhuudara" class="text-3xl font-bold text-cyan-800">-- °C</p>
        </div>

        <!-- Kelembaban -->
        <div class="p-4 bg-indigo-100 rounded-xl shadow-md border border-indigo-200">
            <p class="text-indigo-700 font-semibold">Kelembaban</p>
            <p id="kelembaban" class="text-3xl font-bold text-indigo-800">-- %</p>
        </div>

        <!-- Level Air -->
        <div class="p-4 bg-amber-100 rounded-xl shadow-md border border-amber-200">
            <p class="text-amber-700 font-semibold">Level Air</p>
            <p id="levelair" class="text-3xl font-bold text-amber-800">-- %</p>
        </div>

        <!-- Update -->
        <div class="p-4 bg-slate-100 rounded-xl shadow-md border border-slate-200">
            <p class="text-slate-600 font-semibold">Update</p>
            <p id="waktuUpdate" class="text-2xl font-bold text-slate-800">--</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function updateLiveSensor() {
    try {
        const response = await fetch('/sensor/live');
        const data = await response.json();
        
        const last = arr => Array.isArray(arr) && arr.length ? arr[arr.length - 1] : null;

        document.getElementById("tds").textContent = (last(data.tds)?.toFixed(0) || '--') + " ppm";
        document.getElementById("suhuAir").textContent = (last(data.suhu)?.toFixed(1) || '--') + " °C";
        document.getElementById("suhuudara").textContent = (last(data.suhuudara)?.toFixed(1) || '--') + " °C";
        document.getElementById("kelembaban").textContent = (last(data.kelembaban)?.toFixed(1) || '--') + " %";
        document.getElementById("levelair").textContent = (last(data.level_air)?.toFixed(2) || '--') + " %";

        const lastTime = data.waktu?.length ? data.waktu[data.waktu.length - 1] : null;
        document.getElementById("waktuUpdate").textContent = lastTime || '--';
    } catch (err) {
        console.error('Gagal mengambil data live sensor:', err);
    }
}

// Jalankan pertama kali
updateLiveSensor();

// Update tiap 5 detik
setInterval(updateLiveSensor, 5000);
</script>
@endpush
