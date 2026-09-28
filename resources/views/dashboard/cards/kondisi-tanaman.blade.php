{{-- CARD UTAMA KONDISI TANAMAN --}}
<div id="tanamanAktif" class="glass-card p-6 border border-slate-200/80 shadow-sm rounded-2xl bg-white">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Active Crop Status</span>
            <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2 mt-0.5">
                <i class="fas fa-leaf text-emerald-600"></i>
                Kondisi Tanaman
            </h3>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm border border-emerald-100">
            <i class="fas fa-seedling"></i>
        </div>
    </div>

    <div class="space-y-4">
        {{-- Nama Tanaman --}}
        <div class="flex items-center gap-3 p-3.5 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="w-10 h-10 rounded-lg bg-emerald-100/70 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="fas fa-leaf text-base"></i>
            </div>
            <div class="min-w-0 flex-grow">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Nama Tanaman</p>
                <p id="namaTanaman" class="text-sm font-bold text-slate-800 truncate mt-0.5">Memuat...</p>
            </div>
        </div>

        {{-- Nama Ilmiah --}}
        <div class="flex items-center gap-3 p-3.5 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="w-10 h-10 rounded-lg bg-amber-100/70 text-amber-600 flex items-center justify-center shrink-0">
                <i class="fas fa-book-bookmark text-base"></i>
            </div>
            <div class="min-w-0 flex-grow">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Nama Ilmiah</p>
                <p id="namaIlmiah" class="text-sm font-bold text-slate-700 italic truncate mt-0.5">Memuat...</p>
            </div>
        </div>

        {{-- Tanggal & Usia Semai --}}
        <div class="flex items-center gap-3 p-3.5 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="w-10 h-10 rounded-lg bg-sky-100/70 text-sky-600 flex items-center justify-center shrink-0">
                <i class="fas fa-calendar-alt text-base"></i>
            </div>
            <div class="min-w-0 flex-grow flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Tanggal Semai</p>
                    <p id="tanggalSemai" class="text-sm font-bold text-slate-800 mt-0.5">Memuat...</p>
                </div>
                <div class="text-right">
                    <span class="text-[11px] font-semibold text-slate-400 block">Usia Semai</span>
                    <span id="usiaSemai" class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-sky-100 text-sky-700">
                        - hari
                    </span>
                </div>
            </div>
        </div>

        {{-- Tanggal & Usia Tanam + Progress --}}
        <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100/70 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-plant-wilt text-base"></i>
                </div>
                <div class="min-w-0 flex-grow flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Tanggal Tanam</p>
                        <p id="tanggalTanam" class="text-sm font-bold text-slate-800 mt-0.5">Memuat...</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[11px] font-semibold text-slate-400 block">Usia Tanam</span>
                        <span id="usiaTanam" class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-700">
                            - hari
                        </span>
                    </div>
                </div>
            </div>

            {{-- Progress Bar Pertumbuhan --}}
            <div class="mt-4 pt-3 border-t border-slate-200/60">
                <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                    <span class="text-slate-600">Progres Masa Tumbuh</span>
                    <span id="progressPercent" class="text-emerald-600 font-extrabold">0%</span>
                </div>
                <div class="w-full bg-slate-200/80 rounded-full h-3.5 p-0.5 overflow-hidden shadow-inner">
                    <div id="progressBar" 
                         class="h-full rounded-full transition-all duration-700 ease-out bg-gradient-to-r from-emerald-500 to-teal-400 shadow-sm" 
                         style="width: 0%;">
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <span id="fasePertumbuhan" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 bg-white px-3 py-1 rounded-full border border-slate-200/80 shadow-2xs">
                        Memuat fase...
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    // Safety setter helper
    const setText = (id, text) => {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    };

    const setInputValue = (id, val) => {
        const el = document.getElementById(id);
        if (el && val !== undefined && val !== null) el.value = val;
    };

    async function fetchPengaturan() {
        try {
            const res = await fetch('{{ url('/api/batas') }}');
            if (!res.ok) return;
            const data = await res.json();

            // Tampilkan ambang batas jika elemen input ada
            setInputValue('tds_min', data.tds_min);
            setInputValue('air_min', data.air_min);
            setInputValue('interval', data.interval);

            // Ambil tanaman aktif
            if (data.tanaman_aktif) {
                fetchTanamanAktif(data.tanaman_aktif);
            } else {
                fetchTanamanAktif();
            }
        } catch (error) {
            console.debug("Gagal memuat pengaturan:", error);
            fetchTanamanAktif(); // Fallback fetch
        }
    }

    async function fetchTanamanAktif(id) {
        try {
            const res = await fetch('{{ url('/api/tanaman/aktif') }}');
            if (!res.ok) return;
            const data = await res.json();

            if (data.success && data.data) {
                const tanaman = data.data;

                // Format Tanggal Indonesia
                const formatTanggal = (dateString) => {
                    if (!dateString) return '-';
                    const options = { day: 'numeric', month: 'short', year: 'numeric' };
                    const tanggal = new Date(dateString);
                    return isNaN(tanggal.getTime()) ? '-' : tanggal.toLocaleDateString('id-ID', options);
                };

                // Tampilkan info dasar
                setText('namaTanaman', tanaman.nama_tanaman || '-');
                setText('namaIlmiah', tanaman.nama_ilmiah || '-');
                setText('tanggalSemai', formatTanggal(tanaman.hst));
                setText('tanggalTanam', formatTanggal(tanaman.hss));

                // Hitung Usia (Hari)
                const today = new Date();
                const semaiDate = tanaman.hst ? new Date(tanaman.hst) : null;
                const tanamDate = tanaman.hss ? new Date(tanaman.hss) : null;

                let numUsiaSemai = 0;
                let numUsiaTanam = 0;

                if (semaiDate && !isNaN(semaiDate.getTime())) {
                    numUsiaSemai = Math.max(0, Math.floor((today - semaiDate) / (1000 * 60 * 60 * 24)));
                    setText('usiaSemai', `${numUsiaSemai} hari`);
                } else {
                    setText('usiaSemai', '-');
                }

                if (tanamDate && !isNaN(tanamDate.getTime())) {
                    numUsiaTanam = Math.max(0, Math.floor((today - tanamDate) / (1000 * 60 * 60 * 24)));
                    setText('usiaTanam', `${numUsiaTanam} hari`);
                } else {
                    setText('usiaTanam', '-');
                }

                // Hitung Progres Pertumbuhan (%)
                const totalHari = Number(tanaman.masa_tumbuh) || 30; // Default 30 hari jika kosong
                let progress = Math.min(Math.max((numUsiaTanam / totalHari) * 100, 0), 100);

                const progressBar = document.getElementById('progressBar');
                const progressPercent = document.getElementById('progressPercent');
                const faseLabel = document.getElementById('fasePertumbuhan');

                const progressFormatted = `${progress.toFixed(0)}%`;
                if (progressBar) progressBar.style.width = progressFormatted;
                if (progressPercent) progressPercent.textContent = progressFormatted;

                // Set Warna & Deskripsi Fase
                if (faseLabel && progressBar) {
                    if (progress < 25) {
                        progressBar.className = "h-full rounded-full transition-all duration-700 ease-out bg-gradient-to-r from-sky-400 to-blue-500 shadow-sm";
                        faseLabel.innerHTML = "🌱 <span class='text-sky-700'>Fase Awal Pertumbuhan</span>";
                    } else if (progress < 60) {
                        progressBar.className = "h-full rounded-full transition-all duration-700 ease-out bg-gradient-to-r from-emerald-500 to-teal-400 shadow-sm";
                        faseLabel.innerHTML = "🌿 <span class='text-emerald-700'>Fase Vegetatif (Daun & Batang)</span>";
                    } else if (progress < 90) {
                        progressBar.className = "h-full rounded-full transition-all duration-700 ease-out bg-gradient-to-r from-amber-400 to-orange-500 shadow-sm";
                        faseLabel.innerHTML = "🌼 <span class='text-amber-700'>Fase Pembungaan / Matang</span>";
                    } else {
                        progressBar.className = "h-full rounded-full transition-all duration-700 ease-out bg-gradient-to-r from-emerald-600 to-green-600 shadow-sm animate-pulse";
                        faseLabel.innerHTML = "🥬 <span class='text-emerald-800 font-extrabold'>Siap Panen!</span>";
                    }
                }

                // Panggil fungsi pembantu prediksi panen jika ada
                if (typeof updatePrediksiPanen === 'function') {
                    updatePrediksiPanen(tanaman, tanamDate);
                }
            } else {
                setText('namaTanaman', 'Tidak ada tanaman aktif');
                setText('namaIlmiah', '-');
                setText('tanggalSemai', '-');
                setText('tanggalTanam', '-');
                setText('fasePertumbuhan', 'Belum disetting');
            }
        } catch (error) {
            console.debug("Gagal memuat tanaman aktif:", error);
        }
    }

    // Eksekusi saat DOM Siap
    document.addEventListener('DOMContentLoaded', fetchPengaturan);
})();
</script>
@endpush