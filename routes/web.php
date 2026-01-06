<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisController;
use App\Http\Controllers\TenagaKesehatanController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ObatPublicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentAdminController;
use App\Http\Controllers\AppointmentDokterController;
use App\Http\Controllers\ProfilPasienController;
use App\Http\Controllers\DashboardDokterController;
use Chatify\Chatify;
use App\Http\Controllers\TelemedicineController;
use App\Http\Controllers\DataPasienController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/registrasi', [RegisController::class, 'showRegisterForm'])->name('register');
Route::post('/registrasi', [RegisController::class, 'register']);

Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.request');
Route::post('/reset-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/otp-reset', [AuthController::class, 'showOtpForm'])->name('otp.reset');
Route::post('/verify-otp', [AuthController::class, 'verifyOtpAndResetPassword'])->name('otp.verify');

Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');

// Test email route (development only)
Route::get('/test-email', [AuthController::class, 'testEmail'])->name('test.email');

/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/
Route::get('/dashboard-admin', [DashboardAdminController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');


/*
|--------------------------------------------------------------------------
| Admin Only Routes (Group)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    /*
    |-----------------------------
    | Notifications (Admin)
    |-----------------------------
    */
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('index');
        Route::post('/mark-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('unread-count');
    });

    /*
    |-----------------------------
    | Data Nakes Admin
    |-----------------------------
    */
    Route::get('/admin/data-nakes', function () {
        return view('adminDataNakes.DataNakes');
    })->name('data-nakes.index');

    Route::resource('tenaga-kesehatan', TenagaKesehatanController::class)
        ->only(['index', 'store', 'update', 'destroy', 'show']);

    /*
    |-----------------------------
    | Data Obat Admin
    |-----------------------------
    */
    Route::get('/data-obat', function () {
        return view('adminDataObat.DataObat');
    })->name('data-obat.index');

    Route::resource('obat', ObatController::class)
        ->only(['index', 'store', 'update', 'destroy', 'show']);

    /*
    |-----------------------------
    | Janji Berobat (Appointment Admin)
    |-----------------------------
    */
    Route::get('/data-janji-berobat', [AppointmentAdminController::class, 'index'])
        ->name('appointment.admin');

    Route::get('/admin/appointments/{id}', [AppointmentAdminController::class, 'show'])
        ->name('appointment.admin.show');

    Route::post('/admin/appointments/update/{id}', [AppointmentAdminController::class, 'updateStatus'])
        ->name('appointment.admin.update');
    
    // Admin delete appointment (only allowed when canceled by admin)
    Route::delete('/admin/appointments/{id}', [AppointmentAdminController::class, 'destroy'])
        ->name('appointment.admin.destroy');
});


/*
|--------------------------------------------------------------------------
| Nakes Pages 
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dokter,bidan,perawat'])
    ->prefix('nakes')
    ->group(function () {

        Route::get('/janji-temu', [AppointmentDokterController::class, 'index']);
        Route::get('/janji-temu/{id}', [AppointmentDokterController::class, 'show']);
        
        /*
        |-----------------------------
        | Notifications (Dokter)
        |-----------------------------
        */
        Route::prefix('notifications')->name('dokter.notifications.')->group(function () {
            Route::get('/', [App\Http\Controllers\NotificationController::class, 'getNotificationsByRole'])->name('index');
            Route::post('/mark-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsReadByUser'])->name('mark-read');
            Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsReadByUser'])->name('mark-all-read');
            Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCountByUser'])->name('unread-count');
        });
    });
Route::get('/nakes/dashboard', [DashboardDokterController::class, 'dokter'])
    ->middleware(['auth', 'role:dokter,bidan,perawat'])
    ->name('dokter.Dashboard');

// API endpoints untuk dashboard dokter
Route::middleware(['auth', 'role:dokter,bidan,perawat'])->prefix('api/dokter')->name('api.dokter.')->group(function () {
    Route::get('/jadwal-hari-ini', [DashboardDokterController::class, 'getJadwalHariIniApi'])->name('jadwal.hari.ini');
    Route::get('/riwayat-janji-temu', [DashboardDokterController::class, 'getRiwayatJanjiTemuApi'])->name('riwayat.janji.temu');
    Route::get('/appointment/{id}', [DashboardDokterController::class, 'getDetailAppointment'])->name('appointment.detail');
    Route::get('/jadwal-range', [DashboardDokterController::class, 'getJadwalByDateRange'])->name('jadwal.range');
    Route::get('/statistik-mingguan', [DashboardDokterController::class, 'getStatistikMingguan'])->name('statistik.mingguan');
});
    
