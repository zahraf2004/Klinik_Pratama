<?php

namespace App\Http\Controllers;

use App\Models\TenagaKesehatan;

class HomeController extends Controller
{
    public function index()
    {
        $dokters = TenagaKesehatan::latest()->take(4)->get();
        return view('home.homepage2', compact('dokters'));
    }
}
