<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentAdminController extends Controller
{
    /**
     * Menampilkan semua data janji berobat untuk admin
     */
    public function index()
    {
        $appointments = Appointment::latest()->get();
        return view('adminAppointment.AppointmentAdmin', compact('appointments'));
    }

    /**
     * Update status dan catatan admin
     */
    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);
        return response()->json($appointment);
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'status' => 'required|string',
            'admin_notes' => 'nullable|string',
        ]);

        $appointment->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return response()->json(['success' => true, 'message' => 'Status janji berhasil diperbarui!']);
    }

    /**
     * Hapus janji (hanya jika status 'Dibatalkan' oleh admin)
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Pastikan status adalah Dibatalkan dan ada catatan admin (menandakan dibatalkan oleh admin)
        if ($appointment->status !== 'Dibatalkan' || empty($appointment->admin_notes)) {
            return response()->json(['success' => false, 'message' => 'Hapus hanya diperbolehkan jika janji dibatalkan oleh admin.'], 403);
        }

        $appointment->delete();

        return response()->json(['success' => true, 'message' => 'Janji berhasil dihapus oleh admin.']);
    }

}
