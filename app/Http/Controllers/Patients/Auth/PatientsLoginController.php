<?php

namespace App\Http\Controllers\Patients\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\PatientOtpMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Helpers\Whatsapp;
use App\Jobs\SendOtpJob;

class PatientsLoginController extends Controller
{
    public function index()
    {
        return view('patients.auth.login');
    }

    // ===== Normalisasi nomor ke 62
    private function normalizePhone($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        if (str_starts_with($number, '62')) {
            return $number;
        }
        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }
        return $number;
    }

    // ===== Cari pasien berdasarkan email/nomor (cek 0xxx & 62xxx)
    private function findPatientByIdentifier($identifier)
    {
        if (is_numeric($identifier)) {
            $normalized = $this->normalizePhone($identifier);
            $altFormat = preg_replace('/^62/', '0', $normalized); // balik ke 0xxx
            return Patient::where('phone_number', $normalized)
                ->orWhere('phone_number', $altFormat)
                ->first();
        } elseif (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return Patient::where('email', $identifier)->first();
        } else {
            return Patient::where('card_number', $identifier)->first();
        }
    }

    // ===== Login
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => ['required'],
            'password' => ['required'],
        ]);

        $identifier = $request->identifier;
        $patient = $this->findPatientByIdentifier($identifier);

        if ($patient) {
            $credentials = [
                'phone_number' => $patient->phone_number,
                'password' => $request->password,
            ];
            // Jika dia login pakai email / kartu
            if ($patient->email === $identifier) {
                $credentials = ['email' => $patient->email, 'password' => $request->password];
            } elseif ($patient->card_number === $identifier) {
                $credentials = ['card_number' => $patient->card_number, 'password' => $request->password];
            }

            if (Auth::guard('patient')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();

                if ($request->ajax()) {
                    return response()->json([
                        'message' => 'Login berhasil!',
                        'redirect' => route('patients.dashboard'),
                    ]);
                }

                return redirect()->intended('/pasien/dashboard');
            }
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Login gagal. Periksa kembali data Anda.'], 401);
        }

        return back()->withErrors([
            'identifier' => 'Login gagal. Periksa kembali data Anda.',
        ])->onlyInput('identifier');
    }

    // ===== Kirim OTP
    public function sendOtp(Request $request)
    {
        $request->validate(['identifier' => ['required']]);
        $patient = $this->findPatientByIdentifier($request->identifier);
    
        if (!$patient) {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }
    
        $otp = rand(100000, 999999);
        Session::put('patient_otp', [
            'id' => $patient->id,
            'code' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);
    
        $message = "🔔 *SMART HOSPITAL - KODE OTP* 🔔\n\n" .
            "Yth. Bapak/Ibu {$patient->name},\n\n" .
            "Seseorang mencoba mengakses akun SmartHospital Anda. Jika benar ini adalah Anda, berikut kode OTP untuk verifikasi:\n\n" .
            "*{$otp}*\n\n" .
            "Kode ini berlaku selama 5 menit. *Jangan bagikan kode ini kepada siapapun*, termasuk pihak yang mengaku dari SmartHospital.\n\n" .
            "Jika Anda tidak melakukan permintaan ini, abaikan pesan ini.\n\n" .
            "Hormat kami,\nTim SmartHospital.";
    
        $preferredMode = filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'wa';
    
        dispatch(new SendOtpJob($patient, $otp, $message, $preferredMode));
    
        return response()->json([
            'message' => "Kode OTP sedang diproses dan akan segera dikirim ke " . ($preferredMode === 'email' ? 'Email' : 'WhatsApp') . " Anda.",
        ]);
    }
    
    // ===== Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'otp_code' => 'required|numeric|digits:6',
        ]);

        $otpData = Session::get('patient_otp');

        if (!$otpData || $otpData['code'] != $request->otp_code || now()->gt($otpData['expires_at'])) {
            return response()->json(['message' => 'Kode OTP tidak valid atau sudah kedaluwarsa'], 400);
        }

        $patient = Patient::find($otpData['id']);
        if (!$patient) {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }

        Auth::guard('patient')->login($patient);
        Session::forget('patient_otp');

        return response()->json([
            'message' => 'Verifikasi berhasil',
            'redirect' => route('patients.dashboard'),
        ]);
    }

    // ===== Logout
    public function logout(Request $request)
    {
        Auth::guard('patient')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/pasien/login');
    }
}
