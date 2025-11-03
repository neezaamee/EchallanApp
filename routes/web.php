<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CircleController;
use App\Http\Controllers\DumpingPointController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Profile\EditProfile;

// ==========================
// Public Routes
// ==========================
Route::get('/', fn() => view('landing'))->name('home');

// User profile
Route::get('/user', fn() => view('pages.users.profile-setting'))->name('user.profile');

// ==========================
// Authentication
// ==========================
Route::get('login', [CustomAuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [CustomAuthenticatedSessionController::class, 'store']);
Route::post('logout', [CustomAuthenticatedSessionController::class, 'destroy'])->name('logout');

// Registration (violator only)
Route::get('register', [CustomRegisteredUserController::class, 'create'])->name('register');
Route::post('register', [CustomRegisteredUserController::class, 'store']);

//Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
});

// ==========================
// Email Verification
// ==========================
Route::get('/email/verify', fn() => view('pages.auth.confirm-mail'))
    ->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');
    /* Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify'); */

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ==========================
// Authenticated Routes
// ==========================
Route::middleware(['auth'])->group(function () {

    // UNIVERSAL DASHBOARD (auto role-based redirect)
    Route::get('/dashboard', function () {
        $user = auth()->user();
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

    Route::get('/dashboard/violator', [RoleDashboardController::class, 'violator'])
        ->name('dashboard.violator')->middleware(['role:violator', 'verified']);
});

// ==========================
// Admin + Super Admin Routes
// ==========================
Route::middleware(['auth', 'role:admin|super_admin'])->group(function () {

    // Staff
    Route::resource('staff', StaffController::class)->except(['show']);

    // Provinces
    Route::resource('provinces', ProvinceController::class)->except(['show']);

    // Cities
    Route::resource('cities', CityController::class)->except(['show']);

    // Circles
    Route::resource('circles', CircleController::class)->except(['show']);

    // Dumping Points
    Route::resource('dumping-points', DumpingPointController::class)->except(['show']);
});
