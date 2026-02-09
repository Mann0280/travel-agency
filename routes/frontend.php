<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\PackageDetailsController;
use App\Http\Controllers\Frontend\AgencyController;
use App\Http\Controllers\Frontend\AgencyDetailsController;
use App\Http\Controllers\Frontend\ContentController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard redirect
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

// Search route
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Package Details Route
Route::get('/packages/{package}', [PackageDetailsController::class, 'show'])->name('package.show');

// Agency public routes
Route::get('/agency', [AgencyController::class, 'index'])->name('agency');
Route::get('/agency/{package}', [AgencyController::class, 'index'])->name('agency.package');
Route::get('/agency/{agency}/{package}', [AgencyDetailsController::class, 'show'])->name('agency.details');

// Public Content Routes
Route::get('/info/{type}', [ContentController::class, 'show'])->name('content.show');
