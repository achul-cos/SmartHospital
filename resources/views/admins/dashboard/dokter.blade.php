@extends('layouts.admins_dashboard')

@section('title', 'Dashboard Dokter - Smart Hospital')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    @keyframes trophy-bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .trophy-bounce {
        animation: trophy-bounce 2s ease-in-out infinite;
    }
    .card-hover-effect {
        transition: all 0.3s ease;
    }
    .card-hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>

@section('header', 'Kelola Dokter')
@section('subheader', 'Kelola data dokter dan informasi pribadi.')

@section('content')

<!-- Doctors of the Month Section -->
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Dokter Terbaik</h2>
        <div class="flex space-x-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 active-period" data-period="thisMonth">
                Bulan Ini
            </button>
            <button class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500" data-period="lastMonth">
                Bulan Lalu
            </button>
            <button class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500" data-period="last30Days">
                30 Hari
            </button>
        </div>
    </div>
    <div id="topDoctorsContainer" class="mt-4">
        <div id="topDoctorsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Top doctors will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Doctor List -->
<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Daftar Dokter</h2>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md" id="addDoctorBtn">+ Tambah Dokter</button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spesialisasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($doctors as $doctor)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $doctor->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $doctor->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $doctor->specialization }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($doctor->deactivated_at)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs" data-id="{{ $doctor->id }}" onclick="editDoctor({{ $doctor->id }})">Edit</button>
                        @if($doctor->deactivated_at)
                        <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs" data-id="{{ $doctor->id }}" onclick="toggleStatus({{ $doctor->id }}, 'activate')">Aktifkan</button>
                        @else
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs" data-id="{{ $doctor->id }}" onclick="toggleStatus({{ $doctor->id }}, 'deactivate')">Nonaktifkan</button>
                        @endif
                        <button class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-xs" data-id="{{ $doctor->id }}" onclick="setSchedule({{ $doctor->id }})">Atur Jam</button>
                        <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-xs" data-id="{{ $doctor->id }}" onclick="setTolerance({{ $doctor->id }})">Toleransi</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data dokter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Attendance Calendar View -->
<div class="bg-white shadow rounded-lg p-6 mt-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Laporan Kehadiran Dokter</h2>
        <div class="flex space-x-4">
            <select id="doctorSelect" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih Dokter</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>
            <div class="flex space-x-2">
                <button id="viewThisMonth" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Bulan Ini
                </button>
                <button id="viewLast30Days" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    30 Hari Terakhir
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div id="statisticsContainer" class="hidden mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- This Month vs Last Month -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-semibold mb-4">Perbandingan Bulan Ini vs Bulan Lalu</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-600 mb-2">Bulan Ini</h4>
                        <div id="thisMonthStats" class="space-y-2"></div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-600 mb-2">Bulan Lalu</h4>
                        <div id="lastMonthStats" class="space-y-2"></div>
                    </div>
                </div>
            </div>

            <!-- Last 30 Days vs Previous 30 Days -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-semibold mb-4">Perbandingan 30 Hari</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-600 mb-2">30 Hari Terakhir</h4>
                        <div id="last30DaysStats" class="space-y-2"></div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-600 mb-2">30 Hari Sebelumnya</h4>
                        <div id="previous30DaysStats" class="space-y-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="calendarContainer" class="hidden">
        <!-- Calendar Header -->
        <div class="grid grid-cols-7 gap-1 mb-2">
            <div class="text-center font-semibold text-gray-600">Min</div>
            <div class="text-center font-semibold text-gray-600">Sen</div>
            <div class="text-center font-semibold text-gray-600">Sel</div>
            <div class="text-center font-semibold text-gray-600">Rab</div>
            <div class="text-center font-semibold text-gray-600">Kam</div>
            <div class="text-center font-semibold text-gray-600">Jum</div>
            <div class="text-center font-semibold text-gray-600">Sab</div>
        </div>

        <!-- Calendar Grid -->
        <div id="calendarGrid" class="grid grid-cols-7 gap-1">
            <!-- Calendar cells will be populated by JavaScript -->
        </div>

        <!-- Legend -->
        <div class="mt-6 grid grid-cols-3 md:grid-cols-7 gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-50 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Hadir</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-emerald-50 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Awal</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-50 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Telat</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-50 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Absen</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-orange-50 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Izin</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-purple-50 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Libur</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-50 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Setengah Hari</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Dokter -->
