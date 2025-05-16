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
    // Rute Kelola Admin
    Route::get('admin', [KelolaAdminController::class, 'index'])->name('admin.index');             // Halaman utama list Admin           
    Route::get('admin/list', [KelolaAdminController::class, 'list'])->name('admin.list');          // DataTable list Admin  
    Route::get('admin/create', [KelolaAdminController::class, 'create'])->name('admin.create');    // Modal actions Create
    Route::get('admin/{id}/show_ajax', [KelolaAdminController::class, 'showAjax']);                      // Show modal actions
    Route::get('admin/{id}/edit_ajax', [KelolaAdminController::class, 'editAjax']);                      // Edit modal actions
    Route::get('admin/{id}/delete_ajax', [KelolaAdminController::class, 'deleteAjax']);                  // Delete modal actions
    Route::post('admin/store', [KelolaAdminController::class, 'store'])->name('admin.store');      // Store 
    Route::put('admin/{id}', [KelolaAdminController::class, 'update'])->name('admin.update');      // update
    Route::delete('admin/{id}', [KelolaAdminController::class, 'destroy'])->name('admin.destroy'); //delete
    // Rute Kelola Dosen
    Route::get('dosen', [KelolaDosenController::class, 'index'])->name('dosen.index');             // Halaman utama list Dosen
    Route::get('dosen/list', [KelolaDosenController::class, 'list'])->name('dosen.list');          // DataTable list Dosen
    Route::get('dosen/create', [KelolaDosenController::class, 'create'])->name('dosen.create');    // Modal actions Create
    Route::get('dosen/{id}/show_ajax', [KelolaDosenController::class, 'showAjax']);                      // Show modal actions
    Route::get('dosen/{id}/edit_ajax', [KelolaDosenController::class, 'editAjax']);                      // Edit modal actions
    Route::get('dosen/{id}/delete_ajax', [KelolaDosenController::class, 'deleteAjax']);                  // Delete modal actions
    Route::post('dosen/store', [KelolaDosenController::class, 'store'])->name('dosen.store');      // Store
    Route::put('dosen/{id}', [KelolaDosenController::class, 'update'])->name('dosen.update');      // update
    Route::delete('dosen/{id}', [KelolaDosenController::class, 'destroy'])->name('dosen.destroy'); //delete
    // Rute Kelola Mahasiswa
    Route::get('mahasiswa', [KelolaMahasiswaController::class, 'index'])->name('mahasiswa.index');             // Halaman utama list Mahasiswa
    Route::get('mahasiswa/list', [KelolaMahasiswaController::class, 'list'])->name('mahasiswa.list');          // DataTable list Mahasiswa
    Route::get('mahasiswa/create', [KelolaMahasiswaController::class, 'create'])->name('mahasiswa.create');    // Modal actions Create
    Route::get('mahasiswa/{id}/show_ajax', [KelolaMahasiswaController::class, 'showAjax']);                          // Show modal actions
    Route::get('mahasiswa/{id}/edit_ajax', [KelolaMahasiswaController::class, 'editAjax']);                          // Edit modal actions
    Route::get('mahasiswa/{id}/delete_ajax', [KelolaMahasiswaController::class, 'deleteAjax']);                      // Delete modal actions
    Route::post('mahasiswa/store', [KelolaMahasiswaController::class, 'store'])->name('mahasiswa.store');      // Store
    Route::put('mahasiswa/{id}', [KelolaMahasiswaController::class, 'update'])->name('mahasiswa.update');      // Update
    Route::delete('mahasiswa/{id}', [KelolaMahasiswaController::class, 'destroy'])->name('mahasiswa.destroy'); // Delete
});

