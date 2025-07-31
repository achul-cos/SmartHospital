<?php

namespace App\Jobs;

use App\Mail\PatientOtpMail;
use App\Helpers\Whatsapp;
use App\Models\Patient;
use App\Models\OtpLog;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $patient;
    protected $otp;
    protected $message;
    protected $attemptsWa = 0;
    protected $attemptsEmail = 0;
    protected $mode; // mode aktif: 'wa' / 'email'
    protected $preferredMode;

    public $tries = 6; // total 6x percobaan (3 WA + 3 Email)

    public function __construct(Patient $patient, $otp, $message, $preferredMode = 'wa')
    {
        $this->patient = $patient;
        $this->otp = $otp;
        $this->message = $message;
        $this->preferredMode = $preferredMode;
        $this->mode = $preferredMode;
    }

    public function retryAfter()
    {
        $delays = [5, 10, 20]; 
        $count = $this->mode === 'wa' ? $this->attemptsWa : $this->attemptsEmail;
        return $delays[min($count, count($delays) - 1)];
    }

    public function handle()
    {
        if ($this->mode === 'wa') {
            if ($this->sendViaWhatsApp()) return;
            // WA gagal total → fallback ke email
            $this->switchMode('email');
            return;
        }

        // Mode Email
        if ($this->sendViaEmail()) return;
        // Email gagal total → fallback ke WA (jika awalnya prefer email)
        if ($this->preferredMode === 'email') {
            $this->switchMode('wa');
        }
    }

    private function sendViaWhatsApp()
    {
        $this->attemptsWa++;
        $phone = $this->normalizePhone($this->patient->phone_number);
        $response = Whatsapp::send($phone, $this->message);

        if ($response['success']) {
            $this->logOtp('wa', true, "OTP berhasil dikirim via WhatsApp ke {$phone}");
            return true;
        }

        if ($this->attemptsWa < 3) {
            $this->release($this->retryAfter());
            Log::warning("[OTP] WhatsApp gagal (percobaan ke-{$this->attemptsWa}), retry...");
            return false;
        }

        $this->logOtp('wa', false, "Gagal total mengirim OTP via WhatsApp ke {$phone}");
        return false;
    }

    private function sendViaEmail()
    {
        $this->attemptsEmail++;
        try {
            Mail::to($this->patient->email)->send(new PatientOtpMail($this->otp));
            $this->logOtp('email', true, "OTP berhasil dikirim via Email ke {$this->patient->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("[OTP] Email gagal (percobaan ke-{$this->attemptsEmail}): " . $e->getMessage());

            if ($this->attemptsEmail < 3) {
                $this->release($this->retryAfter());
                return false;
            }

            $this->logOtp('email', false, "Gagal total mengirim OTP via Email ke {$this->patient->email}");
            return false;
        }
    }

    private function switchMode($newMode)
    {
        $this->mode = $newMode;
        $this->release($this->retryAfter());
        Log::error("[OTP] Fallback ke {$newMode} untuk pasien {$this->patient->id}");
    }

    private function logOtp($channel, $success, $message)
    {
        Log::{$success ? 'info' : 'error'}("[OTP] {$message}");
        OtpLog::create([
            'patient_id' => $this->patient->id,
            'channel' => $channel,
            'success' => $success,
            'message' => $message
        ]);
    }

    private function normalizePhone($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        if (str_starts_with($number, '62')) return $number;
        if (str_starts_with($number, '0')) return '62' . substr($number, 1);
        return $number;
    }
}