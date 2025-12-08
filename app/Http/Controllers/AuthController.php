<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); 
    }
    public function login(Request $request)
    {
        $login = $request->input('email');
        $password = $request->input('password');
        $loginField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'hp';
        
        // Cek apakah login dengan email
        if ($loginField === 'email') {
            // Coba login dengan email
            if (Auth::attempt(['email' => $login, 'password' => $password])) {
                $user = Auth::user();
                return $this->redirectBasedOnRole($user);
            }
        } else {
            // Coba cari user berdasarkan nomor HP di tabel tenaga_kesehatan
            $tenagaKesehatan = \App\Models\TenagaKesehatan::where('hp', $login)->first();
            
            if ($tenagaKesehatan && $tenagaKesehatan->user_id) {
                $user = \App\Models\User::find($tenagaKesehatan->user_id);
                
                if ($user && Hash::check($password, $user->password)) {
                    Auth::login($user);
                    return $this->redirectBasedOnRole($user);
                }
            }
        }

        // Tentukan pesan error yang spesifik
        $errorField = 'email';
        $errorMessage = 'Email atau nomor HP tidak ditemukan.';
        
        // Cek apakah user dengan email/hp ada tapi password salah
        if ($loginField === 'email') {
            $userExists = \App\Models\User::where('email', $login)->exists();
            if ($userExists) {
                $errorField = 'password';
                $errorMessage = 'Password yang Anda masukkan salah.';
            }
        } else {
            $userExists = \App\Models\TenagaKesehatan::where('hp', $login)->exists();
            if ($userExists) {
                $errorField = 'password';
                $errorMessage = 'Password yang Anda masukkan salah.';
            }
        }
        
        return back()->withErrors([
            $errorField => $errorMessage,
        ])->withInput(['email' => $login]);
    }    

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
    /**
     * Send reset password link to email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);
        
        // Store OTP in session (in production, store in database with expiry)
        session([
            'reset_email' => $request->email,
            'reset_otp' => $otp,
            'otp_created_at' => now()
        ]);

        // TODO: Send OTP via email
        // For now, just redirect to OTP page
        return redirect('/otp-reset')->with('status', 'Kode OTP telah dikirim ke email Anda. (Untuk testing, kode OTP: ' . $otp . ')');
    }

    /**
     * Verify OTP and reset password
     */
    public function verifyOtpAndResetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ], [
            'otp.required' => 'Kode OTP harus diisi.',
            'otp.digits' => 'Kode OTP harus 6 digit.',
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Check if OTP matches
        if ($request->otp != session('reset_otp')) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->withInput();
        }

        // Check if OTP expired (10 minutes)
        $otpCreatedAt = session('otp_created_at');
        if ($otpCreatedAt && now()->diffInMinutes($otpCreatedAt) > 10) {
            return back()->withErrors(['otp' => 'Kode OTP telah kadaluarsa. Silakan kirim ulang.'])->withInput();
        }

        // Update password
        $user = \App\Models\User::where('email', session('reset_email'))->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Clear session
            session()->forget(['reset_email', 'reset_otp', 'otp_created_at']);

            return redirect('/login')->with('status', 'Password berhasil direset. Silakan login dengan password baru Anda.');
        }

        return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }

    /**
     * Resend OTP
     */
    public function resendOtp()
    {
        if (!session('reset_email')) {
            return redirect('/reset-password')->with('error', 'Sesi telah berakhir. Silakan mulai dari awal.');
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        
        session([
            'reset_otp' => $otp,
            'otp_created_at' => now()
        ]);

        // TODO: Send OTP via email
        return redirect('/otp-reset')->with('status', 'Kode OTP baru telah dikirim. (Untuk testing, kode OTP: ' . $otp . ')');
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($user)
    {
        session()->flash('login_success', 'Selamat datang, ' . $user->name);

        if ($user->role === 'admin') {
            return redirect('/dashboard-admin');
        }

        if (in_array($user->role, ['dokter', 'bidan', 'perawat'])) {
            return redirect('/nakes/dashboard');
        }

        return redirect('/dashboard');
    }
}
