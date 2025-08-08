<?php

namespace App\Http\Controllers\Patients\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientsDashboardResepObatController extends Controller
{
    public function index() {
        return view('patients.dashboard.resep-obat');
    }
 
}
