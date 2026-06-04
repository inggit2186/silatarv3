<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
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
        Route::get('/users/{id}/show', [UserController::class, 'show'])->name('users.show');

        // Placeholder routes for other admin modules
        Route::get('/services', function () {
            return view('admin.services.index');
        })->name('services.index');

        Route::get('/services/create', function () {
            return view('admin.services.create');
        })->name('services.create');

        Route::get('/services/{id}', function ($id) {
            return view('admin.services.edit', ['id' => $id]);
        })->name('services.edit');

        Route::get('/units', function () {
            return view('admin.units.index');
        })->name('units.index');

        Route::get('/units/create', function () {
            return view('admin.units.create');
        })->name('units.create');

        Route::get('/units/{id}', function ($id) {
            return view('admin.units.edit', ['id' => $id]);
        })->name('units.edit');

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

        // Laporan Kinerja Verification
        Route::post('/laporan-kinerja/approve', [DashboardController::class, 'approveLaporanKinerja'])->name('laporan-kinerja.approve');

        // Profile
        Route::get('/profile', function () {
            return view('admin.profile');
        })->name('profile');
    });