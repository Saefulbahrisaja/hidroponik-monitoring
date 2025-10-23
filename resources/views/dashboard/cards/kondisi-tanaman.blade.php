<div id="tanamanAktif" class="glass-card p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-leaf mr-2 text-green-600"></i> Kondisi Tanaman
    </h3>

    <div class="space-y-4">
        <!-- Nama Tanaman -->
        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-2">
                <i class="fas fa-leaf text-green-600 animate-pulse"></i>
                <div>
                    <p class="font-medium text-gray-700">Nama Tanaman</p>
                    <p id="namaTanaman" class="text-gray-800 font-semibold">Loading...</p>
                </div>
            </div>
        </div>

        <!-- Nama Ilmiah -->
        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-2">
                <i class="fas fa-book text-yellow-500 animate-bounce"></i>
                <div>
                    <p class="font-medium text-gray-700">Nama Ilmiah</p>
                    <p id="namaIlmiah" class="text-gray-800 font-semibold">Loading...</p>
                </div>
            </div>
        </div>

        <!-- Tanggal Semai -->
        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-2">
                <i class="fas fa-calendar text-blue-500 animate-pulse"></i>
                <div>
                    <p class="font-medium text-gray-700">Tanggal Semai</p>
                    <p id="tanggalSemai" class="text-gray-800 font-semibold">Loading...</p>
                    <p class="text-sm text-gray-600">Usia Semai: <span id="usiaSemai">-</span> hari</p>
                </div>
            </div>
        </div>

        <!-- Tanggal Tanam -->
        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
            <div class="flex flex-col w-full">
                <div class="flex items-center space-x-2 mb-2">
                    <i class="fas fa-seedling text-green-500 animate-bounce"></i>
                    <div>
                        <p class="font-medium text-gray-700">Tanggal Tanam</p>
                        <p id="tanggalTanam" class="text-gray-800 font-semibold">Loading...</p>
                        <p class="text-sm text-gray-600">Usia Tanam: <span id="usiaTanam">-</span> hari</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-2 w-full">
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div id="progressBar" class="h-4 rounded-full text-xs text-center text-white font-semibold transition-all duration-700 ease-in-out" style="width: 0%;">
                            0%
                        </div>
                    </div>
                    <p id="fasePertumbuhan" class="text-center mt-2 text-sm font-medium text-gray-700 italic">
                        Loading fase...
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function fetchPengaturan() {
    try {
        const res = await fetch('/api/batas');
        const data = await res.json();

        // tampilkan nilai ambang batas
        document.getElementById('tds_min').value = data.tds_min;
        document.getElementById('air_min').value = data.air_min;
        document.getElementById('interval').value = data.interval;

        // ambil tanaman aktif
        if (data.tanaman_aktif) fetchTanamanAktif(data.tanaman_aktif);
    } catch (error) {
        console.error("Gagal memuat pengaturan:", error);
    }
}

async function fetchTanamanAktif(id) {
    try {
        const res = await fetch(`/api/tanaman/aktif`);
        const data = await res.json();
        if (data.success && data.data) {
            const tanaman = data.data;

            // Fungsi bantu: format tanggal gaya Indonesia
            const formatTanggal = (dateString) => {
                if (!dateString) return '-';
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                const tanggal = new Date(dateString);
                return tanggal.toLocaleDateString('id-ID', options);
            };

            // Tampilkan info dasar
            document.getElementById('namaTanaman').textContent = tanaman.nama_tanaman ?? '-';
            document.getElementById('namaIlmiah').textContent = tanaman.nama_ilmiah ?? '-';
            document.getElementById('tanggalSemai').textContent = formatTanggal(tanaman.hst);
            document.getElementById('tanggalTanam').textContent = formatTanggal(tanaman.hss);

            // Hitung usia semai & tanam
            const today = new Date();
            const semaiDate = tanaman.hst ? new Date(tanaman.hst) : null;
            const tanamDate = tanaman.hss ? new Date(tanaman.hss) : null;

            let usiaSemai = '-';
            let usiaTanam = '-';
            let progress = 0;

            if (semaiDate) {
                usiaSemai = Math.floor((today - semaiDate) / (1000 * 60 * 60 * 24));
                document.getElementById('usiaSemai').textContent = `${usiaSemai} hari`;
            }

            if (tanamDate) {
                usiaTanam = Math.floor((today - tanamDate) / (1000 * 60 * 60 * 24));
                document.getElementById('usiaTanam').textContent = `${usiaTanam} hari`;
            }

            // Hitung progres pertumbuhan
            const totalHari = tanaman.masa_tumbuh ?? 40;
            progress = Math.min((usiaTanam / totalHari) * 100, 100);

            const progressBar = document.getElementById('progressBar');
            progressBar.style.width = `${progress.toFixed(0)}%`;
            progressBar.textContent = `${progress.toFixed(0)}%`;

            const faseLabel = document.getElementById('fasePertumbuhan');

            // Ubah warna dan fase
            if (progress < 30) {
                progressBar.className = "bg-blue-500 h-4 rounded-full text-xs text-center text-white font-semibold";
                faseLabel.textContent = "🌱 Fase Semai (awal pertumbuhan)";
            } else if (progress < 60) {
                progressBar.className = "bg-green-400 h-4 rounded-full text-xs text-center text-white font-semibold";
                faseLabel.textContent = "🌿 Fase Vegetatif (pertumbuhan daun dan batang)";
            } else if (progress < 90) {
                progressBar.className = "bg-yellow-500 h-4 rounded-full text-xs text-center text-white font-semibold";
                faseLabel.textContent = "🌼 Fase Generatif (pembungaan & pembentukan buah)";
            } else {
                progressBar.className = "bg-green-600 h-4 rounded-full text-xs text-center text-white font-semibold";
                faseLabel.textContent = "🥬 Siap Panen (fase akhir)";
            }
        }
    } catch (error) {
        console.error("Gagal memuat tanaman aktif:", error);
    }
}

fetchPengaturan();
</script>
@endpush
