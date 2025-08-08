@extends('layouts.doctors_dashboard')

@section('title', 'Riwayat Praktik - Smart Hospital')

@section('header', 'Riwayat Praktik')
@section('subheader', 'Riwayat lengkap praktik, janji temu, rekam medis, dan resep obat')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Appointments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Janji Temu</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalAppointments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Appointments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Janji Temu Selesai</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $completedAppointments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Records -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Rekam Medis</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalMedicalRecords }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prescriptions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Resep Obat</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalPrescriptions }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Statistik Bulan Ini</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $monthlyAppointments }}</div>
                    <div class="text-sm text-gray-500">Janji Temu</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $monthlyMedicalRecords }}</div>
                    <div class="text-sm text-gray-500">Rekam Medis</div>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600">{{ $monthlyPrescriptions }}</div>
                    <div class="text-sm text-gray-500">Resep Obat</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Filter Riwayat</h3>
            <form method="GET" action="{{ route('doctors.dashboard.riwayat-praktik') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Aktivitas</label>
                    <select name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Semua Aktivitas</option>
                        <option value="appointments" {{ $type === 'appointments' ? 'selected' : '' }}>Janji Temu</option>
                        <option value="medical_records" {{ $type === 'medical_records' ? 'selected' : '' }}>Rekam Medis</option>
                        <option value="prescriptions" {{ $type === 'prescriptions' ? 'selected' : '' }}>Resep Obat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>Sedang Berlangsung</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-4 flex justify-end space-x-3">
                    <a href="{{ route('doctors.dashboard.riwayat-praktik') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Practice History -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Riwayat Praktik</h3>
                <span class="text-sm text-gray-500">{{ $allActivities->count() }} aktivitas ditemukan</span>
            </div>
            
            @if($paginatedActivities->count() > 0)
                <div class="space-y-4">
                    @foreach($paginatedActivities as $activity)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    @if($activity->type === 'appointment')
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @elseif($activity->type === 'medical_record')
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                    @elseif($activity->type === 'prescription')
                                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $activity->patient_name }}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->status_color }}">
                                            {{ $activity->status_text }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">
                                        @if($activity->type === 'appointment')
                                            Janji Temu - {{ \Carbon\Carbon::parse($activity->date)->format('d M Y H:i') }}
                                            @if($activity->fee)
                                                • Biaya: Rp {{ number_format($activity->fee, 0, ',', '.') }}
                                            @endif
                                        @elseif($activity->type === 'medical_record')
                                            Rekam Medis - {{ \Carbon\Carbon::parse($activity->date)->format('d M Y') }}
                                        @elseif($activity->type === 'prescription')
                                            Resep Obat - {{ \Carbon\Carbon::parse($activity->date)->format('d M Y') }}
                                        @endif
                                    </p>
                                    @if($activity->notes)
                                        <p class="text-sm text-gray-500">
                                            @if($activity->type === 'medical_record')
                                                Keluhan: {{ Str::limit($activity->notes, 100) }}
                                                @if($activity->diagnosis)
                                                    • Diagnosis: {{ Str::limit($activity->diagnosis, 100) }}
                                                @endif
                                            @elseif($activity->type === 'prescription')
                                                Obat: {{ Str::limit($activity->notes, 100) }}
                                            @else
                                                {{ Str::limit($activity->notes, 100) }}
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('doctors.dashboard.pasien.show', $activity->patient_id) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                    Lihat Detail
                                </a>
                                @if($activity->type === 'appointment')
                                    <span class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($activity->date)->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($allActivities->count() > 20)
                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan {{ (request('page', 1) - 1) * 20 + 1 }} sampai {{ min(request('page', 1) * 20, $allActivities->count()) }} dari {{ $allActivities->count() }} aktivitas
                    </div>
                    <div class="flex space-x-2">
                        @if(request('page', 1) > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page' => request('page', 1) - 1]) }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Sebelumnya
                            </a>
                        @endif
                        @if($allActivities->count() > request('page', 1) * 20)
                            <a href="{{ request()->fullUrlWithQuery(['page' => request('page', 1) + 1]) }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Selanjutnya
                            </a>
                        @endif
                    </div>
                </div>
                @endif
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada riwayat praktik</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada aktivitas praktik yang tercatat.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh page every 5 minutes
    setInterval(function() {
        // You can add AJAX call here to refresh data
        console.log('Practice history refreshed');
    }, 300000);

    // Initialize any page-specific functionality
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Practice history page loaded');
    });
</script>
@endpush
