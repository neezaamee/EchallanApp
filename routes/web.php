<?php


use App\Http\Controllers\ActivityLogController;
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
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController; 
use App\Http\Controllers\PermissionController;


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


Route::get('/', fn() => view('public.landing'))->name('home');

// User profile (public or static page)
Route::get('/user', fn() => view('app.users.profile-setting'))->name('user.profile');


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
    return view('auth.confirm-mail');
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
})->middleware(['auth', 'throttle:1,1'])->name('verification.send');


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
    Route::get('/dashboard', [RoleDashboardController::class, 'index'])->name('dashboard');

    // Role-specific dashboards (all point to index which handles logic)
    Route::get('/dashboard/super-admin', [RoleDashboardController::class, 'index'])
        ->name('dashboard.super-admin')->middleware('role:super_admin');
    
    Route::get('/dashboard/cto', [RoleDashboardController::class, 'index'])
        ->name('dashboard.cto')->middleware('role:cto');

    Route::get('/dashboard/admin', [RoleDashboardController::class, 'index'])
        ->name('dashboard.admin')->middleware('role:admin');
    
    Route::get('/dashboard/doctor', [RoleDashboardController::class, 'index'])
        ->name('dashboard.doctor')->middleware('role:doctor');

    Route::get('/dashboard/officer', [RoleDashboardController::class, 'index'])
        ->name('dashboard.officer')->middleware('role:challan_officer');

    Route::get('/dashboard/accountant', [RoleDashboardController::class, 'index'])
        ->name('dashboard.accountant')->middleware('role:accountant');

    Route::get('/dashboard/citizen', [RoleDashboardController::class, 'citizen'])
        ->name('dashboard.citizen')->middleware(['role:citizen', 'verified']);

    // User profile page
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Medical Requests
    Route::resource('medical-requests', \App\Http\Controllers\MedicalRequestController::class);
    Route::get('/api/cities/{city}/medical-centers', [\App\Http\Controllers\MedicalRequestController::class, 'getMedicalCenters'])->name('api.medical-centers');
    Route::get('/api/provinces/{province}/cities', [\App\Http\Controllers\MedicalRequestController::class, 'getCities'])->name('api.cities');
    Route::get('/api/citizens/check/{cnic}', [\App\Http\Controllers\MedicalRequestController::class, 'checkCitizen'])->name('api.citizens.check');

    // Payment Routes
    Route::get('/payments/pay/{psid}', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/process', [\App\Http\Controllers\PaymentController::class, 'process'])->name('payments.process');
    Route::get('/payments/success/{payment}', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/failed/{payment}', [\App\Http\Controllers\PaymentController::class, 'failed'])->name('payments.failed');
    
    // Receipt Routes
    Route::get('/payments/{payment}/receipt', [\App\Http\Controllers\PaymentController::class, 'viewReceipt'])->name('payments.receipt');
    Route::get('/payments/{payment}/receipt/download', [\App\Http\Controllers\PaymentController::class, 'downloadReceipt'])->name('payments.receipt.download');
    Route::get('/payments/{payment}/receipt/thermal', [\App\Http\Controllers\PaymentController::class, 'downloadThermalReceipt'])->name('payments.receipt.thermal');



    // Feedback
    Route::resource('feedback', \App\Http\Controllers\FeedbackController::class);

    // Lifter Challans
    Route::resource('challans', \App\Http\Controllers\ChallanController::class);
    Route::post('/challans/{challan}/validate-payment', [\App\Http\Controllers\ChallanController::class, 'validatePayment'])->name('challans.validate-payment');
    
    // Impound & Release Workflow
    Route::prefix('impound')->group(function () {
        Route::get('/check-status', [\App\Http\Controllers\ChallanController::class, 'checkStatusForm'])->name('impound.check-status.form');
        Route::post('/check-status', [\App\Http\Controllers\ChallanController::class, 'checkStatus'])->name('impound.check-status.submit');
        Route::get('/release/{challan}', [\App\Http\Controllers\ChallanController::class, 'releaseForm'])->name('impound.release.form');
        Route::post('/release/{challan}', [\App\Http\Controllers\ChallanController::class, 'release'])->name('impound.release.submit');
    });
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

// Admin/Accountant Payment Management Routes
Route::middleware(['auth', 'verified', 'role:super_admin|admin|accountant'])->group(function () {
    Route::get('/payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/search', [\App\Http\Controllers\PaymentController::class, 'search'])->name('payments.search');
    Route::get('/payments/{payment}', [\App\Http\Controllers\PaymentController::class, 'show'])->name('payments.show');
    
    // Payment Dashboard
    Route::get('/dashboard/payments', [\App\Http\Controllers\PaymentDashboardController::class, 'index'])->name('dashboard.payments');

    // Payment Reports
    Route::prefix('reports/payments')->group(function () {
        Route::get('/daily', [\App\Http\Controllers\PaymentReportController::class, 'daily'])->name('reports.payments.daily');
        Route::get('/monthly', [\App\Http\Controllers\PaymentReportController::class, 'monthly'])->name('reports.payments.monthly');
        Route::get('/by-method', [\App\Http\Controllers\PaymentReportController::class, 'byMethod'])->name('reports.payments.by-method');
        Route::get('/by-center', [\App\Http\Controllers\PaymentReportController::class, 'byCenter'])->name('reports.payments.by-center');
    });

    // Refund Management
    Route::prefix('refunds')->group(function () {
        Route::get('/', [\App\Http\Controllers\RefundController::class, 'index'])->name('refunds.index');
        Route::get('/create/{payment}', [\App\Http\Controllers\RefundController::class, 'create'])->name('refunds.create');
        Route::post('/', [\App\Http\Controllers\RefundController::class, 'store'])->name('refunds.store');
        Route::get('/{refund}', [\App\Http\Controllers\RefundController::class, 'show'])->name('refunds.show');
        Route::post('/{refund}/approve', [\App\Http\Controllers\RefundController::class, 'approve'])->name('refunds.approve');
        Route::post('/{refund}/reject', [\App\Http\Controllers\RefundController::class, 'reject'])->name('refunds.reject');
    });

    // Payment Export
    Route::prefix('payments/export')->group(function () {
        Route::get('/excel', [\App\Http\Controllers\PaymentExportController::class, 'excel'])->name('payments.export.excel');
        Route::get('/csv', [\App\Http\Controllers\PaymentExportController::class, 'csv'])->name('payments.export.csv');
    });
});



// ==========================
// Admin & Management Routes (Permission-based)
// ==========================

// Staff Management
Route::middleware(['auth', 'can:staff:view'])->group(function () {
    Route::resource('staff', StaffController::class);
    Route::resource('staff-postings', StaffPostingController::class);
});

// Infrastructure & Locations
// Provinces
Route::middleware(['auth', 'can:provinces:view'])->group(function () {
    Route::get('provinces', [ProvinceController::class, 'index'])->name('provinces.index');
});
Route::middleware(['auth', 'can:provinces:create'])->group(function () {
    Route::get('provinces/create', [ProvinceController::class, 'create'])->name('provinces.create');
    Route::post('provinces', [ProvinceController::class, 'store'])->name('provinces.store');
});
Route::middleware(['auth', 'can:provinces:edit'])->group(function () {
    Route::get('provinces/{province}/edit', [ProvinceController::class, 'edit'])->name('provinces.edit');
    Route::put('provinces/{province}', [ProvinceController::class, 'update'])->name('provinces.update');
    Route::patch('provinces/{province}', [ProvinceController::class, 'update']);
});
Route::middleware(['auth', 'can:provinces:delete'])->group(function () {
    Route::delete('provinces/{province}', [ProvinceController::class, 'destroy'])->name('provinces.destroy');
});

// Cities
Route::middleware(['auth', 'can:cities:view'])->group(function () {
    Route::get('cities', [CityController::class, 'index'])->name('cities.index');
});
Route::middleware(['auth', 'can:cities:create'])->group(function () {
    Route::get('cities/create', [CityController::class, 'create'])->name('cities.create');
    Route::post('cities', [CityController::class, 'store'])->name('cities.store');
});
Route::middleware(['auth', 'can:cities:edit'])->group(function () {
    Route::get('cities/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
    Route::put('cities/{city}', [CityController::class, 'update'])->name('cities.update');
    Route::patch('cities/{city}', [CityController::class, 'update']);
});
Route::middleware(['auth', 'can:cities:delete'])->group(function () {
    Route::delete('cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');
});

// Circles
Route::middleware(['auth', 'can:circles:view'])->group(function () {
    Route::get('circles', [CircleController::class, 'index'])->name('circles.index');
});
Route::middleware(['auth', 'can:circles:create'])->group(function () {
    Route::get('circles/create', [CircleController::class, 'create'])->name('circles.create');
    Route::post('circles', [CircleController::class, 'store'])->name('circles.store');
});
Route::middleware(['auth', 'can:circles:edit'])->group(function () {
    Route::get('circles/{circle}/edit', [CircleController::class, 'edit'])->name('circles.edit');
    Route::put('circles/{circle}', [CircleController::class, 'update'])->name('circles.update');
    Route::patch('circles/{circle}', [CircleController::class, 'update']);
});
Route::middleware(['auth', 'can:circles:delete'])->group(function () {
    Route::delete('circles/{circle}', [CircleController::class, 'destroy'])->name('circles.destroy');
});

// Dumping Points
Route::middleware(['auth', 'can:dumping-points:view'])->group(function () {
    Route::get('dumping-points', [DumpingPointController::class, 'index'])->name('dumping-points.index');
});
Route::middleware(['auth', 'can:dumping-points:create'])->group(function () {
    Route::get('dumping-points/create', [DumpingPointController::class, 'create'])->name('dumping-points.create');
    Route::post('dumping-points', [DumpingPointController::class, 'store'])->name('dumping-points.store');
});
Route::middleware(['auth', 'can:dumping-points:edit'])->group(function () {
    Route::get('dumping-points/{dumpingPoint}/edit', [DumpingPointController::class, 'edit'])->name('dumping-points.edit');
    Route::put('dumping-points/{dumpingPoint}', [DumpingPointController::class, 'update'])->name('dumping-points.update');
    Route::patch('dumping-points/{dumpingPoint}', [DumpingPointController::class, 'update']);
});
Route::middleware(['auth', 'can:dumping-points:delete'])->group(function () {
    Route::delete('dumping-points/{dumpingPoint}', [DumpingPointController::class, 'destroy'])->name('dumping-points.destroy');
});

// Pick Up Points
Route::middleware(['auth', 'can:pick-up-points:view'])->group(function () {
    Route::get('pick-up-points', [\App\Http\Controllers\PickUpPointController::class, 'index'])->name('pick-up-points.index');
});
Route::middleware(['auth', 'can:pick-up-points:create'])->group(function () {
    Route::get('pick-up-points/create', [\App\Http\Controllers\PickUpPointController::class, 'create'])->name('pick-up-points.create');
    Route::post('pick-up-points', [\App\Http\Controllers\PickUpPointController::class, 'store'])->name('pick-up-points.store');
});
Route::middleware(['auth', 'can:pick-up-points:edit'])->group(function () {
    Route::get('pick-up-points/{pickUpPoint}/edit', [\App\Http\Controllers\PickUpPointController::class, 'edit'])->name('pick-up-points.edit');
    Route::put('pick-up-points/{pickUpPoint}', [\App\Http\Controllers\PickUpPointController::class, 'update'])->name('pick-up-points.update');
    Route::patch('pick-up-points/{pickUpPoint}', [\App\Http\Controllers\PickUpPointController::class, 'update']);
});
Route::middleware(['auth', 'can:pick-up-points:delete'])->group(function () {
    Route::delete('pick-up-points/{pickUpPoint}', [\App\Http\Controllers\PickUpPointController::class, 'destroy'])->name('pick-up-points.destroy');
});

// Medical Centers
Route::middleware(['auth', 'can:medical-centers:view'])->group(function () {
    Route::get('medical-centers', [MedicalCenterController::class, 'index'])->name('medical-centers.index');
});
Route::middleware(['auth', 'can:medical-centers:create'])->group(function () {
    Route::get('medical-centers/create', [MedicalCenterController::class, 'create'])->name('medical-centers.create');
    Route::post('medical-centers', [MedicalCenterController::class, 'store'])->name('medical-centers.store');
});
Route::middleware(['auth', 'can:medical-centers:edit'])->group(function () {
    Route::get('medical-centers/{medicalCenter}/edit', [MedicalCenterController::class, 'edit'])->name('medical-centers.edit');
    Route::put('medical-centers/{medicalCenter}', [MedicalCenterController::class, 'update'])->name('medical-centers.update');
    Route::patch('medical-centers/{medicalCenter}', [MedicalCenterController::class, 'update']);
});
Route::middleware(['auth', 'can:medical-centers:delete'])->group(function () {
    Route::delete('medical-centers/{medicalCenter}', [MedicalCenterController::class, 'destroy'])->name('medical-centers.destroy');
});

// User & Role Management (Restricted to those with high-level permissions)
Route::middleware(['auth', 'can:users:view'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::middleware(['auth', 'can:roles:view'])->group(function () {
    Route::get('roles/matrix', [RoleController::class, 'matrix'])->name('roles.matrix')->middleware('role:super_admin');
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/impersonate', [RoleController::class, 'impersonate'])->name('roles.impersonate');
});

Route::get('/stop-impersonation', [RoleController::class, 'stopImpersonation'])->name('impersonate.stop');

Route::middleware(['auth', 'can:roles:view'])->group(function () {
    Route::resource('permissions', PermissionController::class);
});

// Activity Logs (Super Admin & Admin only)
Route::middleware(['auth', 'role:super_admin|admin'])->group(function () {
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/{activity}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
});

// Database Backups (Super Admin & Admin)
Route::middleware(['auth', 'role:super_admin|admin'])->group(function () {
    Route::get('/backups', [App\Http\Controllers\BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups/create', [App\Http\Controllers\BackupController::class, 'create'])->name('backups.create');
    Route::get('/backups/download/{filename}', [App\Http\Controllers\BackupController::class, 'download'])->name('backups.download');
    Route::delete('/backups/delete/{filename}', [App\Http\Controllers\BackupController::class, 'delete'])->name('backups.delete');
    Route::post('/backups/restore/{filename}', [App\Http\Controllers\BackupController::class, 'restore'])->name('backups.restore');
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
    Route::get('/', [StaffqController::class, 'index'])->name('staffq.index');
    Route::get('/create', [StaffqController::class, 'create'])->name('staffq.create');
    Route::post('/store', [StaffqController::class, 'store'])->name('staffq.store');
    Route::get('/{staff}/edit', [StaffqController::class, 'edit'])->name('staffq.edit');
    Route::put('/{staff}', [StaffqController::class, 'update'])->name('staffq.update');
    Route::delete('/{staff}', [StaffqController::class, 'destroy'])->name('staffq.destroy');
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

if (config('bank.sandbox.enabled')) {
    Route::get('/developer/sandbox', function () {
        return view('developer.sandbox');
    })->middleware('auth')->name('developer.sandbox');
}
