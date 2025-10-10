@extends('layouts.admins_dashboard')

@section('title', 'Detail Rekam Medis - Smart Hospital')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Rekam Medis #{{ $record->id }}</h3>
                <p class="text-sm text-gray-500">Pasien: <a href="{{ route('admins.dashboard.pasien.show', $patient->id) }}" class="text-blue-600">{{ $patient->name }}</a> &middot; {{ $patient->card_number }}</p>
                <p class="text-sm text-gray-500">Tanggal: {{ $record->record_date?->format('d M Y') }}</p>
                <p class="text-sm text-gray-500">Dokter: {{ $record->doctor?->name ?? 'Admin' }}</p>
            </div>
            <div>
                <a href="{{ route('admins.dashboard.pasien.medical-records') }}" class="text-sm text-gray-600 hover:underline">&larr; Kembali ke daftar rekam</a>
            </div>
        </div>

        <div class="mt-6">
            <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold">Keluhan Utama</h4>
                    <p class="text-gray-700">{{ $record->chief_complaint }}</p>
                </div>
                <div>
                    <h4 class="font-semibold">Informasi Vital</h4>
                    <ul class="text-gray-700 text-sm space-y-1">
                        @if($record->blood_pressure)
                        <li><strong>Tekanan Darah:</strong> {{ $record->blood_pressure }} mmHg</li>
                        @endif
                        @if($record->temperature)
                        <li><strong>Suhu:</strong> {{ $record->temperature }} °C</li>
                        @endif
                        @if($record->pulse_rate)
                        <li><strong>Denyut Nadi:</strong> {{ $record->pulse_rate }} bpm</li>
                        @endif
                        @if($record->weight)
                        <li><strong>Berat Badan:</strong> {{ $record->weight }} kg</li>
                        @endif
                        @if($record->height)
                        <li><strong>Tinggi Badan:</strong> {{ $record->height }} cm</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="font-semibold">Pemeriksaan Fisik</h4>
                <p class="text-gray-700">{{ $record->physical_examination }}</p>
            </div>
            <div class="mb-4">
                <h4 class="font-semibold">Diagnosis</h4>
                <p class="text-gray-700">{{ $record->diagnosis }}</p>
            </div>
            <div class="mb-4">
                <h4 class="font-semibold">Rencana Pengobatan</h4>
                <p class="text-gray-700">{{ $record->treatment_plan }}</p>
            </div>
            @if($record->notes)
            <div class="mb-4">
                <h4 class="font-semibold">Catatan</h4>
                <p class="text-gray-700">{{ $record->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
