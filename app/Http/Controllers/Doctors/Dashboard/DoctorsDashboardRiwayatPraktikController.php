<?php

namespace App\Http\Controllers\Doctors\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorsDashboardRiwayatPraktikController extends Controller
{
    public function index(Request $request) {
        $doctor = Auth::guard('doctor')->user();
        
        // Get filter parameters
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $type = $request->get('type', 'all'); // all, appointments, medical_records, prescriptions
        
        // Base queries
        $appointmentsQuery = Appointment::where('doctor_id', $doctor->id)->with('patient');
        $medicalRecordsQuery = MedicalRecord::where('doctor_id', $doctor->id)->with('patient');
        $prescriptionsQuery = Prescription::where('doctor_id', $doctor->id)->with('patient');
        
        // Apply date filters
        if ($dateFrom) {
            $appointmentsQuery->whereDate('appointment_date', '>=', $dateFrom);
            $medicalRecordsQuery->whereDate('record_date', '>=', $dateFrom);
            $prescriptionsQuery->whereDate('prescription_date', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $appointmentsQuery->whereDate('appointment_date', '<=', $dateTo);
            $medicalRecordsQuery->whereDate('record_date', '<=', $dateTo);
            $prescriptionsQuery->whereDate('prescription_date', '<=', $dateTo);
        }
        
        // Apply status filter for appointments
        if ($status !== 'all') {
            $appointmentsQuery->where('status', $status);
        }
        
        // Get data based on type filter
        $appointments = collect();
        $medicalRecords = collect();
        $prescriptions = collect();
        
        if ($type === 'all' || $type === 'appointments') {
            $appointments = $appointmentsQuery->orderBy('appointment_date', 'desc')->get();
        }
        
        if ($type === 'all' || $type === 'medical_records') {
            $medicalRecords = $medicalRecordsQuery->orderBy('record_date', 'desc')->get();
        }
        
        if ($type === 'all' || $type === 'prescriptions') {
            $prescriptions = $prescriptionsQuery->orderBy('prescription_date', 'desc')->get();
        }
        
        // Combine and sort all activities
        $allActivities = collect();
        
        // Add appointments
        $appointments->each(function($appointment) use ($allActivities) {
            $allActivities->push((object)[
                'id' => $appointment->id,
                'type' => 'appointment',
                'date' => $appointment->appointment_date,
                'patient_name' => $appointment->patient->name ?? 'Nama Pasien',
                'patient_id' => $appointment->patient_id,
                'status' => $appointment->status,
                'status_text' => $appointment->status_text,
                'status_color' => $appointment->status_color,
                'notes' => $appointment->notes,
                'fee' => $appointment->fee,
                'data' => $appointment
            ]);
        });
        
        // Add medical records
        $medicalRecords->each(function($record) use ($allActivities) {
            $allActivities->push((object)[
                'id' => $record->id,
                'type' => 'medical_record',
                'date' => $record->record_date,
                'patient_name' => $record->patient->name ?? 'Nama Pasien',
                'patient_id' => $record->patient_id,
                'status' => 'completed',
                'status_text' => 'Rekam Medis',
                'status_color' => 'bg-blue-100 text-blue-800',
                'notes' => $record->chief_complaint,
                'diagnosis' => $record->diagnosis,
                'data' => $record
            ]);
        });
        
        // Add prescriptions
        $prescriptions->each(function($prescription) use ($allActivities) {
            $allActivities->push((object)[
                'id' => $prescription->id,
                'type' => 'prescription',
                'date' => $prescription->prescription_date,
                'patient_name' => $prescription->patient->name ?? 'Nama Pasien',
                'patient_id' => $prescription->patient_id,
                'status' => $prescription->status,
                'status_text' => $prescription->status_text,
                'status_color' => $prescription->status_color,
                'notes' => $prescription->medications,
                'data' => $prescription
            ]);
        });
        
        // Sort by date (newest first)
        $allActivities = $allActivities->sortByDesc('date');
        
        // Paginate results
        $perPage = 20;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedActivities = $allActivities->slice($offset, $perPage);
        
        // Statistics
        $totalAppointments = Appointment::where('doctor_id', $doctor->id)->count();
        $completedAppointments = Appointment::where('doctor_id', $doctor->id)->where('status', 'completed')->count();
        $totalMedicalRecords = MedicalRecord::where('doctor_id', $doctor->id)->count();
        $totalPrescriptions = Prescription::where('doctor_id', $doctor->id)->count();
        
        // Monthly statistics
        $currentMonth = Carbon::now()->startOfMonth();
        $monthlyAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereMonth('appointment_date', $currentMonth->month)
            ->whereYear('appointment_date', $currentMonth->year)
            ->count();
        
        $monthlyMedicalRecords = MedicalRecord::where('doctor_id', $doctor->id)
            ->whereMonth('record_date', $currentMonth->month)
            ->whereYear('record_date', $currentMonth->year)
            ->count();
        
        $monthlyPrescriptions = Prescription::where('doctor_id', $doctor->id)
            ->whereMonth('prescription_date', $currentMonth->month)
            ->whereYear('prescription_date', $currentMonth->year)
            ->count();
        
        return view('doctors.dashboard.riwayat-praktik', compact(
            'paginatedActivities',
            'allActivities',
            'appointments',
            'medicalRecords',
            'prescriptions',
            'totalAppointments',
            'completedAppointments',
            'totalMedicalRecords',
            'totalPrescriptions',
            'monthlyAppointments',
            'monthlyMedicalRecords',
            'monthlyPrescriptions',
            'status',
            'dateFrom',
            'dateTo',
            'type'
        ));
    }
}
