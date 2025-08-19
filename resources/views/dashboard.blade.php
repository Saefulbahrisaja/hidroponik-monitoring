<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hidroponik - SIKECE</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #10b981;
            --secondary-green: #059669;
            --accent-blue: #3b82f6;
            --accent-purple: #8b5cf6;
            --light-green: #d1fae5;
            --dark-green: #065f46;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f9fafb 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--gray-800);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06),
                0 0 0 1px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(0, 0, 0, 0.05);
        }

        .status-indicator {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 8px 16px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-indicator.on {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .status-indicator.off {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .metric-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 
                0 1px 3px 0 rgba(0, 0, 0, 0.1),
                0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--gray-200);
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-green), var(--accent-blue));
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 
                0 10px 15px -3px rgba(0, 0, 0, 0.1),
                0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .chart-container {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 
                0 1px 3px 0 rgba(0, 0, 0, 0.1),
                0 1px 2px 0 rgba(0, 0, 0, 0.06);
            padding: 24px;
            border: 1px solid var(--gray-200);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .header-gradient {
            background: linear-gradient(135deg, var(--primary-green), var(--accent-blue), var(--accent-purple));
        }

        .form-input {
            transition: all 0.3s ease;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            background: rgba(255, 255, 255, 0.95);
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: block;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-green), var(--accent-blue));
            color: white;
        }

        .status-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
        }

        .status-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .thinking-dots {
            display: inline-flex;
            gap: 2px;
        }

        .thinking-dots span {
            width: 4px;
            height: 4px;
            background: var(--gray-600);
            border-radius: 50%;
            animation: thinking 1.4s infinite ease-in-out;
        }

        .thinking-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .thinking-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes thinking {
            0%, 80%, 100% {
                opacity: 0;
                transform: scale(0.8);
            }
            40% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .lock-overlay {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
            border-radius: 16px;
        }

        .lock-overlay:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        @media (max-width: 768px) {
            .glass-card {
                margin: 0.5rem;
                padding: 1.5rem;
            }
            
            .metric-card {
                margin: 0.5rem;
            }
        }
        /* Animasi kipas dengan CSS murni */
        .fan-icon {
            width: 32px;
            height: 32px;
            border: 4px solid #fff;
            border-top: 4px solid #444;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes blink {
    0%, 80%, 100% { opacity: 0; }
    40% { opacity: 1; }
}

/* Container teks */
.thinking {
    display: inline-flex;
    align-items: center;
    font-weight: bold;
    color: #374151; /* Tailwind gray-700 */
}

/* Setiap titik */
.thinking span {
    animation: blink 1.4s infinite;
}

/* Delay untuk titik kedua dan ketiga */
.thinking span:nth-child(2) {
    animation-delay: 0.2s;
}
.thinking span:nth-child(3) {
    animation-delay: 0.4s;
}
/* Animasi berputar pelan */
@keyframes rotateIcon {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Animasi berdenyut */
@keyframes pulseIcon {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.15); opacity: 0.8; }
}

/* Style icon dalam label */
.label-icon {
  color: #16a34a; /* hijau */
  animation: pulseIcon 2s infinite ease-in-out;
  margin-right: 6px;
}

