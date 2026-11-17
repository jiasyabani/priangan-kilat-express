<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KurirApiController;

// Login kurir tidak memerlukan token
Route::post('/kurir/login', [KurirApiController::class, 'login']);

// API yang hanya dapat diakses setelah login menggunakan Sanctum
Route::middleware('auth:sanctum')->prefix('kurir')->group(function () {
    Route::post('/ganti-password', [KurirApiController::class, 'changePassword']);
    Route::get('/paket', [KurirApiController::class, 'index']);
    Route::patch('/paket/{paket}/status', [KurirApiController::class, 'updateStatus']);
    Route::post('/logout', [KurirApiController::class, 'logout']);
});
