<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home']);
Route::get('/pelayanan', [PageController::class, 'pelayanan'])->name('pelayanan');
Route::middleware('auth')->group(function () {
    Route::get('/pelayanan/ajukan/{serviceId}', [PageController::class, 'requestService'])->name('pelayanan.request');
    Route::post('/pelayanan/ajukan/{serviceId}', [PageController::class, 'submitServiceRequest'])->name('pelayanan.request.submit');
    Route::get('/pengajuan-saya', [PageController::class, 'myRequests'])->name('pengajuan-saya');
    Route::get('/pengajuan-saya/{requestId}/edit', [PageController::class, 'editRequest'])->name('pengajuan-saya.edit');
    Route::get('/pengajuan-saya/{requestId}/file/{syaratId}/preview', [PageController::class, 'previewRequestFile'])->name('pengajuan-saya.preview-file');
    Route::get('/laporan-kinerja', [PageController::class, 'laporanKinerja'])->name('laporan-kinerja');
    Route::post('/laporan-kinerja/humas', [PageController::class, 'storeHumas'])->name('laporan-humas.store');
    Route::delete('/laporan-kinerja/humas/{id}', [PageController::class, 'destroyHumas'])->name('laporan-humas.destroy');
    Route::post('/laporan-kinerja/harian', [PageController::class, 'storeKinerja'])->name('laporan-kinerja.store');
    Route::get('/laporan-kinerja/rekap', [PageController::class, 'rekapLaporanKinerja'])->name('laporan-kinerja.rekap');
    Route::put('/laporan-kinerja/day', [PageController::class, 'updateLaporanKinerjaByDate'])->name('laporan-kinerja.update-day');
    Route::delete('/laporan-kinerja/day', [PageController::class, 'deleteLaporanKinerjaByDate'])->name('laporan-kinerja.delete-day');
    Route::put('/laporan-kinerja/{activityId}', [PageController::class, 'updateLaporanKinerja'])->whereNumber('activityId')->name('laporan-kinerja.update');
    Route::delete('/laporan-kinerja/{activityId}', [PageController::class, 'deleteLaporanKinerja'])->whereNumber('activityId')->name('laporan-kinerja.delete');
});

Route::get('/satuan-kerja', [PageController::class, 'satuanKerja'])->name('satuan-kerja');
Route::get('/satuan-kerja/{department}', [PageController::class, 'satuanKerjaDetail'])->name('unit-kerja.detail');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::view('/register', 'auth.register')->name('register');
