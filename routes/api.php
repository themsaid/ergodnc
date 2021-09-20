<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Tags...
Route::get('/tags', \App\Http\Controllers\TagController::class);

// Offices...
Route::get('/offices', [\App\Http\Controllers\OfficeController::class, 'index']);
Route::get('/offices/{office}', [\App\Http\Controllers\OfficeController::class, 'show']);
Route::post('/offices', [\App\Http\Controllers\OfficeController::class, 'create'])->middleware(['auth:sanctum', 'verified']);
Route::put('/offices/{office}', [\App\Http\Controllers\OfficeController::class, 'update'])->middleware(['auth:sanctum', 'verified']);
Route::delete('/offices/{office}', [\App\Http\Controllers\OfficeController::class, 'delete'])->middleware(['auth:sanctum', 'verified']);
