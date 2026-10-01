<?php

use App\Http\Controllers\PpdbController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - PPDB SMK Plus Pelita Nusantara
|--------------------------------------------------------------------------
| Ketentuan:
| Semua rute publik & admin diawali dengan /ppdb:
| - /ppdb                                    -> Form Pendaftaran Siswa
| - /ppdb/akomodasi                          -> Biaya & Info Pembiayaan
| - /ppdb/pengumuman                         -> Daftar Pengumuman PPDB
| - /ppdb/cek-status                         -> Cek Status Pendaftar (Input NISN)
| - /ppdb/cetak-kartu/{id}                   -> Cetak Kartu Tanda Peserta Resmi
| - /ppdb/dashboard/*                        -> Dilindungi verify.auth (RBAC: ADMIN, KEPALA_SEKOLAH, TU, DEVELOPER)
*/

// Root redirect to /ppdb
Route::get('/', function () {
    return redirect('/ppdb');
});

// Primary PPDB Routes
Route::prefix('ppdb')->group(function () {
    // 1. Public Routes
    Route::get('/', [PpdbController::class, 'index'])->name('ppdb.index');
    Route::post('/daftar', [PpdbController::class, 'store'])->name('ppdb.store');
    Route::get('/akomodasi', [PpdbController::class, 'akomodasi'])->name('ppdb.akomodasi');
    Route::get('/pengumuman', [PpdbController::class, 'pengumuman'])->name('ppdb.pengumuman');
    Route::get('/cek-status', [PpdbController::class, 'cekStatus'])->name('ppdb.cek-status');
    Route::get('/cetak-kartu/{id}', [PpdbController::class, 'cetakKartu'])->name('ppdb.cetak-kartu');
    Route::match(['GET', 'POST'], '/logout', function () {
        return redirect('/ppdb')->withoutCookie('access_token');
    })->name('ppdb.logout');

    // 2. Protected Dashboard Admin Routes: /ppdb/dashboard/*
    Route::prefix('dashboard')
        ->middleware('verify.auth:ADMIN,KEPALA_SEKOLAH,TU,DEVELOPER')
        ->group(function () {
            // Overview & Pendaftar
            Route::get('/', [PpdbController::class, 'dashboard'])->name('ppdb.dashboard');
            Route::get('/pendaftar', [PpdbController::class, 'pendaftarList'])->name('ppdb.dashboard.pendaftar');
            Route::get('/pendaftar/export', [PpdbController::class, 'exportPendaftar'])->name('ppdb.dashboard.pendaftar.export');
            Route::get('/pendaftar/{id}', [PpdbController::class, 'pendaftarDetail'])->name('ppdb.dashboard.pendaftar.detail');
            Route::put('/pendaftar/{id}', [PpdbController::class, 'pendaftarUpdate'])->name('ppdb.dashboard.pendaftar.update');
            Route::patch('/pendaftar/{id}/status', [PpdbController::class, 'updateStatus'])->name('ppdb.dashboard.pendaftar.status');
            Route::delete('/pendaftar/{id}', [PpdbController::class, 'pendaftarDestroy'])->name('ppdb.dashboard.pendaftar.destroy');

            // Manajemen Gelombang PPDB
            Route::get('/gelombang', [PpdbController::class, 'gelombangIndex'])->name('ppdb.dashboard.gelombang');
            Route::post('/gelombang', [PpdbController::class, 'gelombangStore'])->name('ppdb.dashboard.gelombang.store');
            Route::put('/gelombang/{id}', [PpdbController::class, 'gelombangUpdate'])->name('ppdb.dashboard.gelombang.update');
            Route::patch('/gelombang/{id}/aktifkan', [PpdbController::class, 'gelombangSetActive'])->name('ppdb.dashboard.gelombang.activate');
            Route::delete('/gelombang/{id}', [PpdbController::class, 'gelombangDestroy'])->name('ppdb.dashboard.gelombang.destroy');

            // Manajemen Pengumuman
            Route::get('/pengumuman', [PpdbController::class, 'pengumumanIndex'])->name('ppdb.dashboard.pengumuman');
            Route::post('/pengumuman', [PpdbController::class, 'pengumumanStore'])->name('ppdb.dashboard.pengumuman.store');
            Route::put('/pengumuman/{id}', [PpdbController::class, 'pengumumanUpdate'])->name('ppdb.dashboard.pengumuman.update');
            Route::patch('/pengumuman/{id}/pin', [PpdbController::class, 'pengumumanTogglePin'])->name('ppdb.dashboard.pengumuman.pin');
            Route::delete('/pengumuman/{id}', [PpdbController::class, 'pengumumanDestroy'])->name('ppdb.dashboard.pengumuman.destroy');

            // Manajemen Akomodasi & Biaya
            Route::get('/akomodasi', [PpdbController::class, 'akomodasiIndex'])->name('ppdb.dashboard.akomodasi');
            Route::post('/akomodasi', [PpdbController::class, 'akomodasiUpdate'])->name('ppdb.dashboard.akomodasi.update');

            // Backward compatibility
            Route::patch('/{id}/status', [PpdbController::class, 'updateStatus'])->name('ppdb.dashboard.status');
        });
});

// Short redirects to keep clean URLs
Route::get('/akomodasi', fn () => redirect('/ppdb/akomodasi'));
Route::get('/pengumuman', fn () => redirect('/ppdb/pengumuman'));
Route::get('/cek-status', fn () => redirect('/ppdb/cek-status'));
Route::get('/dashboard', fn () => redirect('/ppdb/dashboard'));
Route::get('/dashboard/pendaftar', fn () => redirect('/ppdb/dashboard/pendaftar'));
Route::get('/dashboard/pendaftar/{id}', fn ($id) => redirect('/ppdb/dashboard/pendaftar/'.$id));
Route::post('/daftar', [PpdbController::class, 'store']);
