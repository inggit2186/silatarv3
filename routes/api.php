<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AcaraController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JanjiTemuController;
use App\Http\Controllers\Api\KegiatanController;
use App\Http\Controllers\Api\LayananController;
use App\Http\Controllers\Api\PengajuanController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\SimpegController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes - SILATAR V2 Mobile App
|--------------------------------------------------------------------------
*/

// ═══════════════════════════════════════════════════════════════════════════
// PUBLIC ROUTES (No Auth Required)
// ═══════════════════════════════════════════════════════════════════════════

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
});

// Layanan - Public (bisa dilihat tanpa login)
Route::get('/layanan', [LayananController::class, 'index']);
Route::get('/layanan/{id}', [LayananController::class, 'show']);

// ═══════════════════════════════════════════════════════════════════════════
// PROTECTED ROUTES (Auth Required)
// ═══════════════════════════════════════════════════════════════════════════

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::put('/update-profile', [AuthController::class, 'updateProfile']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);
    });

    // Layanan dengan syarat (butuh login untuk lihat persyaratan)
    Route::get('/layanan/{id}/syarat', [LayananController::class, 'syarat']);

    // Pengajuan
    Route::prefix('pengajuan')->group(function () {
        Route::get('/', [PengajuanController::class, 'index']);
        Route::post('/', [PengajuanController::class, 'store']);
        Route::get('/{id}', [PengajuanController::class, 'show']);
        Route::put('/{id}', [PengajuanController::class, 'update']);
        Route::delete('/{id}', [PengajuanController::class, 'destroy']);

        // Upload file lampiran
        Route::post('/{id}/upload', [PengajuanController::class, 'upload']);

        // Status tracking
        Route::get('/{id}/tracking', [PengajuanController::class, 'tracking']);
    });

    // User Profile
    Route::prefix('user')->group(function () {
        Route::get('/profile', [UserController::class, 'profile']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::put('/profile/photo', [UserController::class, 'updatePhoto']);
    });

    // Units (Satuan Kerja)
    Route::get('/units', [LayananController::class, 'units']);

    // Presensi
    Route::prefix('presensi')->group(function () {
        Route::post('/', [PresensiController::class, 'store']);
        Route::get('/today', [PresensiController::class, 'today']);
        Route::get('/history', [PresensiController::class, 'history']);
        Route::get('/rekap', [PresensiController::class, 'rekap']);
    });

    // Laporan Kegiatan Harian (CKH)
    Route::prefix('laporan-kinerja')->group(function () {
        Route::get('/', [KegiatanController::class, 'index']);
        Route::post('/harian', [KegiatanController::class, 'store']);
        Route::put('/day', [KegiatanController::class, 'updateByDate']);
        Route::delete('/day', [KegiatanController::class, 'deleteByDate']);
        Route::get('/rekap', [KegiatanController::class, 'rekap']);
        Route::get('/pdf', [KegiatanController::class, 'downloadPdf']);
        Route::get('/bulanan', [KegiatanController::class, 'bulanan']);
    });

    // SIMPEG - Reset Password
    Route::prefix('simpeg')->group(function () {
        Route::post('/reset-password', [SimpegController::class, 'submitResetPassword']);
        Route::get('/my-requests', [SimpegController::class, 'myRequests']);
        Route::get('/{id}', [SimpegController::class, 'show']);
    });

    // Acara / Kegiatan Kankemenag
    Route::prefix('acara')->group(function () {
        Route::get('/', [AcaraController::class, 'index']);
        Route::get('/{id}', [AcaraController::class, 'show']);
        Route::post('/{id}/hadir', [AcaraController::class, 'hadir']);
        Route::post('/{id}/tidak-hadir', [AcaraController::class, 'tidakHadir']);
        Route::get('/history/user', [AcaraController::class, 'history']);
    });

    // ═══════════════════════════════════════════════════════════════════════════
    // JANJI TEMU - User Side
    // ═══════════════════════════════════════════════════════════════════════════

    // Public endpoints (untuk list department & employees)
    Route::get('/janji-temu/departments', [JanjiTemuController::class, 'departments']);
    Route::get('/janji-temu/departments/{id}/employees', [JanjiTemuController::class, 'departmentEmployees']);

    // Protected endpoints (auth required)
    Route::prefix('janji-temu')->group(function () {
        Route::post('/', [JanjiTemuController::class, 'store']);
        Route::get('/my-appointments', [JanjiTemuController::class, 'myAppointments']);
        Route::get('/{id}', [JanjiTemuController::class, 'show']);
        Route::put('/{id}/cancel', [JanjiTemuController::class, 'cancel']);
    });

    // ═══════════════════════════════════════════════════════════════════════════
    // JANJI TEMU - Admin/Staff Side
    // ═══════════════════════════════════════════════════════════════════════════

    Route::prefix('admin/janji-temu')->group(function () {
        Route::get('/', [JanjiTemuController::class, 'adminIndex']);
        Route::get('/{id}', [JanjiTemuController::class, 'adminShow']);
        Route::put('/{id}/approve', [JanjiTemuController::class, 'approve']);
        Route::put('/{id}/reject', [JanjiTemuController::class, 'reject']);
        Route::put('/{id}/assign', [JanjiTemuController::class, 'assignStaff']);
    });

    // SIMPEG Admin
    Route::prefix('admin/simpeg')->group(function () {
        Route::get('/', [SimpegController::class, 'adminIndex']);
        Route::get('/{id}', [SimpegController::class, 'adminShow']);
        Route::put('/{id}/verify', [SimpegController::class, 'verify']);
    });
});
