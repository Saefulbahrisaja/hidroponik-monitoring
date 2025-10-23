<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Models\Pengaturan;
use Illuminate\Http\JsonResponse;
use App\Models\Tanaman;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->input('data');

        // Ambil ID tanaman aktif dari tabel pengaturans
        $tanamanAktifId = Pengaturan::where('nama', 'tanaman_aktif')->value('nilai');

        // Simpan data sensor ke database
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
            'tanaman_id'    => $tanamanAktifId, // <<— disimpan otomatis
        ]);

        return response()->json([
            'success' => true,
            'data' => $sensor
        ], 201);
    }

    public function getBatas()
    {
        $tdsMin   = Pengaturan::where('nama', 'tds_min')->value('nilai') ?? 700;
        $airMin   = Pengaturan::where('nama', 'air_min')->value('nilai') ?? 10;
        $interval = Pengaturan::where('nama', 'interval')->value('nilai') ?? 10; 
        $tanamanAktif = Pengaturan::where('nama', 'tanaman_aktif')->value('nilai');

        return response()->json([
            'tds_min'  => (float) $tdsMin,
            'air_min'  => (float) $airMin,
            'interval' => (int) $interval,
            'tanaman_aktif' => $tanamanAktif ? (int) $tanamanAktif : null,
        ]);
    }

    public function updateBatas(Request $request): JsonResponse
{
    // Gunakan nilai default jika request tidak mengirimkan data
    $tdsMin     = $request->input('tds_min', 700);
    $airMin     = $request->input('air_min', 10);
    $interval   = $request->input('interval', 10);
    $tanamanAktif = $request->input('tanaman_aktif', null);

    // Update atau buat baru
    Pengaturan::updateOrCreate(['nama' => 'tds_min'], ['nilai' => $tdsMin]);
    Pengaturan::updateOrCreate(['nama' => 'air_min'], ['nilai' => $airMin]);
    Pengaturan::updateOrCreate(['nama' => 'interval'], ['nilai' => $interval]);

    // Update tanaman aktif jika dikirim
    if (!is_null($tanamanAktif)) {
        Pengaturan::updateOrCreate(['nama' => 'tanaman_aktif'], ['nilai' => $tanamanAktif]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Pengaturan berhasil disimpan!',
        'data' => [
            'tds_min' => $tdsMin,
            'air_min' => $airMin,
            'interval' => $interval,
            'tanaman_aktif' => $tanamanAktif,
        ]
    ]);
}

/**
     * Ambil detail tanaman aktif dari tabel tanaman
     */
    public function getTanamanAktif(): JsonResponse
    {
        $tanamanAktifId = Pengaturan::where('nama', 'tanaman_aktif')->value('nilai');

        if (!$tanamanAktifId) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada tanaman aktif yang diset',
                'data' => null
            ], 404);
        }

        $tanaman = Tanaman::find($tanamanAktifId);

        if (!$tanaman) {
            return response()->json([
                'success' => false,
                'message' => 'Tanaman aktif tidak ditemukan di database',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tanaman
        ]);
    }

}
