<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\TpgController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PenilaianKinerjaController;
use App\Http\Controllers\Admin\MadrasahController;
use App\Http\Controllers\Admin\MadrasahLaporanController;
use App\Http\Controllers\Admin\JanjiTemuController;
use App\Http\Controllers\Admin\AcaraController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the AdminAccess middleware to ensure proper
| authentication and authorization.
|
| Note: Prefix 'admin' is set in AppServiceProvider
*/

Route::middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{id}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
        Route::get('/users/{id}/show', [UserController::class, 'show'])->name('users.show');

        // News Management
        Route::get('/news', [NewsController::class, 'index'])->name('news.index');
        Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
        Route::post('/news', [NewsController::class, 'store'])->name('news.store');
        Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{id}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
        Route::post('/news/upload-image', [NewsController::class, 'uploadImage'])->name('news.upload-image');

        // Services Management
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
        Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
        Route::post('/services/{id}/requirement', [ServiceController::class, 'addRequirement'])->name('services.add-requirement');
        Route::put('/services/{id}/requirement/{reqId}', [ServiceController::class, 'updateRequirement'])->name('services.update-requirement');
        Route::delete('/services/{id}/requirement/{reqId}', [ServiceController::class, 'deleteRequirement'])->name('services.delete-requirement');

        // Janji Temu Management
        Route::get('/janji-temu', [JanjiTemuController::class, 'index'])->name('janji-temu');
        Route::get('/janji-temu/{id}', [JanjiTemuController::class, 'show'])->name('janji-temu.show');
        Route::post('/janji-temu/{id}/approve', [JanjiTemuController::class, 'approve'])->name('janji-temu.approve');
        Route::post('/janji-temu/{id}/reject', [JanjiTemuController::class, 'reject'])->name('janji-temu.reject');

        Route::get('/units', [UnitController::class, 'index'])->name('units.index');

        Route::get('/units/create', [UnitController::class, 'create'])->name('units.create');

        Route::post('/units', [UnitController::class, 'store'])->name('units.store');

        Route::get('/units/{id}/edit', [UnitController::class, 'edit'])->name('units.edit');

        Route::put('/units/{id}', [UnitController::class, 'update'])->name('units.update');

        Route::delete('/units/{id}', [UnitController::class, 'destroy'])->name('units.destroy');

        Route::get('/units/{id}', [UnitController::class, 'show'])->name('units.show');

        // Acara / Kegiatan Kankemenag
        Route::get('/acara', [AcaraController::class, 'index'])->name('acara');
        Route::get('/acara/create', [AcaraController::class, 'create'])->name('acara.create');
        Route::post('/acara', [AcaraController::class, 'store'])->name('acara.store');
        Route::get('/acara/{id}', [AcaraController::class, 'show'])->name('acara.show');
        Route::get('/acara/{id}/edit', [AcaraController::class, 'edit'])->name('acara.edit');
        Route::put('/acara/{id}', [AcaraController::class, 'update'])->name('acara.update');
        Route::delete('/acara/{id}', [AcaraController::class, 'destroy'])->name('acara.destroy');

        Route::get('/requests', function () {
            return view('admin.requests.index');
        })->name('requests.index');

        Route::get('/requests/{id}', function ($id) {
            return view('admin.requests.show', ['id' => $id]);
        })->name('requests.show');

        Route::get('/reports', function () {
            return view('admin.reports.index');
        })->name('reports.index');

        // Utilities
        Route::post('/utilities/migrate-satker', [DashboardController::class, 'migrateSatker'])->name('utilities.migrate-satker');
        Route::post('/utilities/migrate-satker-preview', [DashboardController::class, 'migrateSatkerPreview'])->name('utilities.migrate-satker-preview');

        // Impersonate (Login sebagai user lain)
        Route::post('/impersonate', [UserController::class, 'impersonate'])->name('impersonate');
        Route::post('/impersonate/stop', [UserController::class, 'stopImpersonate'])->name('impersonate.stop');

        // Ubah Password User Sendiri
        Route::post('/change-password-own', [UserController::class, 'changePasswordOwn'])->name('users.change-password-own');

        // Laporan Kinerja Verification
        Route::post('/laporan-kinerja/approve', [DashboardController::class, 'approveLaporanKinerja'])->name('laporan-kinerja.approve');

        // Penilaian Kinerja (Hanya untuk role kepala)
        Route::middleware(['kepala'])->prefix('penilaian-kinerja')->name('penilaian-kinerja.')->group(function () {
            Route::get('/', [PenilaianKinerjaController::class, 'index'])->name('index');
            Route::get('/create', [PenilaianKinerjaController::class, 'create'])->name('create');
            Route::post('/', [PenilaianKinerjaController::class, 'store'])->name('store');
            Route::get('/{id}', [PenilaianKinerjaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PenilaianKinerjaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PenilaianKinerjaController::class, 'update'])->name('update');
            Route::delete('/{id}', [PenilaianKinerjaController::class, 'destroy'])->name('destroy');
            Route::get('/pejabat', [PenilaianKinerjaController::class, 'getPejabat'])->name('pejabat');
        });

        // Profile
        Route::get('/profile', function () {
            return view('admin.profile');
        })->name('profile');

        // TPG Verification Routes
        Route::prefix('tpg')->name('tpg.')->group(function () {
            Route::get('/', [TpgController::class, 'index'])->name('index');
            Route::get('/semester', [TpgController::class, 'semesterIndex'])->name('semester.index');
            Route::get('/{id}', [TpgController::class, 'show'])->name('show');
            Route::post('/{id}/verify', [TpgController::class, 'verify'])->name('verify');
            Route::post('/{id}/reject', [TpgController::class, 'reject'])->name('reject');
            Route::get('/{id}/file/{syaratId}', [TpgController::class, 'downloadFile'])->name('file');
            Route::get('/{id}/preview/{syaratId}', [TpgController::class, 'previewFile'])->name('preview');
        });

        // Madrasah Management Routes
        Route::prefix('madrasah')->name('madrasah.')->group(function () {
            Route::get('/', [MadrasahController::class, 'index'])->name('index');
            Route::post('/', [MadrasahController::class, 'saveProfile'])->name('store');
            Route::put('/{id}', [MadrasahController::class, 'saveProfile'])->name('update');
            Route::delete('/{id}', [MadrasahController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/profile', [MadrasahController::class, 'getProfile'])->name('profile');
            Route::post('/assign-user', [MadrasahController::class, 'assignUser'])->name('assign-user');
        });

        // Laporan Madrasah Verification Routes
        Route::prefix('madrasah/laporan')->name('madrasah.laporan.')->group(function () {
            Route::get('/', [MadrasahLaporanController::class, 'index'])->name('index');
            Route::get('/{type}/{id}', [MadrasahLaporanController::class, 'show'])->name('show');
            Route::post('/{type}/{id}/verify', [MadrasahLaporanController::class, 'verify'])->name('verify');
            Route::post('/{type}/{id}/reject', [MadrasahLaporanController::class, 'reject'])->name('reject');
            Route::post('/{type}/{id}/note', [MadrasahLaporanController::class, 'addNote'])->name('note');
        });
    });