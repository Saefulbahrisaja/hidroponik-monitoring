<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\SensorData;

class DashboardController extends Controller
{
    public function index()
    {
        $data = SensorData::latest()->take(50)->get();
        $filter = $request->filter ?? 'hari';
        $query = SensorData::query();

        if ($filter === 'hari') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === 'minggu') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filter === 'bulan') {
            $query->whereMonth('created_at', Carbon::now()->month);
        }

        $dataSensor = $query->orderBy('created_at')->get();

        // Siapkan array untuk grafik
        $waktu = $dataSensor->pluck('created_at')->map(fn($w) => $w->format('H:i'))->toArray();
        $suhu = $dataSensor->pluck('suhu_air')->toArray();
        $tds = $dataSensor->pluck('tds')->toArray();
        $ph = $dataSensor->pluck('ph')->toArray();
        $suhuudara = $dataSensor->pluck('suhu_udara')->toArray();
        $kelembaban = $dataSensor->pluck('kelembaban_udara')->toArray();

        return view('dashboard.index', compact('waktu', 'suhu', 'tds', 'ph', 'suhuudara', 'kelembaban','data'));
}
}
