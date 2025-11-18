<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentDokterController extends Controller
{
    /**
     * Daftar janji yang bisa dilihat dokter
     */
    public function index()
    {
        // Jika belum ada relasi dokter -> tampilkan hanya status disetujui
        $appointments = Appointment::where('status', 'Disetujui')
                                   ->latest()
                                   ->get();

        return view('dokter.JanjiTemuDokter', compact('appointments'));
    }

    /**
     * Dokter hanya bisa melihat detail janji
     */
    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);
        return response()->json($appointment);
    }
}
