<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password OTP</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h2 style="color: #007bff; margin: 0;">🏥 Klinik Online</h2>
        <p style="margin: 5px 0 0 0; color: #6c757d;">Reset Password</p>
    </div>

    <div style="background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 8px;">
        <h3>Halo {{ $user->name ?? 'Pengguna' }},</h3>
        
        <p>Anda telah meminta untuk mereset password akun Anda. Gunakan kode OTP berikut:</p>
        
        <div style="background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px; margin: 20px 0;">
            <h2 style="margin: 0; font-size: 32px; letter-spacing: 5px; font-family: monospace;">{{ $otp }}</h2>
            <p style="margin: 10px 0 0 0; font-size: 14px;">Berlaku selama 15 menit</p>
        </div>

        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Perhatian:</strong> Jangan bagikan kode ini kepada siapa pun. Jika Anda tidak meminta reset password, abaikan email ini.
        </div>

        <p>Terima kasih,<br><strong>Tim Klinik Online</strong></p>
    </div>

    <div style="text-align: center; margin-top: 20px; color: #6c757d; font-size: 12px;">
        <p>© {{ date('Y') }} Klinik Online. Email otomatis, jangan dibalas.</p>
    </div>

</body>
</html>