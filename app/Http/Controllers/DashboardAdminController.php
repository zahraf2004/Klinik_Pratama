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

        return view('adminDashboard.DashboardAdmin', compact('totalNakes', 'listNakes', 'totalObat','totalJanji'));
    }    
}