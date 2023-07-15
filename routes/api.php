<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CarController;
use App\Http\Controllers\api\RegisterController;
use App\Http\Controllers\api\ReservationController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/register', [RegisterController::class, 'create']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('cars', [CarController::class, 'index']);
    Route::get('cars/search', [CarController::class, 'search']);
    Route::post('/reservation', [ReservationController::class, 'create']);
    Route::get('my-reservations/', [ReservationController::class, 'showMyReservations']);
    Route::put('/reservation/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservation/{id}', [ReservationController::class, 'destroy']);
});


Route::middleware('auth:sanctum', 'admin')->group(function () {
    Route::get('reservations/', [ReservationController::class, 'index']);
    Route::delete('cars/{id}', [CarController::class, 'destroy']);
    Route::put('cars/{id}', [CarController::class, 'update']);
    Route::post('cars', [CarController::class, 'store']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('reservations-export', [ReservationController::class, 'export']);

});









