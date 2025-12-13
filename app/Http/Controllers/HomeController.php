<?php

namespace App\Http\Controllers;

use App\Models\TenagaKesehatan;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil hanya dokter_umum untuk ditampilkan di homepage
        $dokters = TenagaKesehatan::where('role', 'dokter_umum')
            ->latest()
            ->take(4)
            ->get();
            
        // Ambil reviews untuk ditampilkan di homepage
        $reviews = Review::with('user')->latest()->get();
            
        return view('home.homepage2', compact('dokters', 'reviews'));
    }
}
