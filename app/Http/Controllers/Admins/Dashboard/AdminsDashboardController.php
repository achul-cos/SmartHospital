<?php

namespace App\Http\Controllers\Admins\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminsDashboardController extends Controller
{
    public function index() {
        return view('admins.dashboard.dashboard');
    }
}
