<?php

namespace App\Http\Controllers\Patients\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientsDashboardKritikSaranController extends Controller
{
    public function index() {
        return view('patients.dashboard.kritik-saran');
    }
    
}
