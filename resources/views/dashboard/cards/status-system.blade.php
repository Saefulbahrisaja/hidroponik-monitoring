{{-- CARD UTAMA STATUS SISTEM & AKTUATOR --}}
<div class="glass-card p-6 border border-slate-200/80 shadow-sm rounded-2xl bg-white">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Hardware Actuators</span>
            <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2 mt-0.5">
                <i class="fas fa-toggle-on text-emerald-600"></i>
                Status Sistem & Aktuator
            </h3>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm border border-emerald-100">
            <i class="fas fa-microchip"></i>
        </div>
    </div>

    {{-- Daftar Status Aktuator --}}
    <div class="space-y-3.5">
        {{-- Pompa Air --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-sky-100/70 text-sky-600 flex items-center justify-center text-base shrink-0">
                    <i class="fas fa-droplet"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Pompa Air Utama</p>
                    <p class="text-xs text-slate-400">Sirkulasi air nutrisi hidroponik</p>
                </div>
            </div>
            <div class="ml-auto">
                <div id="statusPompaAir" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold text-slate-500 bg-slate-200 shadow-2xs transition-all">
                    <i class="fas fa-spinner animate-spin"></i>
                    <span>Connecting...</span>
                </div>
            </div>
        </div>

        {{-- Pompa Nutrisi --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100/70 text-emerald-600 flex items-center justify-center text-base shrink-0">
                    <i class="fas fa-flask"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Pompa Dosis Nutrisi</p>
                    <p class="text-xs text-slate-400">Pemberian nutrisi otomatis</p>
                </div>
            </div>
            <div class="ml-auto">
                <div id="statusPompaNutrisi" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold text-slate-500 bg-slate-200 shadow-2xs transition-all">
                    <i class="fas fa-spinner animate-spin"></i>
                    <span>Connecting...</span>
                </div>
            </div>
        </div>

        {{-- Level Air --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-teal-100/70 text-teal-600 flex items-center justify-center text-base shrink-0">
                    <i class="fas fa-water"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Level Air Reservoir</p>
                    <p class="text-xs text-slate-400">Ketinggian air dalam tandon</p>
                </div>
            </div>
            <div class="ml-auto">
                <div id="statusLevelAir" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold text-slate-500 bg-slate-200 shadow-2xs transition-all">
                    <i class="fas fa-spinner animate-spin"></i>
                    <span>Memuat...</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    async function updateStatusSystem() {
        try {
            const res = await fetch('{{ url('/sensor/live') }}', { cache: 'no-store' });
            if (!res.ok) return;
            const data = await res.json();

            // Helper pembentuk Badge Pompa (ON / OFF)
            const updatePumpStatus = (elementId, isActive) => {
                const el = document.getElementById(elementId);
                if (!el) return;

                if (isActive) {
                    el.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold text-white bg-emerald-600 shadow-sm shadow-emerald-600/30 transition-all';
                    el.innerHTML = `<i class="fas fa-fan animate-spin text-xs"></i> <span>AKTIF (ON)</span>`;
                } else {
                    el.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold text-slate-600 bg-slate-200 transition-all';
                    el.innerHTML = `<i class="fas fa-power-off text-xs text-slate-400"></i> <span>NONAKTIF</span>`;
                }
            };

            // Update Status Pompa Air & Nutrisi
            updatePumpStatus('statusPompaAir', Boolean(data.pompa_air));
            updatePumpStatus('statusPompaNutrisi', Boolean(data.pompa_nutrisi));

            // Update Status Level Air
            const elLevel = document.getElementById('statusLevelAir');
            if (elLevel) {
                const airMin = Number(data.air_min) || 0;
                const waterStat = Number(data.water_stat) || 0;

                // Jika water_stat lebih besar dari batas min -> Air Kurang / Kritis
                if (waterStat > airMin) {
                    elLevel.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold text-white bg-rose-600 shadow-sm shadow-rose-600/30 transition-all animate-pulse';
                    elLevel.innerHTML = `<i class="fas fa-exclamation-triangle text-xs"></i> <span>AIR KURANG</span>`;
                } else {
                    elLevel.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold text-white bg-teal-600 shadow-sm shadow-teal-600/30 transition-all';
                    elLevel.innerHTML = `<i class="fas fa-circle-check text-xs"></i> <span>AIR CUKUP</span>`;
                }
            }

        } catch (err) {
            console.debug("Gagal memperbarui status sistem:", err);
        }
    }

    // Jalankan awal dan interval 5 detik
    document.addEventListener('DOMContentLoaded', () => {
        updateStatusSystem();
        setInterval(updateStatusSystem, 5000);
    });
})();
</script>
@endpush