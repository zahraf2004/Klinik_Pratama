<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    /**
     * Show reset password form
     */
    public function showResetForm()
    {
        return view('auth.resetpw');
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm(Request $request)
    {
        // Get email from request parameter or session
        $email = $request->get('email', session('reset_email'));
        
        if (!$email) {
            return redirect('/reset-password')->with('error', 'Sesi telah berakhir. Silakan mulai dari awal.');
        }
        
        // Store email in session for form
        session(['reset_email' => $email]);
        
        return view('auth.otpreset');
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
        
        // Get user data
        $user = \App\Models\User::where('email', $request->email)->first();
        
        // Store OTP in database with expiry
        $result = \App\Models\ResetPasswordToken::create([
            'reset_email' => $request->email,
            'reset_otp' => $otp,
            'expires_at' => now()->addMinutes(15), // 15 menit sesuai template email
        ]);

        // Store email in session
        session(['reset_email' => $request->email]);

        // Send OTP via email
        // try {
            Log::info($otp);
            Mail::to($request->email)->send(new ResetPassword($user, $otp));
            
            return redirect('/otp-reset')->with('status', 'Kode OTP telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.');
        // } catch (\Exception $e) {
        //     // Log error untuk debugging
        //     \Log::error('Failed to send reset password email: ' . $e->getMessage());
            
        //     // Fallback untuk development/testing
        //     return redirect('/otp-reset')->with('status', 'Kode OTP telah dikirim ke email Anda. (Untuk testing, kode OTP: ' . $otp . ')');
        // }
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

        // Find valid OTP token
        $resetToken = \App\Models\ResetPasswordToken::where('reset_otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetToken) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau telah kadaluarsa.'])->withInput();
        }

        // Update password
        $user = \App\Models\User::where('email', $resetToken->reset_email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete used token
            $resetToken->delete();

            return redirect('/login')->with('status', 'Password berhasil direset. Silakan login dengan password baru Anda.');
        }

        return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Delete old tokens for this email
        \App\Models\ResetPasswordToken::where('reset_email', $request->email)->delete();

        // Generate new OTP
        $otp = rand(100000, 999999);
        
        // Get user data
        $user = \App\Models\User::where('email', $request->email)->first();
        
        // Store new OTP in database
        \App\Models\ResetPasswordToken::create([
            'reset_email' => $request->email,
            'reset_otp' => $otp,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Send OTP via email
        try {
            Mail::to($request->email)->send(new ResetPassword($user, $otp));
            
            return redirect('/otp-reset')->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            \Log::error('Failed to resend reset password email: ' . $e->getMessage());
            
            return redirect('/otp-reset')->with('status', 'Kode OTP baru telah dikirim. (Untuk testing, kode OTP: ' . $otp . ')');
        }
    }

    /**
     * Test email sending (for development only)
     */
    public function testEmail()
    {
        if (app()->environment('production')) {
            abort(404);
        }

        $user = \App\Models\User::first();
        $otp = '123456';

        try {
            Mail::to('test@example.com')->send(new ResetPassword($user, $otp));
            return response()->json(['status' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Clean up expired reset tokens
     */
    public function cleanupExpiredTokens()
    {
        \App\Models\ResetPasswordToken::where('expires_at', '<', now())->delete();
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
