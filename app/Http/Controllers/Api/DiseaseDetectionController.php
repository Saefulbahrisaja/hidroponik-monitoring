<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiseaseDetection;
use App\Models\Pengaturan;
use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DiseaseDetectionController extends Controller
{
    /**
     * Sensor terbaru untuk dikaitkan dengan foto yang baru saja dianalisis.
     */
    public function latestSensor()
    {
        $sensor = SensorData::latest('created_at')->first();

        if (!$sensor) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada data sensor.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'sensor_data_id' => $sensor->id,
                'tanaman_id' => $sensor->tanaman_id,
                'tds' => $sensor->tds,
                'suhu_air' => $sensor->suhu,
                'suhu_udara' => $sensor->suhuudara,
                'kelembaban' => $sensor->kelembaban,
                'recorded_at' => optional($sensor->created_at)->toIso8601String(),
            ],
        ]);
    }

    /**
     * Simpan foto + hasil AI + snapshot kondisi hidroponik.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'disease' => ['required', 'string', 'max:100'],
            'confidence' => ['required', 'numeric', 'min:0', 'max:100'],
            'sensor_data_id' => ['nullable', 'integer', 'exists:sensor_data,id'],
            'tanaman_id' => ['nullable', 'integer', 'exists:tanaman,id'],
            'device_id' => ['nullable', 'string', 'max:120'],
            'detected_at' => ['nullable', 'date'],
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $sensor = null;

            if (!empty($validated['sensor_data_id'])) {
                $sensor = SensorData::find($validated['sensor_data_id']);
            }

            // Jika Android tidak mengirim ID sensor, ambil pembacaan terbaru.
            if (!$sensor) {
                $sensor = SensorData::latest('created_at')->first();
            }

            if (!$sensor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sensor belum tersedia, sehingga snapshot kondisi hidroponik tidak dapat dibuat.',
                ], 422);
            }

            $tanamanId = $validated['tanaman_id']
                ?? $sensor->tanaman_id
                ?? Pengaturan::where('nama', 'tanaman_aktif')->value('nilai');

            $date = Carbon::now();
            $folder = 'uploads/disease/' . $date->format('Y/m/d');
            $filename = Str::uuid()->toString() . '.' . strtolower($request->file('image')->extension());

            // Shared hosting: buat folder secara otomatis, tanpa storage:link.
            File::ensureDirectoryExists(public_path($folder));
            $request->file('image')->move(public_path($folder), $filename);

            $imagePath = $folder . '/' . $filename;

            $detection = DiseaseDetection::create([
                'sensor_data_id' => $sensor->id,
                'tanaman_id' => $tanamanId,
                'device_id' => $validated['device_id'] ?? null,
                'disease' => trim($validated['disease']),
                'confidence' => (float) $validated['confidence'],
                'image_path' => $imagePath,
                'tds' => $sensor->tds,
                'suhu_air' => $sensor->suhu,
                'suhu_udara' => $sensor->suhuudara,
                'kelembaban' => $sensor->kelembaban,
                'detected_at' => !empty($validated['detected_at'])
                    ? Carbon::parse($validated['detected_at'])
                    : Carbon::now(),
            ]);

            $detection->load('tanaman', 'sensorData');

            return response()->json([
                'success' => true,
                'message' => 'Hasil deteksi berhasil disimpan.',
                'data' => [
                    'id' => $detection->id,
                    'disease' => $detection->disease,
                    'confidence' => $detection->confidence,
                    'image_url' => asset($detection->image_path),
                    'sensor_data_id' => $detection->sensor_data_id,
                    'tanaman_id' => $detection->tanaman_id,
                    'tanaman' => optional($detection->tanaman)->nama_tanaman,
                    'tds' => $detection->tds,
                    'suhu_air' => $detection->suhu_air,
                    'suhu_udara' => $detection->suhu_udara,
                    'kelembaban' => $detection->kelembaban,
                    'detected_at' => optional($detection->detected_at)->toIso8601String(),
                ],
            ], 201);
        });
    }

    public function index(Request $request)
    {
        $query = DiseaseDetection::with('tanaman')->latest('detected_at');

        if ($request->filled('disease')) {
            $query->where('disease', $request->string('disease'));
        }

        $detections = $query->paginate(20)->withQueryString();

        return response()->json($detections);
    }
}
