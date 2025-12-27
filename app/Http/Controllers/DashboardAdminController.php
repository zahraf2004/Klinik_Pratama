<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaKesehatan;
use App\Models\Obat;
use App\Models\Appointment;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $totalNakes = TenagaKesehatan::count();
        $totalJanji = Appointment::count();
        $totalObat = Obat::count();
        $listNakes = TenagaKesehatan::latest()->take(10)->get(); // ambil 10 terbaru
        
        // Ambil 3 janji berobat terbaru dengan relasi user
        $janjiTerbaru = Appointment::with('user')
            ->latest('created_at')
            ->take(3)
            ->get();

        // Ambil 4 log aktivitas terbaru
        $logAktivitas = \App\Models\ActivityLog::with('user')
            ->latest('created_at')
            ->take(4)
            ->get();

        return view('adminDashboard.DashboardAdmin', compact('totalNakes', 'listNakes', 'totalObat','totalJanji', 'janjiTerbaru', 'logAktivitas'));
    }    
}