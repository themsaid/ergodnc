<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{LoginController,
    LogoutController,
    RegisterController,
    TagController,
    OfficeController,
    OfficeImageController,
    UserController,
    UserReservationController,
    HostReservationController};

// Auth ...
Route::post('/login', LoginController::class);
Route::post('/register', RegisterController::class);
Route::post('/logout', LogoutController::class);

// Tags...
Route::get('/tags', TagController::class);

// User ...
Route::get('/user', UserController::class)->middleware(['auth:sanctum']);

// Offices...
Route::controller(OfficeController::class)->prefix('offices')->group(function (){
    Route::get('', 'index');
    Route::get('/{office}', 'show');
    Route::middleware('auth:sanctum')->group(function (){         
        Route::post('/', 'create')->middleware(['auth:sanctum', 'verified']);
        Route::put('/{office}', 'update')->middleware(['auth:sanctum', 'verified']);
        Route::delete('/{office}', 'delete')->middleware(['auth:sanctum', 'verified']);
    });
});

// Office Photos...
Route::controller(OfficeImageController::class)->prefix('offices')->middleware(['auth:sanctum', 'verified'])->group(function (){
    Route::post('/{office}/images', 'store');
    Route::delete('/{office}/images/{image:id}', 'delete');
});

// User Reservations...
Route::controller(UserReservationController::class)->prefix('reservations')->middleware(['auth:sanctum', 'verified'])->group(function (){
    Route::get('/', 'index');
    Route::post('/reservations', 'create');
    Route::delete('/reservations/{reservation}', 'cancel');
});

// Host Reservations...
Route::get('/host/reservations', [HostReservationController::class, 'index']);
