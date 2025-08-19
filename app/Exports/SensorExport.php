<?php

namespace App\Exports;

use App\Models\SensorData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;

class SensorExport implements FromCollection
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = SensorData::query();

        if ($this->filter === 'hari') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($this->filter === 'minggu') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($this->filter === 'bulan') {
            $query->whereMonth('created_at', Carbon::now()->month);
        }

        return $query->get([
            'created_at',
            'suhu',
            'tds',
            'ph',
            'suhuudara',
            'kelembaban'
        ]);
    }
}


