<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Klinik Online</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #6c757d;
            font-size: 14px;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #495057;
        }
        .message {
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .otp-container {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
        }
        .otp-label {
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        .otp-validity {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 10px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .contact-info {
            margin-top: 15px;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .email-container {
                padding: 20px;
            }
            .otp-code {
                font-size: 28px;
                letter-spacing: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🏥 Klinik Online</div>
            <div class="subtitle">Layanan Kesehatan Digital Terpercaya</div>
        </div>

        <div class="content">
            <div class="greeting">
                Halo, <strong>{{ $user->name ?? 'Pengguna' }}</strong>!
            </div>

            <div class="message">
                Kami menerima permintaan untuk mereset password akun Anda. Untuk melanjutkan proses reset password, silakan gunakan kode OTP berikut:
            </div>

            <div class="otp-container">
                <div class="otp-label">Kode Verifikasi OTP</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-validity">Berlaku selama 15 menit</div>
            </div>

            <div class="message">
                Masukkan kode OTP ini pada halaman verifikasi untuk melanjutkan proses reset password Anda.
            </div>

            <div class="warning">
                <strong>⚠️ Penting:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Jangan bagikan kode OTP ini kepada siapa pun</li>
                    <li>Kode ini hanya berlaku selama 15 menit</li>
                    <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
                    <li>Segera hubungi kami jika ada aktivitas mencurigakan</li>
                </ul>
            </div>

            <div class="message">
                Jika Anda mengalami kesulitan atau tidak meminta reset password ini, silakan hubungi tim support kami segera.
            </div>
        </div>

        <div class="footer">
            <div>
                <strong>Tim Klinik Online</strong><br>
                Melayani kesehatan Anda dengan sepenuh hati
            </div>
            
            <div class="contact-info">
                📧 Email: support@klinikonline.com<br>
                📞 Telepon: (021) 1234-5678<br>
                🌐 Website: www.klinikonline.com
            </div>

            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Instagram</a> |
                <a href="#">Twitter</a>
            </div>

            <div style="margin-top: 20px; font-size: 11px; color: #999;">
                Email ini dikirim secara otomatis, mohon tidak membalas email ini.<br>
                © {{ date('Y') }} Klinik Online. Semua hak dilindungi.
            </div>
        </div>
    </div>
</body>
</html>