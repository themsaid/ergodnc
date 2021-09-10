<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/tags', \App\Http\Controllers\TagController::class);
