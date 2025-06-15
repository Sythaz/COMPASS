<?php

use Illuminate\Console\View\Components\Info;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\KelolaMahasiswaController;
use App\Http\Controllers\Admin\KelolaDosenController;
use App\Http\Controllers\Admin\KelolaAdminController;
use App\Http\Controllers\Admin\KategoriKeahlianController;
use App\Http\Controllers\Admin\KelolaLombaController;
use App\Http\Controllers\Admin\PeriodeSemesterController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\TingkatLombaController;
use App\Http\Controllers\Admin\DashboardController as DashboardAdminController;
use App\Http\Controllers\Admin\ProfileAdminController as ProfileAdminController;
use App\Http\Controllers\Admin\HistoriPengajuanLombaController;
use App\Http\Controllers\Admin\KelolaPrestasiController;
use App\Http\Controllers\Admin\KriteriaPrometheeController;
use App\Http\Controllers\Admin\RekomendasiLombaController;
use App\Http\Controllers\Admin\VerifikasiLombaController;
use App\Http\Controllers\Admin\VerifikasiPrestasiController;
use App\Http\Controllers\Admin\VerifikasiPendaftaranController;
use App\Http\Controllers\Dosen\DashboardController as DashboardDosenController;
use App\Http\Controllers\Dosen\ProfileDosenController as ProfileDosenController;
use App\Http\Controllers\Dosen\InfoLombaController;
use App\Http\Controllers\Dosen\DataLombaController;
use App\Http\Controllers\Dosen\KelolaBimbinganController;
use App\Http\Controllers\Dosen\VerifikasiBimbinganController;
use App\Http\Controllers\Mahasiswa\DashboardController as DashboardMahasiswaController;
use App\Http\Controllers\Mahasiswa\ProfileMahasiswaController as ProfileMahasiswaController;
use App\Http\Controllers\Mahasiswa\LombaMahasiswaController;
use App\Http\Controllers\Mahasiswa\PrestasiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PrometheeRekomendasiController;
use App\Http\Controllers\LaporanPrestasiController;
use App\Http\Controllers\LaporanPendaftaranController;
use App\Models\KriteriaPrometheeModel;

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

    // Halaman lupa password
    Route::get('/lupa-password', [AuthController::class, 'lupaPassword'])->name('lupa-password');
    Route::post('/lupa-password', [AuthController::class, 'postlupaPassword'])->name('post-lupa-password');
});

