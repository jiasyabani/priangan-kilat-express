
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
use App\Http\Controllers\RiwayatStatusController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\ManagerShippingReportController;
use App\Http\Controllers\ManagerRevenueReportController;
use App\Http\Controllers\ManagerCodReportController;
use App\Http\Controllers\ManagerCustomerController;
use App\Http\Controllers\ManagerCourierController;
use App\Http\Controllers\TrackingController;

// =========================
// HALAMAN UTAMA
// =========================

Route::get('/', function () {
    return view('welcome');
});
// Tracking resi publik tanpa login.
Route::get('/tracking', [TrackingController::class, 'index'])
    ->name('tracking.index');

// =========================
// LOGIN
// =========================

Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login.process');


// =========================
// PENGGUNA YANG SUDAH LOGIN
// =========================

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

            Route::get('/packages/create', [PaketController::class, 'create'])
                ->name('packages.create');

            Route::get('/packages/check-zone', [PaketController::class, 'checkZone'])
                ->name('packages.check-zone');

            Route::get('/packages/{paket}', [PaketController::class, 'show'])
                ->name('packages.show');

            Route::post('/packages', [PaketController::class, 'store'])
                ->name('packages.store');


            // Penugasan Kurir
            Route::get('/assignments', [AssignmentController::class, 'index'])
                ->name('assignments.index');

            Route::post('/assignments/{paket}/assign', [AssignmentController::class, 'store'])
                ->name('assignments.store');


            // Data Pelanggan
            Route::get('/customers', [PelangganController::class, 'index'])
                ->name('customers.index');

            Route::get('/customers/create', [PelangganController::class, 'create'])
                ->name('customers.create');

            Route::post('/customers', [PelangganController::class, 'store'])
                ->name('customers.store');

            Route::get('/customers/{pelanggan}/edit', [PelangganController::class, 'edit'])
                ->name('customers.edit');

            Route::put('/customers/{pelanggan}', [PelangganController::class, 'update'])
                ->name('customers.update');


            // Data Kurir
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

            Route::post('/cod', [SetoranCodController::class, 'store'])
                ->name('cod.store');


            // Tarif Pengiriman
            Route::get('/shipping-rates', [TarifController::class, 'index'])
                ->name('shipping-rates.index');


            // Perubahan Status Paket
            Route::patch('/packages/{paket}/transit', [StatusPaketController::class, 'transit'])
                ->name('packages.transit');

            Route::patch('/packages/{paket}/retur', [StatusPaketController::class, 'retur'])
                ->name('packages.retur');


            // Riwayat Status Paket
            Route::get('/history', [RiwayatStatusController::class, 'index'])
                ->name('history.index');

        });


    // =========================
    // HALAMAN KHUSUS MANAGER
    // =========================

    Route::prefix('manager')
        ->name('manager.')
        ->middleware('role:manajer')
        ->group(function () {

            // Dashboard Manager
            Route::get('/', [ManagerDashboardController::class, 'index'])
                ->name('dashboard');


            // Pengaturan Tarif
            Route::get('/shipping-rates', [TarifController::class, 'managerIndex'])
                ->name('shipping-rates.index');

            Route::put('/shipping-rates/{tarif}', [TarifController::class, 'update'])
                ->name('shipping-rates.update');


            // Laporan
            Route::get('/reports', [ManagerShippingReportController::class, 'index'])
                 ->name('reports.index');

            // Laporan Pendapatan
            Route::get('/revenue-reports', [ManagerRevenueReportController::class, 'index'])
                ->name('revenue-reports.index');
            
            // Data Pelanggan

           Route::get('/customers', [ManagerCustomerController::class, 'index'])
                 ->name('customers.index');

            // Data Kurir
            Route::get('/couriers', [ManagerCourierController::class, 'index'])
                ->name('couriers.index');

                // Rekap COD
            Route::get('/cod-reports', [ManagerCodReportController::class, 'index'])
                ->name('cod-reports.index');

        });

});
