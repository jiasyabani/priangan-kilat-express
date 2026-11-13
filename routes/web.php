<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route untuk menampilkan halaman login Admin.
Route::view('/admin/login', 'auth.login')
    ->name('admin.login');

// Route untuk seluruh halaman Admin.
Route::prefix('admin')->name('admin.')->group(function () {

    // Halaman dashboard Admin.
    Route::view('/', 'admin.dashboard')
        ->name('dashboard');

    // Halaman pengelolaan paket.
    Route::view('/packages', 'admin.packages.index')
        ->name('packages.index');

    // Halaman input paket baru.
    Route::view('/packages/create', 'admin.packages.create')
        ->name('packages.create');

    // Halaman penugasan dan pengiriman ulang.
    Route::view('/assignments', 'admin.assignments.index')
        ->name('assignments.index');

    // Halaman data pelanggan.
    Route::view('/customers', 'admin.customers.index')
        ->name('customers.index');

    // Halaman data kurir dan akun kurir.
    Route::view('/couriers', 'admin.couriers.index')
        ->name('couriers.index');

    // Halaman pencatatan setoran COD.
    Route::view('/cod', 'admin.cod.index')
        ->name('cod.index');

    // Halaman melihat tarif pengiriman.
    Route::view('/shipping-rates', 'admin.shipping-rates.index')
        ->name('shipping-rates.index');
});