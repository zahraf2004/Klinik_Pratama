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
    });
Route::get('/nakes/dashboard', [DashboardDokterController::class, 'dokter'])
    ->middleware(['auth', 'role:dokter,bidan,perawat'])
    ->name('dokter.Dashboard');
    
/*
|--------------------------------------------------------------------------
| Pasien Pages 
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

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
    
    // Chat session management (premium feature)
    Route::post('/chatify/getOrCreateSession', [\App\Http\Controllers\CustomChatifyController::class, 'getOrCreateSession']);
    Route::post('/chatify/incrementMessageCount', [\App\Http\Controllers\CustomChatifyController::class, 'incrementMessageCount']);
    Route::post('/chatify/endSession', [\App\Http\Controllers\CustomChatifyController::class, 'endSession']);
    
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

Route::get('/data-pasien', function () {
    return view('adminDatapasien.DataPasien');
})->middleware('auth')->name('appointment.admin');

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
    Route::get('/failed/{orderId}', [\App\Http\Controllers\PaymentController::class, 'failed'])->name('failed');
    Route::get('/history', [\App\Http\Controllers\PaymentController::class, 'history'])->name('history');
});

// Midtrans webhook (tidak perlu auth dan CSRF)
Route::post('/payment/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

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
});

