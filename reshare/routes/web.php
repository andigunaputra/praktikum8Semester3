<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReshareController;
use App\Http\Controllers\AuthController;

// Pengunjung diarahkan ke login. Pengguna yang sudah masuk diarahkan ke dashboard.
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::get('/barang', [ReshareController::class, 'barang'])->name('barang.index');
Route::get('/barang/{barang}', [ReshareController::class, 'detail'])->whereNumber('barang')->name('barang.detail');

// Modul 2: input -> proses -> tampilkan hasil tanpa database.
Route::get('/simulasi-donasi', [ReshareController::class, 'simulasi'])->name('simulasi.create');
Route::post('/simulasi-donasi/hasil', [ReshareController::class, 'prosesSimulasi'])->name('simulasi.proses');

// Autentikasi manual (Modul 6).
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Semua halaman utama hanya bisa dibuka setelah login.
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ReshareController::class, 'dashboard'])->name('dashboard');
    // Pengelolaan donasi dialihkan ke Modul HMVC Donasi (CRUD AJAX)
    Route::get('/pengajuan', [ReshareController::class, 'pengajuan'])->name('pengajuan.index');
    Route::get('/riwayat', [ReshareController::class, 'riwayat'])->name('riwayat');
    Route::get('/profil', [ReshareController::class, 'profil'])->name('profil');

    // Halaman khusus admin dilindungi middleware role:admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [ReshareController::class, 'admin'])->name('admin.dashboard');
    });
});
