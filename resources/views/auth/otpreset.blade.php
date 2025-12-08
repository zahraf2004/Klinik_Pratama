<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Klinik Dokter Yanti</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/otp.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-image">
                <img src="{{ asset('img/ilustrasi2.png') }}" alt="Doctor Illustration">
            </div>
            <div class="login-form">

                <div class="reset-info">
                    <div class="info-box">
                        <i class="fa-solid fa-envelope"></i>
                        <p>Kode OTP telah dikirim ke <strong>{{ session('reset_email', 'email@example.com') }}</strong></p>
                    </div>
                </div>

                <form action="/verify-otp" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('reset_email') }}">
                    
                    <div class="form-group">
                        <label for="otp" class="form-label">Kode OTP</label>
                        <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" required maxlength="6" placeholder="Masukkan 6 digit kode OTP">
                        @error('otp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Masukkan password baru">
                        <span class="password-toggle" onclick="togglePassword('password','toggleIcon1')">
                            <i class="fa-solid fa-eye-slash" id="toggleIcon1"></i>
                        </span>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required placeholder="Ketik ulang password baru">
                        <span class="password-toggle" onclick="togglePassword('password_confirmation','toggleIcon2')">
                            <i class="fa-solid fa-eye-slash" id="toggleIcon2"></i>
                        </span>
                    </div>

                    @if ($errors->any())
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                @foreach ($errors->all() as $error)
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: '{{ $error }}',
                                        confirmButtonText: 'OK'
                                    });
                                @endforeach
                            });
                        </script>
                    @endif

                    @if (session('error'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: '{{ session('error') }}',
                                    confirmButtonText: 'OK'
                                });
                            });
                        </script>
                    @endif

                    @if (session('status'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Informasi',
                                    text: '{{ session('status') }}',
                                    confirmButtonText: 'OK'
                                });
                            });
                        </script>
                    @endif

                    <div class="form-group">
                        <button type="submit" class="btn-login">
                            Reset Password
                        </button>
                    </div>

                    <div class="otp-resend">
                        <p>Tidak menerima kode OTP?</p>
                        <a href="/resend-otp" class="resend-link">Kirim Ulang Kode</a>
                    </div>

                    <div class="signin">                            
                        <p><a href="/login">Kembali ke Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
    <script>
        // Auto focus on OTP input
        document.getElementById('otp').focus();
        
        // Only allow numbers in OTP field
        document.getElementById('otp').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
