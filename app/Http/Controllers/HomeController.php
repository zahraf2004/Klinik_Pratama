<?php

namespace App\Http\Controllers;

use App\Models\TenagaKesehatan;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil hanya dokter_umum untuk ditampilkan di homepage
        $dokters = TenagaKesehatan::where('role', 'dokter_umum')
            ->latest()
            ->take(4)
            ->get();
            
        return view('home.homepage2', compact('dokters'));
    }
}
