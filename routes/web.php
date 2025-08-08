<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admins\Auth\AdminsLoginController;
use App\Http\Controllers\Doctors\Auth\DoctorsLoginController;
use App\Http\Controllers\Patients\Auth\PatientsLoginController;

use App\Http\Controllers\Admins\Dashboard\AdminsDashboardController;
use App\Http\Controllers\Admins\Dashboard\AdminsDashboardKehadiranController;
use App\Http\Controllers\Admins\Dashboard\AdminsDashboardPasienController;
use App\Http\Controllers\Admins\Dashboard\AdminsDashboardDokterController;
use App\Http\Controllers\Admins\Dashboard\AdminsDashboardLaporanController;
use App\Http\Controllers\Admins\Dashboard\AdminsDashboardPengaturanAkunController;

use App\Http\Controllers\Doctors\Dashboard\DoctorsDashboardController;
use App\Http\Controllers\Doctors\Dashboard\DoctorsDashboardKehadiranController;
use App\Http\Controllers\Doctors\Dashboard\DoctorsDashboardPasienController;
use App\Http\Controllers\Doctors\Dashboard\DoctorsDashboardRiwayatPraktikController;
use App\Http\Controllers\Doctors\Dashboard\DoctorsDashboardHubungiAdminController;
use App\Http\Controllers\Doctors\Dashboard\DoctorsDashboardPengaturanAkunController;
use App\Http\Controllers\Doctors\Dashboard\DoctorsChatController;

use App\Http\Controllers\Patients\Dashboard\PatientsDashboardController;
use App\Http\Controllers\Patients\Dashboard\PatientsDashboardRiwayatKunjunganController;
use App\Http\Controllers\Patients\Dashboard\PatientsDashboardRekamMedisController;
use App\Http\Controllers\Patients\Dashboard\PatientsDashboardResepObatController;
use App\Http\Controllers\Patients\Dashboard\PatientsDashboardKritikSaranController;
use App\Http\Controllers\Patients\Dashboard\PatientsDashboardPengaturanAkunController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

//Aktor Admin
Route::prefix('admin')->group(function () {

    // Form login admin
    Route::get('/login', [AdminsLoginController::class, 'index'])->name('admins.login');

    // Autentikasi Login admin
    Route::post('/login', [AdminsLoginController::class, 'login'])->name('admins.login.submit');

    // Logout Akun admin
    Route::post('/logout', [AdminsLoginController::class, 'logout'])->name('admins.logout');

    Route::middleware('admin')->group(function () {

        //Dashboard - Beranda - Admin
        Route::get('/dashboard', [AdminsDashboardController::class, 'index'])->name('admins.dashboard');

        Route::prefix('dashboard')->group(function () {
            // Doctor attendance data for calendar
            Route::get('/dokter/{doctor}/attendance', [AdminsDashboardDokterController::class, 'getAttendance'])
                ->name('admins.dashboard.dokter.attendance');

            //Dashboard - Kehadiran - Admin
            Route::get('/kehadiran', [AdminsDashboardKehadiranController::class, 'index'])->name('admins.dashboard.kehadiran');

            //Dashboard - Pasien - Admin
            Route::get('/pasien', [AdminsDashboardPasienController::class, 'index'])->name('admins.dashboard.pasien');

            //Dashboard - Dokter - Admin
            Route::get('/dokter', [AdminsDashboardDokterController::class, 'index'])->name('admins.dashboard.dokter');
            Route::post('/dokter', [AdminsDashboardDokterController::class, 'store'])->name('admins.dashboard.dokter.store');
            Route::get('/dokter/{doctor}/edit', [AdminsDashboardDokterController::class, 'edit'])->name('admins.dashboard.dokter.edit');
            Route::put('/dokter/{doctor}', [AdminsDashboardDokterController::class, 'update'])->name('admins.dashboard.dokter.update');
            Route::post('/dokter/{doctor}/toggle-status', [AdminsDashboardDokterController::class, 'toggleStatus'])->name('admins.dashboard.dokter.toggle-status');
            Route::post('/dokter/{doctor}/schedule', [AdminsDashboardDokterController::class, 'setSchedule'])->name('admins.dashboard.dokter.schedule');
            Route::get('/dokter/{doctor}/schedule', [AdminsDashboardDokterController::class, 'getSchedule'])->name('admins.dashboard.dokter.get-schedule');
            Route::post('/dokter/{doctor}/set-tolerance', [AdminsDashboardDokterController::class, 'setLateTolerance'])->name('admins.dashboard.dokter.set-tolerance');
            Route::get('/dokter/top', [AdminsDashboardDokterController::class, 'getTopDoctorsJson'])->name('admins.dashboard.dokter.top');

            //Dashboard - Laporan - Admin
            Route::get('/laporan', [AdminsDashboardLaporanController::class, 'index'])->name('admins.dashboard.laporan');

            //Dashboard - Pengaturan Akun - Admin
            Route::get('/pengaturan-akun', [AdminsDashboardPengaturanAkunController::class, 'index'])->name('admins.dashboard.pengaturan-akun');

        });

    });
});

