<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Models\SensorData;
use App\Models\Pengaturan;
use App\Exports\SensorExport;
use Maatwebsite\Excel\Facades\Excel;

// Export Excel
Route::get('/export-excel', function (Request $request) {
    $filter = $request->filter ?? 'hari';
    return Excel::download(new SensorExport($filter), 'data-sensor.xlsx');
});

// Dashboard utama
Route::get('/', [DashboardController::class, 'index']);

// API live sensor (realtime)
Route::get('/sensor/live', function () {
    $data = SensorData::latest()->take(50)->get()->reverse()->values();
    $latest = $data->last();

    // Ambil batas level air dari pengaturan
    $airMin = (float) Pengaturan::where('nama', 'air_min')->value('nilai') ?? 10;

    return response()->json([
        'waktu' => $data->pluck('created_at')->map(fn($t) => $t->format('H:i')),
        'suhu' => $data->pluck('suhu'),
        'tds' => $data->pluck('tds'),
        'ph' => $data->pluck('ph'),
        'suhuudara' => $data->pluck('suhuudara'),
        'kelembaban' => $data->pluck('kelembaban'),
        'level_air' => $data->pluck('level_air')->map(function ($value) use ($airMin) {
            if ($value === null) return null;

            $max = $airMin > 0 ? $airMin : 1; // hindari pembagian nol

            // jika nilai air makin kecil, persentase makin besar
            $percent = (1 - ($value / $max)) * 100;

            // batasi agar tetap di antara 0–100%
            return round(max(0, min($percent, 100)), 1);
        }),

        // Data tambahan
        'pompa_air'     => (bool) optional($latest)->pompa_air,
        'pompa_nutrisi' => (bool) optional($latest)->pompa_nutrisi,
        'water_stat'    => optional($latest)->level_air,
        'air_min'       => $airMin,
    ]);
});
