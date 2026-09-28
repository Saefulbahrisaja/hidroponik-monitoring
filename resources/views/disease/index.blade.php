@extends('layouts.app')

@section('title', 'Riwayat Deteksi Penyakit Daun - SIKECE')

@section('content')
<div class="space-y-6">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold mb-2 border border-emerald-100">
                <i class="fas fa-camera text-emerald-600"></i>
                AI Leaf Disease Detection
            </div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Riwayat Deteksi Penyakit</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar foto sampel daun, analisis penyakit AI, persentase confidence, dan parameter lingkungan saat pemotretan.</p>
        </div>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-emerald-600 transition-colors shadow-md shrink-0">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Deteksi --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-images"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total Sampel</p>
                <p class="text-xl sm:text-2xl font-black text-slate-800 mt-0.5">{{ $detections->total() }}</p>
            </div>
        </div>

        {{-- Healthy --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 border border-emerald-100">
                <i class="fas fa-seedling"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Tanaman Sehat</p>
                <p class="text-xl sm:text-2xl font-black text-emerald-600 mt-0.5">
                    {{ $detections->whereIn('disease', ['Healthy', 'healthy', 'Sehat'])->count() }}
                </p>
            </div>
        </div>

        {{-- Pest / Hama --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0 border border-amber-100">
                <i class="fas fa-bug"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Hama / Pest</p>
                <p class="text-xl sm:text-2xl font-black text-amber-600 mt-0.5">
                    {{ $detections->whereIn('disease', ['Pest', 'pest', 'Hama'])->count() }}
                </p>
            </div>
        </div>

        {{-- Virus --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0 border border-rose-100">
                <i class="fas fa-virus"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Penyakit / Virus</p>
                <p class="text-xl sm:text-2xl font-black text-rose-600 mt-0.5">
                    {{ $detections->whereIn('disease', ['Virus', 'virus', 'Bakteri'])->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- TABEL DATA DETEKSI --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                <i class="fas fa-list text-emerald-600"></i>
                Daftar Riwayat Deteksi
            </h2>
            <span class="text-xs font-semibold text-slate-400">Total: {{ $detections->total() }} Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-xs sm:text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-4 py-3.5">Sampel Daun</th>
                        <th class="px-4 py-3.5">Waktu Deteksi</th>
                        <th class="px-4 py-3.5">Tanaman</th>
                        <th class="px-4 py-3.5">Hasil Diagnosa AI</th>
                        <th class="px-4 py-3.5">Akurasi (Confidence)</th>
                        <th class="px-4 py-3.5">TDS Nutrisi</th>
                        <th class="px-4 py-3.5">Suhu Air</th>
                        <th class="px-4 py-3.5">Suhu Udara</th>
                        <th class="px-4 py-3.5">Kelembaban</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                @forelse($detections as $detection)
                    @php
                        // Menyesuaikan format confidence
                        $rawConf = (float) $detection->confidence;
                        $confPercent = $rawConf <= 1 ? $rawConf * 100 : $rawConf;
                        $diseaseLower = strtolower($detection->disease);
                        $isHealthy = str_contains($diseaseLower, 'healthy') || str_contains($diseaseLower, 'sehat');
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        {{-- Foto Sampel --}}
                        <td class="px-4 py-3">
                            @if($detection->image_path)
                                <a href="{{ asset($detection->image_path) }}" target="_blank" class="block relative group w-14 h-14">
                                    <img src="{{ asset($detection->image_path) }}" 
                                         class="w-14 h-14 rounded-xl object-cover border border-slate-200 shadow-xs group-hover:scale-105 transition-transform" 
                                         alt="Daun">
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 rounded-xl flex items-center justify-center text-white text-xs transition-opacity">
                                        <i class="fas fa-magnifying-glass-plus"></i>
                                    </div>
                                </a>
                            @else
                                <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                    <i class="fas fa-image text-lg"></i>
                                </div>
                            @endif
                        </td>

                        {{-- Waktu Deteksi --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="font-bold text-slate-800 block">
                                {{ optional($detection->detected_at)->format('d M Y') }}
                            </span>
                            <span class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                <i class="far fa-clock"></i>
                                {{ optional($detection->detected_at)->format('H:i:s') }} WIB
                            </span>
                        </td>

                        {{-- Tanaman --}}
                        <td class="px-4 py-3 font-semibold text-slate-800">
                            {{ optional($detection->tanaman)->nama_tanaman ?? '-' }}
                        </td>

                        {{-- Diagnosa AI --}}
                        <td class="px-4 py-3">
                            @if($isHealthy)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <i class="fas fa-circle-check text-[10px]"></i>
                                    {{ ucwords(str_replace(['_', '-'], ' ', $detection->disease)) }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 text-rose-800 border border-rose-200">
                                    <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                    {{ ucwords(str_replace(['_', '-'], ' ', $detection->disease)) }}
                                </span>
                            @endif
                        </td>

                        {{-- Confidence Bar --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-16 bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                                    <div class="h-full rounded-full {{ $confPercent >= 75 ? 'bg-emerald-500' : 'bg-amber-500' }}" 
                                         style="width: {{ min($confPercent, 100) }}%;"></div>
                                </div>
                                <span class="font-bold text-slate-800 text-xs">
                                    {{ number_format($confPercent, 1) }}%
                                </span>
                            </div>
                        </td>

                        {{-- TDS Nutrisi --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($detection->tds !== null)
                                <span class="font-bold text-emerald-700">{{ number_format($detection->tds, 0) }}</span>
                                <span class="text-[11px] text-slate-400 font-semibold">ppm</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>

                        {{-- Suhu Air --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($detection->suhu_air !== null)
                                <span class="font-bold text-sky-700">{{ number_format($detection->suhu_air, 1) }}</span>
                                <span class="text-[11px] text-slate-400 font-semibold">°C</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>

                        {{-- Suhu Udara --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($detection->suhu_udara !== null)
                                <span class="font-bold text-teal-700">{{ number_format($detection->suhu_udara, 1) }}</span>
                                <span class="text-[11px] text-slate-400 font-semibold">°C</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>

                        {{-- Kelembaban --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($detection->kelembaban !== null)
                                <span class="font-bold text-violet-700">{{ number_format($detection->kelembaban, 1) }}</span>
                                <span class="text-[11px] text-slate-400 font-semibold">%</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center bg-slate-50/50">
                            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-inbox text-xl"></i>
                            </div>
                            <p class="font-bold text-slate-700 text-sm">Belum Ada Riwayat Deteksi</p>
                            <p class="text-xs text-slate-400 mt-1">Gunakan aplikasi mobile untuk melakukan pemotretan sampel daun hidroponik.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINASI --}}
        @if($detections->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $detections->links() }}
            </div>
        @endif
    </div>

</div>
@endsection