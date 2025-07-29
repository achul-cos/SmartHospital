<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admins\Auth\AdminsLoginController;
use App\Http\Controllers\Doctors\Auth\DoctorsLoginController;
use App\Http\Controllers\Patients\Auth\PatientsLoginController;


Route::get('/', [LandingController::class, 'index'])->name('landing');

//Aktor Admin
Route::get('/admin/login', [AdminsLoginController::class, 'index'])->name('admins.login');

//Aktor Dokter
Route::get('/dokter/login', [DoctorsLoginController::class, 'index'])->name('doctors.login');

//Aktor Pasien
Route::get('/pasien/login', [PatientsLoginController::class, 'index'])->name('patients.login');