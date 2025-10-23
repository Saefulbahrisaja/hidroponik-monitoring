<div class="glass-card p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-sliders-h mr-2 text-indigo-600"></i> Pengaturan Sistem
    </h3>
    <div class="space-y-4">
                <!-- Pompa Air -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-tint text-blue-500 animate-bounce"></i>
                        <div>
                            <p class="font-medium text-gray-700">Pompa Air</p>
                            <p class="text-sm text-gray-500">Status pompa utama</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-auto">
                        <div id="statusPompaAir" class="status-indicator px-3 py-1 rounded-full text-white font-bold text-sm bg-gray-400">
                            --
                        </div>
                        
                    </div>
                </div>
                <!-- Pompa Nutrisi -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-flask text-green-500 animate-pulse"></i>
                        <div>
                            <p class="font-medium text-gray-700">Pompa Nutrisi</p>
                            <p class="text-sm text-gray-500">Pompa nutrisi otomatis</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-auto">
                        <div id="statusPompaNutrisi" class="status-indicator px-3 py-1 rounded-full text-white font-bold text-sm bg-gray-400">
                            --
                        </div>
                        
                    </div>
                </div>

                <!-- Level Air -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-water text-cyan-500 animate-bounce"></i>
                        <div>
                            <p class="font-medium text-gray-700">Level Air</p>
                            <p class="text-sm text-gray-500">Kedalaman air reservoir</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-auto">
                        <div id="statusLevelAir" class="px-3 py-1 rounded-full text-white font-bold text-sm bg-gray-400">
                            Loading...
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <script>
async function updateStatusSystem() {
    try {
        const res = await fetch('/sensor/live');
        const data = await res.json();

        const updateEl = (id, value) => {
            const el = document.getElementById(id);
            el.innerHTML = ''; // reset isi
            el.className = 'w-16 h-16 mx-auto flex items-center justify-center text-white font-bold rounded-full relative overflow-hidden';

            if (value) {
                el.style.backgroundColor = 'rgb(120, 200, 65)'; // Hijau
                // Tambahkan ikon kipas animasi
                const fanIcon = document.createElement('i');
                fanIcon.className = 'fas fa-fan animate-spin text-3xl'; // ikon kipas + animasi
                el.appendChild(fanIcon);
            } else {
                el.style.backgroundColor = 'rgb(251, 65, 65)'; // Merah
                el.innerHTML = '<i class="fas fa-power-off text-2xl mr-1"></i> OFF';
            }
        };

        updateEl('statusPompaAir', data.pompa_air);
        updateEl('statusPompaNutrisi', data.pompa_nutrisi);

        const elLevel = document.getElementById('statusLevelAir');
        
        if (data.water_stat < data.air_min) {
            elLevel.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Air Kurang';
            elLevel.className = 'flex items-center bg-red-500 text-white font-bold px-3 py-1 rounded-full text-sm';
        } else {
            elLevel.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Air Cukup';
            elLevel.className = 'flex items-center bg-green-500 text-white font-bold px-3 py-1 rounded-full text-sm';
        }

    } catch (err) {
        console.error("Gagal update status sistem:", err);
    }
}

updateStatusSystem();
setInterval(updateStatusSystem, 5000);
</script>