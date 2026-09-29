<?php

namespace App\Http\Controllers;

use App\Models\DiseaseDetection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class DiseaseAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $days = max(7, min(365, (int) $request->get('days', 30)));
        $since = Carbon::now()->subDays($days - 1)->startOfDay();

        $empty = [
            'count' => 0,
            'mean' => null,
            'min' => null,
            'max' => null,
        ];

        if (!Schema::hasTable('disease_detections')) {
            return view('analysis.disease', [
                'days' => $days,
                'total' => 0,
                'healthy' => 0,
                'sick' => 0,
                'diseaseBreakdown' => collect(),
                'variables' => [],
                'comparison' => [],
                'interpretations' => [],
                'empty' => true,
            ]);
        }

        $rows = DiseaseDetection::query()
            ->where('detected_at', '>=', $since)
            ->orderBy('detected_at')
            ->get([
                'disease', 'confidence', 'tds', 'suhu_air',
                'suhu_udara', 'kelembaban', 'detected_at',
            ]);

        $total = $rows->count();
        $healthy = $rows->filter(fn ($r) => strtolower(trim($r->disease)) === 'healthy')->count();
        $sick = $total - $healthy;

        $diseaseBreakdown = $rows
            ->groupBy(fn ($r) => trim($r->disease))
            ->map(fn ($items, $name) => [
                'disease' => $name,
                'count' => $items->count(),
                'avg_confidence' => round((float) $items->avg('confidence'), 2),
            ])
            ->sortByDesc('count')
            ->values();

        $definitions = [
            'tds' => ['label' => 'TDS', 'unit' => 'ppm'],
            'suhu_air' => ['label' => 'Suhu Air', 'unit' => '°C'],
            'suhu_udara' => ['label' => 'Suhu Udara', 'unit' => '°C'],
            'kelembaban' => ['label' => 'Kelembaban', 'unit' => '%'],
        ];

        $comparison = [];
        foreach ($definitions as $field => $meta) {
            $all = $rows->filter(fn ($r) => is_numeric($r->{$field}));
            $healthyRows = $all->filter(fn ($r) => strtolower(trim($r->disease)) === 'healthy');
            $sickRows = $all->filter(fn ($r) => strtolower(trim($r->disease)) !== 'healthy');

            $comparison[$field] = [
                'label' => $meta['label'],
                'unit' => $meta['unit'],
                'healthy_mean' => $healthyRows->count() ? round((float) $healthyRows->avg($field), 2) : null,
                'sick_mean' => $sickRows->count() ? round((float) $sickRows->avg($field), 2) : null,
                'healthy_count' => $healthyRows->count(),
                'sick_count' => $sickRows->count(),
                'difference' => ($healthyRows->count() && $sickRows->count())
                    ? round((float) $sickRows->avg($field) - (float) $healthyRows->avg($field), 2)
                    : null,
            ];
        }

        // Korelasi Pearson antara nilai sensor dan indikator biner sakit:
        // 0 = healthy, 1 = non-healthy. Ini adalah asosiasi, bukan bukti sebab-akibat.
        $correlations = [];
        foreach ($definitions as $field => $meta) {
            $pairs = $rows->filter(fn ($r) => is_numeric($r->{$field}))
                ->map(fn ($r) => [
                    'x' => (float) $r->{$field},
                    'y' => strtolower(trim($r->disease)) === 'healthy' ? 0.0 : 1.0,
                ])->values();

            $correlations[$field] = $this->pearson($pairs);
        }

        $interpretations = [];
        foreach ($definitions as $field => $meta) {
            $c = $correlations[$field];
            $cmp = $comparison[$field];
            $direction = $c === null ? 'belum dapat dihitung' : ($c > 0 ? 'cenderung lebih tinggi pada deteksi non-sehat' : ($c < 0 ? 'cenderung lebih rendah pada deteksi non-sehat' : 'tidak menunjukkan arah linear yang jelas'));

            $interpretations[] = [
                'field' => $field,
                'label' => $meta['label'],
                'correlation' => $c,
                'direction' => $direction,
                'difference' => $cmp['difference'],
            ];
        }

        $latest = $rows->last();

        return view('analysis.disease', compact(
            'days', 'total', 'healthy', 'sick', 'diseaseBreakdown',
            'comparison', 'correlations', 'interpretations', 'latest', 'empty'
        ));
    }

    private function pearson($pairs): ?float
    {
        $n = $pairs->count();
        if ($n < 3) {
            return null;
        }

        $meanX = $pairs->avg('x');
        $meanY = $pairs->avg('y');

        $num = 0.0;
        $sumX = 0.0;
        $sumY = 0.0;

        foreach ($pairs as $pair) {
            $dx = $pair['x'] - $meanX;
            $dy = $pair['y'] - $meanY;
            $num += $dx * $dy;
            $sumX += $dx * $dx;
            $sumY += $dy * $dy;
        }

        $den = sqrt($sumX * $sumY);
        return $den > 0 ? round($num / $den, 4) : null;
    }
}
