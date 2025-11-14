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
use App\Http\Controllers\ProfilPasienController;

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

/*
|--------------------------------------------------------------------------
| Dashboard (Admin)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard-admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Halaman utama Data Nakes (Blade)
    Route::get('/admin/data-nakes', function () {
        return view('adminDataNakes.DataNakes');
    })->name('data-nakes.index');

    // CRUD Tenaga Kesehatan (lengkap termasuk show)
    Route::resource('tenaga-kesehatan', TenagaKesehatanController::class)
        ->only(['index', 'store', 'update', 'destroy', 'show']);

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Halaman utama Data Obat (Blade)
    Route::get('/data-obat', function () {
        return view('adminDataObat.DataObat');
    })->name('data-obat.index');

    // CRUD Tenaga Kesehatan
    Route::resource('obat', ObatController::class)
        ->only(['index', 'store', 'update', 'destroy', 'show']);
});

Route::get('/data-janji-berobat', [AppointmentAdminController::class, 'index'])
    ->middleware('auth', 'role:admin')
    ->name('appointment.admin');

Route::get('/admin/appointments/{id}', [App\Http\Controllers\AppointmentAdminController::class, 'show'])->middleware('auth');
Route::post('/admin/appointments/update/{id}', [App\Http\Controllers\AppointmentAdminController::class, 'updateStatus'])->middleware('auth');


/*
|--------------------------------------------------------------------------
| Pasien Pages 
|--------------------------------------------------------------------------
*/

// === ROUTE UNTUK JANJI BEROBAT ===
Route::middleware('auth')->group(function () {
    Route::get('/janji-berobat', [AppointmentController::class, 'index'])->name('appointment.index');
    Route::post('/janji-berobat', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::get('/janji-berobat/{id}', [AppointmentController::class, 'show'])->name('appointment.show');
    Route::put('/janji-berobat/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/janji-berobat/{id}', [AppointmentController::class, 'destroy'])->name('appointment.destroy');
});
Route::get('/Janji-Berobat', function(){
    return view('layanan.appointment');
});
Route::get('/Janji-Berobat/status', function(){
    return view('layanan.status');
});

// Route untuk profil pasien (dijalankan oleh controller) - pakai middleware auth
Route::middleware(['auth'])->group(function () {
    // URL: /profil  -> controller show()
    Route::get('/profil', [ProfilPasienController::class, 'show'])->name('pasien.profil');

    // Update profil (PUT)
    Route::put('/profil/update', [ProfilPasienController::class, 'update'])->name('pasien.profil.update');
});


/*
|--------------------------------------------------------------------------
| Other Pages akses harus login
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [ObatPublicController::class, 'index'])->name('obat.index');

Route::get('/obat-all', [ObatPublicController::class, 'all'])->name('obat.all');

Route::get('/obat-details/{id}', [ObatPublicController::class, 'show'])->name('obat.show');


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

Route::get('/home', function () {
    return view('home.homepage');
});

Route::get('/data-pasien', function () {
    return view('adminDatapasien.DataPasien');
})->middleware('auth')->name('appointment.admin');

Route::get('/coba', function () {
    return view('ujicoba');
});

