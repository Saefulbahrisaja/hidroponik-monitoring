<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $table = 'sensor_data';
    protected $fillable = ['suhu', 
    'kelembaban',
    'suhuudara', 
    'ph', 
    'tds',
    'pompa_air',
    'pompa_nutrisi',
    'level_air',
    'air_min',];
}
