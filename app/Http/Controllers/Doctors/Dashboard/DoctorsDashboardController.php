<?php

namespace App\Http\Controllers\Doctors\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorsDashboardController extends Controller
{
    public function index() {
        return view('doctors.dashboard.dashboard');
    }
}
