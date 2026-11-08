<?php

use Illuminate\Support\Facades\Route;
use Modules\Donasi\Http\Controllers\DonasiController;

/*
|--------------------------------------------------------------------------
| Web Routes - Modul Donasi (CRUD AJAX)
|--------------------------------------------------------------------------
| Seluruh endpoint AJAX dilindungi oleh middleware 'auth'.
| Hak akses dibatasi di controller:
| - Admin: Melihat dan mengelola seluruh data donasi.
| - User: Hanya melihat dan mengelola data donasi miliknya sendiri.
*/

Route::middleware('auth')->prefix('donasi')->name('donasi.')->group(function () {
    Route::get('/', [DonasiController::class, 'index'])->name('index');
    Route::get('/create', [DonasiController::class, 'index'])->name('create');
    Route::post('/', [DonasiController::class, 'store'])->name('store');
    Route::get('/{id}', [DonasiController::class, 'show'])->whereNumber('id')->name('show');
    Route::post('/{id}', [DonasiController::class, 'update'])->whereNumber('id')->name('update.post');
    Route::put('/{id}', [DonasiController::class, 'update'])->whereNumber('id')->name('update');
    Route::delete('/{id}', [DonasiController::class, 'destroy'])->whereNumber('id')->name('destroy');
});
