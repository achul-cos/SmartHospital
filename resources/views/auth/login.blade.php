@extends('layouts.app')

@section('title', 'Login - Smart Hospital')
@section('description', 'Login ke Smart Hospital Management System')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Selamat Datang</h2>
            <p class="mt-2 text-sm text-gray-600">Silakan pilih jenis akun Anda</p>
        </div>

        <!-- Actor Selection Tabs -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg mb-6">
                <button id="admin-tab" class="flex-1 py-2 px-4 text-sm font-medium rounded-md bg-white text-red-600 shadow-sm transition-all duration-200">
                    Admin
                </button>
                <button id="doctor-tab" class="flex-1 py-2 px-4 text-sm font-medium rounded-md text-gray-600 hover:text-gray-900 transition-all duration-200">
                    Dokter
                </button>
                <button id="patient-tab" class="flex-1 py-2 px-4 text-sm font-medium rounded-md text-gray-600 hover:text-gray-900 transition-all duration-200">
                    Pasien
                </button>
            </div>

            <!-- Admin Login Form -->
            <div id="admin-form" class="login-form">
                <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="admin_identifier" class="block text-sm font-medium text-gray-700 mb-2">
                            Email / Nama / Nomor Pegawai
                        </label>
                        <input id="admin_identifier" name="identifier" type="text" required 
                               class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm"
                               placeholder="Masukkan email, nama, atau nomor pegawai">
                        @error('identifier')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input id="admin_password" name="password" type="password" required 
                                   class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm pr-10"
                                   placeholder="Masukkan password">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center password-toggle" data-target="admin_password">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="admin_remember" name="remember" type="checkbox" 
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label for="admin_remember" class="ml-2 block text-sm text-gray-900">
                                Ingat saya
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="{{ route('admin.password.request') }}" class="font-medium text-red-600 hover:text-red-500">
                                Lupa password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-red-500 group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </span>
                            Masuk sebagai Admin
                        </button>
                    </div>
                </form>
            </div>

            <!-- Doctor Login Form -->
            <div id="doctor-form" class="login-form hidden">
                <form method="POST" action="{{ route('doctor.login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="doctor_identifier" class="block text-sm font-medium text-gray-700 mb-2">
                            Email / Nama / Nomor Dokter
                        </label>
                        <input id="doctor_identifier" name="identifier" type="text" required 
                               class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm"
                               placeholder="Masukkan email, nama, atau nomor dokter">
                        @error('identifier')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="doctor_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input id="doctor_password" name="password" type="password" required 
                                   class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm pr-10"
                                   placeholder="Masukkan password">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center password-toggle" data-target="doctor_password">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="doctor_remember" name="remember" type="checkbox" 
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="doctor_remember" class="ml-2 block text-sm text-gray-900">
                                Ingat saya
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="{{ route('doctor.password.request') }}" class="font-medium text-green-600 hover:text-green-500">
                                Lupa password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-green-500 group-hover:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </span>
                            Masuk sebagai Dokter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Patient Login Form -->
            <div id="patient-form" class="login-form hidden">
                <!-- Password Login -->
                <div id="patient-password-login">
                    <form method="POST" action="{{ route('patient.login') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="patient_identifier" class="block text-sm font-medium text-gray-700 mb-2">
                                Email / Nama / Nomor Kartu
                            </label>
                            <input id="patient_identifier" name="identifier" type="text" required 
                                   class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                   placeholder="Masukkan email, nama, atau nomor kartu">
                            @error('identifier')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="patient_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <input id="patient_password" name="password" type="password" required 
                                       class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm pr-10"
                                       placeholder="Masukkan password">
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center password-toggle" data-target="patient_password">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="patient_remember" name="remember" type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="patient_remember" class="ml-2 block text-sm text-gray-900">
                                    Ingat saya
                                </label>
                            </div>
                            <div class="text-sm">
                                <a href="{{ route('patient.password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                    Lupa password?
                                </a>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button type="submit" 
                                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </span>
                                Masuk dengan Password
                            </button>
                            
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">Atau</span>
                                </div>
                            </div>

                            <button type="button" id="show-otp-login" 
                                    class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Masuk dengan Kode OTP
                            </button>
                        </div>
                    </form>
                </div>

                <!-- OTP Login -->
                <div id="patient-otp-login" class="hidden">
                    <form method="POST" action="{{ route('patient.login.otp') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="patient_otp_identifier" class="block text-sm font-medium text-gray-700 mb-2">
                                Email / Nomor Telepon
                            </label>
                            <input id="patient_otp_identifier" name="identifier" type="text" required 
                                   class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                   placeholder="Masukkan email atau nomor telepon">
                        </div>

                        <div id="otp-input-group" class="hidden">
                            <label for="patient_otp_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode OTP
                            </label>
                            <div class="flex space-x-2">
                                <input id="patient_otp_code" name="otp_code" type="text" maxlength="6" 
                                       class="flex-1 text-center px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                       placeholder="000000">
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                Kode OTP telah dikirim ke email/WhatsApp Anda
                            </p>
                        </div>

                        <div class="space-y-3">
                            <button type="button" id="send-otp" 
                                    class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Kirim Kode OTP
                            </button>

                            <button type="submit" id="verify-otp" 
                                    class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 hidden">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Verifikasi OTP
                            </button>

                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">Atau</span>
                                </div>
                            </div>

                            <button type="button" id="show-password-login" 
                                    class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Masuk dengan Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer Links -->
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordToggle = document.querySelector('.password-toggle');
    const passwordInput = document.getElementById('password');
    const icon = passwordToggle?.querySelector('svg');
    if (passwordToggle) {
        passwordToggle.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>`;
            } else {
                passwordInput.type = 'password';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
            }
        });
    }

    const showOtpLogin = document.getElementById('show-otp-login');
    const showPasswordLogin = document.getElementById('show-password-login');
    const passwordLogin = document.getElementById('password-login');
    const otpLogin = document.getElementById('otp-login');
    const sendOtpForm = document.getElementById('send-otp-form');
    const verifyOtpForm = document.getElementById('verify-otp-form');
    const sendOtpBtn = document.getElementById('send-otp');
    const verifyOtpBtn = document.getElementById('verify-otp');
    const otpInputGroup = document.getElementById('otp-input-group');
    const resendBtn = document.getElementById('resend-otp');
    const countdownElement = document.getElementById('countdown');
    const otpTimerWrap = document.getElementById('otp-timer-wrap');
    const otpCodeInput = document.getElementById('otp_code');
    let countdown = null;

    showOtpLogin?.addEventListener('click', () => { passwordLogin.classList.add('hidden'); otpLogin.classList.remove('hidden'); });
    showPasswordLogin?.addEventListener('click', () => { otpLogin.classList.add('hidden'); passwordLogin.classList.remove('hidden'); });

    function startOtpTimer(duration) {
        let timer = duration;
        otpTimerWrap.classList.remove('hidden');
        resendBtn.classList.add('hidden');
        clearInterval(countdown);
        countdownElement.textContent = timer;
        countdown = setInterval(() => {
            countdownElement.textContent = timer;
            timer--;
            if (timer < 0) {
                clearInterval(countdown);
                otpTimerWrap.classList.add('hidden');
                resendBtn.classList.remove('hidden');
                const identifier = document.getElementById('otp_identifier').value;
                fetch("{{ route('patients.otp.send') }}", {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ identifier })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.message) {
                        showFlashModal("Kode OTP baru telah dikirim otomatis.", 'success');
                        startOtpTimer(250);
                    }
                })
                .catch(() => showFlashModal('Gagal mengirim OTP otomatis', 'error'));
            }
        }, 1000);
    }

    sendOtpForm?.addEventListener('submit', function (e) {
        e.preventDefault();
        const identifier = document.getElementById('otp_identifier').value;
        if (!identifier) return showFlashModal('Silakan masukkan email atau nomor telepon', 'error');
        sendOtpBtn.disabled = true;
        sendOtpBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>Mengirim OTP...`;
        fetch("{{ route('patients.otp.send') }}", {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ identifier })
        })
        .then(async res => {
            let data = await res.json();
            if (res.ok) {
                otpInputGroup.classList.remove('hidden');
                verifyOtpBtn.classList.remove('hidden');
                sendOtpBtn.classList.add('hidden');
                startOtpTimer(250);
                showFlashModal(data.message, 'success');
            } else {
                showFlashModal(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(() => showFlashModal('Terjadi kesalahan saat mengirim OTP', 'error'))
        .finally(() => { sendOtpBtn.disabled = false; sendOtpBtn.innerHTML = 'Kirim Kode OTP'; });
    });

    resendBtn?.addEventListener('click', () => sendOtpForm.dispatchEvent(new Event('submit')));

    verifyOtpForm?.addEventListener('submit', function (e) {
        e.preventDefault();
        const identifier = document.getElementById('otp_identifier').value;
        const otpCode = otpCodeInput.value;
        if (!otpCode) return showFlashModal('Silakan masukkan kode OTP', 'error');
        verifyOtpBtn.disabled = true;
        verifyOtpBtn.innerHTML = `Memverifikasi...`;
        fetch("{{ route('patients.verifyOtp') }}", {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ identifier, otp_code: otpCode })
        })
        .then(async res => {
            let data = await res.json();
            if (res.ok) {
                showFlashModal(data.message, 'success');
                if (data.redirect) setTimeout(() => window.location.href = data.redirect, 1500);
            } else {
                showFlashModal(data.message || 'Kode OTP salah', 'error');
                verifyOtpBtn.disabled = false;
                verifyOtpBtn.innerHTML = 'Verifikasi OTP';
            }
        })
        .catch(() => {
            showFlashModal('Terjadi kesalahan saat memverifikasi OTP', 'error');
            verifyOtpBtn.disabled = false;
            verifyOtpBtn.innerHTML = 'Verifikasi OTP';
        });
    });

    otpCodeInput?.addEventListener('input', function () { this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6); });
});
</script>
@endpush
@endsection 