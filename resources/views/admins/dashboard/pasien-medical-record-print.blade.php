<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekam Medis #{{ $record->id }}</title>
    <style>
        body{font-family: Arial, Helvetica, sans-serif; color:#222}
        .container{max-width:800px;margin:0 auto;padding:20px}
        h1{font-size:18px}
        .section{margin-bottom:12px}
        .vital li{margin-bottom:4px}
    </style>
</head>
<body>
    <div class="container">
        <h1>Rekam Medis #{{ $record->id }}</h1>
        <div class="section">Pasien: {{ $patient->name }} ({{ $patient->card_number }})</div>
        <div class="section">Tanggal: {{ $record->record_date?->format('d M Y') }}</div>
        <div class="section">Dokter: {{ $record->doctor?->name ?? 'Admin' }}</div>

        <div class="section">
            <h3>Keluhan Utama</h3>
            <div>{{ $record->chief_complaint }}</div>
        </div>

        <div class="section">
            <h3>Pemeriksaan Fisik</h3>
            <div>{{ $record->physical_examination }}</div>
        </div>

        <div class="section">
            <h3>Diagnosis</h3>
            <div>{{ $record->diagnosis }}</div>
        </div>

        <div class="section">
            <h3>Rencana Pengobatan</h3>
            <div>{{ $record->treatment_plan }}</div>
        </div>

        <div class="section">
            <h3>Informasi Vital</h3>
            <ul class="vital">
                @if($record->blood_pressure)<li>Tekanan Darah: {{ $record->blood_pressure }} mmHg</li>@endif
                @if($record->temperature)<li>Suhu: {{ $record->temperature }} °C</li>@endif
                @if($record->pulse_rate)<li>Denyut Nadi: {{ $record->pulse_rate }} bpm</li>@endif
                @if($record->weight)<li>Berat: {{ $record->weight }} kg</li>@endif
                @if($record->height)<li>Tinggi: {{ $record->height }} cm</li>@endif
            </ul>
        </div>

        @if($record->notes)
        <div class="section">
            <h3>Catatan</h3>
            <div>{{ $record->notes }}</div>
        </div>
        @endif

        <div class="section">
            <small>Dicetak pada {{ now()->format('d M Y H:i') }}</small>
        </div>
    </div>
</body>
</html>
