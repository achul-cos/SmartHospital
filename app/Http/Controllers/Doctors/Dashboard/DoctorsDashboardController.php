<?php

namespace App\Http\Controllers\Doctors\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DoctorAttendance;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorsDashboardController extends Controller
{
    public function index() {
        $doctor = Auth::guard('doctor')->user();
        $today = Carbon::today();
        
        // Attendance Statistics
        $todayAttendance = DoctorAttendance::where('doctor_id', $doctor->id)
            ->whereDate('date', $today)
            ->first();
        
        $currentMonth = Carbon::now()->startOfMonth();
        $monthlyAttendance = DoctorAttendance::where('doctor_id', $doctor->id)
            ->whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->get();
        
        $presentDays = $monthlyAttendance->where('status', 'present')->count();
        $absentDays = $monthlyAttendance->where('status', 'absent')->count();
        $lateDays = $monthlyAttendance->where('status', 'late')->count();
        $halfDays = $monthlyAttendance->where('status', 'half_day')->count();
        
        // Patient Statistics
        $totalPatientsToday = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $today)
            ->count();

        $appointmentsToday = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $today)
            ->count();

        $completedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $today)
            ->where('status', 'completed')
            ->count();

        $pendingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $today)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->count();

        $inProgressAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $today)
            ->where('status', 'in_progress')
            ->count();
        
        // Recent Appointments
        $recentAppointments = Appointment::where('doctor_id', $doctor->id)
            ->with('patient')
            ->orderBy('appointment_date', 'desc')
            ->limit(5)
            ->get();
        
        // Get today's appointments for this doctor
        $todaySchedule = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_date')
            ->get();

        // Get current in-progress appointment
        $currentPractice = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'in_progress')
            ->first();

        // Get recent activities (combine attendance and appointments)
        $recentActivities = collect();
        
        // Add attendance activities
        if ($todayAttendance) {
            $recentActivities->push((object)[
                'description' => 'Check-in pada ' . $todayAttendance->check_in_time->format('H:i'),
                'created_at' => $todayAttendance->check_in_time
            ]);
        }
        
        // Add appointment activities
        $recentAppointments->each(function($appointment) use ($recentActivities) {
            $recentActivities->push((object)[
                'description' => 'Janji temu dengan ' . ($appointment->patient->name ?? 'Pasien') . ' - ' . ($appointment->status_text ?? 'Terjadwal'),
                'created_at' => $appointment->appointment_date
            ]);
        });
        
        $recentActivities = $recentActivities->sortByDesc('created_at')->take(5);
        
        return view('doctors.dashboard.beranda', compact(
            'todayAttendance',
            'totalPatientsToday',
            'appointmentsToday',
            'completedAppointments',
            'pendingAppointments',
            'inProgressAppointments',
            'recentAppointments',
            'todaySchedule',
            'recentActivities',
            'presentDays',
            'absentDays',
            'lateDays',
            'halfDays',
            'currentPractice'
        ));
    }
}
