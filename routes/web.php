<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\Frontend\AgencyController;
use App\Http\Controllers\Frontend\PackageDetailsController;
use App\Http\Controllers\Frontend\AgencyDetailsController;
use App\Http\Controllers\Frontend\ContentController;


// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard route
Route::middleware('auth.check')->get('/dashboard', function () {
    $user = auth()->user();
    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('account');
})->name('dashboard');

// Search route
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Account routes
Route::middleware('auth.check')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/account/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
    Route::post('/account/change-password', [AccountController::class, 'changePassword'])->name('account.changePassword');
    Route::post('/account/submit-feedback', [AccountController::class, 'submitFeedback'])->name('account.submitFeedback');
    Route::post('/account/partner-application', [AccountController::class, 'submitPartnerApplication'])->name('account.partnerApplication');
});

// Agency routes
Route::get('/agency', [AgencyController::class, 'index'])->name('agency');
Route::get('/agency/{package}', [AgencyController::class, 'index'])->name('agency.package');
Route::get('/agency/{agency}/{package}', [AgencyDetailsController::class, 'show'])->name('agency.details');

// Package show route
Route::get('/package/{package}', [PackageDetailsController::class, 'show'])->name('package.show');

// Public Content Routes
Route::get('/info/{type}', [ContentController::class, 'show'])->name('content.show');