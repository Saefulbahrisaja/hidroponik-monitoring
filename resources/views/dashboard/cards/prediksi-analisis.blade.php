{{-- CARD UTAMA PREDIKSI & ANALISIS --}}
<div class="glass-card p-6 border border-slate-200/80 shadow-sm rounded-2xl bg-white">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Smart AI Insights</span>
            <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2 mt-0.5">
                <i class="fas fa-brain text-emerald-600"></i>
                Prediksi & Analisis
            </h3>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm border border-emerald-100">
            <i class="fas fa-chart-pie"></i>
        </div>
    </div>

    {{-- Daftar Indikator Analisis --}}
    <div class="space-y-3.5">
        
        {{-- Prediksi Kondisi Lingkungan --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100/70 text-amber-600 flex items-center justify-center text-base shrink-0">
                    <i class="fas fa-cloud-sun"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Kondisi Lingkungan</p>
                    <p class="text-xs text-slate-400">Analisis suhu & kelembaban</p>
                </div>
            </div>
            <div class="ml-auto text-right min-w-0">
                <div id="prediksiLingkungan" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="thinking-dots"><span></span><span></span><span></span></span>
                    <span class="ml-1">Menganalisis...</span>
                </div>
            </div>
        </div>

        {{-- Deteksi Penyakit --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-rose-100/70 text-rose-600 flex items-center justify-center text-base shrink-0">
                    <i class="fas fa-virus"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Deteksi Penyakit</p>
                    <p class="text-xs text-slate-400">Status kesehatan daun sampel</p>
                </div>
            </div>
            <div class="ml-auto text-right min-w-0">
                <div id="deteksiPenyakit" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="thinking-dots"><span></span><span></span><span></span></span>
                    <span class="ml-1">Memeriksa...</span>
                </div>
            </div>
        </div>

        {{-- Perkiraan Panen --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100/70 text-emerald-600 flex items-center justify-center text-base shrink-0">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Perkiraan Panen</p>
                    <p class="text-xs text-slate-400">Estimasi masa panen tanaman</p>
                </div>
            </div>
            <div class="ml-auto text-right min-w-0">
                <div id="prediksiPanen" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="thinking-dots"><span></span><span></span><span></span></span>
                    <span class="ml-1">Menghitung...</span>
                </div>
            </div>
        </div>

        {{-- Pemakaian Air & Nutrisi --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-sky-100/70 text-sky-600 flex items-center justify-center text-base shrink-0">
                    <i class="fas fa-droplet"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Pemakaian Air & Nutrisi</p>
                    <p class="text-xs text-slate-400">Estimasi konsumsi cairan</p>
                </div>
            </div>
            <div class="ml-auto text-right min-w-0">
                <div id="prediksiAirNutrisi" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="thinking-dots"><span></span><span></span><span></span></span>
                    <span class="ml-1">Mengkalkulasi...</span>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
// Fungsi Global Prediksi Panen (Dapat dipanggil dari komponen tanaman)
function updatePrediksiPanen(tanaman, tanamDate) {
    const elemenPanen = document.getElementById('prediksiPanen');
    if (!elemenPanen) return;

    const today = new Date();
    if (tanamDate && tanaman && tanaman.masa_tumbuh) {
        const masaTumbuh = Number(tanaman.masa_tumbuh);
        const perkiraanPanenDate = new Date(tanamDate);
        perkiraanPanenDate.setDate(perkiraanPanenDate.getDate() + masaTumbuh);

        if (isNaN(perkiraanPanenDate.getTime())) {
            elemenPanen.innerHTML = `<span class="text-xs text-slate-400 italic">Format tanggal salah</span>`;
            return;
        }

        const options = { day: 'numeric', month: 'short', year: 'numeric' };
        const tanggalPanenStr = perkiraanPanenDate.toLocaleDateString('id-ID', options);

        // Hitung sisa hari
        const sisaHari = Math.ceil((perkiraanPanenDate - today) / (1000 * 60 * 60 * 24));

        if (sisaHari <= 0) {
            elemenPanen.innerHTML = `
                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xs">
                    <i class="fas fa-wheat-awn text-emerald-600"></i>
                    <span>Siap Panen!</span>
                </div>
            `;
        } else if (sisaHari <= 3) {
            elemenPanen.innerHTML = `
                <div class="text-right">
                    <span class="block text-xs font-bold text-rose-600 animate-pulse">${sisaHari} Hari Lagi</span>
                    <span class="block text-[11px] font-semibold text-slate-400">${tanggalPanenStr}</span>
                </div>
            `;
        } else if (sisaHari <= 7) {
            elemenPanen.innerHTML = `
                <div class="text-right">
                    <span class="block text-xs font-bold text-amber-600">${sisaHari} Hari Lagi</span>
                    <span class="block text-[11px] font-semibold text-slate-400">${tanggalPanenStr}</span>
                </div>
            `;
        } else {
            elemenPanen.innerHTML = `
                <div class="text-right">
                    <span class="block text-xs font-bold text-emerald-600">${sisaHari} Hari Lagi</span>
                    <span class="block text-[11px] font-semibold text-slate-400">${tanggalPanenStr}</span>
                </div>
            `;
        }
    } else {
        elemenPanen.innerHTML = `<span class="text-xs text-slate-400 italic">Data tanam belum ada</span>`;
    }
}

// Module Analisis AI Realtime
(function () {
    async function loadAnalysisData() {
        try {
            const res = await fetch('{{ url('/sensor/live') }}', { cache: 'no-store' });
            if (!res.ok) return;
            const data = await res.json();

            const lastVal = (arr) => (Array.isArray(arr) && arr.length) ? Number(arr[arr.length - 1]) : null;

            const suhuAir = lastVal(data.suhu);
            const suhuUdara = lastVal(data.suhuudara);
            const kelembaban = lastVal(data.kelembaban);
            const tds = lastVal(data.tds);

            // 1. Analisis Kondisi Lingkungan
            const elLingkungan = document.getElementById('prediksiLingkungan');
            if (elLingkungan) {
                if (suhuUdara !== null && kelembaban !== null) {
                    if (suhuUdara >= 22 && suhuUdara <= 30 && kelembaban >= 50 && kelembaban <= 80) {
                        elLingkungan.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                <i class="fas fa-circle-check text-[10px] mr-1"></i> Sempurna / Optimal
                            </span>
                        `;
                    } else if (suhuUdara > 32) {
                        elLingkungan.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                <i class="fas fa-temperature-arrow-up text-[10px] mr-1"></i> Udara Panas
                            </span>
                        `;
                    } else {
                        elLingkungan.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700">
                                <i class="fas fa-circle-info text-[10px] mr-1"></i> Stabil / Normal
                            </span>
                        `;
                    }
                } else {
                    elLingkungan.innerHTML = `<span class="text-xs text-slate-400">Sensor Offline</span>`;
                }
            }

            // 2. Analisis Deteksi Penyakit Terakhir
            const elPenyakit = document.getElementById('deteksiPenyakit');
            if (elPenyakit) {
                if (data.latest_disease) {
                    const diseaseName = data.latest_disease.disease || 'Sehat';
                    if (diseaseName.toLowerCase().includes('healthy') || diseaseName.toLowerCase().includes('sehat')) {
                        elPenyakit.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                <i class="fas fa-shield-halved text-[10px] mr-1"></i> Tanaman Sehat
                            </span>
                        `;
                    } else {
                        elPenyakit.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                <i class="fas fa-bug text-[10px] mr-1"></i> ${diseaseName}
                            </span>
                        `;
                    }
                } else {
                    elPenyakit.innerHTML = `
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                            <i class="fas fa-check text-[10px] mr-1"></i> Bebas Hama
                        </span>
                    `;
                }
            }

            // 3. Analisis Pemakaian Air & Nutrisi
            const elAirNutrisi = document.getElementById('prediksiAirNutrisi');
            if (elAirNutrisi) {
                if (tds !== null) {
                    const tdsMin = Number(data.tds_min) || 600;
                    if (tds < tdsMin) {
                        elAirNutrisi.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                <i class="fas fa-triangle-exclamation text-[10px] mr-1"></i> Perlu Tambah Nutrisi
                            </span>
                        `;
                    } else {
                        elAirNutrisi.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-teal-100 text-teal-700">
                                <i class="fas fa-droplet text-[10px] mr-1"></i> Pemakaian Efisien
                            </span>
                        `;
                    }
                } else {
                    elAirNutrisi.innerHTML = `<span class="text-xs text-slate-400">Normal</span>`;
                }
            }

        } catch (e) {
            console.debug("Gagal memuat analisis AI:", e);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadAnalysisData();
        setInterval(loadAnalysisData, 10000);
    });
})();
</script>
@endpush