@extends('layouts.doctors_dashboard')

@section('title', 'Kelola Pasien - Smart Hospital')

@section('header', 'Kelola Pasien')
@section('subheader', 'Kelola antrian pasien, rekam medis, dan resep obat')

@section('content')
<div class="space-y-6">
    <!-- Workflow Help -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Alur Kerja Janji Temu</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p class="mb-2"><strong>Terjadwal</strong> → <strong>Dikonfirmasi</strong> → <strong>Sedang Berlangsung</strong> → <strong>Selesai</strong></p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li><strong>Konfirmasi:</strong> Memastikan pasien akan datang sebelum praktik</li>
                        <li><strong>Mulai Praktik:</strong> Langsung mulai praktik (bisa dari Terjadwal atau Dikonfirmasi)</li>
                        <li><strong>Selesai:</strong> Menyelesaikan praktik yang sedang berlangsung</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Practice Status -->
    @if($inProgressAppointments->count() > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800">Praktik Sedang Berlangsung</h3>
                    <p class="text-sm text-yellow-700">Ada {{ $inProgressAppointments->count() }} praktik yang sedang berlangsung. Selesaikan terlebih dahulu sebelum memulai praktik baru.</p>
                </div>
            </div>
            <div class="flex space-x-2">
                @foreach($inProgressAppointments as $appointment)
                <a href="{{ route('doctors.dashboard.pasien.show', $appointment->patient->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Lanjutkan Praktik
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Today's Patient Queue -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Antrian Pasien Hari Ini</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $todayAppointments->count() }} Pasien
                </span>
            </div>
            
            @if($todayAppointments->count() > 0)
                <!-- In Progress Appointments -->
                @if($inProgressAppointments->count() > 0)
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                        Sedang Berlangsung ({{ $inProgressAppointments->count() }})
                    </h4>
                    <div class="space-y-3">
                        @foreach($inProgressAppointments as $appointment)
                        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <span class="text-lg font-medium text-yellow-600">{{ substr($appointment->patient->name ?? 'P', 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->name ?? 'Nama Pasien' }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->patient->card_number ?? 'No. Kartu' }} • {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') : 'Waktu' }}</p>
                                    <p class="text-xs text-gray-400">{{ $appointment->notes ?? 'Tidak ada catatan' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Sedang Berlangsung
                                </span>
                                <div class="flex space-x-1">
                                    <a href="{{ route('doctors.dashboard.pasien.show', $appointment->patient->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Detail
                                    </a>
                                    <button onclick="openMedicalRecordModal({{ $appointment->patient->id }}, {{ $appointment->id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Rekam Medis
                                    </button>
                                    <button onclick="openPrescriptionModal({{ $appointment->patient->id }}, {{ $appointment->id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                        Resep
                                    </button>
                                    <form action="{{ route('doctors.dashboard.pasien.appointment.complete', $appointment->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Selesai
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Confirmed Appointments -->
                @if($confirmedAppointments->count() > 0)
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                        Dikonfirmasi ({{ $confirmedAppointments->count() }})
                        <span class="ml-2 text-xs text-gray-500">- Pasien dipastikan akan datang</span>
                    </h4>
                    <div class="space-y-3">
                        @foreach($confirmedAppointments as $appointment)
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-lg font-medium text-blue-600">{{ substr($appointment->patient->name ?? 'P', 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->name ?? 'Nama Pasien' }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->patient->card_number ?? 'No. Kartu' }} • {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') : 'Waktu' }}</p>
                                    <p class="text-xs text-gray-400">{{ $appointment->notes ?? 'Tidak ada catatan' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Dikonfirmasi
                                </span>
                                <div class="flex space-x-1">
                                    <a href="{{ route('doctors.dashboard.pasien.show', $appointment->patient->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Lihat detail pasien">
                                        Detail
                                    </a>
                                    <form action="{{ route('doctors.dashboard.pasien.appointment.start', $appointment->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" title="Mulai praktik dengan pasien">
                                            Mulai Praktik
                                        </button>
                                    </form>
                                    <form action="{{ route('doctors.dashboard.pasien.appointment.cancel', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan janji temu ini?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" title="Batalkan janji temu">
                                            Batalkan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Scheduled Appointments -->
                @if($scheduledAppointments->count() > 0)
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                        <span class="w-3 h-3 bg-gray-500 rounded-full mr-2"></span>
                        Terjadwal ({{ $scheduledAppointments->count() }})
                        <span class="ml-2 text-xs text-gray-500">- Belum dikonfirmasi kehadiran</span>
                    </h4>
                    <div class="space-y-3">
                        @foreach($scheduledAppointments as $appointment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border-l-4 border-gray-500">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <span class="text-lg font-medium text-gray-600">{{ substr($appointment->patient->name ?? 'P', 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->name ?? 'Nama Pasien' }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->patient->card_number ?? 'No. Kartu' }} • {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') : 'Waktu' }}</p>
                                    <p class="text-xs text-gray-400">{{ $appointment->notes ?? 'Tidak ada catatan' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Terjadwal
                                </span>
                                <div class="flex space-x-1">
                                    <a href="{{ route('doctors.dashboard.pasien.show', $appointment->patient->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Lihat detail pasien">
                                        Detail
                                    </a>
                                    <form action="{{ route('doctors.dashboard.pasien.appointment.confirm', $appointment->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Konfirmasi kehadiran pasien">
                                            Konfirmasi
                                        </button>
                                    </form>
                                    <form action="{{ route('doctors.dashboard.pasien.appointment.start', $appointment->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" title="Langsung mulai praktik tanpa konfirmasi">
                                            Mulai Praktik
                                        </button>
                                    </form>
                                    <form action="{{ route('doctors.dashboard.pasien.appointment.cancel', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan janji temu ini?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" title="Batalkan janji temu">
                                            Batalkan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Completed Appointments -->
                @if($completedAppointments->count() > 0)
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        Selesai ({{ $completedAppointments->count() }})
                    </h4>
                    <div class="space-y-3">
                        @foreach($completedAppointments as $appointment)
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-lg font-medium text-green-600">{{ substr($appointment->patient->name ?? 'P', 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->name ?? 'Nama Pasien' }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->patient->card_number ?? 'No. Kartu' }} • {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') : 'Waktu' }}</p>
                                    <p class="text-xs text-gray-400">{{ $appointment->notes ?? 'Tidak ada catatan' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Selesai
                                </span>
                                <div class="flex space-x-1">
                                    <a href="{{ route('doctors.dashboard.pasien.show', $appointment->patient->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada antrian pasien</h3>
                    <p class="mt-1 text-sm text-gray-500">Tidak ada janji temu untuk hari ini.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Patient List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Pasien</h3>
                <div class="flex space-x-2">
                    <input type="text" id="patientSearch" placeholder="Cari pasien..." class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Kartu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Janji Temu Terakhir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rekam Medis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resep</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($patients as $patient)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">{{ substr($patient->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $patient->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $patient->gender ?? 'N/A' }} • {{ $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->age . ' tahun' : 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $patient->card_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($patient->appointments->count() > 0)
                                    {{ \Carbon\Carbon::parse($patient->appointments->first()->appointment_date)->format('d M Y') }}
                                @else
                                    Belum ada
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $patient->medicalRecords->count() ?? 0 }} rekam
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $patient->prescriptions->count() ?? 0 }} resep
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('doctors.dashboard.pasien.show', $patient->id) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                    <button onclick="openMedicalRecordModal({{ $patient->id }})" class="text-green-600 hover:text-green-900">Rekam Medis</button>
                                    <button onclick="openPrescriptionModal({{ $patient->id }})" class="text-purple-600 hover:text-purple-900">Resep</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data pasien
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Medical Records & Prescriptions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Medical Records -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Rekam Medis Terbaru</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $recentMedicalRecords->count() }} Rekam
                    </span>
                </div>
                <div class="space-y-4">
                    @forelse($recentMedicalRecords as $record)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-900">{{ $record->patient->name ?? 'Nama Pasien' }}</h4>
                            <span class="text-xs text-gray-500">{{ $record->formatted_record_date ?? 'Tanggal' }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($record->chief_complaint ?? 'Keluhan', 100) }}</p>
                        <p class="text-sm text-gray-600">{{ Str::limit($record->diagnosis ?? 'Diagnosis', 100) }}</p>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Belum ada rekam medis</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Prescriptions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Resep Obat Terbaru</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $recentPrescriptions->count() }} Resep
                    </span>
                </div>
                <div class="space-y-4">
                    @forelse($recentPrescriptions as $prescription)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-900">{{ $prescription->patient->name ?? 'Nama Pasien' }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prescription->status_color ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $prescription->status_text ?? 'Status' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($prescription->medications ?? 'Obat', 100) }}</p>
                        <p class="text-xs text-gray-500">{{ $prescription->formatted_prescription_date ?? 'Tanggal' }}</p>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Belum ada resep obat</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Medical Record Modal -->
<div id="medicalRecordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Rekam Medis</h3>
                <button onclick="closeMedicalRecordModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('doctors.dashboard.pasien.medical-record') }}" method="POST">
                @csrf
                <input type="hidden" id="medicalRecordPatientId" name="patient_id">
                <input type="hidden" id="medicalRecordAppointmentId" name="appointment_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tekanan Darah</label>
                        <input type="text" name="blood_pressure" placeholder="120/80 mmHg" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Suhu</label>
                        <input type="text" name="temperature" placeholder="36.5°C" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Denyut Nadi</label>
                        <input type="text" name="pulse_rate" placeholder="72 bpm" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Berat Badan</label>
                        <input type="text" name="weight" placeholder="65 kg" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tinggi Badan</label>
                        <input type="text" name="height" placeholder="170 cm" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Keluhan Utama</label>
                    <textarea name="chief_complaint" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Keluhan utama pasien..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Pemeriksaan Fisik</label>
                    <textarea name="physical_examination" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Hasil pemeriksaan fisik..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Diagnosis</label>
                    <textarea name="diagnosis" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Diagnosis..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Rencana Pengobatan</label>
                    <textarea name="treatment_plan" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Rencana pengobatan..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                    <textarea name="notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan tambahan..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeMedicalRecordModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Prescription Modal -->
<div id="prescriptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Buat Resep Obat</h3>
                <button onclick="closePrescriptionModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('doctors.dashboard.pasien.prescription') }}" method="POST">
                @csrf
                <input type="hidden" id="prescriptionPatientId" name="patient_id">
                <input type="hidden" id="prescriptionAppointmentId" name="appointment_id">
                <input type="hidden" id="prescriptionMedicalRecordId" name="medical_record_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Daftar Obat</label>
                    <textarea name="medications" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan daftar obat yang diresepkan..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Instruksi Dosis</label>
                    <textarea name="dosage_instructions" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Instruksi cara minum obat..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Instruksi Khusus</label>
                    <textarea name="special_instructions" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Instruksi khusus (opsional)..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan tambahan..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePrescriptionModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Buat Resep
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Patient search functionality
    document.getElementById('patientSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const patientName = row.querySelector('td:first-child').textContent.toLowerCase();
            if (patientName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Medical Record Modal
    function openMedicalRecordModal(patientId, appointmentId = null) {
        document.getElementById('medicalRecordPatientId').value = patientId;
        if (appointmentId) {
            document.getElementById('medicalRecordAppointmentId').value = appointmentId;
        }
        document.getElementById('medicalRecordModal').classList.remove('hidden');
    }

    function closeMedicalRecordModal() {
        document.getElementById('medicalRecordModal').classList.add('hidden');
        document.getElementById('medicalRecordPatientId').value = '';
        document.getElementById('medicalRecordAppointmentId').value = '';
    }

    // Prescription Modal
    function openPrescriptionModal(patientId, appointmentId = null, medicalRecordId = null) {
        document.getElementById('prescriptionPatientId').value = patientId;
        if (appointmentId) {
            document.getElementById('prescriptionAppointmentId').value = appointmentId;
        }
        if (medicalRecordId) {
            document.getElementById('prescriptionMedicalRecordId').value = medicalRecordId;
        }
        document.getElementById('prescriptionModal').classList.remove('hidden');
    }

    function closePrescriptionModal() {
        document.getElementById('prescriptionModal').classList.add('hidden');
        document.getElementById('prescriptionPatientId').value = '';
        document.getElementById('prescriptionAppointmentId').value = '';
        document.getElementById('prescriptionMedicalRecordId').value = '';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const medicalRecordModal = document.getElementById('medicalRecordModal');
        const prescriptionModal = document.getElementById('prescriptionModal');
        
        if (event.target === medicalRecordModal) {
            closeMedicalRecordModal();
        }
        if (event.target === prescriptionModal) {
            closePrescriptionModal();
        }
    }

    // Auto-refresh patient queue every 2 minutes
    setInterval(function() {
        // You can add AJAX call here to refresh patient queue
        console.log('Patient queue refreshed');
    }, 120000);
</script>
@endpush
