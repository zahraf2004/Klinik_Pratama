<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaKesehatan;

class TelemedicineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil semua tenaga kesehatan
        $nakes = TenagaKesehatan::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('profesi', 'like', "%{$search}%")
                  ->orWhere('alumnus', 'like', "%{$search}%");
        })
        ->paginate(6);

        return view('konsultasi.konsultasiNakes', compact('nakes', 'search'));
    }
}
