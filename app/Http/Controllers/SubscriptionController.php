<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeSubscription = $user->activeSubscription();
        $remainingFreeChats = $user->getRemainingFreeChats();
        
        return view('subscription.index', compact('activeSubscription', 'remainingFreeChats'));
    }

    public function plans()
    {
        $plans = [
            [
                'name' => 'monthly',
                'title' => 'Berlangganan Bulanan',
                'price' => 50000,
                'duration' => '1 Bulan',
                'features' => [
                    'Chat unlimited dengan dokter',
                    'Konsultasi 24/7',
                    'Riwayat chat tersimpan',
                    'Prioritas respon dokter'
                ]
            ],
            [
                'name' => 'yearly',
                'title' => 'Berlangganan Tahunan',
                'price' => 500000,
                'duration' => '12 Bulan',
                'features' => [
                    'Chat unlimited dengan dokter',
                    'Konsultasi 24/7',
                    'Riwayat chat tersimpan',
                    'Prioritas respon dokter',
                    'Hemat 2 bulan (Rp 100.000)'
                ],
                'popular' => true
            ]
        ];

        return view('subscription.plans', compact('plans'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,yearly'
        ]);

        $user = Auth::user();
        
        // Cek apakah sudah punya subscription aktif
        if ($user->hasActiveSubscription()) {
            return response()->json([
                'error' => 'Anda sudah memiliki subscription aktif'
            ], 400);
        }

        $plans = [
            'monthly' => ['price' => 50000, 'duration' => 1],
            'yearly' => ['price' => 500000, 'duration' => 12]
        ];

        $selectedPlan = $plans[$request->plan];
        
        // Redirect ke payment dengan data subscription
        return redirect()->route('payment.index', [
            'type' => 'subscription',
            'plan' => $request->plan,
            'amount' => $selectedPlan['price'],
            'description' => 'Berlangganan Chat Dokter - ' . ucfirst($request->plan)
        ]);
    }

    public function activate(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
            'plan' => 'required|in:monthly,yearly'
        ]);

        $transaction = Transaction::where('order_id', $request->transaction_id)
            ->where('user_id', Auth::id())
            ->where('transaction_status', 'settlement')
            ->first();

        if (!$transaction) {
            return response()->json([
                'error' => 'Transaction not found or not completed'
            ], 404);
        }

        $plans = [
            'monthly' => 1,
            'yearly' => 12
        ];

        $duration = $plans[$request->plan];
        $startsAt = now();
        $expiresAt = $startsAt->copy()->addMonths($duration);

        $subscription = Subscription::create([
            'user_id' => Auth::id(),
            'plan_name' => $request->plan,
            'price' => $transaction->gross_amount,
            'status' => 'active',
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'transaction_id' => $transaction->order_id
        ]);

        return response()->json([
            'success' => true,
            'subscription' => $subscription
        ]);
    }

    public function cancel(Request $request)
    {
        $subscription = Auth::user()->activeSubscription();
        
        if (!$subscription) {
            return redirect()->back()->with('error', 'Tidak ada subscription aktif');
        }

        $subscription->update(['status' => 'cancelled']);

        return redirect()->route('subscription.index')
            ->with('success', 'Subscription berhasil dibatalkan');
    }

    public function history()
    {
        $subscriptions = Auth::user()->subscriptions()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('subscription.history', compact('subscriptions'));
    }
}
