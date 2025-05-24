<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KelolaMahasiswaController;
use App\Http\Controllers\Admin\KelolaDosenController;
use App\Http\Controllers\Admin\KelolaAdminController;
use App\Http\Controllers\Admin\KategoriKeahlianController;
use App\Http\Controllers\Admin\KelolaLombaController;
use App\Http\Controllers\Admin\PeriodeSemesterController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\TingkatLombaController;
use App\Http\Controllers\Admin\DashboardController as DashboardAdminController;
use App\Http\Controllers\Dosen\DashboardController as DashboardDosenController;
use App\Http\Controllers\Mahasiswa\DashboardController as DashboardMahasiswaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

// Validasi global parameter {id} agar hanya angka
Route::pattern('id', '[0-9]+');

Route::get('home', [HomeController::class, 'index'])->name('home');

// Cek Sesuai role
Route::get('/', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    switch ($user->getRole()) {
        case 'Admin':
            return redirect()->route('admin.dashboard');
        case 'Dosen':
            return redirect()->route('dosen.dashboard');
        case 'Mahasiswa':
            return redirect()->route('mahasiswa.dashboard');
        default:
            abort(403, 'Role tidak dikenali');
    }
});

// Group route untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'postlogin'])->name('login');
    Route::post('register', [AuthController::class, 'postregister']); // Untuk Register jika ada
});

