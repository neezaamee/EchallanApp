<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleDashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// public home
Route::get('/', function () {
    return view('index'); // replace with your landing page
})->name('home');

// Authentication (override Breeze defaults if present)
Route::get('login', [CustomAuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [CustomAuthenticatedSessionController::class, 'store']);
Route::post('logout', [CustomAuthenticatedSessionController::class, 'destroy'])->name('logout');

// Registration (violator only)
Route::get('register', [CustomRegisteredUserController::class, 'create'])->name('register');
Route::post('register', [CustomRegisteredUserController::class, 'store']);

// Password reset routes are provided by Breeze/Fortify; keep them if you installed Breeze.
// Email verification routes (use Laravel defaults)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Email verification handler
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend verification
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Central dashboard entry (redirect to role-specific dashboards)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // role specific dashboards - protected by role middleware
    Route::get('/dashboard/super-admin', [RoleDashboardController::class, 'superAdmin'])
        ->name('dashboard.super-admin')->middleware('role:super_admin');

    Route::get('/dashboard/admin', [RoleDashboardController::class, 'admin'])
        ->name('dashboard.admin')->middleware('role:admin');

    Route::get('/dashboard/officer', [RoleDashboardController::class, 'officer'])
        ->name('dashboard.officer')->middleware('role:challan_officer');

    Route::get('/dashboard/accountant', [RoleDashboardController::class, 'accountant'])
        ->name('dashboard.accountant')->middleware('role:accountant');

    // violator: must be verified
    Route::get('/dashboard/violator', [RoleDashboardController::class, 'violator'])
        ->name('dashboard.violator')->middleware(['role:violator', 'verified']);
});

