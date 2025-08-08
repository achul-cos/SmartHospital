<?php

namespace App\Http\Controllers\Admins\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorAttendance;
use App\Models\Revenue;
use App\Models\Activity;
use Illuminate\Http\Request;

class AdminsDashboardController extends Controller
{
    public function index() {
        // Get today's date
        $today = now();
        
        // Statistics data
        $statistics = [
            'patients_today' => Appointment::today()->distinct('patient_id')->count(),
            'active_doctors' => DoctorAttendance::today()->present()->count(),
            'appointments_today' => Appointment::today()->count(),
            'revenue_today' => Revenue::today()->first()?->total_revenue ?? 0,
        ];

        // Get present doctors today
        $presentDoctors = Doctor::with(['todayAttendance'])
            ->whereHas('attendance', function($query) use ($today) {
                $query->whereDate('date', $today->toDateString())
                      ->whereIn('status', ['present', 'late']);
            })
            ->get();

        // Get recent activities (last 10) without eager loading
        $recentActivities = Activity::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get revenue data for charts (last 7 days)
        $revenueData = Revenue::whereBetween('date', [
            $today->copy()->subDays(6)->toDateString(),
            $today->toDateString()
        ])->orderBy('date')->get();

        // Get patient statistics for charts (last 7 days)
        $patientStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $patientStats[] = [
                'date' => $date->format('d/m'),
                'count' => Appointment::whereDate('appointment_date', $date->toDateString())->count()
            ];
        }

        return view('admins.dashboard.beranda', compact(
            'statistics',
            'presentDoctors',
            'recentActivities',
            'revenueData',
            'patientStats'
        ));
    }
}
