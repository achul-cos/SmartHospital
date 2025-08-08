function initLoginPage() {
    console.log("[Init] Login Page script dijalankan");

    // === Password Toggle ===
    const passwordToggle = document.querySelector('.password-toggle');
    const passwordInput = document.getElementById('password');
    const icon = passwordToggle?.querySelector('svg');

    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', () => {
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

    // === OTP Login Toggle ===
    const showOtpLogin = document.getElementById('show-otp-login');
    const showPasswordLogin = document.getElementById('show-password-login');
    const passwordLogin = document.getElementById('password-login');
    const otpLogin = document.getElementById('otp-login');

    showOtpLogin?.addEventListener('click', () => {
        passwordLogin.classList.add('hidden');
        otpLogin.classList.remove('hidden');
    });
    showPasswordLogin?.addEventListener('click', () => {
        otpLogin.classList.add('hidden');
        passwordLogin.classList.remove('hidden');
    });

    // === OTP Form Handling ===
    const sendOtpForm = document.getElementById('send-otp-form');
    const verifyOtpForm = document.getElementById('verify-otp-form');
    const sendOtpBtn = document.getElementById('send-otp');
    const verifyOtpBtn = document.getElementById('verify-otp');
    const otpInputGroup = document.getElementById('otp-input-group');
    const resendBtn = document.getElementById('resend-otp');
    const countdownElement = document.getElementById('countdown');
    const otpTimerWrap = document.getElementById('otp-timer-wrap');
    const otpCodeInput = document.getElementById('otp_code');
    const otpIdentifier = document.getElementById('otp_identifier');
    let countdown = null;

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
            }
        }, 1000);
    }

    sendOtpForm?.addEventListener('submit', function (e) {
        e.preventDefault();
        const identifier = otpIdentifier.value;
        if (!identifier) return showFlashModal('Silakan masukkan email atau nomor telepon', 'error');
        sendOtpBtn.disabled = true;
        sendOtpBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>Mengirim OTP...`;
        fetch("{{ route('patients.otp.send') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ identifier })
        })
        .then(async res => {
            let data = await res.json();
            if (res.ok) {
                otpInputGroup.classList.remove('hidden');
                verifyOtpForm.classList.remove('hidden');
                verifyOtpBtn.classList.remove('hidden');
                otpIdentifier.setAttribute('readonly', true);
                otpIdentifier.classList.add('bg-gray-100', 'cursor-not-allowed');
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
        const identifier = otpIdentifier.value;
        const otpCode = otpCodeInput.value;
        if (!otpCode) return showFlashModal('Silakan masukkan kode OTP', 'error');
        verifyOtpBtn.disabled = true;
        verifyOtpBtn.innerHTML = `Memverifikasi...`;
        fetch("{{ route('patients.verifyOtp') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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

    otpCodeInput?.addEventListener('input', function () { 
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6); 
    });
}
// Jalankan saat pertama kali load
document.addEventListener('DOMContentLoaded', initLoginPage);    