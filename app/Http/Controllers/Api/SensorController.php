<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Models\Pengaturan;
use Illuminate\Http\JsonResponse;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        
        $data = $request->input('data');

        $sensor = SensorData::create([
                'suhu'          => $data['suhu'],
                'kelembaban'    => $data['kelembaban'],
                'suhuudara'     => $data['suhuudara'],
                'ph'            => $data['ph'],
                'tds'           => $data['tds'],
                'pompa_air'     => $data['pompa_air'],
                'pompa_nutrisi' => $data['pompa_nutrisi'],
                'level_air'     => $data['level_air'],
                'air_min'       => $data['air_min'],
            ]);

        return response()->json(['success' => true, 'data' => $sensor], 201);
    }
    
        public function getBatas()
        {
            $tdsMin   = Pengaturan::where('nama', 'tds_min')->value('nilai') ?? 700;
            $airMin   = Pengaturan::where('nama', 'air_min')->value('nilai') ?? 10;
            $interval = Pengaturan::where('nama', 'interval')->value('nilai') ?? 10; 
            // $suhu_udara_max = Pengaturan::where('nama', 'suhu_udara_max')->value('nilai') ?? 10; 
            // $volume_air = Pengaturan::where('nama', 'volume_air')->value('nilai') ?? 10; 
            // $volume_nutrisi_a = Pengaturan::where('nama', 'volume_nutrisi_a')->value('nilai') ?? 10; 
            // $volume_nutrisi_b = Pengaturan::where('nama', 'volume_nutrisi_b')->value('nilai') ?? 10; 

            return response()->json([
                'tds_min'  => (float) $tdsMin,
                'air_min'  => (float) $airMin,
                'interval' => (int) $interval,
                // 'suhu_udara_max'  => (float) $suhu_udara_max,
                // 'volume_nutrisi_a'  => (float) $volume_nutrisi_a,
                // 'volume_nutrisi_b' => (int) $volume_nutrisi_b,
            ]);
        }

        public function updateBatas(Request $request): JsonResponse
        {
            Pengaturan::updateOrCreate(['nama' => 'tds_min'], ['nilai' => $request->tds_min]);
            Pengaturan::updateOrCreate(['nama' => 'air_min'], ['nilai' => $request->air_min]);
            Pengaturan::updateOrCreate(['nama' => 'interval'], ['nilai' => $request->interval]);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan berhasil disimpan!'
            ]);
        }
}
