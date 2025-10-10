@extends('layouts.admins_dashboard')

@section('title', 'Detail Pasien - Smart Hospital')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg p-6">
        <div class="flex items-start space-x-6">
            <div class="flex-shrink-0">
                <img src="{{ $patient->photo ? asset('storage/' . $patient->photo) : asset('favicon.ico') }}" alt="Foto pasien" class="h-24 w-24 rounded-full object-cover">
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-semibold text-gray-900">{{ $patient->name }}</h3>
                <p class="text-sm text-gray-500">No. Kartu: {{ $patient->card_number }}</p>
                <p class="text-sm text-gray-500">{{ ucfirst($patient->gender) }} &middot; {{ $patient->birth_date?->format('d M Y') }}</p>
                <p class="mt-3 text-sm text-gray-700">{{ $patient->address }}</p>
                <div class="mt-3 text-sm text-gray-600">
                    <div>Telepon: {{ $patient->phone_number }}</div>
                    <div>Email: {{ $patient->email }}</div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admins.dashboard.pasien') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke daftar pasien</a>
                </div>
            </div>
            <div class="w-40 text-right">
                <form method="POST" action="{{ route('admins.dashboard.pasien.update', $patient->id) }}">
                    @csrf
                    @method('PUT')
                    <button type="button" onclick="document.getElementById('edit-patient-form').classList.toggle('hidden')" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                </form>
            </div>
        </div>

        <!-- Edit form (hidden by default) -->
        <div id="edit-patient-form" class="mt-6 hidden border-t pt-4">
            <form method="POST" action="{{ route('admins.dashboard.pasien.update', $patient->id) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $patient->name) }}" class="mt-1 block w-full border rounded px-2 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Kartu</label>
                        <input type="text" name="card_number" value="{{ old('card_number', $patient->card_number) }}" class="mt-1 block w-full border rounded px-2 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="gender" class="mt-1 block w-full border rounded px-2 py-2">
                            <option value="male" {{ $patient->gender === 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ $patient->gender === 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ $patient->birth_date?->format('Y-m-d') }}" class="mt-1 block w-full border rounded px-2 py-2" required />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" class="mt-1 block w-full border rounded px-2 py-2">{{ old('address', $patient->address) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telepon</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $patient->phone_number) }}" class="mt-1 block w-full border rounded px-2 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $patient->email) }}" class="mt-1 block w-full border rounded px-2 py-2" required />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" class="mt-1 block w-full border rounded px-2 py-2">{{ old('notes', $patient->notes) }}</textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('edit-patient-form').classList.add('hidden')" class="px-4 py-2 border rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        <!-- Appointments -->
        <div class="mt-8 border-t pt-6">
            <div class="flex items-center justify-between">
                <h4 class="text-lg font-medium">Janji Temu</h4>
                <button onclick="document.getElementById('create-appointment-form').classList.toggle('hidden')" class="px-3 py-1 bg-blue-600 text-white rounded">Buat Janji</button>
            </div>

            <div id="create-appointment-form" class="mt-4 hidden border-t pt-4">
                <form method="POST" action="{{ route('admins.dashboard.pasien.appointment.store', $patient->id) }}">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dokter</label>
                            <select name="doctor_id" required class="mt-1 block w-full border rounded px-2 py-2">
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->specialization ?? 'Umum' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal & Waktu</label>
                            <input type="datetime-local" name="appointment_date" required class="mt-1 block w-full border rounded px-2 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                            <input type="text" name="notes" class="mt-1 block w-full border rounded px-2 py-2" />
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" onclick="document.getElementById('create-appointment-form').classList.add('hidden')" class="px-4 py-2 border rounded">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Jadwalkan</button>
                    </div>
                </form>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($patient->appointments as $appt)
                        <tr>
                            <td class="px-6 py-4">{{ $appt->appointment_date?->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">{{ $appt->doctor?->name }}</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 rounded text-sm {{ $appt->getStatusColorAttribute() }}">{{ $appt->getStatusTextAttribute() }}</span></td>
                            <td class="px-6 py-4">{{ $appt->notes }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada janji temu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Medical Records -->
        <div class="mt-8 border-t pt-6">
            <div class="flex items-center justify-between">
                <h4 class="text-lg font-medium">Rekam Medis</h4>
                <button onclick="document.getElementById('create-medical-record-form').classList.toggle('hidden')" class="px-3 py-1 bg-indigo-600 text-white rounded">Tambah Rekam</button>
            </div>

            <div id="create-medical-record-form" class="mt-4 hidden border-t pt-4">
                <form method="POST" action="{{ route('admins.dashboard.pasien.medical-record.store', $patient->id) }}">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dokter (opsional)</label>
                            <select name="doctor_id" class="mt-1 block w-full border rounded px-2 py-2">
                                <option value="">-- Pilih dokter --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Rekam</label>
                            <input type="date" name="record_date" required value="{{ now()->toDateString() }}" class="mt-1 block w-full border rounded px-2 py-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Keluhan Utama</label>
                            <textarea name="chief_complaint" class="mt-1 block w-full border rounded px-2 py-2" required></textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Pemeriksaan Fisik</label>
                            <textarea name="physical_examination" class="mt-1 block w-full border rounded px-2 py-2" required></textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Diagnosis</label>
                            <textarea name="diagnosis" class="mt-1 block w-full border rounded px-2 py-2" required></textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Rencana Pengobatan</label>
                            <textarea name="treatment_plan" class="mt-1 block w-full border rounded px-2 py-2" required></textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                            <textarea name="notes" class="mt-1 block w-full border rounded px-2 py-2"></textarea>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" onclick="document.getElementById('create-medical-record-form').classList.add('hidden')" class="px-4 py-2 border rounded">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan Rekam</button>
                    </div>
                </form>
            </div>

            <div class="mt-4 space-y-4">
                @forelse($patient->medicalRecords as $record)
                <div class="border rounded p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm text-gray-500">{{ $record->record_date?->format('d M Y') }} &middot; oleh {{ $record->doctor?->name ?? 'Admin' }}</div>
                            <h5 class="text-md font-semibold text-gray-800">{{ Str::limit($record->chief_complaint, 80) }}</h5>
                        </div>
                        <div class="text-sm text-gray-500 text-right">
                            <div>ID: {{ $record->id }}</div>
                            <div class="mt-2">
                                <a href="{{ route('admins.dashboard.pasien.medical-record.show', [$patient->id, $record->id]) }}" class="inline-block px-3 py-1 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">Lihat Rekam</a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-700">
                        <p><strong>Diagnosis:</strong> {{ $record->diagnosis }}</p>
                        <p><strong>Rencana:</strong> {{ $record->treatment_plan }}</p>
                        @if($record->notes)
                        <p><strong>Catatan:</strong> {{ $record->notes }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-gray-500">Belum ada rekam medis.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@include('admins.dashboard.partials.medical-record-modal')

@push('scripts')
<script>
    (function(){
        function ajax(url, opts = {}){
            return fetch(url, Object.assign({headers:{'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN':'{{ csrf_token() }}'}}, opts)).then(r=>r.json());
        }

        document.querySelectorAll('a[data-open-record]').forEach(function(el){
            el.addEventListener('click', function(e){
                e.preventDefault();
                const url = el.getAttribute('href');
                openModal(url);
            });
        });

        function openModal(jsonUrl){
            const modal = document.getElementById('medical-record-modal');
            const body = document.getElementById('modal-body');
            modal.classList.remove('hidden');
            body.innerHTML = '<div class="text-gray-500">Memuat...</div>';
            ajax(jsonUrl).then(function(data){
                // render content
                const html = `
                    <div class="space-y-3">
                        <div class="text-sm text-gray-600">Tanggal: ${data.record_date}</div>
                        <div><strong>Keluhan:</strong> ${escapeHtml(data.chief_complaint)}</div>
                        <div><strong>Pemeriksaan Fisik:</strong> ${escapeHtml(data.physical_examination)}</div>
                        <div><strong>Diagnosis:</strong> ${escapeHtml(data.diagnosis)}</div>
                        <div><strong>Rencana:</strong> ${escapeHtml(data.treatment_plan)}</div>
                        ${data.notes ? `<div><strong>Catatan:</strong> ${escapeHtml(data.notes)}</div>` : ''}
                        <div class="mt-2"><strong>Vital:</strong>
                            <ul class="text-sm text-gray-700">
                                ${data.blood_pressure ? `<li>Tekanan Darah: ${escapeHtml(data.blood_pressure)}</li>` : ''}
                                ${data.temperature ? `<li>Suhu: ${escapeHtml(data.temperature)} °C</li>` : ''}
                                ${data.pulse_rate ? `<li>Denyut Nadi: ${escapeHtml(data.pulse_rate)} bpm</li>` : ''}
                                ${data.weight ? `<li>Berat: ${escapeHtml(data.weight)} kg</li>` : ''}
                                ${data.height ? `<li>Tinggi: ${escapeHtml(data.height)} cm</li>` : ''}
                            </ul>
                        </div>
                    </div>
                `;
                body.innerHTML = html;

                // set actions
                const editBtn = document.getElementById('modal-edit');
                const delBtn = document.getElementById('modal-delete');
                const printBtn = document.getElementById('modal-print');

                editBtn.onclick = function(){ openEditForm(data); };
                delBtn.onclick = function(){ if(confirm('Hapus rekam medis ini?')){ ajax("{{ url('admin/dashboard/pasien') }}/{{ $patient->id }}/medical-record/"+data.id, {method:'DELETE'}) .then(r=>{ if(r.success){ location.reload(); } else alert('Gagal menghapus'); }); }};
                printBtn.onclick = function(){ window.open("{{ url('admin/dashboard/pasien') }}/{{ $patient->id }}/medical-record/"+data.id+"/export","_blank"); };

            }).catch(function(err){
                body.innerHTML = '<div class="text-red-500">Gagal memuat.</div>';
                console.error(err);
            });
        }

        document.getElementById('modal-close').addEventListener('click', function(){
            document.getElementById('medical-record-modal').classList.add('hidden');
        });

        function openEditForm(data){
            // Replace body with edit form
            const body = document.getElementById('modal-body');
            body.innerHTML = `
                <form id="medical-edit-form">
                    <div class="grid grid-cols-1 gap-3">
                        <label class="text-sm">Tanggal</label>
                        <input type="date" name="record_date" value="${data.record_date}" class="border px-2 py-1" />
                        <label class="text-sm">Keluhan</label>
                        <textarea name="chief_complaint" class="border px-2 py-1">${escapeHtml(data.chief_complaint)}</textarea>
                        <label class="text-sm">Pemeriksaan Fisik</label>
                        <textarea name="physical_examination" class="border px-2 py-1">${escapeHtml(data.physical_examination)}</textarea>
                        <label class="text-sm">Diagnosis</label>
                        <textarea name="diagnosis" class="border px-2 py-1">${escapeHtml(data.diagnosis)}</textarea>
                        <label class="text-sm">Rencana</label>
                        <textarea name="treatment_plan" class="border px-2 py-1">${escapeHtml(data.treatment_plan)}</textarea>
                        <label class="text-sm">Catatan</label>
                        <textarea name="notes" class="border px-2 py-1">${escapeHtml(data.notes || '')}</textarea>
                        <label class="text-sm">Tekanan Darah</label>
                        <input type="text" name="blood_pressure" value="${escapeHtml(data.blood_pressure || '')}" class="border px-2 py-1" />
                        <label class="text-sm">Suhu (°C)</label>
                        <input type="number" step="0.1" name="temperature" value="${escapeHtml(data.temperature || '')}" class="border px-2 py-1" />
                        <label class="text-sm">Denyut Nadi (bpm)</label>
                        <input type="number" name="pulse_rate" value="${escapeHtml(data.pulse_rate || '')}" class="border px-2 py-1" />
                        <label class="text-sm">Berat (kg)</label>
                        <input type="number" step="0.1" name="weight" value="${escapeHtml(data.weight || '')}" class="border px-2 py-1" />
                        <label class="text-sm">Tinggi (cm)</label>
                        <input type="number" step="0.1" name="height" value="${escapeHtml(data.height || '')}" class="border px-2 py-1" />
                    </div>
                    <div class="mt-3 flex justify-end">
                        <button type="button" id="cancel-edit" class="px-3 py-1 border rounded mr-2">Batal</button>
                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Simpan</button>
                    </div>
                </form>
            `;

            document.getElementById('cancel-edit').onclick = function(){ openModal("{{ url('admin/dashboard/pasien') }}/{{ $patient->id }}/medical-record/"+data.id+"/json"); };

            document.getElementById('medical-edit-form').onsubmit = function(e){
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);
                ajax("{{ url('admin/dashboard/pasien') }}/{{ $patient->id }}/medical-record/"+data.id, {method:'PUT', body: formData})
                    .then(function(resp){
                        if(resp.success){ location.reload(); } else alert('Gagal menyimpan');
                    }).catch(function(){ alert('Gagal menyimpan'); });
            };
        }

        function escapeHtml(unsafe){ return (unsafe||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    })();
</script>
@endpush

@endsection
