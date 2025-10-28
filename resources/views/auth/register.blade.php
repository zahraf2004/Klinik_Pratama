<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik Dokter Yanti</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/regis.css') }}">
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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                             @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li> 
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
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
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password">
                        <span class="password-toggle" onclick="togglePassword('password_confirmation','toggleIcon2')">
                            <i class="fa-solid fa-eye-slash" id="toggleIcon2"></i>
                        </span>
                    </div>            

                    <div class="form-group">
                        <button type="submit" class="btn-login">
                            Daftar
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
                        <a href="/login">Sudah punya akun?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>