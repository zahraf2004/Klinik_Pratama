<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaKesehatan;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $totalNakes = TenagaKesehatan::count();
        $listNakes = TenagaKesehatan::latest()->take(10)->get(); // ambil 10 terbaru

        return view('adminDashboard.DashboardAdmin', compact('totalNakes', 'listNakes'));
    }
}