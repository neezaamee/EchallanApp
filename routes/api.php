<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OneLinkController;
use App\Http\Controllers\Api\MockBankController;
use App\Http\Controllers\MedicalRequestController;
use App\Http\Controllers\Api\PaymentIntegrationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// APIs for Medical Request dynamic dropdowns & citizen lookup (used in web forms via AJAX)
Route::middleware(['web', 'auth', 'throttle:60,1'])->group(function () {
    Route::get('/cities/{city}/medical-centers', [MedicalRequestController::class, 'getMedicalCenters'])->name('api.medical-centers');
    Route::get('/provinces/{province}/cities', [MedicalRequestController::class, 'getCities'])->name('api.cities');
    Route::get('/citizens/check/{cnic}', [MedicalRequestController::class, 'checkCitizen'])->name('api.citizens.check');
});

// 1Link Simulation Routes
Route::prefix('1link')->middleware(['bank.token', 'bank.ip', 'throttle:60,1'])->group(function () {
    Route::post('/inquiry', [OneLinkController::class, 'inquiry']);
    Route::post('/payment', [OneLinkController::class, 'payment']);
});

// Real Bank / 1Bill Integration Routes
Route::prefix('payment')->middleware(['bank.token', 'bank.ip', 'throttle:60,1'])->group(function () {
    Route::post('/inquiry', [PaymentIntegrationController::class, 'inquiry']);
    Route::post('/callback', [PaymentIntegrationController::class, 'paymentCallback']);
});

// Mock Bank Routes (Sandbox only)
Route::prefix('mock-bank')->middleware(['bank.sandbox', 'throttle:30,1'])->group(function () {
    Route::post('/generate-psid', [MockBankController::class, 'generatePsid']);
    Route::post('/payment-status', [MockBankController::class, 'checkStatus']);
    Route::post('/pay', [MockBankController::class, 'forcePay']);
    Route::post('/sync-local-status', [MockBankController::class, 'syncLocalStatus']);
});

