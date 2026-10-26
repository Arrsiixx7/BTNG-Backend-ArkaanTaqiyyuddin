<?php

use App\Http\Controllers\Api\MobilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 5 Endpoint Wajib CRUD Mobil
Route::apiResource('mobil', MobilController::class);
