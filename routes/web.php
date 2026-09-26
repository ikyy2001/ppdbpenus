<?php

use App\Http\Controllers\PpdbController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - PPDB SMK Plus Pelita Nusantara
|--------------------------------------------------------------------------
| Ketentuan:
| Semua rute utama diawali dengan /ppdb:
| - /ppdb                                    -> Form Pendaftaran Siswa
| - /ppdb/akomodasi                          -> Biaya & Info Pembiayaan
| - /ppdb/pengumuman                         -> Daftar Pengumuman PPDB
| - /ppdb/cek-status                         -> Cek Status Pendaftar (Input NISN)
| - /ppdb/dashboard                          -> Dashboard Admin Page
| - /ppdb/dashboard/pendaftar                -> List Pendaftar (Pagination & Filter)
| - /ppdb/dashboard/pendaftar/{id_pendaftar} -> Detail Pendaftar & Form Update
*/

// Root redirect to /ppdb
Route::get('/', function () {
    return redirect('/ppdb');
});

// Primary PPDB Routes (Semua diawali /ppdb)
Route::prefix('ppdb')->group(function () {
    // Public routes
    Route::get('/', [PpdbController::class, 'index'])->name('ppdb.index');
    Route::post('/daftar', [PpdbController::class, 'store'])->name('ppdb.store');
    Route::get('/akomodasi', [PpdbController::class, 'akomodasi'])->name('ppdb.akomodasi');
    Route::get('/pengumuman', [PpdbController::class, 'pengumuman'])->name('ppdb.pengumuman');
    Route::get('/cek-status', [PpdbController::class, 'cekStatus'])->name('ppdb.cek-status');

    // Dashboard Admin Routes
    Route::get('/dashboard', [PpdbController::class, 'dashboard'])->name('ppdb.dashboard');
    Route::get('/dashboard/pendaftar', [PpdbController::class, 'pendaftarList'])->name('ppdb.dashboard.pendaftar');
    Route::get('/dashboard/pendaftar/{id}', [PpdbController::class, 'pendaftarDetail'])->name('ppdb.dashboard.pendaftar.detail');
    Route::put('/dashboard/pendaftar/{id}', [PpdbController::class, 'pendaftarUpdate'])->name('ppdb.dashboard.pendaftar.update');
    Route::patch('/dashboard/pendaftar/{id}/status', [PpdbController::class, 'updateStatus'])->name('ppdb.dashboard.pendaftar.status');
    Route::delete('/dashboard/pendaftar/{id}', [PpdbController::class, 'pendaftarDestroy'])->name('ppdb.dashboard.pendaftar.destroy');

    // Backward compatibility for status patch
    Route::patch('/dashboard/{id}/status', [PpdbController::class, 'updateStatus'])->name('ppdb.dashboard.status');
});

// Short redirects to keep URLs clean
Route::get('/akomodasi', fn() => redirect('/ppdb/akomodasi'));
Route::get('/pengumuman', fn() => redirect('/ppdb/pengumuman'));
Route::get('/cek-status', fn() => redirect('/ppdb/cek-status'));
Route::get('/dashboard', fn() => redirect('/ppdb/dashboard'));
Route::get('/dashboard/pendaftar', fn() => redirect('/ppdb/dashboard/pendaftar'));
Route::get('/dashboard/pendaftar/{id}', fn($id) => redirect('/ppdb/dashboard/pendaftar/' . $id));
Route::post('/daftar', [PpdbController::class, 'store']);
Route::patch('/dashboard/{id}/status', [PpdbController::class, 'updateStatus']);
