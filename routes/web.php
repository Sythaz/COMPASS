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

// KELOLA USER (ADMIN)
Route::prefix('admin/kelola-pengguna')->group(function () {
    // Halaman utama list admin
    Route::get('admin', [KelolaAdminController::class, 'index'])->name('admin.index');
    // DataTables AJAX list admin
    Route::get('admin/list', [KelolaAdminController::class, 'list'])->name('admin.list');
    // AJAX modal actions
    Route::get('admin/create', [KelolaAdminController::class, 'create'])->name('admin.create');
    Route::get('admin/{id}/show_ajax', [KelolaAdminController::class, 'showAjax']);
    Route::get('admin/{id}/edit_ajax', [KelolaAdminController::class, 'editAjax']);
    Route::get('admin/{id}/delete_ajax', [KelolaAdminController::class, 'deleteAjax']);
    // Store, update, delete admin
    Route::post('admin/store', [KelolaAdminController::class, 'store'])->name('admin.store');
    Route::put('admin/{id}', [KelolaAdminController::class, 'update'])->name('admin.update');
    Route::delete('admin/{id}', [KelolaAdminController::class, 'destroy'])->name('admin.destroy');

    // Rute resource untuk dosen dan mahasiswa
    Route::resource('dosen', KelolaDosenController::class);
    Route::resource('mahasiswa', KelolaMahasiswaController::class);
});