// Route logout hanya untuk yang sudah login
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Route group untuk user yang sudah login
Route::middleware(['auth'])->group(function () { // Masukkan semua route didalam sini ya!
    // Notifikasi
    Route::get('notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('notifikasi/baca_semua_notifikasi', [NotifikasiController::class, 'bacaSemuaNotifikasi'])->name('notifikasi.bacaSemuaNotifikasi');
    Route::post('notifikasi/tandai_sudah_dibaca_notifikasi/{id}', [NotifikasiController::class, 'tandaiSudahDibacaNotifikasi'])->name('notifikasi.tandaiSudahDibacaNotifikasi');
    Route::post('/notifikasi/tandai-dibaca-banyak', [NotifikasiController::class, 'tandaiDibacaBanyakNotifikasi'])->name('notifikasi.tandaiDibacaBanyakNotifikasi');
    Route::post('notifikasi/hapus_notifikasi/{id}', [NotifikasiController::class, 'hapusNotifikasi'])->name('notifikasi.hapusNotifikasi');
    Route::post('notifikasi/hapus_banyak_notifikasi', [NotifikasiController::class, 'hapusBanyakNotifikasi'])->name('notifikasi.hapusBanyakNotifikasi');

    // Route Cetak Laporan Prestasi
    Route::get('/export-prestasi-excel', [LaporanPrestasiController::class, 'export_excel'])->name('prestasi.export-excel');
    Route::get('/export-prestasi-pdf', [LaporanPrestasiController::class, 'export_pdf'])->name('prestasi.export-pdf');
    // Cetak Laporan Tiap Prestasi PDF
    Route::get('/laporan-prestasi-pdf/{id}', [LaporanPrestasiController::class, 'cetak_laporan'])->name('laporan.prestasi.pdf');

    // Route Cetak Laporan Pendaftaran Lomba
    Route::get('/export-pendaftaran-excel', [LaporanPendaftaranController::class, 'export_excel'])->name('pendaftaran.export-excel');
    Route::get('/export-pendaftaran-pdf', [LaporanPendaftaranController::class, 'export_pdf'])->name('pendaftaran.export-pdf');


    // ROUTE ADMIN
    Route::middleware('authorize:Admin')->group(function () {
        // Dashboard admin, hanya untuk role admin
        Route::get('/admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

        // PROFILE ADMIN
        Route::prefix('admin/profile-admin')->group(function () {
            Route::get('/', [ProfileAdminController::class, 'index'])->name('admin.profile.index');
            Route::get('/edit/{id}', [ProfileAdminController::class, 'edit'])->name('admin.profile.edit');
            Route::put('/update', [ProfileAdminController::class, 'update'])->name('admin.profile.update');
            Route::post('/cek-username', [ProfileAdminController::class, 'cekUsername'])->name('admin.profile.cek-username');
            Route::post('/ubah-password', [ProfileAdminController::class, 'changePassword'])->name('ubah-password');
        });

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
            Route::get('admin/history/aktivasi/konfirmasi/{id}', [KelolaAdminController::class, 'confirm_aktivasi'])->name('admin.history.aktivasi.konfirmasi');
            Route::put('admin/history/aktivasi/{id}', [KelolaAdminController::class, 'aktivasi'])->name('admin.history.aktivasi');
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
            Route::get('dosen/history/aktivasi/konfirmasi/{id}', [KelolaDosenController::class, 'confirm_aktivasi'])->name('dosen.history.aktivasi.konfirmasi');
            Route::put('dosen/history/aktivasi/{id}', [KelolaDosenController::class, 'aktivasi'])->name('dosen.history.aktivasi');
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
            Route::get('mahasiswa/history/aktivasi/konfirmasi/{id}', [KelolaMahasiswaController::class, 'confirm_aktivasi'])->name('mahasiswa.history.aktivasi.konfirmasi');
            Route::put('mahasiswa/history/aktivasi/{id}', [KelolaMahasiswaController::class, 'aktivasi'])->name('mahasiswa.history.aktivasi');
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

            // Rute Bobot Rekomendasi
            Route::get('bobot-rekomendasi', [KriteriaPrometheeController::class, 'index'])->name('bobot-rekomendasi.index');
            Route::put('/update', [KriteriaPrometheeController::class, 'update'])->name('bobot-rekomendasi.update');
            Route::post('/reset', [KriteriaPrometheeController::class, 'reset'])->name('bobot-rekomendasi.reset');
            Route::get('/ambil-bobot', [KriteriaPrometheeController::class, 'ambilBobot'])->name('bobot-rekomendasi.get');
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

            // Rute Histori Pengajuan Lomba
            Route::get('histori-pengajuan-lomba', [HistoriPengajuanLombaController::class, 'index'])->name('histori-pengajuan-lomba.index');
            Route::post('histori-pengajuan-lomba/list', [HistoriPengajuanLombaController::class, 'list'])->name('histori-pengajuan-lomba.list');
            Route::get('histori-pengajuan-lomba/{id}/show_ajax', [HistoriPengajuanLombaController::class, 'showAjax']);
            Route::get('histori-pengajuan-lomba/{id}/edit_ajax', [HistoriPengajuanLombaController::class, 'editAjax']);
            Route::put('histori-pengajuan-lomba/{id}', [HistoriPengajuanLombaController::class, 'update'])->name('histori-pengajuan-lomba.update');

            // Rute Verifikasi Lomba
            Route::get('verifikasi-lomba', [VerifikasiLombaController::class, 'index'])->name('verifikasi-lomba.index');
            Route::post('verifikasi-lomba/list', [VerifikasiLombaController::class, 'list'])->name('verifikasi-lomba.list');
            Route::get('verifikasi-lomba/{id}/show_ajax', [VerifikasiLombaController::class, 'showAjax']);
            Route::get('verifikasi-lomba/{id}/terima_lomba_ajax', [VerifikasiLombaController::class, 'terimaLombaAjax']);
            Route::get('verifikasi-lomba/{id}/tolak_lomba_ajax', [VerifikasiLombaController::class, 'tolakLombaAjax']);
            Route::put('verifikasi-lomba/verifikasi/{id}', [VerifikasiLombaController::class, 'terimaLomba'])->name('verifikasi-lomba.terimaLomba');
            Route::put('verifikasi-lomba/tolak/{id}', [VerifikasiLombaController::class, 'tolakLomba'])->name('verifikasi-lomba.tolakLomba');
            Route::delete('verifikasi-lomba/{id}', [VerifikasiLombaController::class, 'destroy'])->name('verifikasi-lomba.destroy');

            // Rute Histori Pengajuan Lomba
            Route::get('histori-pengajuan-lomba', [HistoriPengajuanLombaController::class, 'index'])->name('histori-pengajuan-lomba.index');
            Route::post('histori-pengajuan-lomba/list', [HistoriPengajuanLombaController::class, 'list'])->name('histori-pengajuan-lomba.list');
            Route::get('histori-pengajuan-lomba/{id}/show_ajax', [HistoriPengajuanLombaController::class, 'showAjax']);
            Route::get('histori-pengajuan-lomba/{id}/edit_ajax', [HistoriPengajuanLombaController::class, 'editAjax']);
            Route::put('histori-pengajuan-lomba/{id}', [HistoriPengajuanLombaController::class, 'update'])->name('histori-pengajuan-lomba.update');

            // Rute Rekomendasi Lomba
            Route::get('rekomendasi-lomba', [RekomendasiLombaController::class, 'index'])->name('rekomendasi-lomba.index');
            Route::get('rekomendasi-lomba/{id}/show_ajax', [RekomendasiLombaController::class, 'showAjax']);
            Route::get('rekomendasi-lomba/{id}/rekomendasi_ajax', [RekomendasiLombaController::class, 'rekomendasiAjax']);
            Route::get('rekomendasi-lomba/tambah_rekomendasi_ajax', [RekomendasiLombaController::class, 'tambahRekomendasiAjax']);
            Route::post('rekomendasi-lomba/notifikasi', [RekomendasiLombaController::class, 'notifikasiRekomendasi'])->name('rekomendasi-lomba.notifikasi');
            Route::get('rekomendasi-lomba/{id}/data', [RekomendasiLombaController::class, 'getLombaData'])->name('rekomendasi-lomba.data');

            // Rute Verifikasi Pendaftaran Lomba
            Route::get('pendaftaran-lomba', [VerifikasiPendaftaranController::class, 'index'])->name('verifikasi-pendaftaran.index');
            Route::post('pendaftaran-lomba/list', [VerifikasiPendaftaranController::class, 'list'])->name('verifikasi-pendaftaran.list');
            Route::get('pendaftaran-lomba/show/{id}', [VerifikasiPendaftaranController::class, 'detail_pendaftaran'])->name('verifikasi-pendaftaran.show');
            Route::get('riwayat-pendaftaran-lomba', [VerifikasiPendaftaranController::class, 'riwayat_index'])->name('riwayat-pendaftaran.index');
            Route::post('riwayat-pendaftaran-lomba/list', [VerifikasiPendaftaranController::class, 'riwayat_list'])->name('riwayat-pendaftaran.list');
            Route::get('pendaftaran-lomba/{id}/terima', [VerifikasiPendaftaranController::class, 'terimaView'])->name('verifikasi-pendaftaran.terima_view');
            Route::post('pendaftaran-lomba/{id}/terima', [VerifikasiPendaftaranController::class, 'terima'])->name('verifikasi-pendaftaran.terima');
            Route::get('pendaftaran-lomba/{id}/tolak', [VerifikasiPendaftaranController::class, 'tolakView'])->name('verifikasi-pendaftaran.tolak_view');
            Route::post('pendaftaran-lomba/{id}/tolak', [VerifikasiPendaftaranController::class, 'tolak'])->name('verifikasi-pendaftaran.tolak');
            Route::get('pendaftaran-lomba/{id}/edit', [VerifikasiPendaftaranController::class, 'edit'])->name('verifikasi-pendaftaran.edit');
            Route::put('pendaftaran-lomba/{id}', [VerifikasiPendaftaranController::class, 'update_pendaftaran'])->name('verifikasi-pendaftaran.update');
            Route::get('pendaftaran-lomba/{id}/hapus', [VerifikasiPendaftaranController::class, 'hapus'])->name('verifikasi-pendaftaran.hapus');
            Route::delete('pendaftaran-lomba/{id}', [VerifikasiPendaftaranController::class, 'destroy'])->name('verifikasi-pendaftaran.destroy');
        });

        // MANAJEMEN PRESTASI
        Route::prefix('admin/manajemen-prestasi')->group(function () {
            // Rute Kelola Prestasi
            Route::get('kelola-prestasi', [KelolaPrestasiController::class, 'index'])->name('kelola-prestasi.index');
            Route::post('kelola-prestasi/list', [KelolaPrestasiController::class, 'list'])->name('kelola-prestasi.list');
            Route::get('kelola-prestasi/create', [KelolaPrestasiController::class, 'create'])->name('kelola-prestasi.create');
            Route::get('kelola-prestasi/{id}/show_ajax', [KelolaPrestasiController::class, 'showAjax'])->name('kelola-prestasi.showAjax');
            Route::get('kelola-prestasi/{id}/edit_ajax', [KelolaPrestasiController::class, 'editAjax'])->name('kelola-prestasi.editAjax');
            Route::get('kelola-prestasi/{id}/delete_ajax', [KelolaPrestasiController::class, 'deleteAjax'])->name('kelola-prestasi.deleteAjax');
            Route::post('kelola-prestasi/store', [KelolaPrestasiController::class, 'store'])->name('kelola-prestasi.store');
            Route::put('kelola-prestasi/{id}', [KelolaPrestasiController::class, 'update'])->name('kelola-prestasi.update');
            Route::delete('kelola-prestasi/{id}', [KelolaPrestasiController::class, 'destroy'])->name('kelola-prestasi.destroy');

            // Rute Verifikasi Prestasi
            Route::get('verifikasi-prestasi', [VerifikasiPrestasiController::class, 'index'])->name('verifikasi-prestasi.index');
            Route::post('verifikasi-prestasi/list', [VerifikasiPrestasiController::class, 'list'])->name('verifikasi-prestasi.list');
            Route::get('verifikasi-prestasi/{id}/show_ajax', [VerifikasiPrestasiController::class, 'showAjax'])->name('verifikasi-prestasi.showAjax');
            Route::get('verifikasi-prestasi/{id}/terima_prestasi_ajax', [VerifikasiPrestasiController::class, 'terimaPrestasiAjax'])->name('verifikasi-prestasi.terimaPrestasiAjax');
            Route::get('verifikasi-prestasi/{id}/tolak_prestasi_ajax', [VerifikasiPrestasiController::class, 'tolakPrestasiAjax'])->name('verifikasi-prestasi.tolakPrestasiAjax');
            Route::put('verifikasi-prestasi/verifikasi/{id}', [VerifikasiPrestasiController::class, 'terimaPrestasi'])->name('verifikasi-prestasi.terimaPrestasi');
            Route::put('verifikasi-prestasi/tolak/{id}', [VerifikasiPrestasiController::class, 'tolakPrestasi'])->name('verifikasi-prestasi.tolakPrestasi');
            Route::delete('verifikasi-prestasi/{id}', [VerifikasiPrestasiController::class, 'destroy'])->name('verifikasi-prestasi.destroy');
        });
    });

    // ROUTE DOSEN
    Route::middleware('authorize:dosen')->group(function () {
        // Dashboard dosen, hanya untuk role dosen
        Route::get('/Dosen', [DashboardDosenController::class, 'index'])->name('dosen.dashboard');

        // Profil Dosen
        Route::prefix('dosen/profile-dosen')->group(function () {
            Route::get('/', [ProfileDosenController::class, 'index'])->name('dosen.profile.index');
            Route::get('/edit/{id}', [ProfileDosenController::class, 'edit'])->name('dosen.profile.edit');
            Route::put('/update', [ProfileDosenController::class, 'update'])->name('dosen.profile.update');
            Route::post('/cek-username', [ProfileDosenController::class, 'cekUsername'])->name('dosen.profile.cek-username');
            Route::post('/ubah-password', [ProfileDosenController::class, 'changePassword'])->name('ubah-password-dosen');
            Route::post('/preferensi', [ProfileDosenController::class, 'storePreferensi'])->name('dosen.profile.preferensi');
        });

        // Halaman Kelola Bimbingan (Menampilkan Riwayat Prestasi mahasiswa sesuai Mahasiswa yang Di Bimbing)
        Route::prefix('dosen/kelola-bimbingan')->group(function () {
            Route::get('/', [KelolaBimbinganController::class, 'index'])->name('dosen.kelola-bimbingan.index');
            Route::post('list', [KelolaBimbinganController::class, 'list'])->name('dosen.kelola-bimbingan.list');
            Route::get('{id}/show_ajax', [KelolaBimbinganController::class, 'showAjax'])->name('dosen.kelola-bimbingan.showAjax');
        });

        // Halaman Verifikasi Bimbingan
        Route::prefix('dosen/verifikasi-bimbingan')->group(function () {
            Route::get('/', [VerifikasiBimbinganController::class, 'index'])->name('dosen.verifikasi-bimbingan.index');
            Route::get('list', [VerifikasiBimbinganController::class, 'list'])->name('dosen.verifikasi-bimbingan.list');
            Route::get('{id}/terima_prestasi_ajax', [VerifikasiBimbinganController::class, 'terimaPrestasiAjax'])->name('dosen.terimaPrestasiAjax');
            Route::get('{id}/tolak_prestasi_ajax', [VerifikasiBimbinganController::class, 'tolakPrestasiAjax'])->name('dosen.tolakPrestasiAjax');
            Route::put('verifikasi/{id}', [VerifikasiBimbinganController::class, 'terimaPrestasi'])->name('dosen.terimaPrestasi');
            Route::put('tolak/{id}', [VerifikasiBimbinganController::class, 'tolakPrestasi'])->name('dosen.tolakPrestasi');
        });

        // Halaman Informasi Lomba (Menampilkan Lomba yang statusnya Aktif Dan Terverifikasi saja)
        Route::prefix('dosen/info-lomba')->group(function () {
            Route::get('/', [InfoLombaController::class, 'index'])->name('dosen.info-lomba.index');
            Route::get('list', [InfoLombaController::class, 'list'])->name('info-lomba.list');
            Route::get('{id}/show', [InfoLombaController::class, 'showAjax'])->name('info-lomba.show');

            // Rekomendasi
            Route::get('rekomendasi-lomba/{id}/rekomendasi_ajax', [InfoLombaController::class, 'rekomendasiAjax']);
            Route::get('rekomendasi-lomba/tambah_rekomendasi_ajax', [InfoLombaController::class, 'tambahRekomendasiAjax'])->name('info-lomba.tambah-rekomendasi-ajax');
            Route::post('rekomendasi-lomba/notifikasi', [InfoLombaController::class, 'notifikasiRekomendasi'])->name('info-lomba.rekomendasi-lomba.notifikasi');
        });

        // Halaman yang menampilkan riwayat Lomba yang pernah diajukan dosen
        Route::prefix('dosen/data-lomba')->as('dosen.data-lomba.')->group(function () {
            Route::get('/', [DataLombaController::class, 'index'])->name('index');
            Route::get('list', [DataLombaController::class, 'list'])->name('list');
            Route::get('{id}/show', [DataLombaController::class, 'showAjax'])->name('show');
            Route::get('create', [DataLombaController::class, 'create'])->name('create');
            Route::post('store', [DataLombaController::class, 'store'])->name('store');
        });
    });

    // ROUTE MAHASISWA
    Route::middleware('authorize:mahasiswa')->group(function () {
        // Dashboard mahasiswa, hanya untuk role mahasiswa
        Route::get('/Mahasiswa', [DashboardMahasiswaController::class, 'index'])->name('mahasiswa.dashboard');

        // Rute Laporan Promethee
        Route::prefix('mahasiswa/promethee')->group(function () {
            Route::get('laporan', [PrometheeRekomendasiController::class, 'laporan'])->name('laporan-promethee');
        });

        Route::prefix('mhs/prestasi')->group(function () {
            Route::get('/', [PrestasiController::class, 'index'])->name('mhs.prestasi.index');
            Route::get('list', [PrestasiController::class, 'list'])->name('mhs.prestasi.list');
            Route::get('tambah', [PrestasiController::class, 'create_prestasi'])->name('mhs.prestasi.create');
            Route::get('show-ajax/{id}', [PrestasiController::class, 'showAjax'])->name('mhs.prestasi.showAjax');
            Route::post('simpan', [PrestasiController::class, 'store'])->name('mhs.prestasi.store');
            Route::post('cek-lomba-duplicate', [PrestasiController::class, 'cekLombaDuplicate'])->name('mhs.prestasi.cekLombaDuplicate');
        });

        // Halaman Informasi Lomba (Menampilkan Lomba yang statusnya Aktif Dan Terverifikasi saja)
        Route::prefix('mahasiswa/info-lomba')->group(function () {
            // Halaman utama lomba
            Route::get('/', [LombaMahasiswaController::class, 'index'])->name('mahasiswa.informasi-lomba.index');
            Route::get('list', [LombaMahasiswaController::class, 'list'])->name('mahasiswa.informasi-lomba.list');
            // Halaman riwayat lomba
            Route::get('history', [LombaMahasiswaController::class, 'history'])->name('mahasiswa.informasi-lomba.history');
            Route::get('list-history', [LombaMahasiswaController::class, 'list_history'])->name('mahasiswa.informasi-lomba.list-history');
            // Riwayat Pendaftaran
            Route::get('riwayat-pendaftaran', [LombaMahasiswaController::class, 'riwayat_pendaftaran'])
                ->name('mahasiswa.informasi-lomba.riwayat-pendaftaran');
            Route::get('riwayat-pendaftaran/list', [LombaMahasiswaController::class, 'list_pendaftaran'])
                ->name('mahasiswa.informasi-lomba.list-pendaftaran');
            // Detail Pendaftaran
            Route::get('riwayat-pendaftaran/{id}/detail', [LombaMahasiswaController::class, 'detail_pendaftaran'])
                ->name('mahasiswa.informasi-lomba.detail-pendaftaran');

            // Aksi
            Route::get('{id}/show', [LombaMahasiswaController::class, 'showAjax'])->name('informasi-lomba.show');
            Route::get('{id}/daftar', [LombaMahasiswaController::class, 'form_daftar'])->name('informasi-lomba.daftar');
            Route::post('{id}/daftar', [LombaMahasiswaController::class, 'store_pendaftaran'])->name('informasi-lomba.store');
            Route::get('create', [LombaMahasiswaController::class, 'create'])->name('create-lomba');
            Route::post('store', [LombaMahasiswaController::class, 'store_lomba'])->name('store-lomba');
        });

        Route::prefix('mahasiswa/profile-mahasiswa')->group(function () {
            Route::get('/', [ProfileMahasiswaController::class, 'index'])->name('mahasiswa.profile.index');
            Route::get('/edit/{id}', [ProfileMahasiswaController::class, 'edit'])->name('mahasiswa.profile.edit');
            Route::put('/update', [ProfileMahasiswaController::class, 'update'])->name('mahasiswa.profile.update');
            Route::post('/cek-username', [ProfileMahasiswaController::class, 'cekUsername'])->name('mahasiswa.profile.cek-username');
            Route::post('/ubah-password', [ProfileMahasiswaController::class, 'changePassword'])->name('ubah-password-mahasiswa');
            Route::put('/preferensi', [ProfileMahasiswaController::class, 'storePreferensi'])->name('mahasiswa.profile.preferensi');
        });
    });
});