@keyframes slideUpFade {
    0% {
        transform: translateY(30px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}
@keyframes pulseLock {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.lock-overlay {
    animation: slideUpFade 0.4s ease-out forwards;
    cursor: pointer;
    transition: background-color 0.2s ease;
}
.lock-overlay:hover {
    background-color: rgba(0, 0, 0, 0.5);
}
.lock-icon {
    animation: pulseLock 1.5s infinite ease-in-out;
}

/* Login form animasi */
.login-form {
    animation: slideUpFade 0.4s ease-out forwards;
}
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
<!-- Header -->
<header class="fixed top-0 left-0 w-full z-50 bg-green-700/70 backdrop-blur-md text-white py-6 shadow-lg">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold text-center">
            <i class="fas fa-seedling mr-3"></i>
            SIKECE - Sistem Kebun Cerdas
        </h1>
        <p class="text-center text-green-100 mt-2">Monitoring Hidroponik Real-time</p>
    </div>
</header>

<!-- Main Content -->
<main class="container mx-auto px-6 py-8 pt-32">
        <!-- Filter & Status Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            
            <!-- System Status -->
            <div class="glass-card p-6 mt-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-cogs mr-2 text-blue-600 animate-spin"></i>
                Status Sistem
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

            <!-- System Settings -->
            <div class="relative glass-card p-6 mt-6 shadow-lg rounded-2xl border border-white/20 backdrop-blur-md">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-sliders-h text-purple-600 text-lg"></i>
                    Pengaturan Sistem
                </h3>

                <!-- Form Pengaturan -->
                <form id="pengaturanForm" method="POST" action="/api/update-batas" class="space-y-5">
                    <div>
                        <label for="tds_min" class="block text-sm font-semibold text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-tint label-icon"></i> TDS Minimum (ppm)
                        </label>
                        <input type="number" id="tds_min" name="tds_min" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                focus:ring-2 focus:ring-green-500 focus:border-green-500
                                bg-white/80 backdrop-blur-sm shadow-sm"
                            placeholder="Masukkan nilai TDS minimum"
                            required>
                    </div>

                    <div>
                        <label for="air_min" class="block text-sm font-semibold text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-water label-icon"></i> Air Minimum (cm)
                        </label>
                        <input type="number" id="air_min" name="air_min" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                focus:ring-2 focus:ring-green-500 focus:border-green-500
                                bg-white/80 backdrop-blur-sm shadow-sm"
                            placeholder="Masukkan batas minimum air"
                            required>
                    </div>

                    <div>
                        <label for="interval" class="block text-sm font-semibold text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-clock label-icon"></i> Interval Simpan (detik)
                        </label>
                        <input type="number" id="interval" name="interval" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                focus:ring-2 focus:ring-green-500 focus:border-green-500
                                bg-white/80 backdrop-blur-sm shadow-sm"
                            placeholder="Contoh: 60"
                            required>
                    </div>

                    <button type="submit" 
                        class="w-full px-4 py-3 rounded-lg bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold 
                            shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98] 
                            transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Pengaturan
                    </button>
                </form>

                <!-- Overlay saat belum login -->
                <div id="formLock" 
                    class="lock-overlay absolute inset-0 bg-black/40 backdrop-blur-sm flex flex-col items-center justify-center text-white rounded-2xl">
                    <i class="fas fa-lock text-5xl mb-3 lock-icon"></i>
                    <p class="text-lg font-semibold">Silakan login untuk mengubah pengaturan</p>
                </div>
            </div>

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
                                <p class="text-sm text-gray-500">Prediksi suhu dan kelembapan</p>
                            </div>
                        </div>
                        <div class="ml-auto thinking" id="prediksiLingkungan">
                            <span>.</span><span>.</span><span>.</span>
                        </div>
                    </div>

                    <!-- Deteksi Penyakit -->
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-virus text-red-500 animate-spin"></i>
                            <div>
                                <p class="font-medium text-gray-700">Deteksi Penyakit</p>
                                <p class="text-sm text-gray-500">Hasil deteksi otomatis tanaman</p>
                            </div>
                        </div>
                        <div class="ml-auto thinking" id="deteksiPenyakit">
                            <span>.</span><span>.</span><span>.</span>
                        </div>
                    </div>

                    <!-- Prediksi Panen -->
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-calendar-check text-green-600 animate-pulse"></i>
                            <div>
                                <p class="font-medium text-gray-700">Prediksi Panen</p>
                                <p class="text-sm text-gray-500">Perkiraan tanggal panen</p>
                            </div>
                        </div>
                        <div class="ml-auto thinking" id="prediksiPanen">
                             <span>.</span><span>.</span><span>.</span>
                        </div>
                    </div>

                    <!-- Prediksi Penggunaan Air & Nutrisi -->
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-tint text-blue-600 animate-bounce"></i>
                            <div>
                                <p class="font-medium text-gray-700">Prediksi Pemakaian Air & Nutrisi</p>
                                <p class="text-sm text-gray-500">Estimasi penggunaan dalam periode tertentu</p>
                            </div>
                        </div>
                        <div class="ml-auto thinking" id="prediksiAirNutrisi">
                            <span>.</span><span>.</span><span>.</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        

        <!-- Live Sensor Data -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="metric-card p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                         <i data-lucide="thermometer" class="w-10 h-10 text-red-500 animate-pulse"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Suhu Air</p>
                        <p id="suhuAir" class="text-2xl font-bold text-gray-800">--°C</p>
                    </div>
                </div>
            </div>
            <div class="metric-card p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                         <i data-lucide="leaf" class="w-10 h-10 text-emerald-500 animate-wiggle"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">TDS</p>
                        <p id="tds" class="text-2xl font-bold text-gray-800">-- ppm</p>
                    </div>
                </div>
            </div>
            <div class="metric-card p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                         <i data-lucide="thermometer" class="w-10 h-10 text-blue-500 animate-pulse"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Suhu Udara</p>
                        <p id="suhuudara" class="text-2xl font-bold text-gray-800">--°C</p>
                    </div>
                </div>
            </div>
            <div class="metric-card p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                         <i data-lucide="droplet" class="w-10 h-10 text-blue-500 animate-bounce"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Kelembaban</p>
                        <p id="kelembaban" class="text-2xl font-bold text-gray-800">--%</p>
                    </div>
                </div>
            </div>
            <div class="metric-card p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                         <i data-lucide="clock-fading" class="w-10 h-10 text-green-500 animate-spin-slow"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Update</p>
                        <p id="lastUpdate" class="text-lg font-bold text-gray-800">--:--</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
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

        <!-- Combined Chart -->
        <div class="chart-container">
            <h4 class="text-xl font-semibold mb-4 text-center">
                <i class="fas fa-chart-area mr-2"></i>
                Grafik Gabungan Sensor
            </h4>
            <canvas id="combinedChart" class="w-full h-80"></canvas>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2024 SIKECE - Sistem Kebun Cerdas. All rights reserved.</p>
        </div>
    </footer>



<!-- FETCH PENGATURAN -->
<script>
    async function fetchPengaturan() {
        try {
            const res = await fetch('/api/batas');
            const data = await res.json();
            document.getElementById('tds_min').value = data.tds_min;
            document.getElementById('air_min').value = data.air_min;
            document.getElementById('interval').value = data.interval;
        } catch (error) {
            console.error("Gagal memuat pengaturan:", error);
        }
    }
    fetchPengaturan();
</script>

<!-- STATUS SISTEM & KIPAS -->
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
        if (data.level_air <= data.air_min) {
            elLevel.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Level Air Cukup';
            elLevel.className = 'flex items-center bg-green-500 text-white font-bold px-3 py-1 rounded-full text-sm';
        } else {
            elLevel.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Level Air Kurang';
            elLevel.className = 'flex items-center bg-red-500 text-white font-bold px-3 py-1 rounded-full text-sm';
        }

    } catch (err) {
        console.error("Gagal update status sistem:", err);
    }
}

