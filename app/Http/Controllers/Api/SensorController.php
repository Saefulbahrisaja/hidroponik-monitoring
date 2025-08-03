<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Models\Pengaturan;

class SensorController extends Controller
{
    public function store(Request $request)
    {
         $data = $request->input('data');

        $sensor = SensorData::create([
            'suhu' => $data['suhu'],
            'kelembaban' => $data['kelembaban'],
            'suhuudara' => $data['suhuudara'],
            'ph' => $data['ph'],
            'tds' => $data['tds'],
        ]);

        return response()->json(['success' => true, 'data' => $sensor], 201);
        }
        public function getBatas()
        {
            $tdsMin   = Pengaturan::where('nama', 'tds_min')->value('nilai') ?? 700;
            $airMin   = Pengaturan::where('nama', 'air_min')->value('nilai') ?? 10;
            $interval = Pengaturan::where('nama', 'interval')->value('nilai') ?? 10; // default 10 detik

            return response()->json([
                'tds_min'  => (float) $tdsMin,
                'air_min'  => (float) $airMin,
                'interval' => (int) $interval
            ]);
        }

        public function updateBatas(Request $request)
        {
            Pengaturan::updateOrCreate(['nama' => 'tds_min'], ['nilai' => $request->tds_min]);
            Pengaturan::updateOrCreate(['nama' => 'air_min'], ['nilai' => $request->air_min]);
            Pengaturan::updateOrCreate(['nama' => 'interval'], ['nilai' => $request->interval]);

            return response()->json(['success' => true]);
        }
}
