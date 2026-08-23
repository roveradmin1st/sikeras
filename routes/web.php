<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RayonController;
use App\Http\Controllers\Admin\JemaatController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Bendahara\BendaharaKasController;
use App\Http\Controllers\Bendahara\BendaharaPembangunanController;
use App\Http\Controllers\PendetaController;

// 1. Public Landing Pages
Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/profil-gereja', function () {
    return view('profil_gereja');
})->name('profil_gereja');

Route::get('/pelayanan', function () {
    return view('pelayanan');
})->name('pelayanan');

// 2. Tenant Scoped Routes
Route::group([
    'prefix' => '{church_slug}',
    'middleware' => ['web', 'tenant']
], function () {

    // Guest Authentication Routes
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Authenticated Routes
    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // General Dashboard (routes to role-specific views)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Profiles & Reports placeholders to prevent routing errors in sidebar
        Route::get('/profil', [DashboardController::class, 'profile'])->name('profile');

        // Admin Only Routes (Fase 3 CRUD Master)
        Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
            // Rayon CRUD
            Route::get('/rayon', [RayonController::class, 'index'])->name('rayon.index');
            Route::post('/rayon', [RayonController::class, 'store'])->name('rayon.store');
            Route::put('/rayon/{id_rayon}', [RayonController::class, 'update'])->name('rayon.update');
            Route::delete('/rayon/{id_rayon}', [RayonController::class, 'destroy'])->name('rayon.destroy');

            // Jemaat CRUD
            Route::get('/jemaat', [JemaatController::class, 'index'])->name('jemaat.index');
            Route::post('/jemaat', [JemaatController::class, 'store'])->name('jemaat.store');
            Route::post('/jemaat/{id_jemaat}/buat-akun', [JemaatController::class, 'buatAkun'])->name('jemaat.buat-akun');
            Route::put('/jemaat/{id_jemaat}', [JemaatController::class, 'update'])->name('jemaat.update');
            Route::delete('/jemaat/{id_jemaat}', [JemaatController::class, 'destroy'])->name('jemaat.destroy');

            // Kategori CRUD
            Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
            Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
            Route::put('/kategori/{id_kategori}', [KategoriController::class, 'update'])->name('kategori.update');
            Route::delete('/kategori/{id_kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

            // User CRUD
            Route::get('/user', [UserController::class, 'index'])->name('user.index');
            Route::post('/user', [UserController::class, 'store'])->name('user.store');
            Route::put('/user/{id_user}', [UserController::class, 'update'])->name('user.update');
            Route::delete('/user/{id_user}', [UserController::class, 'destroy'])->name('user.destroy');

            // Backup & Restore
            Route::get('/backup-restore', [DashboardController::class, 'backup'])->name('backup');
            Route::post('/backup-restore/do-backup', [DashboardController::class, 'doBackup'])->name('backup.do');
            Route::post('/backup-restore/do-restore', [DashboardController::class, 'doRestore'])->name('backup.restore');

            Route::get('/laporan-semua', [DashboardController::class, 'allReports'])->name('reports');
            
            // Pengaturan Instansi / Kop Surat
            Route::get('/pengaturan', [DashboardController::class, 'settings'])->name('pengaturan');
            Route::post('/pengaturan', [DashboardController::class, 'updateSettings'])->name('pengaturan.update');
        });

        // Bendahara Kas Routes
        Route::middleware(['role:bendahara_kas'])->prefix('kas')->name('kas.')->group(function () {
            // Persembahan Mingguan
            Route::get('/persembahan', [BendaharaKasController::class, 'persembahanIndex'])->name('persembahan.index');
            Route::post('/persembahan', [BendaharaKasController::class, 'persembahanStore'])->name('persembahan.store');
            Route::put('/persembahan/{id}', [BendaharaKasController::class, 'persembahanUpdate'])->name('persembahan.update');
            Route::delete('/persembahan/{id}', [BendaharaKasController::class, 'persembahanDestroy'])->name('persembahan.destroy');

            // Donasi Umum
            Route::get('/donasi', [BendaharaKasController::class, 'donasiIndex'])->name('donasi.index');
            Route::post('/donasi', [BendaharaKasController::class, 'donasiStore'])->name('donasi.store');
            Route::put('/donasi/{id}', [BendaharaKasController::class, 'donasiUpdate'])->name('donasi.update');
            Route::delete('/donasi/{id}', [BendaharaKasController::class, 'donasiDestroy'])->name('donasi.destroy');

            // Buku Kas Gereja
            Route::get('/buku', [BendaharaKasController::class, 'bukuIndex'])->name('buku.index');

            // Data Transaksi Kas
            Route::get('/transaksi', [BendaharaKasController::class, 'transaksiIndex'])->name('transaksi.index');
            Route::post('/transaksi', [BendaharaKasController::class, 'transaksiStore'])->name('transaksi.store');
            Route::put('/transaksi/{id}', [BendaharaKasController::class, 'transaksiUpdate'])->name('transaksi.update');
            Route::delete('/transaksi/{id}', [BendaharaKasController::class, 'transaksiDestroy'])->name('transaksi.destroy');

            // Laporan Kas (Fase 4 - keselarasan Gambar IV.31)
            Route::get('/laporan-persembahan', [BendaharaKasController::class, 'laporanPersembahanIndex'])->name('laporan.persembahan');
            Route::get('/laporan-persembahan/cetak', [BendaharaKasController::class, 'laporanPersembahanCetakPdf'])->name('laporan.persembahan.cetak');
            Route::get('/laporan-kas', [BendaharaKasController::class, 'laporanKasIndex'])->name('laporan');
            Route::get('/laporan-kas/cetak', [BendaharaKasController::class, 'laporanKasCetakPdf'])->name('laporan.cetak');
        });

        // Bendahara Pembangunan Routes
        Route::middleware(['role:bendahara_pembangunan'])->prefix('pembangunan')->name('pembangunan.')->group(function () {
            // Janji Iman
            Route::get('/janji/create', [BendaharaPembangunanController::class, 'janjiCreate'])->name('janji.create');
            Route::get('/janji', [BendaharaPembangunanController::class, 'janjiIndex'])->name('janji.index');
            Route::post('/janji', [BendaharaPembangunanController::class, 'janjiStore'])->name('janji.store');
            Route::put('/janji/{id}', [BendaharaPembangunanController::class, 'janjiUpdate'])->name('janji.update');
            Route::delete('/janji/{id}', [BendaharaPembangunanController::class, 'janjiDestroy'])->name('janji.destroy');

            // Pembayaran Janji
            Route::get('/bayar/create', [BendaharaPembangunanController::class, 'bayarCreate'])->name('bayar.create');
            Route::get('/bayar', [BendaharaPembangunanController::class, 'bayarIndex'])->name('bayar.index');
            Route::post('/bayar', [BendaharaPembangunanController::class, 'bayarStore'])->name('bayar.store');
            Route::put('/bayar/{id}', [BendaharaPembangunanController::class, 'bayarUpdate'])->name('bayar.update');
            Route::delete('/bayar/{id}', [BendaharaPembangunanController::class, 'bayarDestroy'])->name('bayar.destroy');

            // Daftar Belum Lunas
            Route::get('/belum-lunas', [BendaharaPembangunanController::class, 'belumLunasIndex'])->name('belum-lunas.index');

            // Laporan Janji Iman (Fase 4 - keselarasan Gambar IV.42)
            Route::get('/laporan', [BendaharaPembangunanController::class, 'laporanIndex'])->name('laporan');
            Route::get('/laporan/cetak', [BendaharaPembangunanController::class, 'laporanCetakPdf'])->name('laporan.cetak');
            
            // Laporan Pengeluaran Pembangunan
            Route::get('/laporan-pengeluaran', [BendaharaPembangunanController::class, 'laporanPengeluaranIndex'])->name('laporan.pengeluaran');
            Route::get('/laporan-pengeluaran/cetak', [BendaharaPembangunanController::class, 'laporanPengeluaranCetakPdf'])->name('laporan.pengeluaran.cetak');
        });
        // Pendeta Routes
        Route::middleware(['role:pendeta'])->prefix('pendeta')->name('pendeta.')->group(function () {
            // Approval Kas
            Route::get('/approval-kas', [PendetaController::class, 'approvalKasIndex'])->name('approval.kas');
            Route::post('/approval-kas/{id}/approve', [PendetaController::class, 'approveKas'])->name('approval.kas.approve');
            Route::post('/approval-kas/{id}/reject', [PendetaController::class, 'rejectKas'])->name('approval.kas.reject');

            // Approval Laporan Janji Iman
            Route::get('/approval-janji', [PendetaController::class, 'approvalJanjiIndex'])->name('approval.janji');
            Route::post('/approval-janji/{id}/approve', [PendetaController::class, 'approveJanji'])->name('approval.janji.approve');
            Route::post('/approval-janji/{id}/reject', [PendetaController::class, 'rejectJanji'])->name('approval.janji.reject');

            // Laporan Kas Mingguan
            Route::get('/laporan-kas', [PendetaController::class, 'laporanKasIndex'])->name('laporan.kas');
            Route::get('/laporan-kas/cetak', [PendetaController::class, 'cetakLaporanKas'])->name('laporan.kas.cetak');
            Route::get('/laporan-kas/excel', [PendetaController::class, 'excelLaporanKas'])->name('laporan.kas.excel');
            
            // Laporan Janji Iman
            Route::get('/laporan-janji', [PendetaController::class, 'laporanJanjiIndex'])->name('laporan.janji');
            Route::get('/laporan-janji/cetak', [PendetaController::class, 'cetakLaporanJanji'])->name('laporan.janji.cetak');
            Route::get('/laporan-janji/excel', [PendetaController::class, 'excelLaporanJanji'])->name('laporan.janji.excel');
        });

        // ==========================================
        // Jemaat (Ummat) Routes
        // ==========================================
        Route::middleware(['role:jemaat'])->prefix('jemaat')->name('jemaat.')->group(function () {
            // Dashboard Transparansi Kas Umum
            Route::get('/transparansi', [\App\Http\Controllers\Jemaat\PortalJemaatController::class, 'dashboard'])->name('transparansi');
            
            // Pantau Janji Iman Pribadi
            Route::get('/janji-imanku', [\App\Http\Controllers\Jemaat\PortalJemaatController::class, 'janjiImanKu'])->name('janji_imanku');
        });
    });

});
