<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Models\SensorData;
use App\Models\Pengaturan;
use App\Exports\SensorExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;


Route::get('/export-excel', function (Request $request) {
    $filter = $request->filter ?? 'hari';
    return Excel::download(new SensorExport($filter), 'data-sensor.xlsx');
});

Route::get('/', function () {
    return view('dashboard.index');
});

Route::get('/', [DashboardController::class, 'index']);
Route::get('/sensor/live', function () {
    $data = SensorData::latest()->take(50)->get()->reverse()->values();
    $latest = $data->last(); // Ambil data terbaru (terakhir setelah reverse)

    return response()->json([
        'waktu' => $data->pluck('created_at')->map(fn($t) => $t->format('H:i')),
        'suhu' => $data->pluck('suhu'),
        'tds' => $data->pluck('tds'),
        'ph' => $data->pluck('ph'),
        'suhuudara' => $data->pluck('suhuudara'),
        'kelembaban' => $data->pluck('kelembaban'),
        'level_air'     => $data->pluck('level_air'),
        
        // Ambil status sistem dari record terbaru
        'pompa_air'     => (bool) optional($latest)->pompa_air,
        'pompa_nutrisi' => (bool) optional($latest)->pompa_nutrisi,
        'water_stat'     => optional($latest)->level_air,
        'air_min'       => (float) Pengaturan::where('nama', 'air_min')->value('nilai') ?? 10,
    ]);
}); 


