<?php

namespace App\Http\Controllers\Patients\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientsLoginController extends Controller
{
    public function index() {
        return view('patients.auth.login');
    }
}
