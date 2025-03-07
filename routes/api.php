<?php

use App\Http\Controllers\Api\ApiBookingsController;
use App\Http\Controllers\Api\ApiProductsController;
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

Route::middleware(
    'auth:sanctum'
)->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('products', ApiProductsController::class);

Route::post('v1/bookings/{bookingId}/cancel', [ApiBookingsController::class,  'cancel']);
Route::post('v1/bookings', [ApiBookingsController::class,  'store']);