<div id="addDoctorModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 hidden">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <button id="closeAddDoctorModal" class="absolute text-2xl top-2 right-2 text-gray-800 hover:text-gray-400">&times;</button>
        <div class="text-lg font-bold mb-2 bg-blue-500 text-white p-6 px-8">Tambah Dokter Baru</div>
        <form action="{{ route('admins.dashboard.dokter.store') }}" method="POST" class="space-y-4 p-8" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="doctor_number" class="block text-sm font-medium text-gray-700">Nomor Dokter</label>
                    <input type="text" id="doctor_number" class="p-2 mt-1 block w-full rounded-md border-gray-300 bg-gray-100" value="[Auto Generated]" disabled>
                </div>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" id="name" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="specialization" class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                    <input type="text" name="specialization" id="specialization" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="gender" id="gender" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label for="late_tolerance_minutes" class="block text-sm font-medium text-gray-700">Toleransi Keterlambatan (menit)</label>
                    <input type="number" name="late_tolerance_minutes" id="late_tolerance_minutes" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" value="15" min="0" max="120">
                </div>
                <div class="col-span-2">
                    <label for="photo" class="block text-sm font-medium text-gray-700">Foto</label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="p-2 mt-1 block w-full">
                </div>
                <div class="col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="address" id="address" rows="3" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Dokter -->
<div id="editDoctorModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <button id="closeEditDoctorModal" class="absolute text-2xl top-2 right-2 text-white hover:text-gray-200">&times;</button>
        <div class="text-lg font-bold mb-2 bg-blue-500 text-white p-6 px-8">Edit Dokter</div>
        <form id="editDoctorForm" method="POST" class="space-y-4 p-8" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="edit_doctor_number" class="block text-sm font-medium text-gray-700">Nomor Dokter</label>
                    <input type="text" id="edit_doctor_number" class="p-2 mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly>
                </div>
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_name" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="edit_email" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="edit_phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" id="edit_phone" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="edit_specialization" class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                    <input type="text" name="specialization" id="edit_specialization" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="edit_gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="gender" id="edit_gender" required class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label for="edit_late_tolerance_minutes" class="block text-sm font-medium text-gray-700">Toleransi Keterlambatan (menit)</label>
                    <input type="number" name="late_tolerance_minutes" id="edit_late_tolerance_minutes" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" max="120">
                </div>
                <div class="col-span-2">
                    <label for="edit_photo" class="block text-sm font-medium text-gray-700">Foto Baru (Opsional)</label>
                    <input type="file" name="photo" id="edit_photo" accept="image/*" class="p-2 mt-1 block w-full">
                </div>
                <div class="col-span-2">
                    <label for="edit_address" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="address" id="edit_address" rows="3" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Atur Jam Operasional -->
