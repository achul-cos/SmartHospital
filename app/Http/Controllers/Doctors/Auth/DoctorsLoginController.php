<?php

namespace App\Http\Controllers\Doctors\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorsLoginController extends Controller
{
    public function index() {
        return view('doctors.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required'], // bisa email, doctor_number, atau phone_number
            'password' => ['required'],
        ]);

        $loginValue = $request->login;
        $loginField = 'email';
        if (is_numeric($loginValue)) {
            $loginField = 'phone_number';
        } elseif (!filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $loginField = 'doctor_number';
        }

        $credentials = [
            $loginField => $loginValue,
            'password' => $request->password,
        ];

        if (Auth::guard('doctor')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/dokter/dashboard');
        }

        return back()->withErrors([
            'login' => 'Email/Nomor Dokter/Nomor Telepon atau password salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::guard('doctor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/dokter/login');
    }   
    
}
