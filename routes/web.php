<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PpidController;
use App\Http\Controllers\PenilaianKinerjaController;
use App\Http\Controllers\RegisterController;
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
    Route::post('/pelayanan/ajukan/tpg/{serviceId}', [PageController::class, 'submitTpgRequest'])->whereNumber('serviceId')->name('pelayanan.tpg.submit');
    Route::post('/pelayanan/ajukan/tpg-bulanan/{serviceId}', [PageController::class, 'submitTpgBulananRequest'])->whereNumber('serviceId')->name('pelayanan.tpg-bulanan.submit');
    Route::post('/pelayanan/ajukan/penmad-tpg-bulanan/{serviceId}', [PageController::class, 'submitPenmadTpgBulananRequest'])->whereNumber('serviceId')->name('pelayanan.penmad-tpg-bulanan.submit');
    Route::post('/pelayanan/ajukan/penmad-pengawas-bulanan/{serviceId}', [PageController::class, 'submitPenmadPengawasBulananRequest'])->whereNumber('serviceId')->name('pelayanan.penmad-pengawas-bulanan.submit');
    Route::get('/pengajuan-saya', [PageController::class, 'myRequests'])->name('pengajuan-saya');
    Route::get('/pengajuan-saya/{requestId}/edit', [PageController::class, 'editRequest'])->name('pengajuan-saya.edit');
    Route::delete('/pengajuan-saya/{requestId}/delete', [PageController::class, 'deleteRequest'])->name('pengajuan-saya.delete');
    Route::get('/pengajuan-saya/{requestId}/file/{syaratId}/preview', [PageController::class, 'previewRequestFile'])->name('pengajuan-saya.preview-file');
    Route::get('/pelayanan/tpg/{pemberkasanId}/file/{syaratId}/preview', [PageController::class, 'previewTpgFile'])->whereNumber(['pemberkasanId', 'syaratId'])->name('pelayanan.tpg.preview-file');
    Route::get('/pelayanan/tpg/{pemberkasanId}/edit', [PageController::class, 'editTpgRequest'])->name('pelayanan.tpg.form');
    Route::post('/pelayanan/tpg/{pemberkasanId}/update', [PageController::class, 'updateTpgRequest'])->name('pelayanan.tpg.update');
    Route::delete('/pelayanan/tpg/{pemberkasanId}/delete', [PageController::class, 'deleteTpgRequest'])->name('pelayanan.tpg.delete');
    // TPG Bulanan routes (for service 1038 and 1081)
    Route::get('/pelayanan/tpg-bulanan/{pemberkasanId}/file/{syaratId}/preview', [PageController::class, 'previewTpgBulananFile'])->whereNumber(['pemberkasanId', 'syaratId'])->name('pelayanan.tpg-bulanan.preview-file');
    Route::get('/pelayanan/tpg-bulanan/{pemberkasanId}/edit', [PageController::class, 'editTpgBulananRequest'])->name('pelayanan.tpg-bulanan.form');
    Route::post('/pelayanan/tpg-bulanan/{pemberkasanId}/update', [PageController::class, 'updateTpgBulananRequest'])->name('pelayanan.tpg-bulanan.update');
    Route::delete('/pelayanan/tpg-bulanan/{pemberkasanId}/delete', [PageController::class, 'deleteTpgBulananRequest'])->name('pelayanan.tpg-bulanan.delete');
    // PENMAD TPG Bulanan routes (for service 1081)
    Route::get('/pelayanan/penmad-tpg-bulanan/{pemberkasanId}/file/{syaratId}/preview', [PageController::class, 'previewPenmadTpgBulananFile'])->whereNumber(['pemberkasanId', 'syaratId'])->name('pelayanan.penmad-tpg-bulanan.preview-file');
    Route::get('/pelayanan/penmad-tpg-bulanan/{pemberkasanId}/edit', [PageController::class, 'editPenmadTpgBulananRequest'])->name('pelayanan.penmad-tpg-bulanan.form');
    Route::post('/pelayanan/penmad-tpg-bulanan/{pemberkasanId}/update', [PageController::class, 'updatePenmadTpgBulananRequest'])->name('pelayanan.penmad-tpg-bulanan.update');
    Route::delete('/pelayanan/penmad-tpg-bulanan/{pemberkasanId}/delete', [PageController::class, 'deletePenmadTpgBulananRequest'])->name('pelayanan.penmad-tpg-bulanan.delete');
    // PENMAD Pengawas Bulanan routes (for service 1082)
    Route::get('/pelayanan/penmad-pengawas-bulanan/{pemberkasanId}/file/{syaratId}/preview', [PageController::class, 'previewPenmadPengawasBulananFile'])->whereNumber(['pemberkasanId', 'syaratId'])->name('pelayanan.penmad-pengawas-bulanan.preview-file');
    Route::get('/pelayanan/penmad-pengawas-bulanan/{pemberkasanId}/edit', [PageController::class, 'editPenmadPengawasBulananRequest'])->name('pelayanan.penmad-pengawas-bulanan.form');
    Route::post('/pelayanan/penmad-pengawas-bulanan/{pemberkasanId}/update', [PageController::class, 'updatePenmadPengawasBulananRequest'])->name('pelayanan.penmad-pengawas-bulanan.update');
    Route::delete('/pelayanan/penmad-pengawas-bulanan/{pemberkasanId}/delete', [PageController::class, 'deletePenmadPengawasBulananRequest'])->name('pelayanan.penmad-pengawas-bulanan.delete');
    Route::get('/laporan-kinerja', [PageController::class, 'laporanKinerja'])->name('laporan-kinerja');
    Route::get('/laporan-kinerja/bawahan', [PageController::class, 'laporanKinerjaBawahan'])->name('laporan-kinerja.bawahan');

    // Penilaian Kinerja - Hanya untuk role kepala
    Route::prefix('penilaian-kinerja')->name('penilaian-kinerja.')->group(function () {
        Route::get('/', [PenilaianKinerjaController::class, 'index'])->name('index');
        Route::get('/create', [PenilaianKinerjaController::class, 'create'])->name('create');
        Route::post('/', [PenilaianKinerjaController::class, 'store'])->name('store');
        Route::get('/{id}', [PenilaianKinerjaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PenilaianKinerjaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PenilaianKinerjaController::class, 'update'])->name('update');
        Route::delete('/{id}', [PenilaianKinerjaController::class, 'destroy'])->name('destroy');
    });
    Route::get('/profil', [PageController::class, 'profil'])->name('profil');
    Route::get('/profil/edit', [PageController::class, 'editProfil'])->name('profil.edit');
    Route::put('/profil/edit', [PageController::class, 'updateProfil'])->name('profil.update');
    Route::get('/ubah-password', [PageController::class, 'ubahPassword'])->name('ubah-password');
    Route::post('/ubah-password', [PageController::class, 'updatePassword'])->name('ubah-password.update');
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
    Route::post('/madrasah/profil/save', [PageController::class, 'saveProfilMadrasah'])->name('madrasah.profil.save');
    Route::get('/madrasah/pegawai', [PageController::class, 'pegawaiMadrasah'])->name('madrasah.pegawai');
    Route::post('/madrasah/pegawai/save', [PageController::class, 'savePegawaiMadrasah'])->name('madrasah.pegawai.save');
    Route::post('/madrasah/pegawai/update', [PageController::class, 'updatePegawaiMadrasah'])->name('madrasah.pegawai.update');
    Route::post('/madrasah/pegawai/delete', [PageController::class, 'deletePegawaiMadrasah'])->name('madrasah.pegawai.delete');
    Route::get('/madrasah/guru', [PageController::class, 'guruMadrasah'])->name('madrasah.guru');
    Route::post('/madrasah/guru/save', [PageController::class, 'saveGuruMadrasah'])->name('madrasah.guru.save');
    Route::post('/madrasah/guru/update', [PageController::class, 'updateGuruMadrasah'])->name('madrasah.guru.update');
    Route::post('/madrasah/guru/delete', [PageController::class, 'deleteGuruMadrasah'])->name('madrasah.guru.delete');
    Route::get('/madrasah/laporan-semester', [PageController::class, 'laporanSemesterMadrasah'])->name('madrasah.laporan-semester');
    Route::post('/madrasah/laporan-semester/save', [PageController::class, 'saveLaporanSemesterMadrasah'])->name('madrasah.laporan-semester.save');
    Route::get('/madrasah/laporan-bulanan', [PageController::class, 'laporanBulananMadrasah'])->name('madrasah.laporan-bulanan');
    Route::post('/madrasah/laporan-bulanan/save', [PageController::class, 'saveLaporanBulananMadrasah'])->name('madrasah.laporan-bulanan.save');
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

    // Registration routes
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
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

// WhatsApp Webhook Routes
use App\Http\Controllers\WebhookController;

Route::prefix('webhook')->group(function () {
    // WhatsApp webhook endpoint - POST
    Route::post('/whatsapp', [WebhookController::class, 'Webhook'])->name('webhook.whatsapp');

    // WhatsApp webhook verification - GET (for Facebook/WhatsApp API verification)
    Route::get('/whatsapp', [WebhookController::class, 'verify'])->name('webhook.whatsapp.verify');
});

// Test endpoint for development - POST /webhook/whatsapp/test?message=halo&from=6281234567890&name=Test
Route::post('/webhook/whatsapp/test', [WebhookController::class, 'test'])->name('webhook.whatsapp.test');
