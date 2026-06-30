<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PpidController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/berita', [PageController::class, 'allNews'])->name('news.index');
Route::get('/berita/{slug}', [PageController::class, 'newsShow'])->name('news.show');
Route::get('/pelayanan', [PageController::class, 'pelayanan'])->name('pelayanan');
Route::get('/pelayanan/unit/{deptId}/employees', [PageController::class, 'unitEmployees'])->name('pelayanan.unit.employees');
Route::get('/pelayanan/janji-temu/{deptId}', [PageController::class, 'janjiTemu'])->name('pelayanan.janji-temu')->whereNumber('deptId');
Route::post('/pelayanan/janji-temu/{deptId}', [PageController::class, 'submitJanjiTemu'])->name('pelayanan.janji-temu.submit')->whereNumber('deptId');
Route::get('/whistleblowing', [PageController::class, 'whistleblowing'])->name('whistleblowing');
Route::post('/whistleblowing', [PageController::class, 'submitWhistleblowing'])->name('whistleblowing.submit');
Route::middleware('auth')->group(function () {
    Route::get('/pelayanan/ajukan/{serviceId}', [PageController::class, 'requestService'])->whereNumber('serviceId')->name('pelayanan.request');
    Route::post('/pelayanan/ajukan/{serviceId}', [PageController::class, 'submitServiceRequest'])->whereNumber('serviceId')->name('pelayanan.request.submit');
    Route::get('/pengajuan-saya', [PageController::class, 'myRequests'])->name('pengajuan-saya');
    Route::get('/pengajuan-saya/{requestId}/edit', [PageController::class, 'editRequest'])->name('pengajuan-saya.edit');
    Route::get('/pengajuan-saya/{requestId}/file/{syaratId}/preview', [PageController::class, 'previewRequestFile'])->name('pengajuan-saya.preview-file');
    Route::get('/laporan-kinerja', [PageController::class, 'laporanKinerja'])->name('laporan-kinerja');
    Route::get('/laporan-kinerja/bawahan', [PageController::class, 'laporanKinerjaBawahan'])->name('laporan-kinerja.bawahan');
    Route::get('/profil', [PageController::class, 'profil'])->name('profil');
    Route::get('/profil/edit', [PageController::class, 'editProfil'])->name('profil.edit');
    Route::put('/profil/edit', [PageController::class, 'updateProfil'])->name('profil.update');
    Route::post('/laporan-kinerja/humas', [PageController::class, 'storeHumas'])->name('laporan-humas.store');
    Route::delete('/laporan-kinerja/humas/{id}', [PageController::class, 'destroyHumas'])->name('laporan-humas.destroy');
    Route::post('/laporan-kinerja/harian', [PageController::class, 'storeLaporanKinerja'])->name('laporan-kinerja.store');
    Route::get('/laporan-kinerja/rekap', [PageController::class, 'rekapLaporanKinerja'])->name('laporan-kinerja.rekap');
    Route::post('/laporan-kinerja/rekap/supervisor', [PageController::class, 'submitSupervisor'])->name('laporan-kinerja.rekap.supervisor');
    Route::get('/laporan-kinerja/bulanan/{reportId}/pdf', [PageController::class, 'downloadLaporanKinerjaPdf'])->whereNumber('reportId')->name('laporan-kinerja.pdf');
    Route::post('/laporan-kinerja/bulanan/{reportId}/replace', [PageController::class, 'replaceLaporanKinerjaFile'])->whereNumber('reportId')->name('laporan-kinerja.replace');
    Route::post('/laporan-kinerja/bulanan/upload', [PageController::class, 'uploadLaporanKinerjaManual'])->name('laporan-kinerja.upload');
    Route::put('/laporan-kinerja/day', [PageController::class, 'updateLaporanKinerjaByDate'])->name('laporan-kinerja.update-day');
    Route::delete('/laporan-kinerja/day', [PageController::class, 'deleteLaporanKinerjaByDate'])->name('laporan-kinerja.delete-day');
    Route::put('/laporan-kinerja/{activityId}', [PageController::class, 'updateLaporanKinerja'])->whereNumber('activityId')->name('laporan-kinerja.update');
    Route::delete('/laporan-kinerja/{activityId}', [PageController::class, 'deleteLaporanKinerja'])->whereNumber('activityId')->name('laporan-kinerja.delete');
    Route::get('/madrasah/profil', [PageController::class, 'profilMadrasah'])->name('madrasah.profil');
    Route::get('/madrasah/pegawai', [PageController::class, 'pegawaiMadrasah'])->name('madrasah.pegawai');
    Route::get('/madrasah/guru', [PageController::class, 'guruMadrasah'])->name('madrasah.guru');
    Route::get('/madrasah/laporan-semester', [PageController::class, 'laporanSemesterMadrasah'])->name('madrasah.laporan-semester');
    Route::post('/madrasah/laporan-semester/save', [PageController::class, 'saveLaporanSemesterMadrasah'])->name('madrasah.laporan-semester.save');
});