<div id="setScheduleModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <button id="closeSetScheduleModal" class="absolute text-2xl top-2 right-2 text-white hover:text-gray-200">&times;</button>
        <div class="text-lg font-bold mb-2 bg-blue-500 text-white p-6 px-8">Atur Jam Operasional Dokter</div>
        <form id="setScheduleForm" method="POST" class="space-y-4 p-8">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <!-- Senin -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Senin</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="monday_status" id="monday_status" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="kerja">Kerja</option>
                                <option value="libur">Libur</option>
                                <option value="izin">Izin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <div class="relative">
                                <input type="time" name="monday_start" id="monday_start" 
                                    class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pl-10 cursor-pointer hover:border-blue-400">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <div class="relative">
                                <input type="time" name="monday_end" id="monday_end" 
                                    class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pl-10 cursor-pointer hover:border-blue-400">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <input type="text" name="monday_notes" id="monday_notes" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Opsional">
                        </div>
                    </div>
                </div>

                <!-- Selasa -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Selasa</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="tuesday_status" id="tuesday_status" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="kerja">Kerja</option>
                                <option value="libur">Libur</option>
                                <option value="izin">Izin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <div class="relative">
                                <input type="time" name="tuesday_start" id="tuesday_start" 
                                    class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pl-10 cursor-pointer hover:border-blue-400">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <div class="relative">
                                <input type="time" name="tuesday_end" id="tuesday_end" 
                                    class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pl-10 cursor-pointer hover:border-blue-400">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <input type="text" name="tuesday_notes" id="tuesday_notes" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Opsional">
                        </div>
                    </div>
                </div>

                <!-- Rabu -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Rabu</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="wednesday_status" id="wednesday_status" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="kerja">Kerja</option>
                                <option value="libur">Libur</option>
                                <option value="izin">Izin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <input type="time" name="wednesday_start" id="wednesday_start" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <input type="time" name="wednesday_end" id="wednesday_end" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <input type="text" name="wednesday_notes" id="wednesday_notes" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Opsional">
                        </div>
                    </div>
                </div>

                <!-- Kamis -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Kamis</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="thursday_status" id="thursday_status" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="kerja">Kerja</option>
                                <option value="libur">Libur</option>
                                <option value="izin">Izin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <input type="time" name="thursday_start" id="thursday_start" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <input type="time" name="thursday_end" id="thursday_end" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <input type="text" name="thursday_notes" id="thursday_notes" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Opsional">
                        </div>
                    </div>
                </div>

                <!-- Jumat -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Jumat</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="friday_status" id="friday_status" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="kerja">Kerja</option>
                                <option value="libur">Libur</option>
                                <option value="izin">Izin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <input type="time" name="friday_start" id="friday_start" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <input type="time" name="friday_end" id="friday_end" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <input type="text" name="friday_notes" id="friday_notes" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Opsional">
                        </div>
                    </div>
                </div>

                <!-- Sabtu -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Sabtu</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="saturday_status" id="saturday_status" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="kerja">Kerja</option>
                                <option value="libur">Libur</option>
                                <option value="izin">Izin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <input type="time" name="saturday_start" id="saturday_start" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <input type="time" name="saturday_end" id="saturday_end" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <input type="text" name="saturday_notes" id="saturday_notes" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Opsional">
                        </div>
                    </div>
                </div>

                <!-- Minggu -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Minggu</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="sunday_status" id="sunday_status" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="kerja">Kerja</option>
                                <option value="libur">Libur</option>
                                <option value="izin">Izin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <input type="time" name="sunday_start" id="sunday_start" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <input type="time" name="sunday_end" id="sunday_end" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <input type="text" name="sunday_notes" id="sunday_notes" class="p-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Opsional">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">Simpan Jam Operasional</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Atur Toleransi Keterlambatan -->
<div id="setToleranceModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <button id="closeSetToleranceModal" class="absolute text-2xl top-2 right-2 text-white hover:text-gray-200">&times;</button>
        <div class="text-lg font-bold mb-2 bg-blue-500 text-white p-6 px-8">Atur Toleransi Keterlambatan Dokter</div>
        <form id="setToleranceForm" method="POST" class="space-y-4 p-8">
            @csrf
            <div>
                <label for="tolerance_minutes" class="block text-sm font-medium text-gray-700">Toleransi Keterlambatan (menit)</label>
                <input type="number" name="late_tolerance_minutes" id="tolerance_minutes" min="0" max="120" value="15" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <p class="mt-1 text-sm text-gray-500">Jumlah menit toleransi sebelum ditandai terlambat (0-120 menit)</p>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">Simpan Toleransi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 hidden transition-all duration-300"></div>
@endsection

