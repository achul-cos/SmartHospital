<?php

namespace App\Http\Controllers\Admins\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminsDashboardPasienController extends Controller
{
    public function index(Request $request) {
        // List patients with optional search
        $query = Patient::query();

        if ($search = $request->query('q')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('card_number', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admins.dashboard.pasien', compact('patients', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'card_number' => 'nullable|string|max:50|unique:patients,card_number',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:30',
            'email' => 'required|email|unique:patients,email',
            'notes' => 'nullable|string',
        ]);

        // Ensure card number exists
        if (empty($data['card_number'])) {
            $data['card_number'] = 'CARD-' . strtoupper(Str::random(8));
        }

        // Default password for new patient
        $data['password'] = Hash::make('password');

        $patient = Patient::create($data);

        return redirect()->route('admins.dashboard.pasien')->with('success', 'Pasien berhasil ditambahkan. Password default: "password" (harap ubah setelah login).');
    }

    public function show($id)
    {
        $patient = Patient::with(['appointments' => function($q) {
            $q->orderBy('appointment_date', 'desc');
        }, 'medicalRecords' => function($q) {
            $q->orderBy('record_date', 'desc');
        }])->findOrFail($id);

        $doctors = Doctor::orderBy('name')->get();

        return view('admins.dashboard.pasien-detail', compact('patient', 'doctors'));
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'card_number' => "required|string|max:50|unique:patients,card_number,{$patient->id}",
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:30',
            'email' => "required|email|unique:patients,email,{$patient->id}",
            'notes' => 'nullable|string',
        ]);

        $patient->update($data);

        return redirect()->back()->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);

        // Soft delete isn't implemented in model, so do a hard delete carefully
        $patient->delete();

        return redirect()->route('admins.dashboard.pasien')->with('success', 'Pasien berhasil dihapus.');
    }

    /**
     * Create appointment to direct patient to a doctor
     */
    public function createAppointment(Request $request, $patientId)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'status' => 'scheduled',
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Pasien berhasil diarahkan ke dokter (janji temu terjadwal).');
    }

    /**
     * Record a medical record for a patient (created by admin)
     */
    public function createMedicalRecord(Request $request, $patientId)
    {
        $request->validate([
            'doctor_id' => 'nullable|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'record_date' => 'required|date',
            'chief_complaint' => 'required|string',
            'physical_examination' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $medicalRecord = MedicalRecord::create([
            'patient_id' => $patientId,
            'doctor_id' => $request->doctor_id,
            'appointment_id' => $request->appointment_id,
            'record_date' => $request->record_date,
            'chief_complaint' => $request->chief_complaint,
            'physical_examination' => $request->physical_examination,
            'diagnosis' => $request->diagnosis,
            'treatment_plan' => $request->treatment_plan,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Rekam medis pasien berhasil ditambahkan oleh admin.');
    }

    /**
     * View collective medical records (all patients) - for admin overview
     */
    public function allMedicalRecords(Request $request)
    {
        $query = MedicalRecord::with('patient', 'doctor')->orderBy('record_date', 'desc');

        if ($q = $request->query('q')) {
            $query->whereHas('patient', function($p) use ($q) {
                $p->where('name', 'like', "%{$q}%")
                  ->orWhere('card_number', 'like', "%{$q}%");
            });
        }

        $records = $query->paginate(25)->withQueryString();

        return view('admins.dashboard.pasien-medical-records', compact('records'));
    }

    /**
     * Show a specific medical record detail for a patient
     */
    public function showMedicalRecord($patientId, $recordId)
    {
        $patient = Patient::with(['appointments' => function($q) {
            $q->orderBy('appointment_date', 'desc');
        }])->findOrFail($patientId);

        $record = MedicalRecord::with('doctor', 'appointment')->where('patient_id', $patientId)->where('id', $recordId)->firstOrFail();

        return view('admins.dashboard.pasien-medical-record-detail', compact('patient', 'record'));
    }

    /**
     * Return medical record data as JSON for AJAX modal
     */
    public function medicalRecordJson($patientId, $recordId)
    {
        $record = MedicalRecord::with('doctor', 'appointment')->where('patient_id', $patientId)->where('id', $recordId)->firstOrFail();
        return response()->json($record);
    }

    /**
     * Update a medical record
     */
    public function updateMedicalRecord(Request $request, $patientId, $recordId)
    {
        $record = MedicalRecord::where('patient_id', $patientId)->where('id', $recordId)->firstOrFail();

        $data = $request->validate([
            'record_date' => 'required|date',
            'chief_complaint' => 'required|string',
            'physical_examination' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'notes' => 'nullable|string',
            'blood_pressure' => 'nullable|string',
            'temperature' => 'nullable|numeric',
            'pulse_rate' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
        ]);

        $record->update($data);

        return response()->json(['success' => true, 'message' => 'Rekam medis berhasil diperbarui.']);
    }

    /**
     * Delete a medical record
     */
    public function deleteMedicalRecord($patientId, $recordId)
    {
        $record = MedicalRecord::where('patient_id', $patientId)->where('id', $recordId)->firstOrFail();
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Rekam medis dihapus.']);
    }
}
