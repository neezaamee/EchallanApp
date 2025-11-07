<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CircleController;
use App\Http\Controllers\DumpingPointController;
use App\Livewire\Profile\EditProfile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| This file defines all web routes for your application.
| Routes are grouped and protected using authentication, role, and verification middlewares.
|--------------------------------------------------------------------------
*/

// ==========================
// Public Routes
// ==========================
Route::get('/', fn() => view('landing'))->name('home');

// User profile (public or static page)
Route::get('/user', fn() => view('pages.users.profile-setting'))->name('user.profile');


// ==========================
// Authentication Routes
// ==========================

// Routes accessible ONLY to guests (not logged-in users)
Route::middleware('guest')->group(function () {
    // Login Form + Authentication
    Route::get('login', [CustomAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [CustomAuthenticatedSessionController::class, 'store']);

    // Registration (for violator users only)
    Route::get('register', [CustomRegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [CustomRegisteredUserController::class, 'store']);
});

// Logout route (only accessible to authenticated users)
Route::post('logout', [CustomAuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


// ==========================
// Profile (Authenticated Users Only)
// ==========================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
});


// ==========================
// Email Verification
// ==========================

// Verification notice page
Route::get('/email/verify', function () {
    return view('pages.auth.confirm-mail');
})->middleware('auth')->name('verification.notice');

// Handle actual verification link (signed)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('/dashboard')->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// ==========================
// Authenticated Routes (All Logged-in Users)
// ==========================
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | UNIVERSAL DASHBOARD
    |--------------------------------------------------------------------------
    | When any user visits /dashboard, redirect them automatically
    | to their respective role-based dashboard.
    */
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) return redirect()->route('dashboard.super-admin');
        if ($user->hasRole('admin')) return redirect()->route('dashboard.admin');
        if ($user->hasRole('challan_officer')) return redirect()->route('dashboard.officer');
        if ($user->hasRole('accountant')) return redirect()->route('dashboard.accountant');
        if ($user->hasRole('violator')) return redirect()->route('dashboard.violator');

        return abort(403, 'Unauthorized access.');
    })->name('dashboard');

    // Role-specific dashboards
    Route::get('/dashboard/super-admin', [RoleDashboardController::class, 'superAdmin'])
        ->name('dashboard.super-admin')->middleware('role:super_admin');

    Route::get('/dashboard/admin', [RoleDashboardController::class, 'admin'])
        ->name('dashboard.admin')->middleware('role:admin');

    Route::get('/dashboard/officer', [RoleDashboardController::class, 'officer'])
        ->name('dashboard.officer')->middleware('role:challan_officer');

    Route::get('/dashboard/accountant', [RoleDashboardController::class, 'accountant'])
        ->name('dashboard.accountant')->middleware('role:accountant');

    Route::get('/dashboard/citizen', [RoleDashboardController::class, 'citizen'])
        ->name('dashboard.citizen')->middleware(['role:citizen', 'verified']);
});


// ==========================
// Admin + Super Admin Routes
// ==========================

/*
|--------------------------------------------------------------------------
| These routes are restricted to users with either 'admin' or 'super_admin' role.
| They can manage system entities like staff, provinces, cities, circles, and dumping points.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin'])->group(function () {

    // Staff Management
    Route::resource('staff', StaffController::class)->except(['show']);

    // Provinces Management
    Route::resource('provinces', ProvinceController::class)->except(['show']);

    // Cities Management
    Route::resource('cities', CityController::class)->except(['show']);

    // Circles Management
    Route::resource('circles', CircleController::class)->except(['show']);

    // Dumping Points Management
    Route::resource('dumping-points', DumpingPointController::class)->except(['show']);
});

Route::get('/force-verify', function () {
    $user = Auth::user();
    $user->markEmailAsVerified();
    return redirect('/dashboard')->with('status', 'Email verified!');
})->middleware('auth');
