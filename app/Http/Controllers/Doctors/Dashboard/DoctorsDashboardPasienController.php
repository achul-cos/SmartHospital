<?php

namespace App\Http\Controllers\Doctors\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorsDashboardPasienController extends Controller
{
    public function index() {
        $doctor = Auth::guard('doctor')->user();
        
        // Get today's appointments for this doctor grouped by status
        $todayAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_date')
            ->get();

        // Group appointments by status
        $scheduledAppointments = $todayAppointments->where('status', 'scheduled');
        $confirmedAppointments = $todayAppointments->where('status', 'confirmed');
        $inProgressAppointments = $todayAppointments->where('status', 'in_progress');
        $completedAppointments = $todayAppointments->where('status', 'completed');

        // Get all patients who have appointments with this doctor
        $patients = Patient::whereHas('appointments', function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id);
        })->with(['appointments' => function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id)->orderBy('appointment_date', 'desc');
        }])->get();

        // Get recent medical records created by this doctor
        $recentMedicalRecords = MedicalRecord::with('patient')
            ->where('doctor_id', $doctor->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent prescriptions created by this doctor
        $recentPrescriptions = Prescription::with('patient')
            ->where('doctor_id', $doctor->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('doctors.dashboard.pasien', compact(
            'todayAppointments',
            'scheduledAppointments',
            'confirmedAppointments',
            'inProgressAppointments',
            'completedAppointments',
            'patients',
            'recentMedicalRecords',
            'recentPrescriptions'
        ));
    }

    public function showPatient($patientId) {
        $doctor = Auth::guard('doctor')->user();
        $patient = Patient::findOrFail($patientId);
        
        // Get patient's appointments with this doctor
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patientId)
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Get patient's medical records created by this doctor
        $medicalRecords = MedicalRecord::where('doctor_id', $doctor->id)
            ->where('patient_id', $patientId)
            ->orderBy('record_date', 'desc')
            ->get();

        // Get patient's prescriptions created by this doctor
        $prescriptions = Prescription::where('doctor_id', $doctor->id)
            ->where('patient_id', $patientId)
            ->orderBy('prescription_date', 'desc')
            ->get();

        return view('doctors.dashboard.patient-detail', compact(
            'patient',
            'appointments',
            'medicalRecords',
            'prescriptions'
        ));
    }

    public function createMedicalRecord(Request $request) {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'chief_complaint' => 'required|string',
            'physical_examination' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'notes' => 'nullable|string',
            'blood_pressure' => 'nullable|string',
            'temperature' => 'nullable|string',
            'pulse_rate' => 'nullable|string',
            'weight' => 'nullable|string',
            'height' => 'nullable|string',
        ]);

        $doctor = Auth::guard('doctor')->user();

        $medicalRecord = MedicalRecord::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $doctor->id,
            'appointment_id' => $request->appointment_id,
            'record_date' => today(),
            'chief_complaint' => $request->chief_complaint,
            'physical_examination' => $request->physical_examination,
            'diagnosis' => $request->diagnosis,
            'treatment_plan' => $request->treatment_plan,
            'notes' => $request->notes,
            'blood_pressure' => $request->blood_pressure,
            'temperature' => $request->temperature,
            'pulse_rate' => $request->pulse_rate,
            'weight' => $request->weight,
            'height' => $request->height,
        ]);

        return redirect()->back()->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    public function createPrescription(Request $request) {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'medications' => 'required|string',
            'dosage_instructions' => 'required|string',
            'special_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $doctor = Auth::guard('doctor')->user();

        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $doctor->id,
            'medical_record_id' => $request->medical_record_id,
            'appointment_id' => $request->appointment_id,
            'prescription_date' => today(),
            'medications' => $request->medications,
            'dosage_instructions' => $request->dosage_instructions,
            'special_instructions' => $request->special_instructions,
            'notes' => $request->notes,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Resep obat berhasil dibuat.');
    }

    public function updateAppointmentStatus(Request $request, $appointmentId) {
        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $doctor = Auth::guard('doctor')->user();
        
        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('id', $appointmentId)
            ->firstOrFail();

        $appointment->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status janji temu berhasil diperbarui.');
    }

    public function confirmAppointment($appointmentId) {
        $doctor = Auth::guard('doctor')->user();
        
        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('id', $appointmentId)
            ->firstOrFail();

        if (!$appointment->canBeConfirmed()) {
            return redirect()->back()->with('error', 'Janji temu tidak dapat dikonfirmasi.');
        }

        $appointment->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', 'Janji temu berhasil dikonfirmasi.');
    }

    public function startPractice($appointmentId) {
        $doctor = Auth::guard('doctor')->user();
        
        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('id', $appointmentId)
            ->firstOrFail();

        if (!$appointment->canBeStarted()) {
            return redirect()->back()->with('error', 'Praktik tidak dapat dimulai.');
        }

        // Check if there's already an in-progress appointment
        $inProgressAppointment = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'in_progress')
            ->first();

        if ($inProgressAppointment) {
            return redirect()->back()->with('error', 'Ada praktik yang sedang berlangsung. Selesaikan terlebih dahulu.');
        }

        $appointment->update(['status' => 'in_progress']);

        return redirect()->back()->with('success', 'Praktik dengan pasien berhasil dimulai.');
    }

    public function completePractice($appointmentId) {
        $doctor = Auth::guard('doctor')->user();
        
        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('id', $appointmentId)
            ->firstOrFail();

        if (!$appointment->canBeCompleted()) {
            return redirect()->back()->with('error', 'Praktik tidak dapat diselesaikan.');
        }

        $appointment->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Praktik dengan pasien berhasil diselesaikan.');
    }

    public function cancelAppointment($appointmentId) {
        $doctor = Auth::guard('doctor')->user();
        
        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('id', $appointmentId)
            ->firstOrFail();

        if (!$appointment->canBeCancelled()) {
            return redirect()->back()->with('error', 'Janji temu tidak dapat dibatalkan.');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Janji temu berhasil dibatalkan.');
    }
}