updateStatusSystem();
setInterval(updateStatusSystem, 5000);
</script>

<!-- GRAFIK -->
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

<script>
document.getElementById("pengaturanForm").addEventListener("submit", async function(e) {
    e.preventDefault(); // Mencegah reload halaman

    const form = e.target;
    const data = {
        tds_min: form.tds_min.value,
        air_min: form.air_min.value,
        interval: form.interval.value
    };

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pengaturan berhasil disimpan.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }

    } catch (err) {
        console.error("Gagal simpan pengaturan:", err);
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan jaringan.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
});
</script>
<script>
async function updateLiveSensor() {
    try {
        const response = await fetch('/sensor/live');
        const data = await response.json();
        // Tampilkan data ke elemen teks
        const last = arr => Array.isArray(arr) && arr.length ? arr[arr.length - 1] : null;
        document.getElementById("suhuAir").textContent = last(data.suhu)?.toFixed(1) || '--';
        document.getElementById("tds").textContent = last(data.tds)?.toFixed(0) || '--';
        //document.getElementById("ph").textContent = last(data.ph)?.toFixed(2) || '--';
        document.getElementById("suhuudara").textContent = last(data.suhuudara)?.toFixed(1) || '--';
        document.getElementById("kelembaban").textContent = last(data.kelembaban)?.toFixed(1) || '--';

    } catch (err) {
        console.error('Gagal mengambil data live sensor:', err);
    }
}

// Jalankan pertama kali
updateLiveSensor();

// Update tiap 5 detik
setInterval(updateLiveSensor, 5000);
</script>
<script>
    lucide.createIcons();

    // Custom animation untuk wiggle & spin slow
    tailwind.config = {
        theme: {
            extend: {
                animation: {
                    'spin-slow': 'spin 6s linear infinite',
                    'wiggle': 'wiggle 1s ease-in-out infinite',
                },
                keyframes: {
                    wiggle: {
                        '0%, 100%': { transform: 'rotate(-5deg)' },
                        '50%': { transform: 'rotate(5deg)' },
                    }
                }
            }
        }
    }
</script>
<script>
document.getElementById("formLock").addEventListener("click", function () {
    const overlay = this;
    
    // Ganti overlay dengan form login
    overlay.outerHTML = `
        <div class="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center rounded-2xl login-form p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Login</h2>
            <form id="loginForm" class="w-full max-w-xs space-y-4">
                <input id="username" type="text" placeholder="Username" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <input id="password" type="password" placeholder="Password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <button type="submit" 
                    class="w-full px-4 py-2 rounded-lg bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    `;

    setTimeout(() => {
    document.getElementById("loginForm").addEventListener("submit", async function (e) {
        e.preventDefault();

        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value.trim();

        if (!username || !password) {
            alert("Username dan password wajib diisi!");
            return;
        }

        try {
            // Kirim ke server untuk dicek di database
            const res = await fetch("/api/login", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username, password })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                alert("Login berhasil!");
                document.querySelector("#pengaturanForm")
                    .querySelectorAll("input, button")
                    .forEach(el => el.disabled = false);

                document.querySelector(".login-form").remove();
            } else {
                alert(data.message || "Login gagal! Username atau password salah.");
            }
        } catch (err) {
            console.error(err);
            alert("Gagal terhubung ke server.");
        }
    });
}, 100);
});
</script>
</body>
</html>
