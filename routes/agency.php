<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Agency;

Route::prefix('agency-panel')->name('agency.')->group(function () {
    Route::get('/', [Agency\DashboardController::class, 'redirect']);
    Route::get('/login', [Agency\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [Agency\AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [Agency\AuthController::class, 'logout'])->name('logout');

    Route::middleware(['agency'])->group(function () {
        Route::get('/dashboard', [Agency\DashboardController::class, 'index'])->name('dashboard');
        
        // Packages Management
        Route::resource('packages', Agency\PackageController::class);
        
        // Bookings Management
        Route::get('bookings', [Agency\BookingController::class, 'index'])->name('bookings');
        Route::get('bookings/{booking}', [Agency\BookingController::class, 'show'])->name('bookings.show');
        Route::patch('bookings/{booking}/status', [Agency\BookingController::class, 'updateStatus'])->name('bookings.status');
        
        // Profile Management
        Route::get('profile', [Agency\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [Agency\ProfileController::class, 'update'])->name('profile.update');

        // Analytics
        Route::get('analytics/clicks', [Agency\ClickAnalyticsController::class, 'index'])->name('analytics.clicks');
    });
});
