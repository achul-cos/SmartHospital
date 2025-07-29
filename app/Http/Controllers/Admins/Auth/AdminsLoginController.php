<?php

namespace App\Http\Controllers\Admins\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminsLoginController extends Controller
{
    public function index() {
        return view('admins.auth.login');
    }
}
