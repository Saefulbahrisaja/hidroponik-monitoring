<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseDetection extends Model
{
    use HasFactory;

    protected $table = 'disease_detections';

    protected $fillable = [
        'sensor_data_id',
        'tanaman_id',
        'device_id',
        'disease',
        'confidence',
        'image_path',
        'tds',
        'suhu_air',
        'suhu_udara',
        'kelembaban',
        'detected_at',
    ];

    protected $casts = [
        'confidence' => 'float',
        'tds' => 'float',
        'suhu_air' => 'float',
        'suhu_udara' => 'float',
        'kelembaban' => 'float',
        'detected_at' => 'datetime',
    ];

    public function sensorData()
    {
        return $this->belongsTo(SensorData::class);
    }

    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class);
    }
}
