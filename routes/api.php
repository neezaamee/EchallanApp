<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MockBankController;

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
Route::get('/cities/{city}/medical-centers', [\App\Http\Controllers\MedicalRequestController::class, 'getMedicalCenters'])->name('api.medical-centers');
Route::get('/provinces/{province}/cities', [\App\Http\Controllers\MedicalRequestController::class, 'getCities'])->name('api.cities');
Route::get('/citizens/check/{cnic}', [\App\Http\Controllers\MedicalRequestController::class, 'checkCitizen'])->name('api.citizens.check');

// Real Bank / 1Bill Integration Routes
Route::prefix('payment')->group(function () {
    Route::post('/inquiry', [\App\Http\Controllers\Api\PaymentIntegrationController::class, 'inquiry']);
    Route::post('/callback', [\App\Http\Controllers\Api\PaymentIntegrationController::class, 'paymentCallback']);
});

// Mock Bank Routes (Sandbox)
// In a real app, these would only be available if config('bank.sandbox.enabled') is true
Route::prefix('mock-bank')->group(function () {
    Route::post('/generate-psid', [MockBankController::class, 'generatePsid']);
    Route::post('/payment-status', [MockBankController::class, 'checkStatus']);
    Route::post('/pay', [MockBankController::class, 'forcePay']);
    Route::post('/sync-local-status', [MockBankController::class, 'syncLocalStatus']);
});
