<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenagaKesehatanRequest;
use App\Http\Requests\UpdateTenagaKesehatanRequest;
use App\Models\TenagaKesehatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;

class TenagaKesehatanController extends Controller
{
    public function index(Request $request)
    {
        $q = TenagaKesehatan::query()
            ->when($request->get('role'), fn($query, $r) => $query->where('role', $r))
            ->when($request->get('search'), function ($query, $s) {
                $query->where(function ($q2) use ($s) {
                    $q2->where('nama', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%")
                    ->orWhere('hp', 'like', "%$s%")
                    ->orWhere('str', 'like', "%$s%")
                    ->orWhere('sip', 'like', "%$s%");
                });
            })
            ->latest();

        // === Bagian ini khusus untuk response AJAX (JSON) ===
        if ($request->ajax() || $request->wantsJson()) {
            $data = $q->get();
            return response()->json($data);
        }

        // === Kalau bukan AJAX, kirim ke view biasa ===
        $q = $q->paginate(10)->withQueryString();
        return view('tenaga_kesehatan.index', compact('q'));
    }

    public function create()
    {
        return view('tenaga_kesehatan.create');
    }

    public function store(StoreTenagaKesehatanRequest $request)
    {
        $data = $request->validated();

        // Upload + Resize Foto
        if ($request->hasFile('foto')) {
            // Simpan langsung ke storage/app/public/tenaga_kesehatan
            $data['foto_path'] = $request->file('foto')->store('tenaga_kesehatan', 'public');
        }

        // Handle jadwal_shift dari JSON string
        if ($request->has('jadwal_shift')) {
            $data['jadwal_shift'] = is_string($request->jadwal_shift) 
                ? json_decode($request->jadwal_shift, true) 
                : $request->jadwal_shift;
        }

        $tk = TenagaKesehatan::create($data);

        // Otomatis buat akun user
        $existingUser = User::where('email', $tk->email)->first();
        if (!$existingUser) {
            // Map role tenaga kesehatan ke role user
            $userRole = $tk->role === 'dokter_umum' ? 'dokter' : $tk->role;
            
            $user = User::create([
                'name'     => $tk->nama,
                'email'    => $tk->email,
                'password' => bcrypt($tk->hp), // nomor HP jadi password
                'role'     => $userRole,
            ]);

            $tk->update(['user_id' => $user->id]);

            // Simpan info akun baru untuk ditampilkan ke admin
            session()->flash('akun_baru', [
                'email'    => $user->email,
                'password' => $tk->hp, // password asli = nomor HP
                'role'     => $user->role,
            ]);
        }

        return redirect()->route('tenaga-kesehatan.index')->with('success', 'Data tenaga kesehatan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $tk = TenagaKesehatan::findOrFail($id);
        return response()->json($tk);
    }

    public function edit(TenagaKesehatan $tenaga_kesehatan)
    {
        return view('tenaga_kesehatan.edit', ['tenagaKesehatan' => $tenaga_kesehatan]);
    }

    public function update(UpdateTenagaKesehatanRequest $request, TenagaKesehatan $tenaga_kesehatan)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            // Hapus foto lama kalau ada
            if ($tenaga_kesehatan->foto_path) {
                Storage::disk('public')->delete($tenaga_kesehatan->foto_path);
            }

            // Simpan foto baru
            $data['foto_path'] = $request->file('foto')->store('tenaga_kesehatan', 'public');
        }

        // Handle jadwal_shift dari JSON string
        if ($request->has('jadwal_shift')) {
            $data['jadwal_shift'] = is_string($request->jadwal_shift) 
                ? json_decode($request->jadwal_shift, true) 
                : $request->jadwal_shift;
        }

        $tenaga_kesehatan->update($data);

        if ($tenaga_kesehatan->user) {
            // Map role tenaga kesehatan ke role user
            $userRole = $tenaga_kesehatan->role === 'dokter_umum' ? 'dokter' : $tenaga_kesehatan->role;
            
            $tenaga_kesehatan->user->update([
                'name'     => $tenaga_kesehatan->nama,
                'email'    => $tenaga_kesehatan->email,
                'role'     => $userRole,
                'password' => bcrypt($tenaga_kesehatan->hp), // sync password dengan hp
            ]);
        }

        return redirect()->route('tenaga-kesehatan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(TenagaKesehatan $tenaga_kesehatan)
    {
        // Hapus foto kalau ada
        if ($tenaga_kesehatan->foto_path) {
            Storage::disk('public')->delete($tenaga_kesehatan->foto_path);
        }

        // Hapus user terkait
        if ($tenaga_kesehatan->user) {
            $tenaga_kesehatan->user->forceDelete(); // hapus permanen user
        }

        // Hapus tenaga kesehatan permanen juga
        $tenaga_kesehatan->forceDelete();

        return redirect()->route('tenaga-kesehatan.index')->with('success', 'Data berhasil dihapus.');
    }

}