Route::get('/satuan-kerja', [PageController::class, 'satuanKerja'])->name('satuan-kerja');
Route::get('/satuan-kerja/{department}', [PageController::class, 'satuanKerjaDetail'])->name('unit-kerja.detail');

// Profil Kantor Pages
Route::get('/profil-kantor', [PageController::class, 'profilKantor'])->name('profil-kantor');
Route::get('/sejarah', [PageController::class, 'sejarah'])->name('sejarah');
Route::get('/struktur-organisasi', [PageController::class, 'strukturOrganisasi'])->name('struktur-organisasi');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Route::view('/register', 'auth.register')->name('register'); // Disabled for now

// Laporan Kinerja API (verification by atasan)
Route::post('/laporan-kinerja/verify', [App\Http\Controllers\PageController::class, 'verifyLaporanKinerja'])->middleware('auth')->name('laporan-kinerja.verify');

// User Signature Management
Route::middleware('auth')->group(function () {
    Route::get('/signature', [App\Http\Controllers\PageController::class, 'getSignature'])->name('signature.get');
    Route::post('/signature', [App\Http\Controllers\PageController::class, 'saveSignature'])->name('signature.save');
});

// Impersonate (Stop) - accessible from anywhere when logged in
Route::post('/impersonate/stop', [App\Http\Controllers\Admin\UserController::class, 'stopImpersonate'])->middleware('auth')->name('impersonate.stop');

// PPID Routes
Route::prefix('ppid')->group(function () {
    // Beranda
    Route::get('/', [PpidController::class, 'index'])->name('ppid');

    // Profil PPID
    Route::get('/profil-singkat', [PpidController::class, 'profilSingkat'])->name('ppid.profil-singkat');
    Route::get('/visi-misi', [PpidController::class, 'visiMisi'])->name('ppid.visi-misi');
    Route::get('/tugas-fungsi', [PpidController::class, 'tugasFungsi'])->name('ppid.tugas-fungsi');
    Route::get('/struktur', [PpidController::class, 'struktur'])->name('ppid.struktur');

    // Regulasi
    Route::get('/regulasi', [PpidController::class, 'regulasi'])->name('ppid.regulasi');

    // Standar Layanan
    Route::get('/maklumat', [PpidController::class, 'maklumat'])->name('ppid.maklumat');
    Route::get('/jadwal', [PpidController::class, 'jadwal'])->name('ppid.jadwal');
    Route::get('/biaya', [PpidController::class, 'biaya'])->name('ppid.biaya');
    Route::get('/laporan-layanan', [PpidController::class, 'laporanLayanan'])->name('ppid.laporan-layanan');

    // Layanan Informasi
    Route::get('/prosedur-permohonan', [PpidController::class, 'prosedurPermohonan'])->name('ppid.prosedur-permohonan');
    Route::get('/prosedur-keberatan', [PpidController::class, 'prosedurKeberatan'])->name('ppid.prosedur-keberatan');
    Route::get('/prosedur-sengketa', [PpidController::class, 'prosedurSengketa'])->name('ppid.prosedur-sengketa');
    Route::get('/formulir-permohonan', [PpidController::class, 'formulirPermohonan'])->name('ppid.formulir-permohonan');
    Route::get('/formulir-keberatan', [PpidController::class, 'formulirKeberatan'])->name('ppid.formulir-keberatan');
    Route::get('/informasi-berkala', [PpidController::class, 'informasiBerkala'])->name('ppid.informasi-berkala');
    Route::get('/informasi-serta-merta', [PpidController::class, 'informasiSertaMerta'])->name('ppid.informasi-serta-merta');
    Route::get('/informasi-setiap-saat', [PpidController::class, 'informasiSetiapSaat'])->name('ppid.informasi-setiap-saat');
    Route::get('/pengaduan', [PpidController::class, 'pengaduan'])->name('ppid.pengaduan');

    // Gallery
    Route::get('/gallery-fasilitas', [PpidController::class, 'galleryFasilitas'])->name('ppid.gallery-fasilitas');
    Route::get('/gallery-kegiatan', [PpidController::class, 'galleryKegiatan'])->name('ppid.gallery-kegiatan');

    // Tentang Kami
    Route::get('/tentang-kami', [PpidController::class, 'tentangKami'])->name('ppid.tentang-kami');
});
