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

        // Data untuk grafik - janji berobat selesai per bulan (12 bulan terakhir)
        $chartData = $this->getChartData();

        return view('adminDashboard.DashboardAdmin', compact('totalNakes', 'listNakes', 'totalObat','totalJanji', 'janjiTerbaru', 'logAktivitas', 'chartData'));
    }

    private function getChartData()
    {
        $months = [];
        $data = [];
        
        // Generate 12 bulan terakhir
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y'); // Jan 2025, Feb 2025, etc
            $months[] = $monthName;
            
            // Hitung janji berobat yang selesai di bulan tersebut
            $count = Appointment::where('status', 'Selesai')
                ->whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->count();
                
            $data[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }    
}