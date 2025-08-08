<?php

namespace App\Http\Controllers\Doctors\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DoctorAttendance;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorsDashboardKehadiranController extends Controller
{
    public function index() {
        $doctor = Auth::guard('doctor')->user();
        $today = Carbon::today();
        
        // Get today's attendance
        $todayAttendance = DoctorAttendance::where('doctor_id', $doctor->id)
            ->whereDate('date', $today)
            ->first();
        
        // Get today's schedule
        $dayOfWeek = strtolower($today->format('l')); // monday, tuesday, etc.
        $todaySchedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();
        
        // Get attendance history (last 30 days)
        $attendanceHistory = DoctorAttendance::where('doctor_id', $doctor->id)
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();
        
        // Monthly statistics
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $monthlyAttendance = DoctorAttendance::where('doctor_id', $doctor->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();
        
        $presentDays = $monthlyAttendance->where('status', 'present')->count();
        $earlyDays = $monthlyAttendance->where('status', 'early')->count();
        $absentDays = $monthlyAttendance->where('status', 'absent')->count();
        $lateDays = $monthlyAttendance->where('status', 'late')->count();
        $halfDays = $monthlyAttendance->where('status', 'half_day')->count();
        $izinDays = $monthlyAttendance->where('status', 'izin')->count();
        $liburDays = $monthlyAttendance->where('status', 'libur')->count();
        
        return view('doctors.dashboard.kehadiran', compact(
            'todayAttendance', 
            'attendanceHistory', 
            'presentDays', 
            'earlyDays',
            'absentDays', 
            'lateDays', 
            'halfDays', 
            'izinDays', 
            'liburDays',
            'todaySchedule'
        ));
    }
    
    public function checkIn(Request $request) {
        $doctor = Auth::guard('doctor')->user();
        $today = Carbon::today();
        $now = Carbon::now();
        
        // Check if already checked in today
        $existingAttendance = DoctorAttendance::where('doctor_id', $doctor->id)
            ->whereDate('date', $today)
            ->first();
        
        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }
        
        // Get today's schedule
        $dayOfWeek = strtolower($today->format('l')); // monday, tuesday, etc.
        $todaySchedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();
        
        // Determine status based on schedule and time
        $status = 'present';
        $notes = $request->notes ?? null;
        
        if (!$todaySchedule) {
            // No schedule set for today, use default logic
            $expectedTime = Carbon::createFromTime(8, 0, 0);
            $toleranceMinutes = $doctor->late_tolerance_minutes ?? 15;
            $toleranceTime = $expectedTime->copy()->addMinutes($toleranceMinutes);
            
            if ($now->lt($expectedTime)) {
                $status = 'early';
            } elseif ($now->gt($toleranceTime)) {
                $status = 'late';
            }
        } else {
            // Check schedule status
            if ($todaySchedule->status === 'libur') {
                return redirect()->back()->with('error', 'Hari ini adalah hari libur Anda.');
            } elseif ($todaySchedule->status === 'izin') {
                $status = 'izin';
                $notes = $todaySchedule->notes ?: 'Izin sesuai jadwal';
            } else {
                // Working day, check time
                if ($todaySchedule->start_time) {
                    $expectedTime = Carbon::parse($todaySchedule->start_time);
                    $toleranceMinutes = $doctor->late_tolerance_minutes ?? 15;
                    $toleranceTime = $expectedTime->copy()->addMinutes($toleranceMinutes);
                    
                    if ($now->lt($expectedTime)) {
                        $status = 'early';
                    } elseif ($now->gt($toleranceTime)) {
                        $status = 'late';
                    }
                } else {
                    // No start time set, use default
                    $expectedTime = Carbon::createFromTime(8, 0, 0);
                    $toleranceMinutes = $doctor->late_tolerance_minutes ?? 15;
                    $toleranceTime = $expectedTime->copy()->addMinutes($toleranceMinutes);
                    
                    if ($now->lt($expectedTime)) {
                        $status = 'early';
                    } elseif ($now->gt($toleranceTime)) {
                        $status = 'late';
                    }
                }
            }
        }
        
        // Create attendance record
        DoctorAttendance::create([
            'doctor_id' => $doctor->id,
            'date' => $today,
            'check_in_time' => $now,
            'status' => $status,
            'notes' => $notes,
        ]);
        
        $statusText = match($status) {
            'izin' => 'Izin',
            'early' => 'Datang lebih awal',
            'late' => 'Check-in terlambat',
            default => 'Check-in tepat waktu'
        };
        
        return redirect()->back()->with('success', $statusText . ' berhasil dilakukan pada ' . $now->format('H:i'));
    }
    
    public function checkOut(Request $request) {
        $doctor = Auth::guard('doctor')->user();
        $today = Carbon::today();
        $now = Carbon::now();
        
        // Find today's attendance
        $attendance = DoctorAttendance::where('doctor_id', $doctor->id)
            ->whereDate('date', $today)
            ->first();
        
        if (!$attendance) {
            return redirect()->back()->with('error', 'Anda belum melakukan check-in hari ini.');
        }
        
        if ($attendance->check_out_time) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }
        
        // Update check-out time
        $attendance->update([
            'check_out_time' => $now,
            'notes' => $request->notes ?? $attendance->notes,
        ]);
        
        return redirect()->back()->with('success', 'Check-out berhasil dilakukan pada ' . $now->format('H:i'));
    }
    
    public function markAbsent(Request $request) {
        $doctor = Auth::guard('doctor')->user();
        $today = Carbon::today();
        
        // Check if already has attendance record for today
        $existingAttendance = DoctorAttendance::where('doctor_id', $doctor->id)
            ->whereDate('date', $today)
            ->first();
        
        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Anda sudah memiliki catatan kehadiran untuk hari ini.');
        }
        
        // Create absent record
        DoctorAttendance::create([
            'doctor_id' => $doctor->id,
            'date' => $today,
            'status' => 'absent',
            'notes' => $request->notes ?? 'Tidak hadir',
        ]);
        
        return redirect()->back()->with('success', 'Status tidak hadir telah dicatat.');
    }
    
    public function updateNotes(Request $request) {
        $doctor = Auth::guard('doctor')->user();
        $today = Carbon::today();
        
        $attendance = DoctorAttendance::where('doctor_id', $doctor->id)
            ->whereDate('date', $today)
            ->first();
        
        if (!$attendance) {
            return redirect()->back()->with('error', 'Tidak ada catatan kehadiran untuk hari ini.');
        }
        
        $attendance->update([
            'notes' => $request->notes,
        ]);
        
        return redirect()->back()->with('success', 'Catatan berhasil diperbarui.');
    }
}
