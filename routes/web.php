<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffqController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CircleController;
use App\Http\Controllers\DumpingPointController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MedicalCenterController;
use App\Http\Controllers\StaffPostingController;
use App\Http\Controllers\LocationController;


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

    // Registration (for citizen users only)
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
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    //Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
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
    return redirect()->route('dashboard')->with('verified', true);
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
        if ($user->hasRole('doctor')) return redirect()->route('dashboard.doctor');
        if ($user->hasRole('admin')) return redirect()->route('dashboard.admin');
        if ($user->hasRole('challan_officer')) return redirect()->route('dashboard.officer');
        if ($user->hasRole('accountant')) return redirect()->route('dashboard.accountant');
        if ($user->hasRole('citizen')) return redirect()->route('dashboard.citizen');

        return abort(403, 'Unauthorized access.');
    })->name('dashboard');

    // Role-specific dashboards
    Route::get('/dashboard/super-admin', [RoleDashboardController::class, 'index'])
        ->name('dashboard.super-admin')->middleware('role:super_admin');

    Route::get('/dashboard/admin', [RoleDashboardController::class, 'admin'])
        ->name('dashboard.admin')->middleware('role:admin');
    
    Route::get('/dashboard/doctor', [RoleDashboardController::class, 'doctor'])
        ->name('dashboard.doctor')->middleware('role:doctor');

    Route::get('/dashboard/officer', [RoleDashboardController::class, 'officer'])
        ->name('dashboard.officer')->middleware('role:challan_officer');

    Route::get('/dashboard/accountant', [RoleDashboardController::class, 'accountant'])
        ->name('dashboard.accountant')->middleware('role:accountant');

    Route::get('/dashboard/citizen', [RoleDashboardController::class, 'citizen'])
        ->name('dashboard.citizen')->middleware(['role:citizen', 'verified']);

    // User profile page
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Medical Requests
    Route::resource('medical-requests', \App\Http\Controllers\MedicalRequestController::class);
    Route::get('/api/cities/{city}/medical-centers', [\App\Http\Controllers\MedicalRequestController::class, 'getMedicalCenters'])->name('api.medical-centers');
    Route::get('/api/provinces/{province}/cities', [\App\Http\Controllers\MedicalRequestController::class, 'getCities'])->name('api.cities');
    Route::get('/api/citizens/check/{cnic}', [\App\Http\Controllers\MedicalRequestController::class, 'checkCitizen'])->name('api.citizens.check');

    // Feedback
    Route::resource('feedback', \App\Http\Controllers\FeedbackController::class);
});

// Public Changelog (accessible to all authenticated users)
Route::get('/changelog', [\App\Http\Controllers\ChangelogController::class, 'publicView'])
    ->middleware(['auth', 'verified'])
    ->name('changelog.public');

// Admin Changelog Routes
Route::middleware(['auth', 'verified', 'role:super_admin|admin'])->group(function () {
    Route::resource('admin/changelog', \App\Http\Controllers\ChangelogController::class)->names([
        'index' => 'changelog.index',
        'create' => 'changelog.create',
        'store' => 'changelog.store',
        'show' => 'changelog.show',
        'edit' => 'changelog.edit',
        'update' => 'changelog.update',
        'destroy' => 'changelog.destroy',
    ]);
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

    // Medical Centers
    Route::resource('staff-postings', StaffPostingController::class);
    Route::resource('medical-centers', MedicalCenterController::class)->except(['show']);
});

/* Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    // Dashboard overview
    Route::get('/locations', [LocationController::class, 'index'])->name('admin.locations');

    // Provinces, Cities, Circles, and Medical Centers management pages
    Route::get('/locations/provinces', [LocationController::class, 'provinces'])->name('admin.provinces');
    Route::get('/locations/cities', [LocationController::class, 'cities'])->name('admin.cities');
    Route::get('/locations/circles', [LocationController::class, 'circles'])->name('admin.circles');
    Route::get('/locations/medical-centers', [LocationController::class, 'medicalCenters'])->name('admin.medical-centers');
}); */
Route::prefix('staffq')->middleware('auth')->group(function(){
    Route::get('/', [StaffqController::class, 'index'])->name('staff.index');
    Route::get('/create', [StaffqController::class, 'create'])->name('staff.create');
    Route::post('/store', [StaffqController::class, 'store'])->name('staff.store');
    Route::get('/{staff}/edit', [StaffqController::class, 'edit'])->name('staff.edit');
    Route::put('/{staff}', [StaffqController::class, 'update'])->name('staff.update');
    Route::delete('/{staff}', [StaffqController::class, 'destroy'])->name('staff.destroy');
});

Route::get('/force-verify', function () {
    $user = Auth::user();
    $user->markEmailAsVerified();
    return redirect('/dashboard')->with('status', 'Email verified!');
})->middleware('auth');

// Test route to check current user's roles and permissions
Route::middleware(['auth'])->get('/test-permissions', function () {
    $user = Auth::user();

    return response()->json([
        'user' => $user->name,
        'roles' => $user->getRoleNames(),
        'permissions' => $user->getAllPermissions()->pluck('name')
    ]);
})->name('test.permissions');
