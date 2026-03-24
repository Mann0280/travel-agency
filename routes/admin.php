<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Stories\StoryController;
use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PackageAgencyController;
use App\Http\Controllers\Admin\PopularSearchController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AccountContentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClickAnalyticsController;
use App\Http\Controllers\Admin\PartnerApplicationController;

// Admin Authentication Routes
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login']);
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// Admin Protected Routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Stories handled by resource below
    
    // Agencies
    Route::resource('agencies', AgencyController::class);
    Route::get('/agencies/{agency}/impersonate', [AgencyController::class, 'impersonate'])->name('agencies.impersonate');
    Route::post('/agencies/stop-impersonating', [AgencyController::class, 'stopImpersonating'])->name('agencies.stop-impersonate');

    // Banner
    Route::resource('banner', BannerController::class);
    Route::post('/banner/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banner.toggle-status');
    
    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    
    // Click Analytics
    Route::get('/analytics/clicks', [ClickAnalyticsController::class, 'index'])->name('analytics.clicks');
    
    // Destinations
    Route::resource('destinations', DestinationController::class);

    // Packages
    Route::resource('packages', PackageController::class);
    Route::post('/packages/{package}/toggle-approval', [PackageController::class, 'toggleApproval'])->name('packages.toggle-approval');

    // Package Agencies
    Route::get('/packages/{package}/agencies', [PackageAgencyController::class, 'index'])->name('packages.agencies.index');
    Route::post('/packages/{package}/agencies', [PackageAgencyController::class, 'store'])->name('packages.agencies.store');
    Route::put('/package-agencies/{packageAgency}', [PackageAgencyController::class, 'update'])->name('package-agencies.update');
    Route::delete('/package-agencies/{packageAgency}', [PackageAgencyController::class, 'destroy'])->name('package-agencies.destroy');
    
    // Popular Searches
    Route::resource('popular-searches', PopularSearchController::class);
    Route::post('/popular-searches/{popularSearch}/toggle-status', [PopularSearchController::class, 'toggleStatus'])->name('popular-searches.toggle-status');
    Route::post('/popular-searches/{popularSearch}/reset-clicks', [PopularSearchController::class, 'resetClicks'])->name('popular-searches.reset-clicks');
    
    // Stories
    Route::resource('stories', \App\Http\Controllers\Admin\Stories\StoryController::class);
    Route::post('/stories/{story}/toggle-status', [\App\Http\Controllers\Admin\Stories\StoryController::class, 'toggleStatus'])->name('stories.toggle-status');
    Route::delete('/stories/items/{id}', [\App\Http\Controllers\Admin\Stories\StoryController::class, 'deleteItem'])->name('stories.items.destroy');
    
    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/status', [ReviewController::class, 'updateStatus'])->name('reviews.status');
    Route::post('/reviews/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
    Route::post('/reviews/reply/delete', [ReviewController::class, 'deleteReply'])->name('reviews.reply.delete');
    Route::post('/reviews/featured', [ReviewController::class, 'toggleFeatured'])->name('reviews.toggleFeatured');
    Route::post('/reviews/delete', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Feedback
    Route::get('/feedback', [\App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedback.index');
    Route::put('/feedback/{feedback}', [\App\Http\Controllers\Admin\FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}', [\App\Http\Controllers\Admin\FeedbackController::class, 'destroy'])->name('feedback.destroy');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Account Content
    Route::prefix('account-content')->name('account-content.')->group(function () {
        Route::get('/', [AccountContentController::class, 'index'])->name('index');
        
        Route::get('/about', [AccountContentController::class, 'about'])->name('about');
        Route::post('/about', [AccountContentController::class, 'updateAbout'])->name('about.update');
        
        Route::get('/faq', [AccountContentController::class, 'faq'])->name('faq');
        Route::post('/faq', [AccountContentController::class, 'storeFaq'])->name('faq.store');
        Route::post('/faq/{id}/update', [AccountContentController::class, 'updateFaq'])->name('faq.update');
        Route::post('/faq/{id}/delete', [AccountContentController::class, 'destroyFaq'])->name('faq.destroy');
        
        Route::get('/partner', [AccountContentController::class, 'partner'])->name('partner');
        Route::post('/partner', [AccountContentController::class, 'updatePartner'])->name('partner.update');
        
        Route::get('/terms', [AccountContentController::class, 'terms'])->name('terms');
        Route::post('/terms', [AccountContentController::class, 'updateTerms'])->name('terms.update');

        Route::get('/privacy', [AccountContentController::class, 'privacy'])->name('privacy');
        Route::post('/privacy', [AccountContentController::class, 'updatePrivacy'])->name('privacy.update');

        // Password Reset Logs
        Route::get('/logs/password-resets', [AccountContentController::class, 'passwordResetLogs'])->name('admin.logs.passwordResets');
        
        Route::get('/feedback', [AccountContentController::class, 'feedback'])->name('feedback');
        Route::post('/feedback/settings', [AccountContentController::class, 'updateFeedbackSettings'])->name('feedback.settings');
        Route::post('/feedback/category', [AccountContentController::class, 'storeFeedbackCategory'])->name('feedback.category.store');
        Route::post('/feedback/category/{category}/update', [AccountContentController::class, 'updateFeedbackCategory'])->name('feedback.category.update');
        Route::post('/feedback/category/{category}/delete', [AccountContentController::class, 'destroyFeedbackCategory'])->name('feedback.category.destroy');
    });

    // Partner Applications
    Route::prefix('partner-applications')->name('partner-applications.')->group(function () {
        Route::get('/', [PartnerApplicationController::class, 'index'])->name('index');
        Route::get('/{application}', [PartnerApplicationController::class, 'show'])->name('show');
        Route::post('/{application}/approve', [PartnerApplicationController::class, 'approve'])->name('approve');
        Route::post('/{application}/reject', [PartnerApplicationController::class, 'reject'])->name('reject');
    });

    // Users
    Route::resource('users', UserController::class);
});