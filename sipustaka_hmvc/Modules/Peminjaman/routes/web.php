
<?php

use Illuminate\Support\Facades\Route;
use Modules\Peminjaman\Http\Controllers\PeminjamanController;

Route::middleware(['web', 'auth'])->group(function () {

    // Menampilkan data peminjaman
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->name('peminjaman.index');

    // Menambahkan data peminjaman
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])
        ->name('peminjaman.store');

    // Mengubah data peminjaman
    Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])
        ->name('peminjaman.update');

    // Menghapus data peminjaman
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])
        ->name('peminjaman.destroy');

});
