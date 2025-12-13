<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Subscription;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index(Request $request)
    {
        // Cek apakah ini untuk subscription
        $isSubscription = $request->get('type') === 'subscription';
        $subscriptionData = null;
        
        if ($isSubscription) {
            $subscriptionData = [
                'plan' => $request->get('plan'),
                'amount' => $request->get('amount'),
                'description' => $request->get('description')
            ];
        }
        
        return view('payment.index', compact('isSubscription', 'subscriptionData'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|max:255',
            'type' => 'nullable|string',
            'plan' => 'nullable|string'
        ]);

        $orderId = 'ORDER-' . time() . '-' . Str::random(6);
        
        // Create transaction record
        $transaction = Transaction::create([
            'order_id' => $orderId,
            'user_id' => Auth::id(),
            'gross_amount' => $request->amount,
            'description' => $request->description,
            'transaction_status' => 'pending'
        ]);

        // Prepare transaction details for Midtrans
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => (int) $request->amount,
        ];

        $itemDetails = [
            [
                'id' => 'item-1',
                'price' => (int) $request->amount,
                'quantity' => 1,
                'name' => $request->description
            ]
        ];

        $customerDetails = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ];

        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($transactionData);
            
            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'type' => $request->type,
                'plan' => $request->plan
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment initialization failed'], 500);
        }
    }

    public function webhook(Request $request)
    {
        try {
            // Log incoming webhook untuk debugging
            Log::info('Midtrans Webhook Received', $request->all());

            // Verifikasi signature dari Midtrans untuk keamanan
            $serverKey = config('midtrans.server_key');
            $orderId = $request->order_id;
            $statusCode = $request->status_code;
            $grossAmount = $request->gross_amount;
            $signatureKey = $request->signature_key;
            
            $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            
            if ($calculatedSignature !== $signatureKey) {
                Log::error('Invalid Midtrans signature', [
                    'order_id' => $orderId,
                    'received_signature' => $signatureKey,
                    'calculated_signature' => $calculatedSignature
                ]);
                return response()->json(['message' => 'Invalid signature'], 401);
            }

            // Ambil data dari webhook
            $transactionStatus = $request->transaction_status;
            $fraudStatus = $request->fraud_status ?? null;
            $paymentType = $request->payment_type ?? null;
            $transactionId = $request->transaction_id ?? null;

            // Cari transaction di database
            $transaction = Transaction::where('order_id', $orderId)->first();
            
            if (!$transaction) {
                Log::error('Transaction not found for webhook', ['order_id' => $orderId]);
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Simpan raw webhook data
            $webhookData = $request->all();

            // Update transaction berdasarkan status
            switch ($transactionStatus) {
                case 'capture':
                    // Untuk credit card, cek fraud_status
                    if ($fraudStatus == 'challenge') {
                        $transaction->update([
                            'transaction_status' => 'challenge',
                            'transaction_id' => $transactionId,
                            'payment_type' => $paymentType,
                            'fraud_status' => $fraudStatus,
                            'midtrans_response' => $webhookData
                        ]);
                    } else if ($fraudStatus == 'accept') {
                        $transaction->update([
                            'transaction_status' => 'settlement',
                            'transaction_id' => $transactionId,
                            'payment_type' => $paymentType,
                            'fraud_status' => $fraudStatus,
                            'midtrans_response' => $webhookData
                        ]);
                        
                        // Aktivasi subscription jika pembayaran berhasil
                        $this->activateSubscription($transaction);
                    }
                    break;

                case 'settlement':
                    $transaction->update([
                        'transaction_status' => 'settlement',
                        'transaction_id' => $transactionId,
                        'payment_type' => $paymentType,
                        'fraud_status' => $fraudStatus,
                        'midtrans_response' => $webhookData
                    ]);
                    
                    // Aktivasi subscription jika pembayaran berhasil
                    $this->activateSubscription($transaction);
                    
                    Log::info('Payment successful', ['order_id' => $orderId]);
                    break;

                case 'pending':
                    $transaction->update([
                        'transaction_status' => 'pending',
                        'transaction_id' => $transactionId,
                        'payment_type' => $paymentType,
                        'midtrans_response' => $webhookData
                    ]);
                    
                    Log::info('Payment pending', ['order_id' => $orderId]);
                    break;

                case 'deny':
                case 'cancel':
                case 'expire':
                case 'failure':
                    $transaction->update([
                        'transaction_status' => $transactionStatus,
                        'transaction_id' => $transactionId,
                        'payment_type' => $paymentType,
                        'fraud_status' => $fraudStatus,
                        'midtrans_response' => $webhookData
                    ]);
                    
                    Log::info('Payment failed', [
                        'order_id' => $orderId,
                        'status' => $transactionStatus
                    ]);
                    break;

                default:
                    Log::warning('Unknown transaction status', [
                        'order_id' => $orderId,
                        'status' => $transactionStatus
                    ]);
                    break;
            }

            return response()->json(['message' => 'Webhook processed successfully'], 200);
            
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    public function success($orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)->first();
        
        if (!$transaction) {
            Log::error('Transaction not found: ' . $orderId);
            return redirect()->route('payment.index')->with('error', 'Transaction not found');
        }

        Log::info('Success page accessed', [
            'order_id' => $orderId,
            'transaction_status' => $transaction->transaction_status,
            'description' => $transaction->description,
            'amount' => $transaction->gross_amount
        ]);

        // Jika ini adalah pembayaran subscription, aktifkan subscription
        if ($transaction->isSuccess()) {
            Log::info("Transaction is successful, activating subscription");
            $this->activateSubscription($transaction);
        } else {
            Log::warning('Transaction is not successful: ' . $transaction->transaction_status);
            
            // TEMPORARY FIX: Jika user sampai ke success page, anggap pembayaran berhasil
            // Ini karena Midtrans sandbox kadang webhook delay atau gak sampai
            if (strpos($transaction->description, 'Berlangganan') !== false) {
                Log::info("Force activating subscription for sandbox testing");
                
                // Update transaction status ke settlement
                $transaction->update(['transaction_status' => 'settlement']);
                
                // Aktivasi subscription
                $this->activateSubscription($transaction);
            }
        }

        return view('payment.success', compact('transaction'));
    }

    public function subscriptionSuccess($orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)->first();
        
        if (!$transaction) {
            return redirect()->route('payment.index')->with('error', 'Transaction not found');
        }

        // Aktivasi subscription jika belum
        if ($transaction->isSuccess()) {
            $this->activateSubscription($transaction);
        }

        // Auto-redirect ke chat setelah 3 detik
        return view('payment.subscription-success', compact('transaction'));
    }

    private function activateSubscription($transaction)
    {
        Log::info('Activating subscription for transaction: ' . $transaction->order_id);
        
        // Cek apakah subscription sudah ada untuk transaction ini
        $existingSubscription = \App\Models\Subscription::where('transaction_id', $transaction->order_id)->first();
        
        if ($existingSubscription) {
            Log::info('Subscription already exists for transaction: ' . $transaction->order_id);
            return; // Subscription sudah ada
        }

        // Cek apakah ini pembayaran subscription
        $isSubscription = strpos($transaction->description, 'Subscription') !== false || 
                         strpos($transaction->description, 'Berlangganan') !== false;
        
        if (!$isSubscription) {
            Log::info('Transaction is not a subscription: ' . $transaction->description);
            return; // Bukan pembayaran subscription
        }

        // Tentukan plan berdasarkan amount
        $plan = $transaction->gross_amount == 50000 ? 'monthly' : 'yearly';
        $duration = $plan === 'monthly' ? 1 : 12;
        
        $startsAt = now();
        $expiresAt = $startsAt->copy()->addMonths($duration);

        Log::info('Creating subscription', [
            'user_id' => $transaction->user_id,
            'plan' => $plan,
            'amount' => $transaction->gross_amount,
            'transaction_id' => $transaction->order_id
        ]);

        try {
            $subscription = \App\Models\Subscription::create([
                'user_id' => $transaction->user_id,
                'plan_name' => $plan,
                'price' => $transaction->gross_amount,
                'status' => 'active',
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'transaction_id' => $transaction->order_id
            ]);

            Log::info('Subscription created successfully: ' . $subscription->id);

            // Update semua chat sessions yang aktif menjadi premium
            $updatedSessions = \App\Models\ChatSession::where('patient_id', $transaction->user_id)
                ->where('is_active', true)
                ->update(['is_premium' => true]);

            Log::info('Updated chat sessions to premium: ' . $updatedSessions);
            
        } catch (\Exception $e) {
            Log::error('Error creating subscription: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    public function failed($orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)->first();
        
        if (!$transaction) {
            return redirect()->route('payment.index')->with('error', 'Transaction not found');
        }

        return view('payment.failed', compact('transaction'));
    }

    public function history()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payment.history', compact('transactions'));
    }

    // private function activateSubscription(Transaction $transaction)
    // {
    //     // Extract plan from description
    //     $plan = 'monthly'; // default
    //     if (strpos($transaction->description, 'yearly') !== false || strpos($transaction->description, 'Tahunan') !== false) {
    //         $plan = 'yearly';
    //     }

    //     $duration = $plan === 'yearly' ? 12 : 1;
    //     $startsAt = now();
    //     $expiresAt = $startsAt->copy()->addMonths($duration);

    //     Subscription::create([
    //         'user_id' => $transaction->user_id,
    //         'plan_name' => $plan,
    //         'price' => $transaction->gross_amount,
    //         'status' => 'active',
    //         'starts_at' => $startsAt,
    //         'expires_at' => $expiresAt,
    //         'transaction_id' => $transaction->order_id
    //     ]);

    //     Log::info('Subscription activated for user: ' . $transaction->user_id . ', plan: ' . $plan);
    // }
}
