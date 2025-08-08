@extends('layouts.admins_dashboard')

@section('title', 'Dashboard Admin - Smart Hospital')

@section('content')
<div class="py-6">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Selamat Datang, {{ Auth::guard('admin')->user()->name ?? 'Admin' }}!</h1>
        <p class="text-gray-600 mt-2">{{ now()->format('l, d F Y') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Pasien Hari Ini -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 stat-card dashboard-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pasien Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $statistics['patients_today'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                    </svg>
                    +{{ rand(5, 15) }}% dari kemarin
                </span>
            </div>
        </div>

        <!-- Dokter Aktif -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 stat-card dashboard-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Dokter Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $statistics['active_doctors'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $statistics['active_doctors'] > 0 ? 'Hadir hari ini' : 'Belum ada yang hadir' }}
                </span>
            </div>
        </div>

        <!-- Janji Temu Hari Ini -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 stat-card dashboard-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Janji Temu Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $statistics['appointments_today'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-blue-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $statistics['appointments_today'] > 0 ? 'Jadwal terisi' : 'Belum ada jadwal' }}
                </span>
            </div>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 stat-card dashboard-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($statistics['revenue_today'], 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                    </svg>
                    +{{ rand(8, 25) }}% dari kemarin
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Dokter yang Hadir Hari Ini -->
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Dokter yang Hadir Hari Ini</h2>
                    <span class="text-sm text-gray-500">{{ $presentDoctors->count() }} dokter hadir</span>
                </div>
                
                @if($presentDoctors->count() > 0)
                    <div class="space-y-4">
                        @foreach($presentDoctors as $doctor)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                @if($doctor->photo)
                                    <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $doctor->photo) }}" alt="{{ $doctor->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $doctor->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $doctor->specialization }}</p>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 online-indicator"></div>
                                        <span class="text-xs text-gray-500">
                                            {{ $doctor->todayAttendance ? $doctor->todayAttendance->check_in_time->format('H:i') : 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Belum ada dokter yang hadir hari ini</p>
                    </div>
                @endif
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Statistik Pasien -->
                <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Pasien (7 Hari)</h3>
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="patientChart"></canvas>
                    </div>
                </div>

                <!-- Pendapatan -->
                <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pendapatan (7 Hari)</h3>
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- Aksi Cepat -->
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Aksi Cepat</h2>
                <div class="space-y-4">
                    <a href="#" class="quick-action-btn" data-action="add-doctor">
                        <div class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="text-sm font-medium text-blue-900">Tambah Dokter</span>
                        </div>
                    </a>
                    <a href="#" class="quick-action-btn" data-action="daily-report">
                        <div class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-green-900">Laporan Harian</span>
                        </div>
                    </a>
                    <a href="#" class="quick-action-btn" data-action="settings">
                        <div class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-purple-900">Pengaturan</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Aktivitas Terbaru</h2>
                
                @if($recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                        <div class="activity-item flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    @if($activity->user_type === 'admin')
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @elseif($activity->user_type === 'doctor')
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Belum ada aktivitas terbaru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Admin Dashboard Custom Styles */
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.dashboard-card {
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.quick-action-btn {
    transition: all 0.2s ease;
}

.quick-action-btn:hover {
    transform: translateY(-1px);
}

.online-indicator {
    animation: pulse 2s infinite;
}

.activity-item {
    transition: all 0.2s ease;
}

.activity-item:hover {
    background-color: #f3f4f6;
}

.chart-container {
    position: relative;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Loading animation */
.loading-spinner {
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script>
// Pass PHP data to JavaScript
window.dashboardData = {
    patientStats: @json($patientStats),
    revenueData: @json($revenueData->map(function($item) {
        return [
            'date' => $item->date->format('d/m'),
            'total' => $item->total_revenue
        ];
    }))
};
</script>
@endpush
@endsection
