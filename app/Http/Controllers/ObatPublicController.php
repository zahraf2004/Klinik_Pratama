<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatPublicController extends Controller
{
    // Menampilkan 4 obat di dashboard
    public function index()
    {
        $obat = Obat::latest()->take(4)->get();
        return view('dashboard.dashboardUser', compact('obat'));
    }

    // Menampilkan semua obat (halaman daftar obat) + search + filter kategori
    public function all(Request $request)
    {
        $query = Obat::query();

        // Filter pencarian nama_obat atau bentuk
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('bentuk', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $obat = $query->latest()->paginate(12);

        return view('obat.Obat_all', compact('obat'));
    }

    // Menampilkan detail obat + rekomendasi
    public function show($id)
    {
        $obat = Obat::findOrFail($id);

        // ambil rekomendasi berdasar kategori
        $rekomendasi = Obat::where('kategori', $obat->kategori)
                            ->where('id', '!=', $obat->id)
                            ->inRandomOrder()
                            ->take(4)
                            ->get();

        // kalau jumlah rekomendasi kurang dari 4, tambahkan random lain
        if ($rekomendasi->count() < 4) {
            $excludeIds = $rekomendasi->pluck('id')->toArray();
            $excludeIds[] = $obat->id;

            $tambahan = Obat::whereNotIn('id', $excludeIds)
                            ->inRandomOrder()
                            ->take(4 - $rekomendasi->count())
                            ->get();

            $rekomendasi = $rekomendasi->concat($tambahan);
        }

        return view('obat.detailObat', compact('obat', 'rekomendasi'));
    }
}
 