<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Ketika user mengakses /dashboard, panggil file views/dashboard.blade.php
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard'); // Nama rute wajib 'dashboard' agar dibaca oleh Breeze
    
});

