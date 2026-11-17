
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\SetoranCodController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\StatusPaketController;


// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login.process');

// Route untuk pengguna yang sudah login
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // =========================
    // HALAMAN KHUSUS ADMIN
    // =========================
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {

            // Dashboard
            Route::view('/', 'admin.dashboard')
                ->name('dashboard');

            // Data Paket
            Route::get('/packages', [PaketController::class, 'index'])
                ->name('packages.index');

            // Form Input Paket
            Route::get('/packages/create', [PaketController::class, 'create'])
                ->name('packages.create');

            // Mencari zona berdasarkan kota tujuan
            Route::get('/packages/check-zone', [PaketController::class, 'checkZone'])
                ->name('packages.check-zone');

            // Penugasan Kurir
            Route::get('/assignments', [AssignmentController::class, 'index'])
                ->name('assignments.index');

            Route::post('/assignments/{paket}/assign', [AssignmentController::class, 'store'])
                ->name('assignments.store');

            // Data Pelanggan
            Route::get('/customers', [PelangganController::class, 'index'])
                ->name('customers.index');

            // Pengelolaan Data Kurir dan Akun Kurir oleh Admin
            Route::get('/couriers', [KurirController::class, 'index'])
                ->name('couriers.index');

            Route::get('/couriers/create', [KurirController::class, 'create'])
                ->name('couriers.create');

            Route::post('/couriers', [KurirController::class, 'store'])
                ->name('couriers.store');

            Route::get('/couriers/{kurir}/edit', [KurirController::class, 'edit'])
                ->name('couriers.edit');

            Route::put('/couriers/{kurir}', [KurirController::class, 'update'])
                ->name('couriers.update');

            Route::patch('/couriers/{kurir}/deactivate', [KurirController::class, 'deactivate'])
                ->name('couriers.deactivate');

            Route::patch('/couriers/{kurir}/reset-password', [KurirController::class, 'resetPassword'])
                ->name('couriers.reset-password');

            // Setoran COD
            Route::get('/cod', [SetoranCodController::class, 'index'])
                ->name('cod.index');

            // Menyimpan pencatatan setoran COD.
             Route::post('/cod', [SetoranCodController::class, 'store'])
                 ->name('cod.store');

            // Tarif Pengiriman
            
            Route::get('/shipping-rates', [TarifController::class, 'index'])
                ->name('shipping-rates.index');

            // Perubahan status paket oleh Admin
            Route::patch('/packages/{paket}/transit', [StatusPaketController::class, 'transit'])
                ->name('packages.transit');

            Route::patch('/packages/{paket}/retur', [StatusPaketController::class, 'retur'])
                ->name('packages.retur');

        });

    // =========================
    // HALAMAN KHUSUS MANAJER
    // =========================
    Route::prefix('manager')
        ->name('manager.')
        ->middleware('role:manajer')
        ->group(function () {

            // Dashboard Manajer
            Route::view('/', 'manager.dashboard')
                ->name('dashboard');

            // Tarif Pengiriman
            Route::view('/shipping-rates', 'manager.shipping-rates.index')
                ->name('shipping-rates.index');

            // Laporan
            Route::view('/reports', 'manager.reports.index')
                ->name('reports.index');

            // Data Pelanggan
            Route::view('/customers', 'manager.customers.index')
                ->name('customers.index');

            // Data Kurir
            Route::view('/couriers', 'manager.couriers.index')
                ->name('couriers.index');
        });
});
