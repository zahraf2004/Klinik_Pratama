<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaKesehatan;

class TelemedicineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil hanya tenaga kesehatan dengan role dokter_umum
        $nakes = TenagaKesehatan::where('role', 'dokter_umum')
            ->when($search, function ($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('str', 'like', "%{$search}%")
                      ->orWhere('sip', 'like', "%{$search}%");
                });
            })
            ->paginate(6);

        return view('konsultasi.konsultasiNakes', compact('nakes', 'search'));
    }
}