/*
|--------------------------------------------------------------------------
| Pasien Pages 
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |-----------------------------
    | Notifications (Pasien)
    |-----------------------------
    */
    Route::prefix('user-notifications')->name('user.notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'getNotificationsByRole'])->name('index');
        Route::post('/mark-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsReadByUser'])->name('mark-read');
        Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsReadByUser'])->name('mark-all-read');
        Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCountByUser'])->name('unread-count');
    });

    /*
    |--------------------------------------------------------------------------
    | Janji Berobat
    |--------------------------------------------------------------------------
    */
    Route::prefix('janji-berobat')->name('appointment.')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::get('/{id}', [AppointmentController::class, 'show'])->name('show');
        Route::put('/{id}', [AppointmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | Profil Pasien
    |--------------------------------------------------------------------------
    */
    Route::get('/profil', [ProfilPasienController::class, 'show'])->name('pasien.profil');
    Route::put('/profil/update', [ProfilPasienController::class, 'update'])->name('pasien.profil.update');


    /*
    |--------------------------------------------------------------------------
    | Konsultasi / Telemedicine
    |--------------------------------------------------------------------------
    */
    Route::get('/konsultasi', [TelemedicineController::class, 'index'])
        ->name('konsultasi.index');
});





/*
|--------------------------------------------------------------------------
| Other Pages akses harus login
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [ObatPublicController::class, 'index'])->name('obat.index');

Route::get('/obat-all', [ObatPublicController::class, 'all'])->name('obat.all');

Route::get('/obat-details/{id}', [ObatPublicController::class, 'show'])->name('obat.show');

Route::get('/Janji-Berobat', function(){
    return view('layanan.appointment');
});
Route::get('/Janji-Berobat/status', function(){
    return view('layanan.status');
});

//route chatify - hanya untuk dokter dan pasien
Route::group(['middleware' => ['auth', 'role:dokter,pasien']], function () {
    // Custom routes untuk override Chatify behavior
    Route::get('/chatify/getContacts', [\App\Http\Controllers\CustomChatifyController::class, 'getContacts']);
    Route::post('/chatify/updateContacts', [\App\Http\Controllers\CustomChatifyController::class, 'updateContacts']);
    Route::post('/chatify/getUserDetails', [\App\Http\Controllers\CustomChatifyController::class, 'getUserDetails']);
    
    // Override sendMessage route untuk notifikasi
    Route::post('/chatify/sendMessage', [\App\Http\Controllers\CustomMessagesController::class, 'send'])->name('send.message');
    
    // Chat session management (premium feature)
    Route::post('/chatify/getOrCreateSession', [\App\Http\Controllers\CustomChatifyController::class, 'getOrCreateSession']);
    Route::post('/chatify/incrementMessageCount', [\App\Http\Controllers\CustomChatifyController::class, 'incrementMessageCount']);
    Route::post('/chatify/endSession', [\App\Http\Controllers\CustomChatifyController::class, 'endSession']);
    Route::post('/chatify/checkChatPermission', [\App\Http\Controllers\CustomChatifyController::class, 'checkChatPermission']);
    
    // Default Chatify routes
    Route::get('/chatify', [\Chatify\Http\Controllers\MessagesController::class, 'index'])->name('chatify');
    Route::get('/chatify/{id}', [\Chatify\Http\Controllers\MessagesController::class, 'index'])->name('chatify.user');
});


/*
|--------------------------------------------------------------------------
| Other Pages bisa akses tanpa login
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tentang-kami', function () {
    return view('about.about_klinik');
});

Route::get('/kontak-kami', function () {
    return view('contact.kontak_kami');
});

Route::get('/layanan-kami', function(){
    return view('layanan.layanan_kami');
});

Route::get('/data-pasien', [\App\Http\Controllers\DataPasienController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('data.pasien');

Route::get('/coba', function () {
    return view('ujicoba');
});

/*
|--------------------------------------------------------------------------
| Payment Routes (Midtrans)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('payment')->name('payment.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PaymentController::class, 'index'])->name('index');
    Route::post('/process', [\App\Http\Controllers\PaymentController::class, 'process'])->name('process');
    Route::get('/success/{orderId}', [\App\Http\Controllers\PaymentController::class, 'success'])->name('success');
    Route::get('/subscription-success/{orderId}', [\App\Http\Controllers\PaymentController::class, 'subscriptionSuccess'])->name('subscription.success');
    Route::get('/failed/{orderId}', [\App\Http\Controllers\PaymentController::class, 'failed'])->name('failed');
    Route::get('/history', [\App\Http\Controllers\PaymentController::class, 'history'])->name('history');
});

// Midtrans webhook (tidak perlu auth dan CSRF)
Route::post('/payment/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

/*
|--------------------------------------------------------------------------
| Review Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('review')->name('review.')->group(function () {
    Route::post('/store', [\App\Http\Controllers\ReviewController::class, 'store'])->name('store');
});

// Public review routes (tidak perlu auth)
Route::prefix('api/reviews')->name('api.reviews.')->group(function () {
    Route::get('/homepage', [\App\Http\Controllers\ReviewController::class, 'getHomepageReviews'])->name('homepage');
    Route::get('/featured', [\App\Http\Controllers\ReviewController::class, 'getFeaturedReviews'])->name('featured');
    Route::get('/', [\App\Http\Controllers\ReviewController::class, 'index'])->name('index');
});

/*
|--------------------------------------------------------------------------
| Subscription Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('subscription')->name('subscription.')->group(function () {
    Route::get('/', [\App\Http\Controllers\SubscriptionController::class, 'index'])->name('index');
    Route::get('/plans', [\App\Http\Controllers\SubscriptionController::class, 'plans'])->name('plans');
    Route::post('/subscribe', [\App\Http\Controllers\SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::post('/activate', [\App\Http\Controllers\SubscriptionController::class, 'activate'])->name('activate');
    Route::post('/cancel', [\App\Http\Controllers\SubscriptionController::class, 'cancel'])->name('cancel');
    Route::get('/history', [\App\Http\Controllers\SubscriptionController::class, 'history'])->name('history');
});

/*
|--------------------------------------------------------------------------
| Demo Routes (untuk testing)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/demo/payment-modal', function () {
        return view('demo.payment-modal-demo');
    })->name('demo.payment-modal');
    
    Route::get('/demo/chat-payment', function () {
        return view('examples.chat-with-payment');
    })->name('demo.chat-payment');
    
    // Test webhook (development only)
    Route::get('/test-webhook/{orderId}', function($orderId) {
        if (!app()->environment('local')) {
            abort(404);
        }
        
        $serverKey = config('midtrans.server_key');
        $statusCode = '200';
        $grossAmount = '50000.00';
        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        
        $webhookData = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'transaction_id' => 'test-' . time(),
            'payment_type' => 'credit_card',
            'fraud_status' => 'accept'
        ];
        
        $response = \Illuminate\Support\Facades\Http::post(url('/payment/webhook'), $webhookData);
        
        return response()->json([
            'message' => 'Webhook test sent',
            'webhook_data' => $webhookData,
            'response_status' => $response->status(),
            'response_body' => $response->json()
        ]);
    })->name('test.webhook');
    
    // Test subscription activation (development only)
    Route::get('/test-subscription/{orderId}', function($orderId) {
        if (!app()->environment('local')) {
            abort(404);
        }
        
        $transaction = \App\Models\Transaction::where('order_id', $orderId)->first();
        
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        // Force activate subscription
        $paymentController = new \App\Http\Controllers\PaymentController();
        $reflection = new ReflectionClass($paymentController);
        $method = $reflection->getMethod('activateSubscription');
        $method->setAccessible(true);
        $method->invoke($paymentController, $transaction);
        
        return response()->json([
            'message' => 'Subscription activation attempted',
            'transaction' => $transaction,
            'subscription_check' => \App\Models\Subscription::where('transaction_id', $orderId)->first()
        ]);
    })->name('test.subscription');
    
    // Debug subscription issue
    Route::get('/debug-subscription', function() {
        if (!app()->environment('local')) {
            abort(404);
        }
        
        try {
            // 1. Cek apakah user login
            if (!Auth::check()) {
                return response()->json(['error' => 'User not authenticated']);
            }
            
            // 2. Buat test transaction
            $orderId = 'ORDER-TEST-' . time();
            
            $transaction = \App\Models\Transaction::create([
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'gross_amount' => 50000,
                'description' => 'Berlangganan Chat Dokter - Paket Bulanan',
                'transaction_status' => 'settlement',
                'transaction_id' => 'test-' . time(),
                'payment_type' => 'credit_card'
            ]);
            
            // 3. Test activateSubscription langsung
            $paymentController = new \App\Http\Controllers\PaymentController();
            
            // Use reflection to call private method
            $reflection = new ReflectionClass($paymentController);
            $method = $reflection->getMethod('activateSubscription');
            $method->setAccessible(true);
            
            // Call the method
            $method->invoke($paymentController, $transaction);
            
            // 4. Cek apakah subscription berhasil dibuat
            $subscription = \App\Models\Subscription::where('transaction_id', $orderId)->first();
            
            return response()->json([
                'success' => true,
                'transaction' => $transaction,
                'subscription_created' => $subscription ? true : false,
                'subscription' => $subscription,
                'user_id' => Auth::id(),
                'debug_info' => [
                    'transaction_is_success' => $transaction->isSuccess(),
                    'description_contains_berlangganan' => strpos($transaction->description, 'Berlangganan') !== false,
                    'existing_subscriptions' => \App\Models\Subscription::where('user_id', Auth::id())->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    })->name('debug.subscription');
    
    // Manual activate subscription untuk pembayaran yang sudah berhasil
    Route::get('/activate-paid-subscriptions', function() {
        if (!app()->environment('local')) {
            abort(404);
        }
        
        // Cari semua transaksi berhasil yang belum punya subscription
        $paidTransactions = \App\Models\Transaction::where('transaction_status', 'settlement')
            ->where('description', 'like', '%Berlangganan%')
            ->whereNotIn('order_id', function($query) {
                $query->select('transaction_id')->from('subscriptions');
            })
            ->get();
        
        $activated = [];
        
        foreach ($paidTransactions as $transaction) {
            // Tentukan plan berdasarkan amount
            $plan = $transaction->gross_amount == 50000 ? 'monthly' : 'yearly';
            $duration = $plan === 'monthly' ? 1 : 12;
            
            $startsAt = $transaction->created_at;
            $expiresAt = $startsAt->copy()->addMonths($duration);
            
            $subscription = \App\Models\Subscription::create([
                'user_id' => $transaction->user_id,
                'plan_name' => $plan,
                'price' => $transaction->gross_amount,
                'status' => 'active',
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'transaction_id' => $transaction->order_id
            ]);
            
            $activated[] = [
                'transaction_id' => $transaction->order_id,
                'user_id' => $transaction->user_id,
                'subscription_id' => $subscription->id,
                'plan' => $plan
            ];
        }
        
        return response()->json([
            'message' => 'Manual activation completed',
            'activated_count' => count($activated),
            'activated_subscriptions' => $activated
        ]);
    })->name('activate.paid.subscriptions');
    
    // Simple fix untuk subscription
    Route::get('/fix-subscription', function() {
        if (!Auth::check()) {
            return 'Please login first';
        }
        
        $userId = Auth::id();
        
        // Cari transaksi berhasil user ini yang belum punya subscription
        $transaction = \App\Models\Transaction::where('user_id', $userId)
            ->where('transaction_status', 'settlement')
            ->where('description', 'like', '%Berlangganan%')
            ->whereNotIn('order_id', function($query) {
                $query->select('transaction_id')->from('subscriptions');
            })
            ->latest()
            ->first();
        
        if (!$transaction) {
            return 'No paid transaction found or subscription already exists';
        }
        
        // Buat subscription
        $plan = $transaction->gross_amount == 50000 ? 'monthly' : 'yearly';
        $duration = $plan === 'monthly' ? 1 : 12;
        
        $subscription = \App\Models\Subscription::create([
            'user_id' => $userId,
            'plan_name' => $plan,
            'price' => $transaction->gross_amount,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => now()->addMonths($duration),
            'transaction_id' => $transaction->order_id
        ]);
        
        return 'Subscription activated! ID: ' . $subscription->id . ' - Plan: ' . $plan;
    })->name('fix.subscription');
    
    // Update transaction status untuk testing
    Route::get('/update-transaction-status/{orderId}', function($orderId) {
        if (!app()->environment('local')) {
            abort(404);
        }
        
        $transaction = \App\Models\Transaction::where('order_id', $orderId)->first();
        
        if (!$transaction) {
            return 'Transaction not found';
        }
        
        // Update ke settlement
        $transaction->update([
            'transaction_status' => 'settlement',
            'transaction_id' => 'manual-' . time(),
            'payment_type' => 'credit_card'
        ]);
        
        // Aktivasi subscription
        $paymentController = new \App\Http\Controllers\PaymentController();
        $reflection = new ReflectionClass($paymentController);
        $method = $reflection->getMethod('activateSubscription');
        $method->setAccessible(true);
        $method->invoke($paymentController, $transaction);
        
        return 'Transaction updated to settlement and subscription activated!';
    })->name('update.transaction.status');
});

