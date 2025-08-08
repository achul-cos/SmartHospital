<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        if (auth('doctor')->check()) {
            return redirect()->route('doctors.dashboard');
        }

        if (auth('admin')->check()) {
            return redirect()->route('admins.dashboard');
        }

        if (auth('patient')->check()) {
            return redirect()->route('patients.dashboard');
        }

        return view('pages.landing');
    }
}

