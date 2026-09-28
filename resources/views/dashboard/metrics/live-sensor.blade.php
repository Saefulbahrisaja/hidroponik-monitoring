{{-- CARD UTAMA LIVE SENSOR REALTIME --}}
<div class="glass-card p-6 border border-slate-200/80 shadow-sm rounded-2xl bg-white">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Telemetry Stream</span>
            <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2 mt-0.5">
                <i class="fas fa-microchip text-emerald-600"></i>
                Sensor Aktif (Realtime)
            </h3>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
            <span>Live Sync</span>
        </div>
    </div>

    {{-- Grid Metric Cards (2 Kolom di Mobile, 3 di Tablet, 6 di Desktop) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3.5 sm:gap-4">
        
        {{-- TDS Air --}}
        <div class="p-4 bg-emerald-50/70 border border-emerald-100/80 rounded-2xl flex flex-col justify-between text-center transition-all hover:border-emerald-300 hover:shadow-sm">
            <div class="flex items-center justify-center gap-1.5 text-emerald-700 mb-1">
                <i class="fas fa-flask text-xs"></i>
                <span class="text-xs font-bold uppercase tracking-wider">TDS Air</span>
            </div>
            <div class="my-1">
                <p id="tds" class="text-2xl sm:text-3xl font-black text-emerald-900 tracking-tight">--</p>
            </div>
            <span class="text-[11px] font-semibold text-emerald-600/80">Nutrisi (PPM)</span>
        </div>

        {{-- Suhu Air --}}
        <div class="p-4 bg-sky-50/70 border border-sky-100/80 rounded-2xl flex flex-col justify-between text-center transition-all hover:border-sky-300 hover:shadow-sm">
            <div class="flex items-center justify-center gap-1.5 text-sky-700 mb-1">
                <i class="fas fa-temperature-half text-xs"></i>
                <span class="text-xs font-bold uppercase tracking-wider">Suhu Air</span>
            </div>
            <div class="my-1">
                <p id="suhuAir" class="text-2xl sm:text-3xl font-black text-sky-900 tracking-tight">--</p>
            </div>
            <span class="text-[11px] font-semibold text-sky-600/80">Derajat Celcius (°C)</span>
        </div>

        {{-- Suhu Udara --}}
        <div class="p-4 bg-teal-50/70 border border-teal-100/80 rounded-2xl flex flex-col justify-between text-center transition-all hover:border-teal-300 hover:shadow-sm">
            <div class="flex items-center justify-center gap-1.5 text-teal-700 mb-1">
                <i class="fas fa-sun text-xs"></i>
                <span class="text-xs font-bold uppercase tracking-wider">Suhu Udara</span>
            </div>
            <div class="my-1">
                <p id="suhuudara" class="text-2xl sm:text-3xl font-black text-teal-900 tracking-tight">--</p>
            </div>
            <span class="text-[11px] font-semibold text-teal-600/80">Lingkungan (°C)</span>
        </div>

        {{-- Kelembaban --}}
        <div class="p-4 bg-violet-50/70 border border-violet-100/80 rounded-2xl flex flex-col justify-between text-center transition-all hover:border-violet-300 hover:shadow-sm">
            <div class="flex items-center justify-center gap-1.5 text-violet-700 mb-1">
                <i class="fas fa-wind text-xs"></i>
                <span class="text-xs font-bold uppercase tracking-wider">Kelembaban</span>
            </div>
            <div class="my-1">
                <p id="kelembaban" class="text-2xl sm:text-3xl font-black text-violet-900 tracking-tight">--</p>
            </div>
            <span class="text-[11px] font-semibold text-violet-600/80">Udara RH (%)</span>
        </div>

        {{-- Level Air --}}
        <div class="p-4 bg-amber-50/70 border border-amber-100/80 rounded-2xl flex flex-col justify-between text-center transition-all hover:border-amber-300 hover:shadow-sm">
            <div class="flex items-center justify-center gap-1.5 text-amber-700 mb-1">
                <i class="fas fa-water text-xs"></i>
                <span class="text-xs font-bold uppercase tracking-wider">Level Air</span>
            </div>
            <div class="my-1">
                <p id="levelair" class="text-2xl sm:text-3xl font-black text-amber-900 tracking-tight">--</p>
            </div>
            <span class="text-[11px] font-semibold text-amber-600/80">Tandon Reservoir (%)</span>
        </div>

        {{-- Waktu Update --}}
        <div class="p-4 bg-slate-50/80 border border-slate-200/80 rounded-2xl flex flex-col justify-between text-center transition-all hover:border-slate-300 hover:shadow-sm">
            <div class="flex items-center justify-center gap-1.5 text-slate-500 mb-1">
                <i class="far fa-clock text-xs"></i>
                <span class="text-xs font-bold uppercase tracking-wider">Terakhir</span>
            </div>
            <div class="my-1">
                <p id="waktuUpdate" class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight truncate leading-tight">--</p>
            </div>
            <span class="text-[11px] font-semibold text-slate-400">Waktu Pembacaan</span>
        </div>

    </div>
</div>

@push('scripts')
<script>
(function() {
    async function updateLiveSensor() {
        try {
            const response = await fetch('{{ url('/sensor/live') }}', { cache: 'no-store' });
            if (!response.ok) return;
            const data = await response.json();
            
            // Helper mengambil elemen terakhir dari array
            const getLastVal = (arr) => {
                if (Array.isArray(arr) && arr.length > 0) {
                    const val = arr[arr.length - 1];
                    return (val !== null && val !== undefined && !isNaN(val)) ? Number(val) : null;
                }
                return null;
            };

            const formatVal = (val, decimals = 0, unit = '') => {
                return val !== null ? `${val.toFixed(decimals)} ${unit}`.trim() : `-- ${unit}`.trim();
            };

            // Safe DOM Setter
            const setElemText = (id, text) => {
                const el = document.getElementById(id);
                if (el) el.textContent = text;
            };

            setElemText("tds", formatVal(getLastVal(data.tds), 0, 'PPM'));
            setElemText("suhuAir", formatVal(getLastVal(data.suhu), 1, '°C'));
            setElemText("suhuudara", formatVal(getLastVal(data.suhuudara), 1, '°C'));
            setElemText("kelembaban", formatVal(getLastVal(data.kelembaban), 1, '%'));
            
            // Level air support untuk key level_air / water_stat
            const levelVal = getLastVal(data.level_air) ?? getLastVal(data.water_stat);
            setElemText("levelair", formatVal(levelVal, 0, '%'));

            // Waktu Update Terakhir
            let lastTime = '--';
            if (Array.isArray(data.waktu) && data.waktu.length > 0) {
                lastTime = data.waktu[data.waktu.length - 1];
            } else if (data.updated_at) {
                lastTime = data.updated_at;
            }
            setElemText("waktuUpdate", lastTime);

        } catch (err) {
            console.debug('Gagal mengambil data live sensor:', err);
        }
    }

    // Eksekusi awal & interval tiap 5 detik
    document.addEventListener('DOMContentLoaded', () => {
        updateLiveSensor();
        setInterval(updateLiveSensor, 5000);
    });
})();
</script>
@endpush