<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admins\Auth\AdminsLoginController;
use App\Http\Controllers\Doctors\Auth\DoctorsLoginController;
use App\Http\Controllers\Patients\Auth\PatientsLoginController;

use App\Http\Controllers\Admins\Dashboard\AdminsDashboardController;
use App\Http\Controllers\Doctors\Dashboard\DoctorsDashboardController;
use App\Http\Controllers\Patients\Dashboard\PatientsDashboardController;


Route::get('/', [LandingController::class, 'index'])->name('landing');

//Aktor Admin
Route::prefix('admin')->group(function () {

    // Form login admin
    Route::get('/login', [AdminsLoginController::class, 'index'])->name('admins.login');

    // Autentikasi Login admin
    Route::post('/login', [AdminsLoginController::class, 'login'])->name('admins.login.submit');

    // Logout Akun admin
    Route::get('/logout', [AdminsLoginController::class, 'logout'])->name('admins.logout');

    Route::middleware('admin')->group(function () {

        //Dashboard Admin
        Route::get('/dashboard', [AdminsDashboardController::class, 'index'])->name('admins.dashboard');

    });
});

//Aktor Dokter
Route::prefix('dokter')->group(function () {

    // Form login dokter
    Route::get('/login', [DoctorsLoginController::class, 'index'])->name('doctors.login');

    // Autentikasi Login dokter
    Route::post('/login', [DoctorsLoginController::class, 'login'])->name('doctors.login.submit');

    // Logout Akun dokter    
    Route::get('/logout', [DoctorsLoginController::class, 'logout'])->name('doctors.logout');

    Route::middleware('doctor')->group(function () {

        //Dashboard Dokter
        Route::get('/dashboard', [DoctorsDashboardController::class, 'index'])->name('doctors.dashboard');

    });
});

//Aktor Pasien
Route::prefix('pasien')->group(function () {

    // Form login pasien
    Route::get('login', [PatientsLoginController::class, 'index'])->name('patients.login');

    // Autentikasi Login Pasien
    Route::post('/login', [PatientsLoginController::class, 'login'])->name('patients.login.submit');   

    // Kirim OTP
    Route::post('/patients/send-otp', [PatientsLoginController::class, 'sendOtp'])->name('patients.otp.send');

    // Verifikasi OTP
    Route::post('verify-otp', [PatientsLoginController::class, 'verifyOtp'])->name('patients.verifyOtp');    

    // Logout Akun Pasien
    Route::get('/logout', [PatientsLoginController::class, 'logout'])->name('patients.logout');

    Route::middleware('patient')->group(function () {

        //Dashboard Pasien
        Route::get('/dashboard', [PatientsDashboardController::class, 'index'])->name('patients.dashboard');

    });
});