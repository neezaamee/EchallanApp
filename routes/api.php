<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OneLinkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 1Link Simulation Routes
// Prefixing with /1link for clarity
Route::prefix('1link')->group(function () {
    // Inquiry: GET or POST depending on 1Link spec (Start with POST as it's common for transactional APIs)
    Route::post('/inquiry', [OneLinkController::class, 'inquiry']);
    
    // Payment: POST
    Route::post('/payment', [OneLinkController::class, 'payment']);
});
