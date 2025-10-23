<!-- Prediksi & Analisis -->
            <div class="glass-card p-6 mt-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-brain mr-2 text-indigo-600 animate-pulse"></i>
                Prediksi & Analisis
            </h3>
            <div class="space-y-4">

                <!-- Prediksi Kondisi Lingkungan -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-cloud-sun text-yellow-500 animate-bounce"></i>
                        <div>
                            <p class="font-medium text-gray-700">Kondisi Lingkungan</p>
                            <div class="ml-auto thinking text-right" id="prediksiLingkungan">
                                <span>.</span><span>.</span><span>.</span>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- Deteksi Penyakit -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-virus text-red-500 animate-spin"></i>
                        <div>
                            <p class="font-medium text-gray-700">Deteksi Penyakit</p>
                             <div class="ml-auto thinking" id="deteksiPenyakit">
                                <span>.</span><span>.</span><span>.</span>
                             </div>
                        </div>
                    </div>
                   
                </div>

                <!-- Prediksi Panen -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar-check text-green-600 animate-pulse"></i>
                        <div>
                            <p class="font-medium text-gray-700">Perkiraan Panen</p>
                             <div class="ml-auto thinking text-right" id="prediksiPanen">
                                <span>.</span><span>.</span><span>.</span>
                            </div>  
                        </div>
                    </div>
                </div>
                   

                <!-- Prediksi Penggunaan Air & Nutrisi -->
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-tint text-blue-600 animate-bounce"></i>
                        <div>
                            <p class="font-medium text-gray-700">Pemakaian Air & Nutrisi</p>
                            <div class="ml-auto thinking text-right" id="prediksiAirNutrisi">
                                <span>.</span><span>.</span><span>.</span>
                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>
        </div>
@push('scripts')
<script>
  function updatePrediksiPanen(tanaman, tanamDate) {
    const elemenPanen = document.getElementById('prediksiPanen');
    const today = new Date();
    if (tanamDate && tanaman.masa_tumbuh) {
        const masaTumbuh = tanaman.masa_tumbuh;
        const perkiraanPanenDate = new Date(tanamDate);
        perkiraanPanenDate.setDate(perkiraanPanenDate.getDate() + masaTumbuh);

        // Format tanggal panen
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        const tanggalPanenStr = perkiraanPanenDate.toLocaleDateString('id-ID', options);

        // Hitung sisa hari menuju panen
        const sisaHari = Math.max(0, Math.ceil((perkiraanPanenDate - today) / (1000 * 60 * 60 * 24)));

        // Tentukan warna berdasarkan sisa hari
        let warna = 'text-green-700';
        if (sisaHari <= 3) warna = 'text-red-600 font-bold';
        else if (sisaHari <= 7) warna = 'text-yellow-600 font-semibold';
        else if (sisaHari === 0) warna = 'text-green-800 font-bold';

        // Update tampilan
        elemenPanen.innerHTML = `
            <p class="text-sm text-gray-600">${tanggalPanenStr} <span class="text-sm ${warna}">${sisaHari > 0 ? `${sisaHari} hari lagi` : '🌾 Sudah waktunya panen!'}</p>
              
           
        `;
    } else {
        elemenPanen.innerHTML = `
            <p class="text-gray-500 italic">Data tanam belum tersedia</p>
        `;
    }
}
fetchPengaturan();
</script>
@endpush