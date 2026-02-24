<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuickBookingController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Web\LandingController;

/*
|--------------------------------------------------------------------------
| PUBLIC API
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->name('api.')->group(function () {

    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{slug}', [ServiceController::class, 'show']);

    Route::post('/bookings/quick', [QuickBookingController::class, 'store']);
    Route::get('/bookings/track/{number}', [QuickBookingController::class, 'track']);
    Route::get('/bookings/slots/{date}', [QuickBookingController::class, 'slots']);

    Route::post('/otp/send', [OtpController::class, 'send'])->middleware('throttle:5,1');
    Route::post('/otp/verify', [OtpController::class, 'verify']);

    Route::post('/consultation', [LandingController::class, 'freeConsultation']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', fn () => auth()->user());
        Route::put('/user', [UserController::class, 'update']);
        Route::apiResource('/bookings', BookingController::class)->only(['index','store','show']);
        Route::post('/bookings/{number}/review', [BookingController::class, 'review']);
    });

});