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
use App\Livewire\EditProvince;

// Public home
Route::get('/', function () {
    return view('landing');
})->name('home');

// User profile page
Route::get('/user', function () {
    return view('pages.users.profile-setting');
})->name('user.profile');

// Authentication (override Breeze defaults if present)
Route::get('login', [CustomAuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [CustomAuthenticatedSessionController::class, 'store']);
Route::post('logout', [CustomAuthenticatedSessionController::class, 'destroy'])->name('logout');

// Registration (violator only)
Route::get('register', [CustomRegisteredUserController::class, 'create'])->name('register');
Route::post('register', [CustomRegisteredUserController::class, 'store']);

// Email verification routes
Route::get('/email/verify', function () {
    return view('pages.auth.confirm-mail');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// ========================================
// Admin + Super Admin Protected Routes
// ========================================
Route::middleware(['auth', 'role:admin|super_admin'])->group(function () {

    // Staff
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');

    // Provinces
    Route::get('/provinces', [ProvinceController::class, 'index'])->name('provinces.index');
    Route::get('/provinces/create', [ProvinceController::class, 'create'])->name('provinces.create');
    Route::post('/provinces', [ProvinceController::class, 'store'])->name('provinces.store');
    Route::get('/provinces/{province}/edit', [ProvinceController::class, 'edit'])->name('provinces.edit');
    Route::put('/provinces/{province}', [ProvinceController::class, 'update'])->name('provinces.update');
    Route::delete('/provinces/{province}', [ProvinceController::class, 'destroy'])->name('provinces.destroy');

    // Cities
    Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
    Route::get('/cities/create', [CityController::class, 'create'])->name('cities.create');
    Route::post('/cities', [CityController::class, 'store'])->name('cities.store');
    Route::get('/cities/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
    Route::put('/cities/{city}', [CityController::class, 'update'])->name('cities.update');
    Route::delete('/cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');

    // Circles
    Route::get('/circles', [CircleController::class, 'index'])->name('circles.index');
    Route::get('/circles/create', [CircleController::class, 'create'])->name('circles.create');
    Route::post('/circles', [CircleController::class, 'store'])->name('circles.store');
    Route::get('/circles/{circle}/edit', [CircleController::class, 'edit'])->name('circles.edit');
    Route::put('/circles/{circle}', [CircleController::class, 'update'])->name('circles.update');
    Route::delete('/circles/{circle}', [CircleController::class, 'destroy'])->name('circles.destroy');

    // Dumping Points
    Route::get('/dumping-points', [DumpingPointController::class, 'index'])->name('dumping-points.index');
    Route::get('/dumping-points/create', [DumpingPointController::class, 'create'])->name('dumping-points.create');
    Route::post('/dumping-points', [DumpingPointController::class, 'store'])->name('dumping-points.store');
    Route::get('/dumping-points/{dumpingPoint}/edit', [DumpingPointController::class, 'edit'])->name('dumping-points.edit');
    Route::put('/dumping-points/{dumpingPoint}', [DumpingPointController::class, 'update'])->name('dumping-points.update');
    Route::delete('/dumping-points/{dumpingPoint}', [DumpingPointController::class, 'destroy'])->name('dumping-points.destroy');
});


// ========================================
// General Authenticated Dashboards
// ========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
