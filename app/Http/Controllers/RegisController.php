<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register'); 
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Simpan user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pasien', // default role pasien
        ]);

        // Auto login setelah register
        Auth::login($user);

        // Redirect ke dashboard / halaman lain
        return redirect('/dashboard-admin'); 
    }
}
