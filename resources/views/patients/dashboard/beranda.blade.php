@extends('layouts.patients_dashboard')

@section('title', 'Dashboard Dokter - Smart Hospital')

@section('content')

    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Selamat Datang, {{ Auth::guard('patient')->user()->name ?? 'Pasien' }}!</h1>
        <p class="text-gray-600 mt-2">{{ now()->format('l, d F Y') }}</p>
    </div>

@push('scripts')

<!-- JavasScript -->

@endpush
@endsection
