<?php

namespace App\Http\Controllers\Patients\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientsDashboardController extends Controller
{
    public function index() {
        session()->flash('flash_message', [
            'type' => 'success',
            'message' => 'Selamat Datang di Dashboard!'
        ]);
        return view('patients.dashboard.dashboard');
    }
    
}
