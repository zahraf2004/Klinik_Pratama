<?php

namespace App\Http\Controllers;

use App\Models\ProfilPasien;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

if (!function_exists('formatTanggalIndo')) {
    function formatTanggalIndo($date)
    {
        \Carbon\Carbon::setLocale('id');
        return \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y');
    }
}

class ProfilPasienController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user->isPasien()) {
            abort(403);
        }

        $profilPasien = ProfilPasien::firstOrCreate([
            'user_id' => $user->id
        ]);

        // ambil max 2 janji mendatang
        $appointments = $profilPasien->appointments()
        ->orderBy('tanggal', 'desc')
        ->take(2)
        ->get();

        // format tanggal indonesia
        $appointments->each(function ($item) {
            if ($item->tanggal) {
                $item->tanggal_indo = \Carbon\Carbon::parse($item->tanggal)
                    ->locale('id')
                    ->translatedFormat('l, d F Y'); // contoh: Senin, 20 Januari 2025
            } else {
                $item->tanggal_indo = '-';
            }
        });

                return view('profilPasien.profil', [
                    'profilPasien' => $profilPasien,
                    'appointments' => $appointments,
                    'user' => $user
                ]);
            }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user->isPasien()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $profilPasien = ProfilPasien::firstOrCreate([
            'user_id' => $user->id
        ]);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|max:15',
            'tanggal_lahir' => 'nullable|date|before:today',
            'alamat' => 'nullable|max:500',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'berat_badan' => 'nullable|numeric|min:1|max:300',
            'tinggi_badan' => 'nullable|numeric|min:50|max:250',
            'foto' => 'nullable|image|max:2048'
        ]);

        // Update user (nama + email)
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email']
        ]);

        // Upload foto
        if ($request->hasFile('foto')) {
            if ($profilPasien->foto && Storage::exists('public/' . $profilPasien->foto)) {
                Storage::delete('public/' . $profilPasien->foto);
            }

            $validated['foto'] = $request->file('foto')->store('patient-photos', 'public');
        }

        // jangan masuk ke tabel profil_pasien
        unset($validated['name'], $validated['email']);

        $profilPasien->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!'
        ]);
    }
}
