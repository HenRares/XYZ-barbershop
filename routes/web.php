<?php

use App\Http\Controllers\Admin\CapacityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QueueController as AdminQueueController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QueueTrackingController;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', fn () => view('services.index', ['services' => Service::active()->orderBy('name')->get()]))->name('services.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking/estimate', [BookingController::class, 'estimate'])->name('booking.estimate');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking-sukses/{booking:public_id}', [BookingController::class, 'success'])->name('booking.success');

Route::get('/antrean-saya', [QueueTrackingController::class, 'index'])->name('queue.mine');

// Route::get('/layanan', [AdminServiceController::class, 'index'])->name('services');
Route::get('/antrean/{booking:public_id}/summary', [QueueTrackingController::class, 'summary'])->name('queue.summary');
Route::post('/antrean/{booking:public_id}/cancel', [QueueTrackingController::class, 'cancel'])->name('queue.cancel');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/antrean', [AdminQueueController::class, 'index'])->name('queues'); 
    Route::post('/antrean/walk-in', [AdminQueueController::class, 'storeWalkIn'])->name('queues.walkin');
    Route::patch('/antrean/{booking}/status', [AdminQueueController::class, 'updateStatus'])->name('queues.status');
    Route::get('/layanan', [AdminServiceController::class, 'index'])->name('services');
    Route::post('/layanan', [AdminServiceController::class, 'store'])->name('services.store');
    Route::put('/layanan/{service}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::delete('/layanan/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');
    Route::get('/kapasitas', [CapacityController::class, 'index'])->name('capacities');
    Route::post('/kapasitas', [CapacityController::class, 'store'])->name('capacities.store');
    Route::get('/laporan', ReportController::class)->name('reports');
});
