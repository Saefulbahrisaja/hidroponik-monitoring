@extends('layouts.app')

@section('title', 'Riwayat Deteksi Penyakit')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Deteksi Penyakit Daun</h1>
            <p class="text-gray-500">Foto, hasil AI, confidence, dan kondisi hidroponik pada saat deteksi.</p>
        </div>
        <a href="{{ url('/') }}" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700">Dashboard</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @foreach ([['Total', $detections->total()], ['Healthy', $detections->where('disease','Healthy')->count()], ['Pest', $detections->where('disease','Pest')->count()], ['Virus', $detections->where('disease','Virus')->count()]] as $stat)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="text-sm text-gray-500">{{ $stat[0] }}</div>
                <div class="text-2xl font-bold text-gray-800 mt-1">{{ $stat[1] }}</div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Foto</th>
                        <th class="px-4 py-3 text-left">Waktu</th>
                        <th class="px-4 py-3 text-left">Tanaman</th>
                        <th class="px-4 py-3 text-left">Penyakit</th>
                        <th class="px-4 py-3 text-left">Confidence</th>
                        <th class="px-4 py-3 text-left">TDS</th>
                        <th class="px-4 py-3 text-left">Suhu Air</th>
                        <th class="px-4 py-3 text-left">Suhu Udara</th>
                        <th class="px-4 py-3 text-left">Kelembaban</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($detections as $detection)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <a href="{{ asset($detection->image_path) }}" target="_blank">
                                <img src="{{ asset($detection->image_path) }}" class="w-16 h-16 rounded-xl object-cover border" alt="Daun">
                            </a>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ optional($detection->detected_at)->format('d/m/Y H:i:s') }}</td>
                        <td class="px-4 py-3">{{ optional($detection->tanaman)->nama_tanaman ?? '-' }}</td>
                        <td class="px-4 py-3 font-semibold">{{ $detection->disease }}</td>
                        <td class="px-4 py-3">{{ number_format($detection->confidence, 2) }}%</td>
                        <td class="px-4 py-3">{{ $detection->tds !== null ? number_format($detection->tds, 0).' ppm' : '-' }}</td>
                        <td class="px-4 py-3">{{ $detection->suhu_air !== null ? number_format($detection->suhu_air, 1).' °C' : '-' }}</td>
                        <td class="px-4 py-3">{{ $detection->suhu_udara !== null ? number_format($detection->suhu_udara, 1).' °C' : '-' }}</td>
                        <td class="px-4 py-3">{{ $detection->kelembaban !== null ? number_format($detection->kelembaban, 1).' %' : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-4 py-10 text-center text-gray-500">Belum ada hasil deteksi.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $detections->links() }}</div>
    </div>
</div>
@endsection
