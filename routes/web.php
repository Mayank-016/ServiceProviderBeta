<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Middleware\IsSupplierMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/login', [HomeController::class, 'home'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/services/{service_id}/book', [HomeController::class, 'showBookingPage'])->name('services.book');
    Route::get('/my_bookings',[HomeController::class,'myBookings'])->name('my_bookings');
});

Route::middleware(['auth:sanctum', IsSupplierMiddleware::class])->prefix('supplier')->group(function () {
    Route::get('/manage_services', [HomeController::class, 'manageServices'])->name('supplier.manage.service');
});