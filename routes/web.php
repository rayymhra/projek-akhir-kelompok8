<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::resource('patients', PatientController::class);

    // Payment Methods (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('payment-methods', PaymentMethodController::class);
    });

    // Transactions
    Route::resource('transactions', TransactionController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});