<?php

namespace App\Http\Controllers\Patients\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientsDashboardController extends Controller
{
    public function index() {
        return view('patients.dashboard.beranda');
    }
    
}
