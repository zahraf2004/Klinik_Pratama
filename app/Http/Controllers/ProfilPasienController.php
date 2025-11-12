<?php

namespace App\Http\Controllers;

use App\Models\Profil_Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilPasienController extends Controller
{
    // Tampilkan profil pasien
    public function show()
    {
        $user = Auth::user();
        
        // Pastikan hanya pasien yang bisa akses
        if (!$user->isPasien()) {
            abort(403, 'Unauthorized access.');
        }

        // Dapatkan atau buat profil pasien
        $profilPasien = $user->getProfilPasien();
        
        return view('profilPasien.profil', compact('profilPasien'));
    }

    // Tampilkan form edit
    public function edit()
    {
        $user = Auth::user();
        
        if (!$user->isPasien()) {
            abort(403, 'Unauthorized access.');
        }

        $profilPasien = $user->getProfilPasien();
        
        return view('profilPasien.edit-profil', compact('profilPasien'));
    }

    // Update profil
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isPasien()) {
            abort(403, 'Unauthorized access.');
        }

        $profilPasien = $user->getProfilPasien();

        // Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:15',
            'tanggal_lahir' => 'nullable|date|before:today',
            'alamat' => 'nullable|string|max:500',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'berat_badan' => 'nullable|numeric|min:1|max:300',
            'tinggi_badan' => 'nullable|numeric|min:50|max:250',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Update user data
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email']
            ]);

            // Handle file upload
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($profilPasien->foto && Storage::exists('public/' . $profilPasien->foto)) {
                    Storage::delete('public/' . $profilPasien->foto);
                }
                
                // Simpan foto baru
                $fotoPath = $request->file('foto')->store('patient-photos', 'public');
                $validated['foto'] = $fotoPath;
            }

            // Update patient profile - hapus field name dan email karena sudah diupdate di user
            unset($validated['name'], $validated['email']);
            $profilPasien->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}