<?php

use App\Http\Controllers\Admin\CapacityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QueueController as AdminQueueController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QueueTrackingController;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
| Halaman ini dapat dibuka tanpa login.
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');
Route::get('/layanan', function () {
    return view('services.index', [
        'services' => Service::active()
            ->orderBy('name')
            ->get(),
    ]);
})->name('services.index');

Route::get('/api/cron/booking-auto-complete', CronController::class)
    ->middleware('throttle:10,1')
    ->name('cron.booking-auto-complete');


/*
|--------------------------------------------------------------------------
| Autentikasi Guest
|--------------------------------------------------------------------------
| Hanya dapat dibuka ketika pengguna belum login.
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:3,1')
        ->name('register.store');
});


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
| Hanya dapat dijalankan oleh pengguna yang sudah login.
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Booking dan Antrean Pelanggan
|--------------------------------------------------------------------------
| Seluruh halaman di bawah ini hanya dapat dibuka setelah login.
*/

Route::middleware('auth')->group(function () {

    // Form booking
    Route::get('/booking', [BookingController::class, 'create'])
        ->name('booking.create');

    // Menghitung estimasi antrean
    Route::post('/booking/estimate', [BookingController::class, 'estimate'])
        ->name('booking.estimate');

    // Menyimpan booking
    Route::post('/booking', [BookingController::class, 'store'])
        ->name('booking.store');

    // Halaman booking berhasil
    Route::get('/booking-sukses/{booking:public_id}', [BookingController::class, 'success'])
        ->name('booking.success');

    // Daftar antrean milik pelanggan
    Route::get('/antrean-saya', [QueueTrackingController::class, 'index'])
        ->name('queue.mine');

    // Ringkasan antrean
    Route::get('/antrean/{booking:public_id}/summary', [QueueTrackingController::class, 'summary'])
        ->name('queue.summary');

    // Membatalkan antrean
    Route::post('/antrean/{booking:public_id}/cancel', [QueueTrackingController::class, 'cancel'])
        ->name('queue.cancel');
});


/*
|--------------------------------------------------------------------------
| Halaman Admin
|--------------------------------------------------------------------------
| Pengguna wajib login dan memiliki hak akses admin.
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // Dashboard admin
        Route::get('/', DashboardController::class)
            ->name('dashboard');

        // Pengelolaan antrean
        Route::get('/antrean', [AdminQueueController::class, 'index'])
            ->name('queues');
        Route::post('/antrean/walk-in', [AdminQueueController::class, 'storeWalkIn'])
            ->name('queues.walkin');
        Route::patch('/antrean/{booking}/status', [AdminQueueController::class, 'updateStatus'])
            ->name('queues.status');

        // Pengelolaan layanan
        Route::get('/layanan', [AdminServiceController::class, 'index'])
            ->name('services');
        Route::post('/layanan', [AdminServiceController::class, 'store'])
            ->name('services.store');
        Route::put('/layanan/{service}', [AdminServiceController::class, 'update'])
            ->name('services.update');
        Route::delete('/layanan/{service}', [AdminServiceController::class, 'destroy'])
            ->name('services.destroy');

        // Pengelolaan kapasitas barber
        Route::get('/kapasitas', [CapacityController::class, 'index'])
            ->name('capacities');
        Route::post('/kapasitas', [CapacityController::class, 'store'])
            ->name('capacities.store');

        // Laporan
        Route::get('/laporan', ReportController::class)
            ->name('reports');
    });
