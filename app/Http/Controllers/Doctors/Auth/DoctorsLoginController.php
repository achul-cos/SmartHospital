<?php

namespace App\Http\Controllers\Doctors\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorsLoginController extends Controller
{
    public function index() {
        return view('doctors.auth.login');
    }
}
