<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsSupplierMiddleware;
use App\Http\Middleware\ThrottleLoginRequests;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    // POST Routes
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->middleware(ThrottleLoginRequests::class)->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('auth.logout');
});
Route::middleware('auth:sanctum')->group(function () {
    // Get Routes
    Route::get('/me',[HomeController::class,'getUserDetails']);
    Route::get('/all_services',[SupplierController::class,'getAvailableServices']);
    Route::get('/suppliers',[SupplierController::class,'getSuppliers']);
    Route::get('/get_bookings',[UserController::class,'getBookings']);

    // POST Routes
    Route::post('/cancel_booking',[UserController::class,'cancelBooking']);
    Route::post('/book_service',[SupplierController::class,'bookService']);
});

Route::middleware(['auth:sanctum',IsSupplierMiddleware::class])->prefix('supplier')->group(function () {
    // POST Routes
    Route::post('/create_schedule',[SupplierController::class,'createSchedule']);
    Route::post('/manage_services',[SupplierController::class,'manageServices']);
});