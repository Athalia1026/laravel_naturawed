<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

// ==========================================
// MIDDLEWARE GUEST (Hanya untuk yang BELUM Login)
// ==========================================
Route::middleware('guest')->group(function () {
    
    // Rute untuk Sign Up (Register)
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('register-vendor', [RegisteredUserController::class, 'createVendor'])
                ->name('register.vendor');
    Route::post('register-vendor', [RegisteredUserController::class, 'storeVendor']);

    // Rute untuk Sign In (Login)
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
});

// ==========================================
// MIDDLEWARE AUTH (Hanya untuk yang SUDAH Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Rute untuk Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
});