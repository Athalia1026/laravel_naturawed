<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorPortfolioController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Vendor\VendorPackageController;
use App\Http\Controllers\Vendor\VendorBookingController;
use App\Http\Controllers\Vendor\VendorReviewController;
use App\Http\Controllers\Journalist\ArticleController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\VendorController as CustomerVendorController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\CustomerReviewController;
use App\Http\Controllers\Customer\VendorDisplayController;
use App\Http\Controllers\Journalist\ProfileController as JournalistProfileController;
use App\Http\Controllers\Vendor\AnalyticsController;
use App\Http\Controllers\ChatController;


// =========================================================================
// 1. PUBLIC ROUTES (Bisa diakses siapa saja tanpa perlu LOGIN)
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () { return view('customer.about'); })->name('customer.about');

// Vendor Browsing & Detail
Route::get('/vendors', [CustomerVendorController::class, 'index'])->name('customer.vendors');
Route::get('/vendor-detail/{id}', [VendorDisplayController::class, 'show'])->name('vendor.show');
Route::get('/packages/{id}', [VendorPackageController::class, 'show'])->name('packages.show');

// Editorial & Inspiration (Dipindah ke luar agar tamu/guest bisa membaca)
Route::get('/inspiration', [ArticleController::class, 'inspiration'])->name('inspiration');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/author/{id}', [\App\Http\Controllers\Customer\ArticleController::class, 'authorProfile'])->name('customer.author.profile');

// =========================================================================
// 2. AUTHENTICATION SYSTEM (Laravel Breeze Core)
// =========================================================================
require __DIR__ . '/auth.php';

// =========================================================================
// 3. PROTECTED ROUTES (Wajib LOGIN untuk mengakses fitur di bawah ini)
// =========================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // -----------------------------------------------------------------
    // SISI VENDOR (OPERATIONAL & MANAGEMENT)
    // -----------------------------------------------------------------
    Route::get('/vendor/dashboard', [VendorDashboardController::class, 'index'])->name('vendor.dashboard');
    Route::get('/vendor/portfolio', [VendorPortfolioController::class, 'index'])->name('vendor.portfolio');
    Route::get('/vendor/analytics', [AnalyticsController::class, 'index'])->name('vendor.analytics');
    Route::get('/vendor/analytics/export-pdf', [AnalyticsController::class, 'exportPdf'])->name('vendor.analytics.pdf');

    // Vendor Profile Management
    Route::get('/vendor/profile', [VendorProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/vendor/profile', [VendorProfileController::class, 'update'])->name('profile.update');

    // Vendor Packages CRUD
    Route::get('/vendor/packages', [VendorPackageController::class, 'index'])->name('vendor.packages.index');
    Route::get('/vendor/packages/create', [VendorPackageController::class, 'create'])->name('vendor.packages.create');
    Route::post('/vendor/packages/store', [VendorPackageController::class, 'store'])->name('vendor.packages.store');
    Route::get('/vendor/packages/{id}/edit', [VendorPackageController::class, 'edit'])->name('vendor.packages.edit');
    Route::post('/vendor/packages/update', [VendorPackageController::class, 'update'])->name('vendor.packages.update');
    


    Route::delete('/vendor/packages/{id}', [VendorPackageController::class, 'destroy'])->name('vendor.packages.destroy');

    // Vendor Bookings & Reviews
    Route::get('/vendor/bookings', [VendorBookingController::class, 'index'])->name('vendor.bookings.index');
    Route::post('/vendor/bookings/{id}/approve', [VendorBookingController::class, 'approve'])->name('vendor.bookings.approve');
    Route::post('/vendor/bookings/{id}/reject', [VendorBookingController::class, 'reject'])->name('vendor.bookings.reject');
    Route::get('/vendor/reviews', [VendorReviewController::class, 'index'])->name('vendor.reviews.index');
    Route::post('/vendor/reviews/{id}/reply', [VendorDashboardController::class, 'reply'])->name('vendor.reviews.reply');

    // -----------------------------------------------------------------
    // SISI JURNALIS (CONTENT CREATION)
    // -----------------------------------------------------------------
    Route::get('/journalist/dashboard', [ArticleController::class, 'dashboard'])->name('journalist.dashboard');
    Route::get('/journalist/article/create', [ArticleController::class, 'create'])->name('journalist.article.create');
    Route::post('/journalist/article/store', [ArticleController::class, 'store'])->name('journalist.article.store');
    
    Route::get('/journalist/article/{id}/edit', [ArticleController::class, 'edit'])->name('journalist.article.edit');
    Route::post('/journalist/article/{id}/update', [ArticleController::class, 'update'])->name('journalist.article.update');
    Route::delete('/journalist/article/{id}', [ArticleController::class, 'destroy'])->name('journalist.article.destroy');

    // Jurnalis Profile Management
    Route::get('/journalist/profile', [JournalistProfileController::class, 'show'])->name('journalist.profile.show');
    Route::get('/journalist/profile/edit', [JournalistProfileController::class, 'edit'])->name('journalist.profile.edit');
    Route::post('/journalist/profile', [JournalistProfileController::class, 'update'])->name('journalist.profile.update');

    // -----------------------------------------------------------------
    // SISI CUSTOMER (TRANSACTIONAL LABELS)
    // -----------------------------------------------------------------
    Route::get('/packages/{id}/checkout', [VendorPackageController::class, 'checkout'])->name('packages.checkout');
    Route::get('/checkout/{id}', [CustomerBookingController::class, 'checkout'])->name('customer.checkout');
    Route::post('/bookings', [CustomerBookingController::class, 'store'])->name('customer.bookings.store');
    Route::get('/history', [CustomerBookingController::class, 'history'])->name('customer.bookings.history');
    Route::get('/bookings/{id}', [CustomerBookingController::class, 'show'])->name('customer.bookings.show');
    
    // Payments & Reviews Process
    Route::get('/payment-instruction', [CustomerPaymentController::class, 'showPayment'])->name('customer.payment.show');
    Route::post('/payment-submit', [CustomerPaymentController::class, 'store'])->name('customer.payment.submit');
    Route::post('/reviews/store', [CustomerReviewController::class, 'store'])->name('customer.reviews.store');
    });
