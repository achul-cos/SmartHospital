<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Login - Smart Hospital</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header {
            background: linear-gradient(135deg, #2196F3, #21CBF3);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.3; }
        }
        
        .hospital-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            position: relative;
            z-index: 1;
        }
        
        .hospital-icon::before {
            content: '🏥';
            font-size: 40px;
        }
        
        .header h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        
        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 300;
            position: relative;
            z-index: 1;
        }
        
        .content {
            padding: 50px 40px;
            text-align: center;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .description {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 40px;
            line-height: 1.8;
        }
        
        .otp-container {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            border: 2px dashed #2196F3;
            position: relative;
            overflow: hidden;
        }
        
        .otp-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(33, 150, 243, 0.1), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .otp-label {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        
        .otp-code {
            font-size: 48px;
            font-weight: 700;
            color: #2196F3;
            letter-spacing: 8px;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }
        
        .warning {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 1px solid #ffeaa7;
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .warning-icon {
            font-size: 24px;
            color: #f39c12;
        }
        
        .warning-text {
            font-size: 14px;
            color: #856404;
            font-weight: 500;
            line-height: 1.6;
        }
        
        .timer {
            background: #e74c3c;
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            margin: 20px 0;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer-text {
            font-size: 13px;
            color: #6c757d;
            line-height: 1.6;
        }
        
        .security-tips {
            background: #e8f5e8;
            border-left: 4px solid #27ae60;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .security-tips h3 {
            color: #27ae60;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .security-tips ul {
            color: #2d5a2d;
            font-size: 14px;
            padding-left: 20px;
        }
        
        .security-tips li {
            margin-bottom: 5px;
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #2196F3, transparent);
            margin: 30px 0;
            border-radius: 1px;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .otp-code {
                font-size: 36px;
                letter-spacing: 4px;
            }
            
            .greeting {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="hospital-icon"></div>
            <h1>Smart Hospital</h1>
            <p>Sistem Manajemen Rumah Sakit Terpadu</p>
        </div>
        
        <div class="content">
            <h2 class="greeting">Halo! 👋</h2>
            <p class="description">
                Kami telah menerima permintaan login ke akun Smart Hospital Anda. 
                Untuk melanjutkan, silakan gunakan kode verifikasi berikut:
            </p>
            
            <div class="otp-container">
                <div class="otp-label">Kode Verifikasi OTP</div>
                <h1 class="otp-code">{{ $otp }}</h1>
            </div>
            
            <div class="timer">⏰ Berlaku selama 5 menit</div>
            
            <div class="divider"></div>
            
            <div class="warning">
                <div class="warning-icon">🔒</div>
                <div class="warning-text">
                    <strong>Penting:</strong> Jangan berikan kode ini kepada siapapun. 
                    Tim Smart Hospital tidak akan pernah meminta kode OTP melalui telepon atau email.
                </div>
            </div>
            
            <div class="security-tips">
                <h3>🛡️ Tips Keamanan</h3>
                <ul>
                    <li>Pastikan Anda yang meminta kode ini</li>
                    <li>Jangan bagikan kode kepada orang lain</li>
                    <li>Kode akan kedaluwarsa dalam 5 menit</li>
                    <li>Hubungi support jika Anda tidak merasa meminta kode ini</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p class="footer-text">
                Email ini dikirim secara otomatis dari sistem Smart Hospital.<br>
                Jika Anda tidak meminta kode ini, abaikan email ini atau hubungi tim support kami.<br><br>
                <strong>Smart Hospital</strong> - Melayani dengan Teknologi Terdepan<br>
                © 2025 Smart Hospital. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>