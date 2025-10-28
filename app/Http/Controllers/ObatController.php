<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::query();

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->ajax()) {
            return response()->json($query->latest()->get());
        }

        return view('adminDataObat.DataObat');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_obat'    => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'bentuk'       => 'required|string|max:100',
            'klasifikasi'  => 'nullable|string|max:100',            
            'deskripsi'    => 'nullable|string',
            'dosis'        => 'nullable|string',  
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('obat', 'public');
        }

        $obat = Obat::create($data);

        return response()->json($obat, 201);
    }

    public function show($id)
    {
        $obat = Obat::findOrFail($id);
        return response()->json($obat);
    }

    public function update(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);

        $data = $request->validate([
            'nama_obat'    => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'bentuk'       => 'required|string|max:100',
            'klasifikasi'  => 'nullable|string|max:100',            
            'deskripsi'    => 'nullable|string',
            'dosis'        => 'nullable|string',   
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($obat->foto && Storage::disk('public')->exists($obat->foto)) {
                Storage::disk('public')->delete($obat->foto);
            }
            $data['foto'] = $request->file('foto')->store('obat', 'public');
        }

        $obat->update($data);

        return response()->json($obat);
    }

    public function destroy($id)
    {
        $obat = Obat::findOrFail($id);

        if ($obat->foto && Storage::disk('public')->exists($obat->foto)) {
            Storage::disk('public')->delete($obat->foto);
        }

        $obat->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
