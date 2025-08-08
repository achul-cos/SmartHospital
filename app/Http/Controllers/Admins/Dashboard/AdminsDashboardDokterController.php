<?php

namespace App\Http\Controllers\Admins\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Hash;

class AdminsDashboardDokterController extends Controller
{
    public function index() {
        $doctors = Doctor::all();
        return view('admins.dashboard.dokter', compact('doctors'));
    }

    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:doctors,email',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|max:20',
                'specialization' => 'required|string|max:255',
                'gender' => 'required|in:male,female',
                'address' => 'nullable|string|max:500',
                'late_tolerance_minutes' => 'nullable|integer|min:0|max:120',
                'photo' => 'nullable|image|max:2048',
            ]);

            // Generate a unique doctor number (format: DOC + sequential number)
            $latestDoctor = Doctor::orderBy('doctor_number', 'desc')->first();
            $nextNumber = 1;
            
            if ($latestDoctor && preg_match('/DOC(\d+)/', $latestDoctor->doctor_number, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            }
            
            $doctorNumber = 'DOC' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Create new doctor
            $doctor = new Doctor();
            $doctor->name = $validated['name'];
            $doctor->email = $validated['email'];
            $doctor->password = Hash::make($validated['password']);
            $doctor->doctor_number = $doctorNumber;
            $doctor->phone = $validated['phone']; // Changed from phone_number to phone
            $doctor->specialization = $validated['specialization'];
            $doctor->gender = $validated['gender'];
            $doctor->address = $validated['address'] ?? null;
            $doctor->late_tolerance_minutes = $validated['late_tolerance_minutes'] ?? 15;

            // Handle photo upload if provided
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = 'doctor_' . time() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('public/doctors', $filename);
                $doctor->photo = 'doctors/' . $filename;
            }

            $doctor->save();
            
            return redirect()
                ->route('admins.dashboard.dokter')
                ->with('success', 'Dokter berhasil ditambahkan dengan nomor ' . $doctorNumber);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        
        Doctor::create($validated);
        return redirect()->route('admins.dashboard.dokter')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function edit(Doctor $doctor) {
        return response()->json([
            'success' => true,
            'doctor' => $doctor
        ]);
    }

    public function getTopDoctorsJson() {
        $topDoctors = $this->getTopDoctors();
        return response()->json(['success' => true, 'topDoctors' => $topDoctors]);
    }

    public function update(Request $request, Doctor $doctor) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'address' => 'nullable|string|max:500',
            'late_tolerance_minutes' => 'nullable|integer|min:0|max:120',
        ]);
        
        $validated['late_tolerance_minutes'] = $validated['late_tolerance_minutes'] ?? 15;
        $doctor->update($validated);
        return redirect()->route('admins.dashboard.dokter')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, Doctor $doctor) {
        $action = $request->input('action');
        
        if ($action === 'activate') {
            $doctor->update(['deactivated_at' => null]);
            $message = 'Dokter berhasil diaktifkan.';
        } else {
            $doctor->update(['deactivated_at' => now()]);
            $message = 'Dokter berhasil dinonaktifkan.';
        }
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function setLateTolerance(Request $request, Doctor $doctor) {
        $validated = $request->validate([
            'late_tolerance_minutes' => 'required|integer|min:0|max:120',
        ]);
        
        $doctor->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Toleransi keterlambatan berhasil diperbarui.'
        ]);
    }

    public function setSchedule(Request $request, Doctor $doctor) {
        $validated = $request->validate([
            'monday_start' => 'nullable|date_format:H:i',
            'monday_end' => 'nullable|date_format:H:i',
            'monday_status' => 'required|in:kerja,libur,izin',
            'monday_notes' => 'nullable|string|max:500',
            
            'tuesday_start' => 'nullable|date_format:H:i',
            'tuesday_end' => 'nullable|date_format:H:i',
            'tuesday_status' => 'required|in:kerja,libur,izin',
            'tuesday_notes' => 'nullable|string|max:500',
            
            'wednesday_start' => 'nullable|date_format:H:i',
            'wednesday_end' => 'nullable|date_format:H:i',
            'wednesday_status' => 'required|in:kerja,libur,izin',
            'wednesday_notes' => 'nullable|string|max:500',
            
            'thursday_start' => 'nullable|date_format:H:i',
            'thursday_end' => 'nullable|date_format:H:i',
            'thursday_status' => 'required|in:kerja,libur,izin',
            'thursday_notes' => 'nullable|string|max:500',
            
            'friday_start' => 'nullable|date_format:H:i',
            'friday_end' => 'nullable|date_format:H:i',
            'friday_status' => 'required|in:kerja,libur,izin',
            'friday_notes' => 'nullable|string|max:500',
            
            'saturday_start' => 'nullable|date_format:H:i',
            'saturday_end' => 'nullable|date_format:H:i',
            'saturday_status' => 'required|in:kerja,libur,izin',
            'saturday_notes' => 'nullable|string|max:500',
            
            'sunday_start' => 'nullable|date_format:H:i',
            'sunday_end' => 'nullable|date_format:H:i',
            'sunday_status' => 'required|in:kerja,libur,izin',
            'sunday_notes' => 'nullable|string|max:500',
        ]);
        
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        foreach ($days as $day) {
            $scheduleData = [
                'doctor_id' => $doctor->id,
                'day_of_week' => $day,
                'status' => $validated[$day . '_status'],
                'start_time' => $validated[$day . '_start'],
                'end_time' => $validated[$day . '_end'],
                'notes' => $validated[$day . '_notes'],
                'is_active' => true
            ];
            
            // Update or create schedule for each day
            DoctorSchedule::updateOrCreate(
                ['doctor_id' => $doctor->id, 'day_of_week' => $day],
                $scheduleData
            );
        }
        
        return redirect()->route('admins.dashboard.dokter')->with('success', 'Jam operasional dokter berhasil diperbarui.');
    }

    public function getSchedule(Doctor $doctor) {
        $schedules = $doctor->schedules()->active()->get();
        
        $scheduleData = [];
        foreach ($schedules as $schedule) {
            $scheduleData[$schedule->day_of_week] = [
                'status' => $schedule->status,
                'start_time' => $schedule->start_time ? $schedule->start_time->format('H:i') : null,
                'end_time' => $schedule->end_time ? $schedule->end_time->format('H:i') : null,
                'notes' => $schedule->notes
            ];
        }
        
        return response()->json([
            'success' => true,
            'schedules' => $scheduleData
        ]);
    }

    public function getAttendance(Request $request, Doctor $doctor) {
        try {
            // Get top doctors data
            $topDoctors = $this->getTopDoctors();
            $view = $request->query('view', 'thisMonth');
            $today = now();
            
            // Get date ranges for different periods
            $thisMonth = [
                $today->copy()->startOfMonth(),
                $today->copy()->endOfMonth()
            ];
            
            $lastMonth = [
                $today->copy()->subMonth()->startOfMonth(),
                $today->copy()->subMonth()->endOfMonth()
            ];
            
            $last30Days = [
                $today->copy()->subDays(29),
                $today
            ];
            
            $previous30Days = [
                $today->copy()->subDays(59),
                $today->copy()->subDays(30)
            ];

            // Get attendance records for all periods
            $thisMonthStats = $this->getAttendanceStats($doctor, $thisMonth[0], $thisMonth[1]);
            $lastMonthStats = $this->getAttendanceStats($doctor, $lastMonth[0], $lastMonth[1]);
            $last30DaysStats = $this->getAttendanceStats($doctor, $last30Days[0], $last30Days[1]);
            $previous30DaysStats = $this->getAttendanceStats($doctor, $previous30Days[0], $previous30Days[1]);
            
            // Get calendar view data based on selected view
            if ($view === 'thisMonth') {
                $startDate = $thisMonth[0];
                $endDate = $thisMonth[1];
            } else {
                $startDate = $last30Days[0];
                $endDate = $last30Days[1];
            }
            
            $attendanceRecords = $doctor->attendances()
                ->whereBetween('date', [$startDate, $endDate])
                ->get()
                ->map(function ($attendance) {
                    return [
                        'date' => $attendance->date->format('Y-m-d'),
                        'status' => $attendance->status,
                        'check_in_time' => $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : null,
                        'check_out_time' => $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : null,
                        'notes' => $attendance->notes
                    ];
                });
            
            // Get top doctors data for response

            return response()->json([
                'calendar' => $attendanceRecords,
                'stats' => [
                    'thisMonth' => $thisMonthStats,
                    'lastMonth' => $lastMonthStats,
                    'last30Days' => $last30DaysStats,
                    'previous30Days' => $previous30DaysStats
                ],
                'topDoctors' => $topDoctors
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getAttendance: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    private function getTopDoctors() {
        $today = now();
        
        // Get all doctors
        $doctors = Doctor::all();
        
        // Calculate attendance stats for each period
        $topDoctors = [
            'thisMonth' => [],
            'lastMonth' => [],
            'last30Days' => [],
            'monthComparison' => [],
            'thirtyDaysComparison' => []
        ];
        
        foreach ($doctors as $doctor) {
            // This month stats
            $thisMonthStart = $today->copy()->startOfMonth();
            $thisMonthEnd = $today->copy()->endOfMonth();
            $thisMonthStats = $this->getAttendanceStats($doctor, $thisMonthStart, $thisMonthEnd);
            $topDoctors['thisMonth'][$doctor->id] = [
                'doctor' => [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'specialization' => $doctor->specialization
                ],
                'attendance_rate' => $thisMonthStats['attendance_rate']
            ];
            
            // Last 30 days stats
            $last30DaysStart = $today->copy()->subDays(29);
            $last30DaysStats = $this->getAttendanceStats($doctor, $last30DaysStart, $today);
            $topDoctors['last30Days'][$doctor->id] = [
                'doctor' => [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'specialization' => $doctor->specialization
                ],
                'attendance_rate' => $last30DaysStats['attendance_rate']
            ];
            
            // Last month stats
            $lastMonthStart = $today->copy()->subMonth()->startOfMonth();
            $lastMonthEnd = $today->copy()->subMonth()->endOfMonth();
            $lastMonthStats = $this->getAttendanceStats($doctor, $lastMonthStart, $lastMonthEnd);
            $topDoctors['lastMonth'][$doctor->id] = [
                'doctor' => [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'specialization' => $doctor->specialization
                ],
                'attendance_rate' => $lastMonthStats['attendance_rate']
            ];
            
            // Month comparison (improvement from last month to this month)
            $monthImprovement = $thisMonthStats['attendance_rate'] - $lastMonthStats['attendance_rate'];
            $topDoctors['monthComparison'][$doctor->id] = [
                'doctor' => [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'specialization' => $doctor->specialization
                ],
                'attendance_rate' => $monthImprovement
            ];
            
            // 30 days comparison
            $previous30DaysStart = $today->copy()->subDays(59);
            $previous30DaysEnd = $today->copy()->subDays(30);
            $previous30DaysStats = $this->getAttendanceStats($doctor, $previous30DaysStart, $previous30DaysEnd);
            $thirtyDaysImprovement = $last30DaysStats['attendance_rate'] - $previous30DaysStats['attendance_rate'];
            $topDoctors['thirtyDaysComparison'][$doctor->id] = [
                'doctor' => [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'specialization' => $doctor->specialization
                ],
                'attendance_rate' => $thirtyDaysImprovement
            ];
        }
        
        return $topDoctors;
    }



    private function getAttendanceStats($doctor, $startDate, $endDate) {
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $records = $doctor->attendances()
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $stats = [
            'present' => 0,
            'early' => 0,
            'late' => 0,
            'absent' => 0,
            'izin' => 0,
            'libur' => 0,
            'half_day' => 0
        ];

        foreach ($records as $record) {
            $stats[$record->status]++;
        }

        $totalPresent = $stats['present'] + $stats['early'] + $stats['late'];
        
        return [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ],
            'total_days' => $totalDays,
            'counts' => $stats,
            'percentages' => [
                'present' => ($stats['present'] / $totalDays) * 100,
                'early' => ($stats['early'] / $totalDays) * 100,
                'late' => ($stats['late'] / $totalDays) * 100,
                'absent' => ($stats['absent'] / $totalDays) * 100,
                'izin' => ($stats['izin'] / $totalDays) * 100,
                'libur' => ($stats['libur'] / $totalDays) * 100,
                'half_day' => ($stats['half_day'] / $totalDays) * 100,
            ],
            'attendance_rate' => ($totalPresent / $totalDays) * 100
        ];
    }
}
