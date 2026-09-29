@extends('layouts.app')

@section('title', 'Analisis Kondisi Hidroponik - SIKECE')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <div class="text-muted small">ANALISIS DATA AI + IoT</div>
            <h2 class="fw-bold mb-1">Hubungan Kondisi Hidroponik dengan Penyakit Tanaman</h2>
            <p class="text-muted mb-0">Membandingkan kondisi sensor pada saat tanaman terdeteksi <b>healthy</b> dan <b>non-healthy</b>.</p>
        </div>
        <form method="GET" class="d-flex gap-2">
            <select name="days" class="form-select">
                <option value="7" @selected($days === 7)>7 hari</option>
                <option value="30" @selected($days === 30)>30 hari</option>
                <option value="90" @selected($days === 90)>90 hari</option>
                <option value="180" @selected($days === 180)>180 hari</option>
                <option value="365" @selected($days === 365)>365 hari</option>
            </select>
            <button class="btn btn-primary">Analisis</button>
        </form>
    </div>

    @if($empty || $total === 0)
        <div class="alert alert-info">Belum ada data deteksi penyakit pada periode yang dipilih.</div>
    @else
        <div class="row g-3 mb-4">
            <div class="col-md-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-muted small">TOTAL DETEKSI</div><div class="fs-2 fw-bold">{{ $total }}</div></div></div></div>
            <div class="col-md-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-muted small">HEALTHY</div><div class="fs-2 fw-bold text-success">{{ $healthy }}</div></div></div></div>
            <div class="col-md-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-muted small">NON-HEALTHY</div><div class="fs-2 fw-bold text-danger">{{ $sick }}</div></div></div></div>
            <div class="col-md-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-muted small">PERIODE</div><div class="fs-4 fw-bold">{{ $days }} hari</div></div></div></div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-bold">Distribusi Hasil AI</h5>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead><tr><th>Kondisi</th><th>Jumlah</th><th>Rata-rata Confidence</th></tr></thead>
                                <tbody>
                                @foreach($diseaseBreakdown as $row)
                                    <tr><td>{{ $row['disease'] }}</td><td>{{ $row['count'] }}</td><td>{{ number_format($row['avg_confidence'], 2) }}%</td></tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-bold">Perbandingan Kondisi Sensor</h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead><tr><th>Parameter</th><th>Healthy</th><th>Non-healthy</th><th>Selisih</th></tr></thead>
                                <tbody>
                                @foreach($comparison as $row)
                                    <tr>
                                        <td><b>{{ $row['label'] }}</b> <span class="text-muted">({{ $row['unit'] }})</span></td>
                                        <td>{{ $row['healthy_mean'] !== null ? number_format($row['healthy_mean'], 2) : '-' }}</td>
                                        <td>{{ $row['sick_mean'] !== null ? number_format($row['sick_mean'], 2) : '-' }}</td>
                                        <td>{{ $row['difference'] !== null ? number_format($row['difference'], 2) : '-' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="small text-muted">Selisih = rata-rata non-healthy dikurangi rata-rata healthy. Ini menunjukkan perbedaan observasi, bukan bukti bahwa parameter tersebut menyebabkan penyakit.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-1">Korelasi Sensor dengan Status Penyakit</h5>
                <p class="text-muted small">Pearson correlation terhadap indikator biner: healthy = 0, non-healthy = 1. Nilai mendekati 0 berarti hubungan linear yang teramati lemah; nilai ini bukan ukuran kausalitas.</p>
                <div class="row g-3">
                    @foreach($interpretations as $item)
                        <div class="col-md-6 col-xl-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">{{ $item['label'] }}</div>
                                <div class="fs-3 fw-bold">{{ $item['correlation'] !== null ? number_format($item['correlation'], 4) : '-' }}</div>
                                <div class="small">{{ $item['direction'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold">Cara Membaca Analisis</h5>
                <ol class="mb-0">
                    <li>Gunakan tabel perbandingan untuk melihat parameter mana yang rata-ratanya berbeda antara deteksi healthy dan non-healthy.</li>
                    <li>Gunakan korelasi sebagai indikator asosiasi awal, bukan sebagai kesimpulan sebab-akibat.</li>
                    <li>Semakin banyak data deteksi yang tersimpan pada kondisi hidroponik yang berbeda, semakin informatif analisisnya.</li>
                    <li>Untuk penelitian lebih lanjut, data dapat dianalisis berdasarkan jenis penyakit, waktu, tanaman, dan kondisi sensor sebelum deteksi.</li>
                </ol>
            </div>
        </div>
    @endif
</div>
@endsection
