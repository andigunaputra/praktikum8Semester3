<?php

use Illuminate\Support\Facades\Route;
use Modules\UserManagement\Http\Controllers\UserManagementController;

Route::middleware(['web', 'auth', 'role:admin'])->group(function () {

    // Menampilkan daftar pengguna
    Route::get('/kelola-pengguna', [UserManagementController::class, 'index'])
        ->name('usermanagement.index');

    // Menambahkan pengguna
    Route::post('/kelola-pengguna', [UserManagementController::class, 'store'])
        ->name('usermanagement.store');

    // Mengubah pengguna
    Route::put('/kelola-pengguna/{id}', [UserManagementController::class, 'update'])
        ->name('usermanagement.update');

    // Menghapus pengguna
    Route::delete('/kelola-pengguna/{id}', [UserManagementController::class, 'destroy'])
        ->name('usermanagement.destroy');

});
