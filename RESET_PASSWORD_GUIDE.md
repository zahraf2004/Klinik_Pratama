# Reset Password dengan OTP - Panduan

## Fitur yang Sudah Dibuat

### 1. Template Email
- **HTML Template**: `resources/views/emails/reset-password.blade.php` - Template email yang lengkap dan responsif
- **Simple Template**: `resources/views/emails/reset-password-simple.blade.php` - Template sederhana sebagai alternatif
- **Text Template**: `resources/views/emails/reset-password-text.blade.php` - Template text untuk fallback

### 2. Mail Class
- **File**: `app/Mail/ResetPassword.php`
- **Variabel**: `$user` dan `$otp` tersedia di template
- **Subject**: "Kode OTP Reset Password - Klinik Online"
- **From**: Menggunakan konfigurasi dari .env

### 3. Controller Methods
- **sendResetLinkEmail()**: Mengirim OTP ke email
- **verifyOtpAndResetPassword()**: Verifikasi OTP dan reset password
- **resendOtp()**: Kirim ulang OTP
- **showResetForm()**: Tampilkan form reset password
- **showOtpForm()**: Tampilkan form verifikasi OTP
- **testEmail()**: Test pengiriman email (development only)

### 4. Database
- **Model**: `ResetPasswordToken`
- **Table**: `reset_password_tokens`
- **Fields**: `reset_email`, `reset_otp`, `expires_at`
- **Expiry**: 15 menit

### 5. Routes
```php
Route::get('/reset-password', [AuthController::class, 'showResetForm']);
Route::post('/reset-password', [AuthController::class, 'sendResetLinkEmail']);
Route::get('/otp-reset', [AuthController::class, 'showOtpForm']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtpAndResetPassword']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::get('/test-email', [AuthController::class, 'testEmail']); // Development only
```

## Konfigurasi Email

Pastikan konfigurasi email di `.env` sudah benar:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=zahra.f.andiani@gmail.com
MAIL_PASSWORD=efnhetgpvmycibno
MAIL_FROM_ADDRESS="zahra.f.andiani@gmail.com"
MAIL_FROM_NAME="Klinik Pratama Dokter Yanti"
```

## Cara Testing

1. **Test Email**: Kunjungi `/test-email` untuk test pengiriman email
2. **Reset Password**: 
   - Kunjungi `/reset-password`
   - Masukkan email yang terdaftar
   - Cek email untuk kode OTP
   - Masukkan OTP dan password baru di `/otp-reset`

## Fitur Keamanan

- ✅ OTP 6 digit random
- ✅ Expiry 15 menit
- ✅ Token disimpan di database (bukan session)
- ✅ Token dihapus setelah digunakan
- ✅ Validasi email exists
- ✅ Password confirmation
- ✅ Error handling untuk email gagal kirim

## Template Email Features

- 📧 Responsive design
- 🎨 Professional styling
- ⚠️ Security warnings
- 📱 Mobile-friendly
- 🔒 OTP highlighting
- ⏰ Expiry information
- 📞 Contact information