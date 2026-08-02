<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LayananController;
use App\Http\Controllers\Api\PengajuanController;
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
});
