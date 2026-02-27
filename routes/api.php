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

// APIs for Medical Request dynamic dropdowns (used in web forms via AJAX)
Route::get('/cities/{city}/medical-centers', [MedicalRequestController::class, 'getMedicalCenters'])->name('api.medical-centers');
Route::get('/provinces/{province}/cities', [MedicalRequestController::class, 'getCities'])->name('api.cities');
Route::get('/citizens/check/{cnic}', [MedicalRequestController::class, 'checkCitizen'])->name('api.citizens.check');

// 1Link Simulation Routes
// Prefixing with /1link for clarity
Route::prefix('1link')->group(function () {
    // Inquiry: GET or POST depending on 1Link spec (Start with POST as it's common for transactional APIs)
    Route::post('/inquiry', [OneLinkController::class, 'inquiry']);
    
    // Payment: POST
    Route::post('/payment', [OneLinkController::class, 'payment']);
});

// Real Bank / 1Bill Integration Routes
Route::prefix('payment')->group(function () {
    Route::post('/inquiry', [PaymentIntegrationController::class, 'inquiry']);
    Route::post('/callback', [PaymentIntegrationController::class, 'paymentCallback']);
});

// Mock Bank Routes (Sandbox)
// In a real app, these would only be available if config('bank.sandbox.enabled') is true
Route::prefix('mock-bank')->group(function () {
    Route::post('/generate-psid', [MockBankController::class, 'generatePsid']);
    Route::post('/payment-status', [MockBankController::class, 'checkStatus']);
    Route::post('/pay', [MockBankController::class, 'forcePay']);
    Route::post('/sync-local-status', [MockBankController::class, 'syncLocalStatus']);
});
