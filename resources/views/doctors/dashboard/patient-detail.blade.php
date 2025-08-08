@extends('layouts.doctors_dashboard')

@section('title', 'Detail Pasien - Smart Hospital')

@section('header', 'Detail Pasien')
@section('subheader', 'Informasi lengkap pasien, rekam medis, dan resep obat')

@section('content')
<div class="space-y-6">
    <!-- Patient Information -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Pasien</h3>
                <div class="flex space-x-2">
                    <button onclick="openMedicalRecordModal({{ $patient->id }})" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Tambah Rekam Medis
                    </button>
                    <button onclick="openPrescriptionModal({{ $patient->id }})" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Buat Resep
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl font-medium text-blue-600">{{ substr($patient->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-medium text-gray-900">{{ $patient->name }}</h4>
                        <p class="text-sm text-gray-500">No. Kartu: {{ $patient->card_number ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $patient->gender ?? 'N/A' }} • {{ $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->age . ' tahun' : 'N/A' }}</p>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Email:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $patient->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Telepon:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $patient->phone_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Alamat:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $patient->address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments History -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Riwayat Janji Temu</h3>
            
            @if($appointments->count() > 0)
                <div class="space-y-4">
                    @foreach($appointments as $appointment)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y H:i') }}</p>
                                <p class="text-sm text-gray-500">{{ $appointment->notes ?? 'Tidak ada catatan' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->status_color ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $appointment->status_text ?? 'Status' }}
                            </span>
                            @if($appointment->status === 'pending')
                            <form action="{{ route('doctors.dashboard.pasien.appointment.status', $appointment->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="text-xs text-green-600 hover:text-green-900">Konfirmasi</button>
                            </form>
                            @endif
                            @if($appointment->canBeConfirmed())
                            <form action="{{ route('doctors.dashboard.pasien.appointment.confirm', $appointment->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-blue-600 hover:text-blue-900">Konfirmasi</button>
                            </form>
                            @endif
                            @if($appointment->canBeStarted())
                            <form action="{{ route('doctors.dashboard.pasien.appointment.start', $appointment->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-yellow-600 hover:text-yellow-900">Mulai Praktik</button>
                            </form>
                            @endif
                            @if($appointment->canBeCompleted())
                            <form action="{{ route('doctors.dashboard.pasien.appointment.complete', $appointment->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-green-600 hover:text-green-900">Selesai</button>
                            </form>
                            @endif
                            @if($appointment->canBeCancelled())
                            <form action="{{ route('doctors.dashboard.pasien.appointment.cancel', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan janji temu ini?')">
                                @csrf
                                <button type="submit" class="text-xs text-red-600 hover:text-red-900">Batalkan</button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada riwayat janji temu</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada janji temu yang tercatat.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Medical Records -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Rekam Medis</h3>
            
            @if($medicalRecords->count() > 0)
                <div class="space-y-6">
                    @foreach($medicalRecords as $record)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-900">Rekam Medis - {{ $record->formatted_record_date ?? 'Tanggal' }}</h4>
                            <span class="text-xs text-gray-500">{{ $record->created_at ? \Carbon\Carbon::parse($record->created_at)->format('d M Y H:i') : 'N/A' }}</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            @if($record->blood_pressure)
                            <div>
                                <span class="text-xs text-gray-500">Tekanan Darah:</span>
                                <p class="text-sm font-medium text-gray-900">{{ $record->blood_pressure }}</p>
                            </div>
                            @endif
                            @if($record->temperature)
                            <div>
                                <span class="text-xs text-gray-500">Suhu:</span>
                                <p class="text-sm font-medium text-gray-900">{{ $record->temperature }}</p>
                            </div>
                            @endif
                            @if($record->pulse_rate)
                            <div>
                                <span class="text-xs text-gray-500">Denyut Nadi:</span>
                                <p class="text-sm font-medium text-gray-900">{{ $record->pulse_rate }}</p>
                            </div>
                            @endif
                            @if($record->weight)
                            <div>
                                <span class="text-xs text-gray-500">Berat Badan:</span>
                                <p class="text-sm font-medium text-gray-900">{{ $record->weight }}</p>
                            </div>
                            @endif
                            @if($record->height)
                            <div>
                                <span class="text-xs text-gray-500">Tinggi Badan:</span>
                                <p class="text-sm font-medium text-gray-900">{{ $record->height }}</p>
                            </div>
                            @endif
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500">Keluhan Utama:</span>
                                <p class="text-sm text-gray-900">{{ $record->chief_complaint }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Pemeriksaan Fisik:</span>
                                <p class="text-sm text-gray-900">{{ $record->physical_examination }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Diagnosis:</span>
                                <p class="text-sm text-gray-900">{{ $record->diagnosis }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Rencana Pengobatan:</span>
                                <p class="text-sm text-gray-900">{{ $record->treatment_plan }}</p>
                            </div>
                            @if($record->notes)
                            <div>
                                <span class="text-xs text-gray-500">Catatan:</span>
                                <p class="text-sm text-gray-900">{{ $record->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada rekam medis</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada rekam medis yang tercatat.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Prescriptions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Resep Obat</h3>
            
            @if($prescriptions->count() > 0)
                <div class="space-y-6">
                    @foreach($prescriptions as $prescription)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-900">Resep - {{ $prescription->formatted_prescription_date ?? 'Tanggal' }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prescription->status_color ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $prescription->status_text ?? 'Status' }}
                            </span>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500">Daftar Obat:</span>
                                <p class="text-sm text-gray-900">{{ $prescription->medications }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Instruksi Dosis:</span>
                                <p class="text-sm text-gray-900">{{ $prescription->dosage_instructions }}</p>
                            </div>
                            @if($prescription->special_instructions)
                            <div>
                                <span class="text-xs text-gray-500">Instruksi Khusus:</span>
                                <p class="text-sm text-gray-900">{{ $prescription->special_instructions }}</p>
                            </div>
                            @endif
                            @if($prescription->notes)
                            <div>
                                <span class="text-xs text-gray-500">Catatan:</span>
                                <p class="text-sm text-gray-900">{{ $prescription->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada resep obat</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada resep obat yang tercatat.</p>
                </div>
            @endif
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
                <input type="hidden" id="medicalRecordPatientId" name="patient_id" value="{{ $patient->id }}">
                
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
                <input type="hidden" id="prescriptionPatientId" name="patient_id" value="{{ $patient->id }}">
                
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
    // Medical Record Modal
    function openMedicalRecordModal(patientId) {
        document.getElementById('medicalRecordModal').classList.remove('hidden');
    }

    function closeMedicalRecordModal() {
        document.getElementById('medicalRecordModal').classList.add('hidden');
    }

    // Prescription Modal
    function openPrescriptionModal(patientId) {
        document.getElementById('prescriptionModal').classList.remove('hidden');
    }

    function closePrescriptionModal() {
        document.getElementById('prescriptionModal').classList.add('hidden');
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
</script>
@endpush 