// Route logout hanya untuk yang sudah login
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Route group untuk user yang sudah login
Route::middleware(['auth'])->group(function () { // Masukkan semua route didalam sini ya!

    // Dashboard admin
    Route::middleware('authorize:Admin')->group(function () {
        // Dashboard admin, hanya untuk role admin
        Route::get('/admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

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
            Route::delete('mahasiswa/{id}', [KelolaMahasiswaController::class, 'delete'])->name('mahasiswa.delete'); // Delete
            Route::get('mahasiswa/export_excel', [KelolaMahasiswaController::class, 'export_excel'])->name('mahasiswa.export_excel');  // export excel
            Route::get('mahasiswa/export_pdf', [KelolaMahasiswaController::class, 'export_pdf'])->name('mahasiswa.export_pdf');  // export pdf
            Route::get('mahasiswa/import', [KelolaMahasiswaController::class, 'importForm'])->name('mahasiswa.import.form');  // form import
            Route::post('mahasiswa/import', [KelolaMahasiswaController::class, 'import'])->name('mahasiswa.import');  // import
            Route::get('mahasiswa/history', [KelolaMahasiswaController::class, 'history'])->name('mahasiswa.history');  // history
            Route::get('mahasiswa/list_history', [KelolaMahasiswaController::class, 'list_history'])->name('mahasiswa.list_history');
            Route::get('mahasiswa/history/aktivasi/{id}', [KelolaMahasiswaController::class, 'aktivasi'])->name('mahasiswa.history.aktivasi');
            Route::get('mahasiswa/history/delete/{id}', [KelolaMahasiswaController::class, 'delete_history'])->name('mahasiswa.history.delete');
            Route::delete('mahasiswa/history/destroy/{id}', [KelolaMahasiswaController::class, 'destroy'])->name('mahasiswa.history.destroy');
        });

        // MASTER DATA
        Route::prefix('admin/master-data')->group(function () {
            // Rute Kategori Keahlian
            Route::get('kategori-keahlian', [KategoriKeahlianController::class, 'index'])->name('kategori-keahlian.index');
            Route::post('kategori-keahlian/list', [KategoriKeahlianController::class, 'list'])->name('kategori-keahlian.list');
            Route::get('kategori-keahlian/create', [KategoriKeahlianController::class, 'create'])->name('kategori-keahlian.create');
            Route::get('kategori-keahlian/{id}/show_ajax', [KategoriKeahlianController::class, 'showAjax']);
            Route::get('kategori-keahlian/{id}/edit_ajax', [KategoriKeahlianController::class, 'editAjax']);
            Route::get('kategori-keahlian/{id}/delete_ajax', [KategoriKeahlianController::class, 'deleteAjax']);
            Route::post('kategori-keahlian/store', [KategoriKeahlianController::class, 'store'])->name('kategori-keahlian.store');
            Route::put('kategori-keahlian/{id}', [KategoriKeahlianController::class, 'update'])->name('kategori-keahlian.update');
            Route::put('kategori-keahlian/{id}/delete', [KategoriKeahlianController::class, 'destroy'])->name('kategori-keahlian.destroy'); // Seharusnya ::delete namun karena menggunakan status maka diganti menjadi ::put

            // Rute Periode Semester
            Route::get('periode-semester', [PeriodeSemesterController::class, 'index'])->name('periode-semester.index');
            Route::post('periode-semester/list', [PeriodeSemesterController::class, 'list'])->name('periode-semester.list');
            Route::get('periode-semester/create', [PeriodeSemesterController::class, 'create'])->name('periode-semester.create');
            Route::get('periode-semester/{id}/show_ajax', [PeriodeSemesterController::class, 'showAjax']);
            Route::get('periode-semester/{id}/edit_ajax', [PeriodeSemesterController::class, 'editAjax']);
            Route::get('periode-semester/{id}/delete_ajax', [PeriodeSemesterController::class, 'deleteAjax']);
            Route::post('periode-semester/store', [PeriodeSemesterController::class, 'store'])->name('periode-semester.store');
            Route::put('periode-semester/{id}', [PeriodeSemesterController::class, 'update'])->name('periode-semester.update');
            Route::delete('periode-semester/{id}', [PeriodeSemesterController::class, 'destroy'])->name('periode-semester.destroy');

            // Rute Program Studi
            Route::get('program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
            Route::post('program-studi/list', [ProgramStudiController::class, 'list'])->name('program-studi.list');
            Route::get('program-studi/create', [ProgramStudiController::class, 'create'])->name('program-studi.create');
            Route::get('program-studi/{id}/show_ajax', [ProgramStudiController::class, 'showAjax']);
            Route::get('program-studi/{id}/edit_ajax', [ProgramStudiController::class, 'editAjax']);
            Route::get('program-studi/{id}/delete_ajax', [ProgramStudiController::class, 'deleteAjax']);
            Route::post('program-studi/store', [ProgramStudiController::class, 'store'])->name('program-studi.store');
            Route::put('program-studi/{id}', [ProgramStudiController::class, 'update'])->name('program-studi.update');
            Route::put('program-studi/{id}/delete', [ProgramStudiController::class, 'destroy'])->name('program-studi.destroy'); // Seharusnya ::delete namun karena menggunakan status maka diganti menjadi ::put

            // Rute Tingkat Lomba
            Route::get('tingkat-lomba', [TingkatLombaController::class, 'index'])->name('tingkat-lomba.index');
            Route::post('tingkat-lomba/list', [TingkatLombaController::class, 'list'])->name('tingkat-lomba.list');
            Route::get('tingkat-lomba/create', [TingkatLombaController::class, 'create'])->name('tingkat-lomba.create');
            Route::get('tingkat-lomba/{id}/show_ajax', [TingkatLombaController::class, 'showAjax']);
            Route::get('tingkat-lomba/{id}/edit_ajax', [TingkatLombaController::class, 'editAjax']);
            Route::get('tingkat-lomba/{id}/delete_ajax', [TingkatLombaController::class, 'deleteAjax']);
            Route::post('tingkat-lomba/store', [TingkatLombaController::class, 'store'])->name('tingkat-lomba.store');
            Route::put('tingkat-lomba/{id}', [TingkatLombaController::class, 'update'])->name('tingkat-lomba.update');
            Route::put('tingkat-lomba/{id}/delete', [TingkatLombaController::class, 'destroy'])->name('tingkat-lomba.destroy'); // Seharusnya ::delete namun karena menggunakan status maka diganti menjadi ::put
        });

        // MANAJEMEN LOMBA
        Route::prefix('admin/manajemen-lomba')->group(function () {
            // Rute Kelola Lomba
            Route::get('kelola-lomba', [KelolaLombaController::class, 'index'])->name('kelola-lomba.index');
            Route::post('kelola-lomba/list', [KelolaLombaController::class, 'list'])->name('kelola-lomba.list');
            Route::get('kelola-lomba/create', [KelolaLombaController::class, 'create'])->name('kelola-lomba.create');
            Route::get('kelola-lomba/{id}/show_ajax', [KelolaLombaController::class, 'showAjax']);
            Route::get('kelola-lomba/{id}/edit_ajax', [KelolaLombaController::class, 'editAjax']);
            Route::get('kelola-lomba/{id}/delete_ajax', [KelolaLombaController::class, 'deleteAjax']);
            Route::post('kelola-lomba/store', [KelolaLombaController::class, 'store'])->name('kelola-lomba.store');
            Route::put('kelola-lomba/{id}', [KelolaLombaController::class, 'update'])->name('kelola-lomba.update');
            Route::delete('kelola-lomba/{id}', [KelolaLombaController::class, 'destroy'])->name('kelola-lomba.destroy'); // Seharusnya ::delete namun karena menggunakan status maka diganti menjadi ::put
        });
    });

    // Dashboard dosen, hanya untuk role dosen
    Route::middleware('authorize:dosen')->group(function () {
        Route::get('/Dosen', [DashboardDosenController::class, 'index'])->name('dosen.dashboard');
    });

    // Dashboard mahasiswa, hanya untuk role mahasiswa
    Route::middleware('authorize:mahasiswa')->group(function () {
        Route::get('/Mahasiswa', [DashboardMahasiswaController::class, 'index'])->name('mahasiswa.dashboard');
    });
});
