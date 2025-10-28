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
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($user)
    {
        // Simpan pesan sukses di session
        session()->flash('login_success', 'Selamat datang, ' . $user->name);
        
        if ($user->role === 'admin') {
            return redirect('/dashboard-admin');
        } elseif ($user->role === 'dokter') {
            return redirect('/dashboard');
        } elseif ($user->role === 'bidan') {
            return redirect('/dashboad');
        } else {
            return redirect('/dashboard');
        }
    }
}
