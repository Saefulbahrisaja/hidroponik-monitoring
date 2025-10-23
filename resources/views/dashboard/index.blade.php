@extends('layouts.app')

@section('title', 'Dashboard Hidroponik - SIKECE')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    @include('dashboard.cards.status-system')
    @include('dashboard.cards.kondisi-tanaman')
    @include('dashboard.cards.prediksi-analisis')
</div>

@include('dashboard.metrics.live-sensor')
@include('dashboard.cards.chart-sensor')
@include('dashboard.cards.pengaturan-system')
@endsection

@push('scripts')
    @include('layouts.partials.scripts')
@endpush
