<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaKesehatan;
use App\Models\Obat;
use App\Models\Appointment;

class DashboardDokterController extends Controller
{
    public function dokter()
    {
        $totalNakes = TenagaKesehatan::count();
        $totalJanji = Appointment::count();
        $totalObat = Obat::count();

        return view('dokter.DashboardDokter', compact('totalNakes', 'totalObat','totalJanji'));
    }
}
