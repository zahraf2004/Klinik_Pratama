<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Klinik Dokter Yanti</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-image">
                <img src="{{ asset('img/ilustrasi2.png') }}" alt="Doctor Illustration">
            </div>
            <div class="login-form">
                <div class="form-header">
                    <h2>Reset Password</h2>
                    <p>Masukkan email Anda untuk reset password</p>
                </div>

                <div class="reset-info">
                    <div class="info-box">
                        <i class="fa-solid fa-circle-info"></i>
                        <p>Kami akan mengirimkan kode OTP reset password ke email Anda. Silakan periksa email anda.</p>
                    </div>
                </div>

                <form action="/reset-password" method="POST">
                    @csrf
                    <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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

                    @if (session('status'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: '{{ session('status') }}',
                                    confirmButtonText: 'OK'
                                });
                            });
                        </script>
                    @endif

                    <div class="form-group">
                        <button type="submit" class="btn-login">
                            Kirim Link Reset Password
                        </button>
                    </div>

                    <div class="signin">                            
                        <p><a href="/login">Kembali ke Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>