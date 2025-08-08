@extends('layouts.doctors_dashboard')

@section('title', 'Beranda - Smart Hospital')

@section('header', 'Beranda')
@section('subheader', 'Selamat datang kembali Dokter')

@section('content')
<div class="space-y-6">
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Attendance Status -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if($todayAttendance)
                            @if($todayAttendance->status === 'present')
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @elseif($todayAttendance->status === 'late')
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @elseif($todayAttendance->status === 'absent')
                                <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        @else
                            <div class="w-8 h-8 bg-gray-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Status Hari Ini</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                @if($todayAttendance)
                                    @switch($todayAttendance->status)
                                        @case('present')
                                            Hadir
                                            @break
                                        @case('late')
                                            Terlambat
                                            @break
                                        @case('absent')
                                            Tidak Hadir
                                            @break
                                        @case('half_day')
                                            Setengah Hari
                                            @break
                                        @default
                                            {{ ucfirst($todayAttendance->status) }}
                                    @endswitch
                                @else
                                    Belum Check-in
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
                @if($todayAttendance && $todayAttendance->check_in_time)
                    <div class="mt-2 text-xs text-gray-500">
                        Check-in: {{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Total Patients Today -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pasien Hari Ini</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalPatientsToday ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Today -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Janji Temu Hari Ini</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $appointmentsToday ?? 0 }}</dd>
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
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Janji Temu Selesai</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $completedAppointments ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Appointments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Janji Temu Pending</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $pendingAppointments ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress Appointments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Sedang Berlangsung</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $inProgressAppointments ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Appointments -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Janji Temu Terbaru</h3>
                        <a href="{{ route('doctors.dashboard.pasien') }}" class="text-sm text-blue-600 hover:text-blue-500">Lihat Semua</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentAppointments ?? [] as $appointment)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600">{{ substr($appointment->patient->name ?? 'P', 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $appointment->patient->name ?? 'Nama Pasien' }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i') ?? 'Tanggal Janji Temu' }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->status_color ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $appointment->status_text ?? 'Terjadwal' }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada janji temu</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada janji temu yang dijadwalkan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <!-- Attendance Quick Action -->
                        @if(!$todayAttendance)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-yellow-800">Belum check-in hari ini</span>
                                    </div>
                                    <a href="{{ route('doctors.dashboard.kehadiran') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        Check-in
                                    </a>
                                </div>
                            </div>
                        @elseif($todayAttendance->status !== 'absent' && !$todayAttendance->check_out_time)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-green-800">Sudah check-in</span>
                                    </div>
                                    <a href="{{ route('doctors.dashboard.kehadiran') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Check-out
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($currentPractice)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-yellow-800">Praktik sedang berlangsung</span>
                                    </div>
                                    <a href="{{ route('doctors.dashboard.pasien.show', $currentPractice->patient->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        Lanjutkan
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        <a href="{{ route('doctors.dashboard.pasien') }}" class="flex items-center p-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Kelola Pasien
                        </a>
                        <a href="{{ route('doctors.dashboard.kehadiran') }}" class="flex items-center p-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kelola Kehadiran
                        </a>
                        <a href="{{ route('doctors.dashboard.riwayat-praktik') }}" class="flex items-center p-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Riwayat Praktik
                        </a>
                        <a href="{{ route('doctors.dashboard.hubungi-admin') }}" class="flex items-center p-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            Hubungi Admin
                        </a>
                    </div>
                </div>
            </div>

            <!-- Doctor Info -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Dokter</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">{{ Auth::guard('doctor')->user()->name[0] ?? 'dr' }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('doctor')->user()->name ?? 'Nama Dokter' }}</p>
                                <p class="text-sm text-gray-500">{{ Auth::guard('doctor')->user()->specialization ?? 'Spesialisasi' }}</p>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">ID Dokter:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ Auth::guard('doctor')->user()->id ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Email:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ Auth::guard('doctor')->user()->email ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Status:</dt>
                                    <dd class="text-sm font-medium text-green-600">Aktif</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Attendance Summary -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Ringkasan Kehadiran Bulan Ini</h3>
                        <a href="{{ route('doctors.dashboard.kehadiran') }}" class="text-sm text-blue-600 hover:text-blue-500">Lihat Detail</a>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $presentDays ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Hadir</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $absentDays ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Tidak Hadir</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ $lateDays ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Terlambat</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $halfDays ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Setengah Hari</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Total Hari Kerja:</span>
                            <span class="text-sm font-medium text-gray-900">{{ ($presentDays ?? 0) + ($absentDays ?? 0) + ($lateDays ?? 0) + ($halfDays ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-sm text-gray-500">Tingkat Kehadiran:</span>
                            <span class="text-sm font-medium text-green-600">
                                @php
                                    $totalDays = ($presentDays ?? 0) + ($absentDays ?? 0) + ($lateDays ?? 0) + ($halfDays ?? 0);
                                    $attendanceRate = $totalDays > 0 ? round((($presentDays ?? 0) + ($lateDays ?? 0) + ($halfDays ?? 0)) / $totalDays * 100, 1) : 0;
                                @endphp
                                {{ $attendanceRate }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Jadwal Hari Ini</h3>
                    
                    @if($currentPractice)
                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-yellow-800">Praktik Sedang Berlangsung</p>
                                <p class="text-sm text-yellow-700">{{ $currentPractice->patient->name ?? 'Nama Pasien' }} - {{ \Carbon\Carbon::parse($currentPractice->appointment_date)->format('H:i') }}</p>
                            </div>
                            <a href="{{ route('doctors.dashboard.pasien.show', $currentPractice->patient->id) }}" class="text-xs text-yellow-600 hover:text-yellow-900">Lihat Detail</a>
                        </div>
                    </div>
                    @endif
                    
                    <div class="space-y-3">
                        @forelse($todaySchedule ?? [] as $schedule)
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($schedule->appointment_date)->format('H:i') }}</p>
                                <p class="text-sm text-gray-500">{{ $schedule->patient->name ?? 'Nama Pasien' }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $schedule->status_color ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $schedule->status_text ?? 'Status' }}
                                </span>
                                <a href="{{ route('doctors.dashboard.pasien.show', $schedule->patient->id) }}" class="text-xs text-blue-600 hover:text-blue-900">Detail</a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">Tidak ada jadwal untuk hari ini</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Aktivitas Terbaru</h3>
                <a href="{{ route('doctors.dashboard.riwayat-praktik') }}" class="text-sm text-blue-600 hover:text-blue-500">Lihat Semua</a>
            </div>
            <div class="flow-root">
                <ul class="-mb-8">
                    @forelse($recentActivities ?? [] as $activity)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">{{ $activity->description ?? 'Aktivitas dokter' }}</p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time datetime="{{ $activity->created_at ?? now() }}">{{ \Carbon\Carbon::parse($activity->created_at ?? now())->diffForHumans() }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada aktivitas</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada aktivitas yang tercatat.</p>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh dashboard data every 5 minutes
    setInterval(function() {
        // You can add AJAX call here to refresh dashboard data
        console.log('Dashboard data refreshed');
    }, 300000);

    // Initialize any dashboard-specific functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add any dashboard-specific JavaScript here
        console.log('Doctor dashboard loaded');
    });
</script>
@endpush
