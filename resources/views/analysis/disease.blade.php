@extends('layouts.app')

@section('title', 'Analisis Kondisi Hidroponik - SIKECE')

@section('content')
<div class="space-y-6">

    {{-- HEADER SECTION & FILTER PERIODE --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold mb-2 border border-emerald-100">
                <i class="fas fa-brain text-emerald-600"></i>
                AI + IoT Data Intelligence
            </div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Analisis Kondisi Hidroponik & Penyakit</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Membandingkan rata-rata parameter sensor pada saat tanaman terdeteksi <b class="text-emerald-600">Healthy</b> dan <b class="text-rose-600">Non-Healthy</b>.
            </p>
        </div>

        {{-- Form Filter Hari --}}
        <form method="GET" class="flex items-center gap-2 shrink-0">
            <div class="relative">
                <select name="days" class="form-input !py-2.5 !px-3.5 !text-xs font-bold cursor-pointer pr-8">
                    <option value="7" @selected($days === 7)>7 Hari Terakhir</option>
                    <option value="30" @selected($days === 30)>30 Hari Terakhir</option>
                    <option value="90" @selected($days === 90)>90 Hari Terakhir</option>
                    <option value="180" @selected($days === 180)>180 Hari Terakhir</option>
                    <option value="365" @selected($days === 365)>1 Tahun Terakhir</option>
                </select>
            </div>
            <button type="submit" class="btn-primary !py-2.5 !px-4 !text-xs font-bold">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
        </form>
    </div>

    @if($empty || $total === 0)
        {{-- EMPTY STATE --}}
        <div class="bg-white rounded-2xl p-12 border border-slate-200/80 text-center shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-4 border border-amber-100 text-2xl">
                <i class="fas fa-chart-line text-amber-500"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800">Belum Ada Data Analisis</h3>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mt-1">
                Tidak ditemukan data deteksi penyakit dan sensor pada periode <b>{{ $days }} hari terakhir</b>. Silakan pilih rentang periode yang lebih luas.
            </p>
        </div>
    @else
        {{-- STATS SUMMARY --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Deteksi --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg shrink-0">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total Deteksi</p>
                    <p class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($total) }}</p>
                </div>
            </div>

            {{-- Healthy --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 border border-emerald-100">
                    <i class="fas fa-heart-pulse"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Healthy</p>
                    <p class="text-2xl font-black text-emerald-600 mt-0.5">{{ number_format($healthy) }}</p>
                </div>
            </div>

            {{-- Non-Healthy --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0 border border-rose-100">
                    <i class="fas fa-virus"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Non-Healthy</p>
                    <p class="text-2xl font-black text-rose-600 mt-0.5">{{ number_format($sick) }}</p>
                </div>
            </div>

            {{-- Periode --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg shrink-0 border border-sky-100">
                    <i class="fas fa-calendar-days"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Periode Data</p>
                    <p class="text-2xl font-black text-sky-600 mt-0.5">{{ $days }} <span class="text-xs font-bold text-slate-400">Hari</span></p>
                </div>
            </div>
        </div>

        {{-- GRID METRIK DETEKSI & PERBANDINGAN SENSOR --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Distribusi Hasil AI (5 Kolom Desktop) --}}
            <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-pie-chart text-emerald-600"></i>
                            Distribusi Hasil AI
                        </h3>
                        <span class="text-xs font-semibold text-slate-400">Klasifikasi AI</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                                <tr>
                                    <th class="py-2.5 px-3">Kondisi / Diagnosa</th>
                                    <th class="py-2.5 px-3">Jumlah</th>
                                    <th class="py-2.5 px-3 text-right">Rata-rata Confidence</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                            @foreach($diseaseBreakdown as $row)
                                @php
                                    $dName = strtolower($row['disease']);
                                    $isGood = str_contains($dName, 'healthy') || str_contains($dName, 'sehat');
                                @endphp
                                <tr class="hover:bg-slate-50/80">
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $isGood ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            <i class="fas {{ $isGood ? 'fa-check-circle' : 'fa-triangle-exclamation' }} text-[10px]"></i>
                                            {{ ucwords(str_replace(['_', '-'], ' ', $row['disease'])) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 font-bold text-slate-800">{{ number_format($row['count']) }}</td>
                                    <td class="py-3 px-3 text-right font-bold text-emerald-700">
                                        {{ number_format($row['avg_confidence'], 2) }}%
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Perbandingan Kondisi Sensor (7 Kolom Desktop) --}}
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-sliders text-emerald-600"></i>
                        Perbandingan Rata-rata Sensor
                    </h3>
                    <span class="text-xs font-semibold text-slate-400">Healthy vs Non-Healthy</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                            <tr>
                                <th class="py-2.5 px-3">Parameter Sensor</th>
                                <th class="py-2.5 px-3 text-emerald-700">Healthy</th>
                                <th class="py-2.5 px-3 text-rose-700">Non-Healthy</th>
                                <th class="py-2.5 px-3 text-right">Selisih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($comparison as $row)
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-3 px-3">
                                    <b class="text-slate-800 block">{{ $row['label'] }}</b>
                                    <span class="text-[11px] text-slate-400 font-semibold">({{ $row['unit'] }})</span>
                                </td>
                                <td class="py-3 px-3 font-bold text-emerald-700">
                                    {{ $row['healthy_mean'] !== null ? number_format($row['healthy_mean'], 2) : '-' }}
                                </td>
                                <td class="py-3 px-3 font-bold text-rose-700">
                                    {{ $row['sick_mean'] !== null ? number_format($row['sick_mean'], 2) : '-' }}
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if($row['difference'] !== null)
                                        <span class="inline-block px-2 py-0.5 rounded-md text-xs font-extrabold {{ $row['difference'] > 0 ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
                                            {{ $row['difference'] > 0 ? '+' : '' }}{{ number_format($row['difference'], 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-[11px] text-slate-400 mt-3 leading-relaxed">
                    * <b>Selisih</b> = Rata-rata Non-Healthy dikurangi Rata-rata Healthy. Nilai ini menunjukkan perbedaan observasi empiris lingkungan hidroponik.
                </p>
            </div>

        </div>

        {{-- KORELASI SENSOR DENGAN STATUS PENYAKIT --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <div class="mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-[#0284c7] fa-diagram-project text-sky-600"></i>
                    Korelasi Pearson Sensor Terhadap Status Penyakit
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                    Nilai korelasi terhadap indikator biner: <b>Healthy = 0</b>, <b>Non-Healthy = 1</b>. Nilai mendekati 0 berarti hubungan linear teramati relatif lemah.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach($interpretations as $item)
                    <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/60 hover:border-slate-300 transition-colors">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">{{ $item['label'] }}</p>
                        <div class="flex items-baseline justify-between mt-2">
                            <span class="text-2xl font-black text-slate-800">
                                {{ $item['correlation'] !== null ? number_format($item['correlation'], 4) : '-' }}
                            </span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-white border border-slate-200 text-slate-600">
                                {{ $item['direction'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- PANDUAN MEMBACA ANALISIS --}}
        <div class="glass-card p-6 border border-slate-200/80 shadow-xs rounded-2xl bg-white">
            <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2 mb-3">
                <i class="fas fa-circle-info text-emerald-600"></i>
                Cara Membaca & Memanfaatkan Analisis Data
            </h3>
            <ol class="list-decimal list-inside space-y-2 text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                <li>Gunakan <b>Tabel Perbandingan Rata-rata</b> untuk memantau parameter fisik (seperti Suhu & TDS) yang mengalami lonjakan atau penurunan signifikan pada tanaman sakit.</li>
                <li>Gunakan nilai <b>Korelasi Pearson</b> sebagai indikator asosiasi awal untuk evaluasi pola lingkungan kebun, bukan kesimpulan sebab-akibat langsung.</li>
                <li>Semakin konsisten pengambilan sampel foto dan perekaman sensor IoT, semakin akurat analisis data yang dihasilkan oleh sistem AI.</li>
            </ol>
        </div>
    @endif

</div>
@endsection