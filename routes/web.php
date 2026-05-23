<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorPortfolioController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Vendor\VendorPackageController;


Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    // Ketika user mengakses /dashboard, panggil file views/dashboard.blade.php
    // Route::get('/dashboard', function () {
    //    return view('dashboard');
    //  })->name('dashboard'); // Nama rute wajib 'dashboard' agar dibaca oleh Breeze

    Route::get('/vendor/dashboard', [VendorDashboardController::class, 'index'])
        ->name('vendor.dashboard');

    Route::get('/vendor/portfolio', [VendorPortfolioController::class, 'index'])
        ->name('vendor.portfolio');

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
});

Route::get('/packages/{id}', [VendorPackageController::class, 'show'])->name('packages.show');
Route::get('/packages/{id}/checkout', [VendorPackageController::class, 'checkout'])->name('packages.checkout');
