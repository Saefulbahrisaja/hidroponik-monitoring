<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
});
Route::get('/sensor/live', function () {
    $data = \App\Models\SensorData::latest()->take(50)->get()->reverse()->values();
    return response()->json([
        'waktu' => $data->pluck('created_at')->map(fn($t) => $t->format('H:i:s')),
        'suhu' => $data->pluck('suhu'),
        'tds' => $data->pluck('tds'),
        'ph' => $data->pluck('ph'),
    ]);
});
