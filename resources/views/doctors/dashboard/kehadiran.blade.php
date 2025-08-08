@extends('layouts.doctors_dashboard')

@section('title', 'Kehadiran - Smart Hospital')

@section('header', 'Kehadiran')
@section('subheader', 'Kelola kehadiran dan lihat riwayat kehadiran Anda')

@section('content')
<div class="space-y-6">
    <!-- Today's Schedule Info -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Jadwal Hari Ini</h3>
            
            @if($todaySchedule)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($todaySchedule->status === 'kerja')
                                <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($todaySchedule->status === 'libur')
                                <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            @else
                                <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                @switch($todaySchedule->status)
                                    @case('kerja')
                                        Hari Kerja
                                        @break
                                    @case('libur')
                                        Hari Libur
                                        @break
                                    @case('izin')
                                        Hari Izin
                                        @break
                                    @default
                                        {{ ucfirst($todaySchedule->status) }}
                                @endswitch
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                @if($todaySchedule->status === 'kerja' && $todaySchedule->start_time && $todaySchedule->end_time)
                                    <p>Jam Operasional: {{ \Carbon\Carbon::parse($todaySchedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($todaySchedule->end_time)->format('H:i') }}</p>
                                    <p>Toleransi Keterlambatan: {{ auth()->guard('doctor')->user()->late_tolerance_minutes ?? 15 }} menit</p>
                                @endif
                                @if($todaySchedule->notes)
                                    <p class="mt-1">Catatan: {{ $todaySchedule->notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-800">Jadwal belum diatur</h3>
                            <div class="mt-2 text-sm text-gray-700">
                                <p>Jadwal operasional untuk hari ini belum diatur oleh admin.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Today's Attendance Status -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Status Kehadiran Hari Ini</h3>
            
            @if($todayAttendance)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
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
                                    @case('izin')
                                        Izin
                                        @break
                                    @case('libur')
                                        Libur
                                        @break
                                    @case('early')
                                        Datang Lebih Awal
                                        @break
                                    @default
                                        {{ ucfirst($todayAttendance->status) }}
                                @endswitch
                            </h3>
                            <div class="mt-2 text-sm text-green-700">
                                @if($todayAttendance->check_in_time)
                                    <p>Check-in: {{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') }}</p>
                                @endif
                                @if($todayAttendance->check_out_time)
                                    <p>Check-out: {{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('H:i') }}</p>
                                @endif
                                @if($todayAttendance->notes)
                                    <p class="mt-1">Catatan: {{ $todayAttendance->notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Update Notes Form -->
                <div class="mt-4">
                    <form action="{{ route('doctors.dashboard.kehadiran.update-notes') }}" method="POST">
                        @csrf
                        <div class="flex space-x-3">
                            <input type="text" name="notes" value="{{ $todayAttendance->notes }}" 
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="Tambahkan catatan...">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Catatan
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Check-out Button (if not checked out yet) -->
                @if(!$todayAttendance->check_out_time && $todayAttendance->status !== 'absent')
                    <div class="mt-4">
                        <form action="{{ route('doctors.dashboard.kehadiran.check-out') }}" method="POST">
                            @csrf
                            <div class="flex space-x-3">
                                <input type="text" name="notes" 
                                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="Catatan check-out (opsional)">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Check-out
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Belum ada catatan kehadiran hari ini</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Silakan lakukan check-in atau tandai tidak hadir.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Attendance Actions -->
                <div class="mt-4 space-y-3">
                    <!-- Check-in Form -->
                    <form action="{{ route('doctors.dashboard.kehadiran.check-in') }}" method="POST">
                        @csrf
                        <div class="flex space-x-3">
                            <input type="text" name="notes" 
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="Catatan check-in (opsional)">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Check-in
                            </button>
                        </div>
                    </form>
                    
                    <!-- Mark Absent Form -->
                    <form action="{{ route('doctors.dashboard.kehadiran.mark-absent') }}" method="POST">
                        @csrf
                        <div class="flex space-x-3">
                            <input type="text" name="notes" 
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" 
                                   placeholder="Alasan tidak hadir (opsional)">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Tandai Tidak Hadir
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-7 gap-6">
        <!-- Present Days -->
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Hadir</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $presentDays }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Early Days -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-emerald-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Lebih Awal</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $earlyDays ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Absent Days -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Tidak Hadir</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $absentDays }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Late Days -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Terlambat</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $lateDays }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Half Days -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Setengah Hari</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $halfDays }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Izin Days -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Izin</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $izinDays }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Libur Days -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Libur</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $liburDays }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Riwayat Kehadiran (30 Hari Terakhir)</h3>
            
            @if($attendanceHistory->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($attendanceHistory as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($attendance->date)->format('l') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @switch($attendance->status)
                                            @case('present')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('early')
                                                bg-emerald-100 text-emerald-800
                                                @break
                                            @case('late')
                                                bg-yellow-100 text-yellow-800
                                                @break
                                            @case('absent')
                                                bg-red-100 text-red-800
                                                @break
                                            @case('half_day')
                                                bg-blue-100 text-blue-800
                                                @break
                                            @case('izin')
                                                bg-orange-100 text-orange-800
                                                @break
                                            @case('libur')
                                                bg-purple-100 text-purple-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch">
                                        @switch($attendance->status)
                                            @case('present')
                                                Hadir
                                                @break
                                            @case('early')
                                                Datang Lebih Awal
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
                                            @case('izin')
                                                Izin
                                                @break
                                            @case('libur')
                                                Libur
                                                @break
                                            @default
                                                {{ ucfirst($attendance->status) }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs truncate" title="{{ $attendance->notes }}">
                                        {{ $attendance->notes ?: '-' }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada riwayat kehadiran</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada catatan kehadiran yang tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh attendance data every 2 minutes
    setInterval(function() {
        // You can add AJAX call here to refresh attendance data
        console.log('Attendance data refreshed');
    }, 120000);

    // Initialize attendance page functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add any attendance-specific JavaScript here
        console.log('Attendance page loaded');
        
        // Show current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            
            // You can display this time somewhere on the page if needed
            console.log('Current time:', timeString);
        }
        
        // Update time every second
        setInterval(updateTime, 1000);
        updateTime(); // Initial call
    });
</script>
@endpush
