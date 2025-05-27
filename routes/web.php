<?php

use Illuminate\Console\View\Components\Info;
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
use App\Http\Controllers\Admin\KelolaPrestasiController;
use App\Http\Controllers\Admin\VerifikasiLombaController;
use App\Http\Controllers\Admin\VerifikasiPrestasiController;
use App\Http\Controllers\Dosen\DashboardController as DashboardDosenController;
use App\Http\Controllers\Dosen\ProfileDosenController as ProfileDosenController;
use App\Http\Controllers\Mahasiswa\DashboardController as DashboardMahasiswaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Mahasiswa\InputLombaController;
use App\Http\Controllers\Mahasiswa\InputPrestasiController;
use App\Http\Controllers\Dosen\ManajemenBimbinganController;
use App\Http\Controllers\Dosen\InfoLombaController;

// Validasi global parameter {id} agar hanya angka
Route::pattern('id', '[0-9]+');

// Redirect root ke home (akan dicek di HomeController)
Route::get('/', function () {
    return redirect()->route('home');
});

// Halaman home, cek role di dalam controllernya
Route::get('home', [HomeController::class, 'index'])->name('home');

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
            Route::delete('admin/{id}', [KelolaAdminController::class, 'nonAktif'])->name('admin.nonAktif'); //delete
            Route::get('admin/export_excel', [KelolaAdminController::class, 'export_excel'])->name('admin.export_excel');  // export excel
            Route::get('admin/export_pdf', [KelolaAdminController::class, 'export_pdf'])->name('admin.export_pdf');        // export pdf
            Route::get('admin/import', [KelolaAdminController::class, 'importForm'])->name('admin.import.form');           // form import
            Route::post('admin/import', [KelolaAdminController::class, 'import'])->name('admin.import');                   // import
            Route::get('admin/history', [KelolaAdminController::class, 'history'])->name('admin.history');                           // history
            Route::get('admin/list_history', [KelolaAdminController::class, 'list_history'])->name('admin.list_history');            // List history
            Route::get('admin/history/aktivasi/{id}', [KelolaAdminController::class, 'aktivasi'])->name('admin.history.aktivasi');   // Aktivasi akun kembali
            Route::get('admin/history/delete/{id}', [KelolaAdminController::class, 'delete_history'])->name('admin.history.delete'); // Konfirmasi hapus Permanen
            Route::delete('admin/history/destroy/{id}', [KelolaAdminController::class, 'destroy'])->name('admin.history.destroy');   // Hapus Permanen

            // Rute Kelola Dosen
            Route::get('dosen', [KelolaDosenController::class, 'index'])->name('dosen.index');             // Halaman utama list Dosen
            Route::get('dosen/list', [KelolaDosenController::class, 'list'])->name('dosen.list');          // DataTable list Dosen
            Route::get('dosen/create', [KelolaDosenController::class, 'create'])->name('dosen.create');    // Modal actions Create
            Route::get('dosen/{id}/show_ajax', [KelolaDosenController::class, 'showAjax']);                      // Show modal actions
            Route::get('dosen/{id}/edit_ajax', [KelolaDosenController::class, 'editAjax']);                      // Edit modal actions
            Route::get('dosen/{id}/delete_ajax', [KelolaDosenController::class, 'deleteAjax']);                  // Delete modal actions
            Route::post('dosen/store', [KelolaDosenController::class, 'store'])->name('dosen.store');      // Store
            Route::put('dosen/{id}', [KelolaDosenController::class, 'update'])->name('dosen.update');      // update
            Route::delete('dosen/{id}', [KelolaDosenController::class, 'nonAktif'])->name('dosen.nonAktif'); // Nonaktifkan Akun
            Route::get('dosen/export_excel', [KelolaDosenController::class, 'export_excel'])->name('dosen.export_excel');  // export excel
            Route::get('dosen/export_pdf', [KelolaDosenController::class, 'export_pdf'])->name('dosen.export_pdf');        // export pdf
            Route::get('dosen/import', [KelolaDosenController::class, 'importForm'])->name('dosen.import.form');           // form import
            Route::post('dosen/import', [KelolaDosenController::class, 'import'])->name('dosen.import');                   // import excel
            Route::get('dosen/history', [KelolaDosenController::class, 'history'])->name('dosen.history');                           // history
            Route::get('dosen/list_history', [KelolaDosenController::class, 'list_history'])->name('dosen.list_history');            // List history
            Route::get('dosen/history/aktivasi/{id}', [KelolaDosenController::class, 'aktivasi'])->name('dosen.history.aktivasi');   // Aktivasi akun kembali
            Route::get('dosen/history/delete/{id}', [KelolaDosenController::class, 'delete_history'])->name('dosen.history.delete'); // Konfirmasi hapus Permanen
            Route::delete('dosen/history/destroy/{id}', [KelolaDosenController::class, 'destroy'])->name('dosen.history.destroy');   // Hapus Permanen

            // Rute Kelola Mahasiswa
            Route::get('mahasiswa', [KelolaMahasiswaController::class, 'index'])->name('mahasiswa.index');             // Halaman utama list Mahasiswa
            Route::get('mahasiswa/list', [KelolaMahasiswaController::class, 'list'])->name('mahasiswa.list');          // DataTable list Mahasiswa
            Route::get('mahasiswa/create', [KelolaMahasiswaController::class, 'create'])->name('mahasiswa.create');    // Modal actions Create
            Route::get('mahasiswa/{id}/show_ajax', [KelolaMahasiswaController::class, 'showAjax']);                          // Show modal actions
            Route::get('mahasiswa/{id}/edit_ajax', [KelolaMahasiswaController::class, 'editAjax']);                          // Edit modal actions
            Route::get('mahasiswa/{id}/delete_ajax', [KelolaMahasiswaController::class, 'deleteAjax']);                      // Delete modal actions
            Route::post('mahasiswa/store', [KelolaMahasiswaController::class, 'store'])->name('mahasiswa.store');      // Store
            Route::put('mahasiswa/{id}', [KelolaMahasiswaController::class, 'update'])->name('mahasiswa.update');      // Update
            Route::delete('mahasiswa/{id}', [KelolaMahasiswaController::class, 'nonAktif'])->name('mahasiswa.nonAktif'); // Nonaktifkan Akun
            Route::get('mahasiswa/export_excel', [KelolaMahasiswaController::class, 'export_excel'])->name('mahasiswa.export_excel');  // export excel
            Route::get('mahasiswa/export_pdf', [KelolaMahasiswaController::class, 'export_pdf'])->name('mahasiswa.export_pdf');        // export pdf
            Route::get('mahasiswa/import', [KelolaMahasiswaController::class, 'importForm'])->name('mahasiswa.import.form');           // form import
            Route::post('mahasiswa/import', [KelolaMahasiswaController::class, 'import'])->name('mahasiswa.import');                   // import
            Route::get('mahasiswa/history', [KelolaMahasiswaController::class, 'history'])->name('mahasiswa.history');                            // View history
            Route::get('mahasiswa/list_history', [KelolaMahasiswaController::class, 'list_history'])->name('mahasiswa.list_history');             // List history
            Route::get('mahasiswa/history/aktivasi/{id}', [KelolaMahasiswaController::class, 'aktivasi'])->name('mahasiswa.history.aktivasi');    // Aktivasi akun kembali
            Route::get('mahasiswa/history/delete/{id}', [KelolaMahasiswaController::class, 'delete_history'])->name('mahasiswa.history.delete');  // Konfirmasi hapus Permanen
            Route::delete('mahasiswa/history/destroy/{id}', [KelolaMahasiswaController::class, 'destroy'])->name('mahasiswa.history.destroy');    // Hapus Permanen
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

            // Rute Verifikasi Lomba
            Route::get('verifikasi-lomba', [VerifikasiLombaController::class, 'index'])->name('verifikasi-lomba.index');
            Route::post('verifikasi-lomba/list', [VerifikasiLombaController::class, 'list'])->name('verifikasi-lomba.list');
            Route::get('verifikasi-lomba/{id}/show_ajax', [VerifikasiLombaController::class, 'showAjax']);
            Route::get('verifikasi-lomba/{id}/terima_lomba_ajax', [VerifikasiLombaController::class, 'terimaLombaAjax']);
            Route::get('verifikasi-lomba/{id}/tolak_lomba_ajax', [VerifikasiLombaController::class, 'tolakLombaAjax']);
            Route::put('verifikasi-lomba/verifikasi/{id}', [VerifikasiLombaController::class, 'terimaLomba'])->name('verifikasi-lomba.terimaLomba');
            Route::put('verifikasi-lomba/tolak/{id}', [VerifikasiLombaController::class, 'tolakLomba'])->name('verifikasi-lomba.tolakLomba');
            Route::delete('verifikasi-lomba/{id}', [VerifikasiLombaController::class, 'destroy'])->name('verifikasi-lomba.destroy');
        });

        // MANAJEMEN PRESTASI
        Route::prefix('admin/manajemen-prestasi')->group(function () {
            // Rute Kelola Prestasi
            Route::get('kelola-prestasi', [KelolaPrestasiController::class, 'index'])->name('kelola-prestasi.index');
            Route::post('kelola-prestasi/list', [KelolaPrestasiController::class, 'list'])->name('kelola-prestasi.list');
            Route::get('kelola-prestasi/create', [KelolaPrestasiController::class, 'create'])->name('kelola-prestasi.create');
            Route::get('kelola-prestasi/{id}/show_ajax', [KelolaPrestasiController::class, 'showAjax']);
            Route::get('kelola-prestasi/{id}/edit_ajax', [KelolaPrestasiController::class, 'editAjax']);
            Route::get('kelola-prestasi/{id}/delete_ajax', [KelolaPrestasiController::class, 'deleteAjax']);
            Route::post('kelola-prestasi/store', [KelolaPrestasiController::class, 'store'])->name('kelola-prestasi.store');
            Route::put('kelola-prestasi/{id}', [KelolaPrestasiController::class, 'update'])->name('kelola-prestasi.update');
            Route::delete('kelola-prestasi/{id}', [KelolaPrestasiController::class, 'destroy'])->name('kelola-prestasi.destroy');

            // Rute Verifikasi Prestasi
            Route::get('verifikasi-prestasi', [VerifikasiPrestasiController::class, 'index'])->name('verifikasi-prestasi.index');
            Route::post('verifikasi-prestasi/list', [VerifikasiPrestasiController::class, 'list'])->name('verifikasi-prestasi.list');
            Route::get('verifikasi-prestasi/{id}/show_ajax', [VerifikasiPrestasiController::class, 'showAjax']);
            Route::get('verifikasi-prestasi/{id}/terima_prestasi_ajax', [VerifikasiPrestasiController::class, 'terimaPrestasiAjax']);
            Route::get('verifikasi-prestasi/{id}/tolak_prestasi_ajax', [VerifikasiPrestasiController::class, 'tolakPrestasiAjax']);
            Route::put('verifikasi-prestasi/verifikasi/{id}', [VerifikasiPrestasiController::class, 'terimaPrestasi'])->name('verifikasi-prestasi.terimaPrestasi');
            Route::put('verifikasi-prestasi/tolak/{id}', [VerifikasiPrestasiController::class, 'tolakPrestasi'])->name('verifikasi-prestasi.tolakPrestasi');
            Route::delete('verifikasi-prestasi/{id}', [VerifikasiPrestasiController::class, 'destroy'])->name('verifikasi-prestasi.destroy');
        });
    });

    // Dashboard dosen, hanya untuk role dosen
    Route::middleware('authorize:dosen')->group(function () {
        // Dashboard
        Route::get('/Dosen', [DashboardDosenController::class, 'index'])->name('dosen.dashboard');

        // Profil Dosen
        Route::prefix('dosen/profile-dosen')->group(function () {
            Route::get('/', [ProfileDosenController::class, 'index'])->name('dosen.profile.index');
            Route::get('/edit/{id}', [ProfileDosenController::class, 'edit'])->name('dosen.profile.edit');
            Route::put('/update', [ProfileDosenController::class, 'update'])->name('dosen.profile.update');
        });

        Route::prefix('dosen/manajemen-bimbingan')->group(function () {
            Route::get('/', [ManajemenBimbinganController::class, 'index'])->name('dosen.manajemen-bimbingan.index');
        });

        Route::prefix('dosen/info-lomba')->group(function () {
            Route::get('/', [InfoLombaController::class, 'index'])->name('dosen.info-lomba.index');
            Route::get('info-lomba/list', [InfoLombaController::class, 'list'])->name('info-lomba.list');
        });
    });

    // Dashboard mahasiswa, hanya untuk role mahasiswa
    Route::middleware('authorize:mahasiswa')->group(function () {
        Route::get('/Mahasiswa', [DashboardMahasiswaController::class, 'index'])->name('mahasiswa.dashboard');
        // ==== Input Data Prestasi ====
        Route::prefix('prestasi')->group(function () {
            Route::get('input', [InputPrestasiController::class, 'index'])
                ->name('mahasiswa.prestasi.input');

            Route::post('input/list', [InputPrestasiController::class, 'list'])
                ->name('mahasiswa.prestasi.list');

            Route::get('input/create', [InputPrestasiController::class, 'create'])
                ->name('mahasiswa.prestasi.create');

            Route::get('input/{id}/show_ajax', [InputPrestasiController::class, 'showAjax']);

            Route::get('input/{id}/edit_ajax', [InputPrestasiController::class, 'editAjax']);

            Route::get('input/{id}/delete_ajax', [InputPrestasiController::class, 'deleteAjax']);

            Route::post('input/store', [InputPrestasiController::class, 'store'])
                ->name('mahasiswa.prestasi.store');

            Route::put('input/{id}', [InputPrestasiController::class, 'update'])
                ->name('mahasiswa.prestasi.update');

            Route::delete('input/{id}', [InputPrestasiController::class, 'destroy'])
                ->name('mahasiswa.prestasi.destroy');
        });

        // ==== Input Data Lomba ====
        Route::prefix('lomba')->group(function () {
            Route::get('input', [InputLombaController::class, 'index'])
                ->name('mahasiswa.lomba.input');

            Route::post('input/list', [InputLombaController::class, 'list'])
                ->name('mahasiswa.lomba.list');

            Route::get('input/create', [InputLombaController::class, 'create'])
                ->name('mahasiswa.lomba.create');

            Route::get('input/{id}/show_ajax', [InputLombaController::class, 'showAjax']);

            Route::get('input/{id}/edit_ajax', [InputLombaController::class, 'editAjax']);

            Route::get('input/{id}/delete_ajax', [InputLombaController::class, 'deleteAjax']);

            Route::post('input/store', [InputLombaController::class, 'store'])
                ->name('mahasiswa.lomba.store');

            Route::put('input/{id}', [InputLombaController::class, 'update'])
                ->name('mahasiswa.lomba.update');

            Route::delete('input/{id}', [InputLombaController::class, 'destroy'])
                ->name('mahasiswa.lomba.destroy');
        });
    });
});
