<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/packages', [App\Http\Controllers\Api\PackageController::class, 'index']);

// ── Smart Booking API ────────────────────────────────────────────────────────
Route::prefix('smart-booking')->group(function () {
    Route::get('/init',         [App\Http\Controllers\Api\SmartBookingController::class, 'init']);
    Route::get('/services',     [App\Http\Controllers\Api\SmartBookingController::class, 'services']);
    Route::get('/packages',     [App\Http\Controllers\Api\SmartBookingController::class, 'packages']);
    Route::get('/venues',       [App\Http\Controllers\Api\SmartBookingController::class, 'venues']);
    Route::get('/availability', [App\Http\Controllers\Api\SmartBookingController::class, 'availability']);
    Route::post('/price',       [App\Http\Controllers\Api\SmartBookingController::class, 'price'])->middleware('throttle:60,1');
    Route::post('/submit',      [App\Http\Controllers\Api\SmartBookingController::class, 'submit'])->middleware('throttle:10,1');
});