<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DataPasienController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'pasien')
            ->with(['subscriptions' => function($q) {
                $q->where('status', 'active')
                  ->where('expires_at', '>', now());
            }]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'langganan') {
                $query->whereHas('subscriptions', function($q) {
                    $q->where('status', 'active')
                      ->where('expires_at', '>', now());
                });
            } elseif ($request->status == 'reguler') {
                $query->whereDoesntHave('subscriptions', function($q) {
                    $q->where('status', 'active')
                      ->where('expires_at', '>', now());
                });
            }
        }

        $pasiens = $query->latest()->paginate(10);

        return view('adminDatapasien.DataPasien', compact('pasiens'));
    }
}