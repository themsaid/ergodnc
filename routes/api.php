<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Tags...
Route::get('/tags', \App\Http\Controllers\TagController::class);

// Offices...
Route::prefix('offices/')->group(function(){
    Route::get('/', [\App\Http\Controllers\OfficeController::class, 'index']);
    Route::get('{office}', [\App\Http\Controllers\OfficeController::class, 'show']);

    Route::middleware(['auth:sanctum', 'verified'])->group(function(){

        Route::post('/', [\App\Http\Controllers\OfficeController::class, 'create']);
        Route::put('{office}', [\App\Http\Controllers\OfficeController::class, 'update']);
        Route::delete('{office}', [\App\Http\Controllers\OfficeController::class, 'delete']);

        // Office Photos...
        Route::post('{office}/images', [\App\Http\Controllers\OfficeImageController::class, 'store']);
        Route::delete('{office}/images/{image}', [\App\Http\Controllers\OfficeImageController::class, 'delete']);
    });

});


