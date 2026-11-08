<?php

use Illuminate\Support\Facades\Route;
use Modules\Barang\Http\Controllers\BarangController;

/*
|--------------------------------------------------------------------------
| Web Routes - Modul Barang (CRUD Synchronous)
|--------------------------------------------------------------------------
| Seluruh aksi CRUD pengelolaan data barang dilindungi oleh middleware
| 'auth' dan 'role:admin'. User non-admin yang mencoba mengakses akan
| menerima respon HTTP 403 Forbidden.
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin/barang')->name('admin.barang.')->group(function () {
    Route::get('/', [BarangController::class, 'index'])->name('index');
    Route::get('/create', [BarangController::class, 'create'])->name('create');
    Route::post('/', [BarangController::class, 'store'])->name('store');
    Route::get('/{id}', [BarangController::class, 'show'])->whereNumber('id')->name('show');
    Route::get('/{id}/edit', [BarangController::class, 'edit'])->whereNumber('id')->name('edit');
    Route::put('/{id}', [BarangController::class, 'update'])->whereNumber('id')->name('update');
    Route::delete('/{id}', [BarangController::class, 'destroy'])->whereNumber('id')->name('destroy');
});
