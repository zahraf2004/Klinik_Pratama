<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik Dokter Yanti</title>
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
                    <h2>Klinik Pratama Dokter Yanti</h2>
                    <p>Murah, Nyaman, Sehat</p>
                </div>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="" required autocomplete="email" autofocus placeholder="Email">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                    </div>

                    <div class="form-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="fa-solid fa-eye-slash" id="toggleIcon"></i>
                        </span>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                    </div>
                    @if ($errors->any())
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                @foreach ($errors->all() as $error)
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal Login',
                                        text: '{{ $error }}',
                                        confirmButtonText: 'Coba Lagi'
                                    });
                                @endforeach
                            });
                        </script>
                    @endif

                    <div class="form-group remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Ingat Saya?</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                Temporarily Passwordless
                            </a>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-login">
                            Masuk
                        </button>
                    </div>

                    <div class="social-login">
                        <p>Atau masuk dengan</p>
                        <div class="social-icons">
                            <a href="#" class="social-icon google" style="background-color: #E43636">
                                <i class="fa-brands fa-google"></i>
                            </a>
                            <a href="#" class="social-icon facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                    <div class="signin">                            
                        <a for="remember" href="/registrasi">Belum punya akun?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>