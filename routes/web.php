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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/vendors', [CustomerVendorController::class, 'index'])->name('customer.vendors');

Route::get('/packages/{id}', [VendorPackageController::class, 'show'])->name('packages.show');


require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    // Ketika user mengakses /dashboard, panggil file views/dashboard.blade.php
    // Route::get('/dashboard', function () {
    //    return view('dashboard');
    //  })->name('dashboard'); // Nama rute wajib 'dashboard' agar dibaca oleh Breeze

    Route::get('/vendor/dashboard', [VendorDashboardController::class, 'index'])
        ->name('vendor.dashboard');

    Route::get('/vendor/portfolio', [VendorPortfolioController::class, 'index'])->name('vendor.portfolio');

    Route::get('/vendor/profile', [VendorProfileController::class, 'edit'])
        ->name('profile.edit');

    // Rute untuk memproses update data ke database Laragon
    Route::post('/vendor/profile', [VendorProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/vendor/packages', [VendorPackageController::class, 'index'])->name('vendor.packages.index');
    Route::get('/vendor/packages/create', [VendorPackageController::class, 'create'])->name('vendor.packages.create');
    Route::post('/vendor/packages/store', [VendorPackageController::class, 'store'])->name('vendor.packages.store');
    Route::get('/vendor/packages/{id}/edit', [VendorPackageController::class, 'edit'])->name('vendor.packages.edit');
    Route::post('/vendor/packages/update', [VendorPackageController::class, 'update'])->name('vendor.packages.update');
    Route::delete('/vendor/packages/{id}', [VendorPackageController::class, 'delete'])->name('vendor.packages.delete');

    // Vendor Booking Approval Routes
    Route::post('/vendor/bookings/{id}/approve', [VendorBookingController::class, 'approve'])->name('vendor.bookings.approve');
    Route::post('/vendor/bookings/{id}/reject', [VendorBookingController::class, 'reject'])->name('vendor.bookings.reject');

    // Vendor Review Routes
    Route::get('/vendor/reviews', [VendorReviewController::class, 'index'])->name('vendor.reviews.index');
    Route::post('/vendor/reviews/{id}/reply', [VendorDashboardController::class, 'reply'])->name('vendor.reviews.reply');

///JOURNALIST
    Route::get('/journalist/dashboard', [ArticleController::class, 'dashboard'])
    ->name('journalist.dashboard');

    Route::get('/journalist/article/create', [ArticleController::class, 'create'])
    ->name('journalist.article.create');

    Route::post('/journalist/article/store', [ArticleController::class, 'store']) // Nanti kamu bikin fungsi store-nya ya!
    ->name('journalist.article.store');

    Route::get('/checkout/{id}', [CustomerBookingController::class, 'checkout'])->name('customer.checkout');
    
    // 2. RUTE POST: Untuk memproses simpan data pesanan (Sudah kita buat sebelumnya)
    Route::post('/bookings', [CustomerBookingController::class, 'store'])->name('customer.bookings.store');

    // Tampilan Instruksi Pembayaran Paket
    Route::get('/payment-instruction', [CustomerPaymentController::class, 'showPayment'])->name('customer.payment.show');
    
    // Endpoint AJAX POST Penampung Unggah Bukti Transfer
    Route::post('/payment-submit', [CustomerPaymentController::class, 'store'])->name('customer.payment.submit');

    Route::get('/history', [CustomerBookingController::class, 'history'])->name('customer.bookings.history');
    
    // Booking Detail Route
    Route::get('/bookings/{id}', [CustomerBookingController::class, 'show'])->name('customer.bookings.show');

    // Review Routes
    Route::post('/reviews/store', [CustomerReviewController::class, 'store'])->name('customer.reviews.store');
});

Route::get('/packages/{id}', [VendorPackageController::class, 'show'])->name('packages.show');
Route::get('/packages/{id}/checkout', [VendorPackageController::class, 'checkout'])->name('packages.checkout');
Route::get('/vendor-detail/{id}', [VendorDisplayController::class, 'show'])->name('vendor.show');