
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KurirApiController;

// Login kurir tidak memerlukan token.
Route::post('/kurir/login', [KurirApiController::class, 'login']);

// API yang hanya dapat diakses setelah login menggunakan Sanctum.
Route::middleware('auth:sanctum')
    ->prefix('kurir')
    ->group(function () {

        // Mengganti password kurir.
        Route::post('/ganti-password', [
            KurirApiController::class,
            'changePassword'
        ]);

        // Melihat paket yang sedang dibawa kurir.
        Route::get('/paket', [
            KurirApiController::class,
            'index'
        ]);

        // Memperbarui status pengantaran paket.
        Route::patch('/paket/{paket}/status', [
            KurirApiController::class,
            'updateStatus'
        ]);

        // Melihat rekap COD yang belum disetor.
        Route::get('/cod', [
            KurirApiController::class,
            'cod'
        ]);

        // Logout dan mencabut token.
        Route::post('/logout', [
            KurirApiController::class,
            'logout'
        ]);
    });
