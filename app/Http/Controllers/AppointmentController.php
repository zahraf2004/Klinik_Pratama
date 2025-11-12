<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // === Menampilkan janji milik user yang login ===
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $appointments = Appointment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($appointments);
    }

    // === Menyimpan janji baru ===
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'keluhan' => 'required|string',
        ]);

        $appointment = Appointment::create([
            'user_id' => Auth::id(), // ✅ otomatis ambil ID user yang login
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'keluhan' => $request->keluhan,
            'status' => 'Menunggu',
        ]);

        return response()->json($appointment);
    }

    // === Menampilkan satu janji ===
    public function show(string $id)
    {
        $appointment = Appointment::findOrFail($id);

        // ✅ pastikan user hanya bisa lihat janjinya sendiri
        if ($appointment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        return response()->json($appointment);
    }

    // === Update data janji ===
    public function update(Request $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        // hanya bisa ubah kalau masih menunggu
        if ($appointment->status !== 'Menunggu') {
            return response()->json(['message' => 'Janji tidak dapat diubah karena sudah divalidasi'], 400);
        }

        $appointment->update($request->only([
            'nama', 'no_hp', 'alamat', 'tanggal', 'jam', 'keluhan'
        ]));

        return response()->json($appointment);
    }

    // === Hapus janji ===
    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $appointment->delete();

        return response()->json(['message' => 'Janji berhasil dihapus']);
    }
}
