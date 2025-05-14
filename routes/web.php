<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KelolaMahasiswaController;
use App\Http\Controllers\Admin\KelolaDosenController;
use App\Http\Controllers\Admin\KelolaAdminController;

// Tampilan awal sementara
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

//KELOLA USER (ADMIN)
Route::group(['prefix' => 'admin/kelola-pengguna'], function () {
    // Rute untuk CRUD Mahasiswa
    Route::resource('mahasiswa', KelolaMahasiswaController::class);
    // Rute untuk CRUD Dosen
    Route::resource('dosen', KelolaDosenController::class);
    // Rute untuk CRUD Admin
    Route::resource('admin', KelolaAdminController::class);
});

Route::get('/login', function () {
    return view('auth.login');
});
