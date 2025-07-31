<?php

namespace App\Http\Controllers\Admins\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminsLoginController extends Controller
{
    // Menampilkan halaman login
    public function index() {
        return view('admins.auth.login');
    }

    // Autentikasi login
    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required'], // bisa email atau employee_number
            'password' => ['required'],
        ]);
    
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'employee_number';
        
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];
    
        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }
    
        return back()->withErrors([
            'login' => 'Email/Nomor Pegawai atau password salah.',
        ])->onlyInput('login');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
