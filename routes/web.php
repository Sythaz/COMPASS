<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KelolaMahasiswaController;
use App\Http\Controllers\Admin\KelolaDosenController;
use App\Http\Controllers\Admin\KelolaAdminController;
use App\Http\Controllers\Admin\DashboardController as DashboardAdminController;
use App\Http\Controllers\Dosen\DashboardController as DashboardDosenController;
use App\Http\Controllers\Mahasiswa\DashboardController as DashboardMahasiswaController;

// Rute Login
Route::get('/', function () {
    return view('auth.login');
});

// Tampilan awal DASHBOARD
Route::get('/admin', [DashboardAdminController::class, 'index'])->name('dashboard');
Route::get('/dosen', [DashboardDosenController::class, 'index']);
Route::get('/mahasiswa', [DashboardMahasiswaController::class, 'index']);

//KELOLA USER (ADMIN)
Route::group(['prefix' => 'admin/kelola-pengguna'], function () {
    // Rute untuk CRUD Mahasiswa
    Route::resource('mahasiswa', KelolaMahasiswaController::class);
    // Rute untuk CRUD Dosen
    Route::resource('dosen', KelolaDosenController::class);
    // Rute untuk CRUD Admin
    Route::resource('admin', KelolaAdminController::class);
});