@push('scripts')
<script>
// Top Doctors functionality
document.addEventListener('DOMContentLoaded', function() {
    const topDoctorsGrid = document.getElementById('topDoctorsGrid');
    const periodButtons = document.querySelectorAll('[data-period]');
    let currentPeriod = 'thisMonth';
    
    function loadTopDoctors() {
        if (!topDoctorsGrid) return;
        
        topDoctorsGrid.innerHTML = '<div class="text-center text-gray-600 py-4 col-span-3">Memuat data dokter...</div>';

        fetch('/admin/dashboard/dokter/top')
            .then(response => response.json())
            .then(data => {
                const doctors = data.topDoctors[currentPeriod] || [];
                renderTopDoctors(doctors);
            })
            .catch(error => {
                console.error('Error:', error);
                topDoctorsGrid.innerHTML = '<div class="text-center text-gray-600 py-4 col-span-3">Gagal memuat data dokter</div>';
            });
    }

    function renderTopDoctors(doctors) {
        if (!doctors || Object.keys(doctors).length === 0) {
            topDoctorsGrid.innerHTML = '<div class="text-center text-gray-600 py-4 col-span-3">Tidak ada data dokter untuk periode ini</div>';
            return;
        }

        const sortedDoctors = Object.values(doctors)
            .sort((a, b) => b.attendance_rate - a.attendance_rate)
            .slice(0, 5);
            
        const trophyEmojis = ['🏆', '🥈', '🥉'];
        const medals = ['🎖️', '🎖️', '🎖️', '🎖️', '🎖️'];
        
        topDoctorsGrid.innerHTML = sortedDoctors.map((item, index) => `
            <div class="card-hover-effect relative bg-white rounded-lg shadow-lg p-6 border ${
                index === 0 ? 'border-yellow-300 from-yellow-50 to-white' :
                index === 1 ? 'border-gray-300 from-gray-50 to-white' :
                index === 2 ? 'border-amber-300 from-amber-50 to-white' :
                'border-blue-200'
            } bg-gradient-to-br">
                <!-- Trophy/Medal Badge -->
                <div class="absolute -top-4 -right-4 ${index < 3 ? 'trophy-bounce' : ''} z-10">
                    <span class="text-4xl">${index < 3 ? trophyEmojis[index] : medals[index]}</span>
                </div>
                
                <!-- Rank Circle -->
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 w-20 h-20 ${
                        index === 0 ? 'bg-yellow-100 text-yellow-700 border-yellow-300' :
                        index === 1 ? 'bg-gray-100 text-gray-700 border-gray-300' :
                        index === 2 ? 'bg-amber-100 text-amber-700 border-amber-300' :
                        'bg-blue-100 text-blue-700 border-blue-200'
                    } rounded-full border-2 flex items-center justify-center text-2xl font-bold shadow-inner">
                        #${index + 1}
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold ${
                            index === 0 ? 'text-yellow-700' :
                            index === 1 ? 'text-gray-700' :
                            index === 2 ? 'text-amber-700' :
                            'text-blue-700'
                        }">${item.doctor.name}</h3>
                        <p class="text-gray-600">${item.doctor.specialization || 'Dokter Umum'}</p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Tingkat Kehadiran</span>
                            <span class="font-bold ${
                                index === 0 ? 'text-yellow-600' :
                                index === 1 ? 'text-gray-600' :
                                index === 2 ? 'text-amber-600' :
                                'text-blue-600'
                            }">${item.attendance_rate.toFixed(1)}%</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full ${
                                index === 0 ? 'bg-yellow-500' :
                                index === 1 ? 'bg-gray-500' :
                                index === 2 ? 'bg-amber-500' :
                                'bg-blue-500'
                            } transition-all duration-500" style="width: ${item.attendance_rate}%"></div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-sm border-t pt-4">
                        <span class="text-gray-600">Peringkat</span>
                        <span class="font-bold ${
                            index === 0 ? 'text-yellow-600' :
                            index === 1 ? 'text-gray-600' :
                            index === 2 ? 'text-amber-600' :
                            'text-blue-600'
                        }">
                            ${index === 0 ? 'Juara 1 🏆' :
                              index === 1 ? 'Juara 2 🥈' :
                              index === 2 ? 'Juara 3 🥉' :
                              `Peringkat ${index + 1}`}
                        </span>
                    </div>
                </div>
            </div>
        `).join('');

        topDoctorsGrid.innerHTML = sortedDoctors.map((item, index) => `
            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xl">
                        #${index + 1}
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-lg">${item.doctor.name}</h4>
                        <p class="text-gray-600 text-sm">${item.doctor.specialization || ''}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Tingkat Kehadiran:</span>
                        <div class="flex items-center">
                            <div class="w-20 h-2 bg-gray-200 rounded-full mr-2">
                                <div class="h-full bg-blue-600 rounded-full" style="width: ${item.attendance_rate}%"></div>
                            </div>
                            <span class="font-semibold">${item.attendance_rate.toFixed(1)}%</span>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Period button click handlers
    periodButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            periodButtons.forEach(btn => {
                btn.classList.remove('bg-blue-600');
                btn.classList.add('bg-gray-600');
            });

            // Add active class to clicked button
            button.classList.remove('bg-gray-600');
            button.classList.add('bg-blue-600');

            // Update current period and refresh data
            currentPeriod = button.dataset.period;
            loadTopDoctors();
        });
    });

    // Initial load
    loadTopDoctors();
});
    
    // Calendar functionality
    document.addEventListener('DOMContentLoaded', function() {
        const doctorSelect = document.getElementById('doctorSelect');
        const viewThisMonth = document.getElementById('viewThisMonth');
        const viewLast30Days = document.getElementById('viewLast30Days');
        const calendarContainer = document.getElementById('calendarContainer');
        const calendarGrid = document.getElementById('calendarGrid');
        const topDoctorsGrid = document.getElementById('topDoctorsGrid');

        let selectedDoctorId = null;
        let currentViewMode = 'thisMonth'; // or 'last30Days'

        function loadAttendanceData(doctorId, viewMode) {
            if (!doctorId) return;

            // Show loading state
            calendarGrid.innerHTML = '<div class="col-span-7 text-center py-4">Loading...</div>';
            calendarContainer.classList.remove('hidden');

            // Fetch attendance data from backend
            fetch(`/admin/dashboard/dokter/${doctorId}/attendance?view=${viewMode}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                    renderCalendar(data.calendar, viewMode);
                    renderStatistics(data.stats);
                    document.getElementById('statisticsContainer').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching attendance:', error);
                    calendarGrid.innerHTML = `<div class="col-span-7 text-center py-4 text-red-600">Error loading attendance data: ${error.message || 'Unknown error occurred'}</div>`;
                });
        }

        function renderCalendar(data, viewMode) {
            calendarGrid.innerHTML = '';
            
            const today = new Date();
            let startDate, endDate;

            if (viewMode === 'thisMonth') {
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            } else {
                startDate = new Date(today);
                startDate.setDate(today.getDate() - 29);
                endDate = today;
            }

            // Add empty cells for days before the start date
            const firstDay = startDate.getDay();
            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'h-24 bg-gray-50 rounded-lg';
                calendarGrid.appendChild(emptyCell);
            }

            // Add calendar days
            for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
                const dateStr = date.toISOString().split('T')[0];
                const attendance = data.find(a => a.date === dateStr);
                
                const cell = document.createElement('div');
                cell.className = `h-24 p-2 rounded-lg relative ${date.toDateString() === today.toDateString() ? 'ring-2 ring-blue-500' : ''}`;
                
                // Set background color based on attendance status
                if (attendance) {
                    switch(attendance.status) {
                        case 'present':
                            cell.classList.add('bg-green-50');
                            break;
                        case 'early':
                            cell.classList.add('bg-emerald-50');
                            break;
                        case 'late':
                            cell.classList.add('bg-yellow-50');
                            break;
                        case 'absent':
                            cell.classList.add('bg-red-50');
                            break;
                        case 'izin':
                            cell.classList.add('bg-orange-50');
                            break;
                        case 'libur':
                            cell.classList.add('bg-purple-50');
                            break;
                        default:
                            cell.classList.add('bg-gray-50');
                    }
                } else {
                    cell.classList.add('bg-gray-50');
                }

                // Add date and attendance info
                cell.innerHTML = `
                    <div class="flex justify-between">
                        <span class="font-medium ${date.toDateString() === today.toDateString() ? 'text-blue-600' : 'text-gray-700'}">${date.getDate()}</span>
                        ${attendance ? `<span class="text-sm font-medium ${getStatusColor(attendance.status)}">${getStatusText(attendance.status)}</span>` : ''}
                    </div>
                    ${attendance ? `
                        <div class="mt-1 text-xs text-gray-600">
                            ${attendance.check_in_time ? `<div>Masuk: ${attendance.check_in_time}</div>` : ''}
                            ${attendance.check_out_time ? `<div>Keluar: ${attendance.check_out_time}</div>` : ''}
                            ${attendance.notes ? `<div class="truncate text-gray-500">${attendance.notes}</div>` : ''}
                        </div>
                    ` : ''}
                `;

                calendarGrid.appendChild(cell);
            }
        }

        function getStatusColor(status) {
            switch(status) {
                case 'present': return 'text-green-600';
                case 'early': return 'text-emerald-600';
                case 'late': return 'text-yellow-600';
                case 'absent': return 'text-red-600';
                case 'izin': return 'text-orange-600';
                case 'libur': return 'text-purple-600';
                default: return 'text-gray-600';
            }
        }

        function getStatusText(status) {
            switch(status) {
                case 'present': return 'Hadir';
                case 'early': return 'Awal';
                case 'late': return 'Telat';
                case 'absent': return 'Absen';
                case 'izin': return 'Izin';
                case 'libur': return 'Libur';
                case 'half_day': return 'Setengah Hari';
                default: return '-';
            }
        }

        // Event listeners
        doctorSelect.addEventListener('change', function() {
            selectedDoctorId = this.value;
            if (selectedDoctorId) {
                loadAttendanceData(selectedDoctorId, currentViewMode);
            } else {
                calendarContainer.classList.add('hidden');
            }
        });

        viewThisMonth.addEventListener('click', function() {
            if (selectedDoctorId) {
                currentViewMode = 'thisMonth';
                viewThisMonth.classList.replace('bg-gray-600', 'bg-blue-600');
                viewLast30Days.classList.replace('bg-blue-600', 'bg-gray-600');
                loadAttendanceData(selectedDoctorId, currentViewMode);
            }
        });

        viewLast30Days.addEventListener('click', function() {
            if (selectedDoctorId) {
                currentViewMode = 'last30Days';
                viewLast30Days.classList.replace('bg-gray-600', 'bg-blue-600');
                viewThisMonth.classList.replace('bg-blue-600', 'bg-gray-600');
                loadAttendanceData(selectedDoctorId, currentViewMode);
            }
        });

        function renderStatistics(stats) {
            const periods = ['thisMonth', 'lastMonth', 'last30Days', 'previous30Days'];
            const statuses = ['present', 'early', 'late', 'absent', 'izin', 'libur', 'half_day'];
            const statusColors = {
                present: 'text-green-600',
                early: 'text-emerald-600',
                late: 'text-yellow-600',
                absent: 'text-red-600',
                izin: 'text-orange-600',
                libur: 'text-purple-600',
                half_day: 'text-blue-600'
            };
            const statusText = {
                present: 'Hadir',
                early: 'Awal',
                late: 'Telat',
                absent: 'Absen',
                izin: 'Izin',
                libur: 'Libur',
                half_day: 'Setengah Hari'
            };

            periods.forEach(period => {
                const periodStats = stats[period];
                let html = `
                    <div class="text-sm mb-4">
                        <div class="font-medium text-blue-600">
                            Tingkat Kehadiran: ${periodStats.attendance_rate.toFixed(1)}%
                        </div>
                        <div class="text-xs text-gray-500">
                            Periode: ${periodStats.period.start} s/d ${periodStats.period.end}
                        </div>
                    </div>
                `;

                statuses.forEach(status => {
                    const count = periodStats.counts[status];
                    const percentage = periodStats.percentages[status];
                    html += `
                        <div class="flex justify-between items-center text-sm">
                            <span class="${statusColors[status]}">${statusText[status]}</span>
                            <span class="text-gray-600">${count} (${percentage.toFixed(1)}%)</span>
                        </div>
                    `;
                });

                const container = document.getElementById(`${period}Stats`);
                if (container) {
                    container.innerHTML = html;
                }
            });
        }
    });

    // Modal logic
    document.addEventListener('DOMContentLoaded', function() {
        const addDoctorBtn = document.getElementById('addDoctorBtn');
        const addDoctorModal = document.getElementById('addDoctorModal');
        const closeAddDoctorModal = document.getElementById('closeAddDoctorModal');
        const modalBackdrop = document.getElementById('modalBackdrop');

        const editDoctorModal = document.getElementById('editDoctorModal');
        const closeEditDoctorModal = document.getElementById('closeEditDoctorModal');

        const setScheduleModal = document.getElementById('setScheduleModal');
        const closeSetScheduleModal = document.getElementById('closeSetScheduleModal');

        const setToleranceModal = document.getElementById('setToleranceModal');
        const closeSetToleranceModal = document.getElementById('closeSetToleranceModal');

        addDoctorBtn.addEventListener('click', function() {
            addDoctorModal.classList.remove('hidden');
            modalBackdrop.classList.remove('hidden');
        });
        closeAddDoctorModal.addEventListener('click', function() {
            addDoctorModal.classList.add('hidden');
            modalBackdrop.classList.add('hidden');
        });
        closeEditDoctorModal.addEventListener('click', function() {
            editDoctorModal.classList.add('hidden');
            modalBackdrop.classList.add('hidden');
        });
        closeSetScheduleModal.addEventListener('click', function() {
            setScheduleModal.classList.add('hidden');
            modalBackdrop.classList.add('hidden');
        });
        closeSetToleranceModal.addEventListener('click', function() {
            setToleranceModal.classList.add('hidden');
            modalBackdrop.classList.add('hidden');
        });
        modalBackdrop.addEventListener('click', function() {
            addDoctorModal.classList.add('hidden');
            editDoctorModal.classList.add('hidden');
            setScheduleModal.classList.add('hidden');
            setToleranceModal.classList.add('hidden');
            modalBackdrop.classList.add('hidden');
        });
    });

    function editDoctor(doctorId) {
        fetch(`/admin/dashboard/dokter/${doctorId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const doctor = data.doctor;
                    document.getElementById('edit_doctor_number').value = doctor.doctor_number;
                    document.getElementById('edit_name').value = doctor.name;
                    document.getElementById('edit_email').value = doctor.email;
                    document.getElementById('edit_phone').value = doctor.phone;
                    document.getElementById('edit_specialization').value = doctor.specialization;
                    document.getElementById('edit_gender').value = doctor.gender;
                    document.getElementById('edit_late_tolerance_minutes').value = doctor.late_tolerance_minutes || 15;
                    document.getElementById('edit_address').value = doctor.address || '';
                    
                    // Update form action URL
                    document.getElementById('editDoctorForm').action = `/admin/dashboard/dokter/${doctorId}`;
                    
                    // Show the modal
                    document.getElementById('editDoctorModal').classList.remove('hidden');
                    document.getElementById('modalBackdrop').classList.remove('hidden');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan saat mengambil data dokter');
            });
    }

    function toggleStatus(doctorId, action) {
        if (confirm(`Apakah Anda yakin ingin ${action === 'activate' ? 'mengaktifkan' : 'menonaktifkan'} dokter ini?`)) {
            fetch(`/admin/dashboard/dokter/${doctorId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ action: action })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah status dokter');
            });
        }
    }

    function setSchedule(doctorId) {
        fetch(`/admin/dashboard/dokter/${doctorId}/schedule`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const schedules = data.schedules;
                    
                    // Set default values for each day
                    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    days.forEach(day => {
                        const schedule = schedules[day] || {};
                        document.getElementById(`${day}_start`).value = schedule.start_time || '';
                        document.getElementById(`${day}_end`).value = schedule.end_time || '';
                        document.getElementById(`${day}_status`).value = schedule.status || 'kerja';
                        document.getElementById(`${day}_notes`).value = schedule.notes || '';
                    });
                    
                    document.getElementById('setScheduleForm').action = `/admin/dashboard/dokter/${doctorId}/schedule`;
                    document.getElementById('setScheduleModal').classList.remove('hidden');
                    document.getElementById('modalBackdrop').classList.remove('hidden');
                } else {
                    alert('Terjadi kesalahan saat mengambil data jadwal: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data jadwal');
            });
    }

    function setTolerance(doctorId) {
        document.getElementById('setToleranceForm').action = `/admin/dashboard/dokter/${doctorId}/set-tolerance`;
        document.getElementById('tolerance_minutes').value = document.getElementById('late_tolerance_minutes').value; // Initialize with current tolerance
        document.getElementById('setToleranceModal').classList.remove('hidden');
        document.getElementById('modalBackdrop').classList.remove('hidden');
    }
</script>
@endpush