//Aktor Dokter
Route::prefix('dokter')->group(function () {

    // Form login dokter
    Route::get('/login', [DoctorsLoginController::class, 'index'])->name('doctors.login');

    // Autentikasi Login dokter
    Route::post('/login', [DoctorsLoginController::class, 'login'])->name('doctors.login.submit');

    // Logout Akun dokter    
    Route::post('/logout', [DoctorsLoginController::class, 'logout'])->name('doctors.logout');

    Route::middleware('doctor')->group(function () {

        //Dashboard - Beranda - Dokter
        Route::get('/dashboard', [DoctorsDashboardController::class, 'index'])->name('doctors.dashboard');

        Route::prefix('dashboard')->group(function () {

            //Dashboard - Kehadiran - Dokter
            Route::get('/kehadiran', [DoctorsDashboardKehadiranController::class, 'index'])->name('doctors.dashboard.kehadiran');
            Route::post('/kehadiran/check-in', [DoctorsDashboardKehadiranController::class, 'checkIn'])->name('doctors.dashboard.kehadiran.check-in');
            Route::post('/kehadiran/check-out', [DoctorsDashboardKehadiranController::class, 'checkOut'])->name('doctors.dashboard.kehadiran.check-out');
            Route::post('/kehadiran/mark-absent', [DoctorsDashboardKehadiranController::class, 'markAbsent'])->name('doctors.dashboard.kehadiran.mark-absent');
            Route::post('/kehadiran/update-notes', [DoctorsDashboardKehadiranController::class, 'updateNotes'])->name('doctors.dashboard.kehadiran.update-notes');

            //Dashboard - Pasien - Dokter
            Route::get('/pasien', [DoctorsDashboardPasienController::class, 'index'])->name('doctors.dashboard.pasien');
            Route::get('/pasien/{patientId}', [DoctorsDashboardPasienController::class, 'showPatient'])->name('doctors.dashboard.pasien.show');
            Route::post('/pasien/medical-record', [DoctorsDashboardPasienController::class, 'createMedicalRecord'])->name('doctors.dashboard.pasien.medical-record');
            Route::post('/pasien/prescription', [DoctorsDashboardPasienController::class, 'createPrescription'])->name('doctors.dashboard.pasien.prescription');
            Route::patch('/pasien/appointment/{appointmentId}/status', [DoctorsDashboardPasienController::class, 'updateAppointmentStatus'])->name('doctors.dashboard.pasien.appointment.status');
            
            // Appointment status management
            Route::post('/pasien/appointment/{appointmentId}/confirm', [DoctorsDashboardPasienController::class, 'confirmAppointment'])->name('doctors.dashboard.pasien.appointment.confirm');
            Route::post('/pasien/appointment/{appointmentId}/start', [DoctorsDashboardPasienController::class, 'startPractice'])->name('doctors.dashboard.pasien.appointment.start');
            Route::post('/pasien/appointment/{appointmentId}/complete', [DoctorsDashboardPasienController::class, 'completePractice'])->name('doctors.dashboard.pasien.appointment.complete');
            Route::post('/pasien/appointment/{appointmentId}/cancel', [DoctorsDashboardPasienController::class, 'cancelAppointment'])->name('doctors.dashboard.pasien.appointment.cancel');

            //Dashboard - Riwayat Praktik - Dokter
            Route::get('/riwayat-praktik', [DoctorsDashboardRiwayatPraktikController::class, 'index'])->name('doctors.dashboard.riwayat-praktik');

            //Dashboard - Hubungi Admin - Dokter
            Route::get('/hubungi-admin', [DoctorsDashboardHubungiAdminController::class, 'index'])->name('doctors.dashboard.hubungi-admin');
            Route::post('/hubungi-admin/send', [DoctorsDashboardHubungiAdminController::class, 'sendMessage'])->name('doctors.dashboard.hubungi-admin.send');

            //Dashboard - Chat dengan Admin - Dokter (Integrated into hubungi-admin)
            Route::post('/chat', [DoctorsChatController::class, 'create'])->name('doctors.dashboard.chat.create');
            Route::get('/chat/{roomId}', [DoctorsChatController::class, 'show'])->name('doctors.dashboard.chat.room');
            Route::post('/chat/{roomId}/message', [DoctorsChatController::class, 'sendMessage'])->name('doctors.dashboard.chat.send');
            Route::get('/chat/{roomId}/messages', [DoctorsChatController::class, 'getMessages'])->name('doctors.dashboard.chat.messages');
            Route::post('/chat/{roomId}/close', [DoctorsChatController::class, 'closeChat'])->name('doctors.dashboard.chat.close');
            Route::get('/chat/unread-count', [DoctorsChatController::class, 'getUnreadCount'])->name('doctors.dashboard.chat.unread');
            Route::get('/chat/available-admins', [DoctorsChatController::class, 'getAvailableAdmins'])->name('doctors.dashboard.chat.admins');
            
            // Typing indicators and message status
            Route::post('/chat/{roomId}/typing/start', [DoctorsChatController::class, 'startTyping'])->name('doctors.dashboard.chat.typing.start');
            Route::post('/chat/{roomId}/typing/stop', [DoctorsChatController::class, 'stopTyping'])->name('doctors.dashboard.chat.typing.stop');
            Route::get('/chat/{roomId}/typing/status', [DoctorsChatController::class, 'getTypingStatus'])->name('doctors.dashboard.chat.typing.status');
            Route::post('/chat/message/{messageId}/mark-delivered', [DoctorsChatController::class, 'markMessageDelivered'])->name('doctors.dashboard.chat.message.delivered');
            Route::post('/chat/message/{messageId}/mark-read', [DoctorsChatController::class, 'markMessageRead'])->name('doctors.dashboard.chat.message.read');

            //Dashboard - Pengaturan Akun - Dokter        
            Route::get('/pengaturan-akun', [DoctorsDashboardPengaturanAkunController::class, 'index'])->name('doctors.dashboard.pengaturan-akun');
            Route::put('/pengaturan-akun/update-profile', [DoctorsDashboardPengaturanAkunController::class, 'updateProfile'])->name('doctors.dashboard.pengaturan-akun.update-profile');
            Route::put('/pengaturan-akun/change-password', [DoctorsDashboardPengaturanAkunController::class, 'changePassword'])->name('doctors.dashboard.pengaturan-akun.change-password');
            Route::put('/pengaturan-akun/update-notifications', [DoctorsDashboardPengaturanAkunController::class, 'updateNotifications'])->name('doctors.dashboard.pengaturan-akun.update-notifications');
            Route::put('/pengaturan-akun/deactivate', [DoctorsDashboardPengaturanAkunController::class, 'deactivate'])->name('doctors.dashboard.pengaturan-akun.deactivate');
            Route::delete('/pengaturan-akun/delete', [DoctorsDashboardPengaturanAkunController::class, 'delete'])->name('doctors.dashboard.pengaturan-akun.delete');
            Route::get('/pengaturan-akun/export-data', [DoctorsDashboardPengaturanAkunController::class, 'exportData'])->name('doctors.dashboard.pengaturan-akun.export-data');

        });

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
    Route::post('/logout', [PatientsLoginController::class, 'logout'])->name('patients.logout');

    Route::middleware('patient')->group(function () {

        //Dashboard - Beranda - Pasien
        Route::get('/dashboard', [PatientsDashboardController::class, 'index'])->name('patients.dashboard');

        Route::prefix('dashboard')->group(function () {

            //Dashboard - Riwayat Kunjungan - Pasien
            Route::get('/riwayat-kunjungan', [PatientsDashboardRiwayatKunjunganController::class, 'index'])->name('patients.dashboard.riwayat-kunjungan');

            //Dashboard - Rekam Medis - Pasien
            Route::get('/rekam-medis', [PatientsDashboardRekamMedisController::class, 'index'])->name('patients.dashboard.rekam-medis');

            //Dashboard - Resep Obat - Pasien
            Route::get('/resep-obat', [PatientsDashboardResepObatController::class, 'index'])->name('patients.dashboard.resep-obat');

            //Dashboard - Kritik & Saran - Pasien
            Route::get('/kritik-saran', [PatientsDashboardKritikSaranController::class, 'index'])->name('patients.dashboard.kritik-saran');

            //Dashboard - Pengaturan Akun - Pasien         
            Route::get('/pengaturan-akun', [PatientsDashboardPengaturanAkunController::class, 'index'])->name('patients.dashboard.pengaturan-akun');

        });

    });
});