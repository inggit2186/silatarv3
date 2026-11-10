<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\Admin\UnitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

$adminId = DB::table('users')->where('role', 'superadmin')->value('id');
Auth::loginUsingId($adminId);
View::share('errors', new Illuminate\Support\ViewErrorBag);

$controller = new UnitController;
$fail = 0;

$deptId = 20220927137; // MTsN 1 Tanah Datar
$dept = DB::table('ktd_department')->where('id', $deptId)->first();

// Pool kepala: hanya pegawai dengan dept_id sama
$calon = DB::table('users')->where('dept_id', $deptId)
    ->where(fn ($q) => $q->where('kat_jabatan', '!=', 'kepala')->orWhere('status', '!=', 3))
    ->orderBy('id')->first();

$luar = DB::table('users')->where('dept_id', '!=', $deptId)->orderBy('id')->first();

$before = (array) DB::table('users')->where('id', $calon->id)->first();
echo "unit: {$dept->nama} (id={$deptId})\n";
echo "calon: {$calon->id} {$calon->name} kat={$calon->kat_jabatan} status={$calon->status}\n";

// --- 1. pool kepala hanya berisi user dept yang sama ---
$view = $controller->edit(Request::create("/admin/units/{$deptId}/edit", 'GET'), $deptId);
$poolKepala = $view->getData()['kandidatKepala'];
$poolPlt = $view->getData()['kandidatPlt'];

$semuaDeptSama = $poolKepala->every(fn ($p) => (int) DB::table('users')->where('id', $p['id'])->value('dept_id') === (int) $deptId);
if ($semuaDeptSama && $poolKepala->isNotEmpty()) {
    echo "[OK] pool kepala: {$poolKepala->count()} 항목, semua dept_id = unit\n";
} else {
    $fail++;
    echo "[FAIL] pool kepala memuat user dari dept lain\n";
}

if ($poolKepala->contains('id', $luar->id)) {
    $fail++;
    echo '[FAIL] pool kepala memuat user dept lain '.$luar->id."\n";
}

// --- 2. pool plt hanya berisi user status = 3 ---
$semuaStatus3 = $poolPlt->every(fn ($p) => (int) DB::table('users')->where('id', $p['id'])->value('status') === 3);
if ($semuaStatus3 && $poolPlt->isNotEmpty()) {
    echo "[OK] pool plt: {$poolPlt->count()} item, semua status = 3\n";
} else {
    $fail++;
    echo '[FAIL] pool plt bukan semua status 3'.PHP_EOL;
}

$adaStatus1 = $poolPlt->contains(fn ($p) => (int) DB::table('users')->where('id', $p['id'])->value('status') !== 3);
if ($adaStatus1) {
    $fail++;
    echo '[FAIL] pool plt memuat user status != 3'.PHP_EOL;
}

// --- 3. combobox renders, no server-side search form lagi ---
$html = $view->render();
foreach ([
    'data-combobox-input',
    'data-combobox-pool',
    'data-combobox-menu',
    'data-combobox-add',
    'data-combobox-submit',
    'data-combobox-value',
] as $needle) {
    if (! str_contains($html, $needle)) {
        $fail++;
        echo "[FAIL] halaman tidak memuat: {$needle}\n";
    }
}
if (str_contains($html, 'name="cari_kepala"') || str_contains($html, 'name="cari_plt"')) {
    $fail++;
    echo "[FAIL] form pencarian server-side masih ada\n";
} else {
    echo "[OK] pencarian server-side digantikan typeahead\n";
}

// pool JSON ter-embed dengan benar
if (preg_match('/data-combobox-pool>(.*?)<\/script>/s', $html, $m)) {
    $decoded = json_decode(html_entity_decode($m[1], ENT_QUOTES), true);
    if (is_array($decoded) && count($decoded) > 0 && isset($decoded[0]['id'], $decoded[0]['name'])) {
        echo '[OK] pool JSON valid ('.count($decoded)." entri)\n";
    } else {
        $fail++;
        echo '[FAIL] pool JSON tidak valid: '.substr($m[1], 0, 120).PHP_EOL;
    }
} else {
    $fail++;
    echo '[FAIL] pool JSON tidak ditemukan'.PHP_EOL;
}

// --- 4. promote ---
$request = Request::create("/admin/units/{$deptId}/kepala", 'PUT', ['kepala_ids' => [$calon->id]]);
$request->setLaravelSession(app('session.store'));
$controller->updateKepala($request, $deptId);
$after = (array) DB::table('users')->where('id', $calon->id)->first();
if ($after['kat_jabatan'] === 'kepala' && (int) $after['status'] === 3) {
    echo "[OK] promote: kat={$after['kat_jabatan']} status={$after['status']}\n";
} else {
    $fail++;
    echo '[FAIL] promote'.PHP_EOL;
}

// --- 5. demote: hanya kat_jabatan -> ex-kepala, status TIDAK berubah ---
$request = Request::create("/admin/units/{$deptId}/kepala", 'PUT', ['kepala_ids' => []]);
$request->setLaravelSession(app('session.store'));
$controller->updateKepala($request, $deptId);
$demoted = (array) DB::table('users')->where('id', $calon->id)->first();
if ($demoted['kat_jabatan'] === 'ex-kepala') {
    echo "[OK] demote: kat_jabatan = ex-kepala\n";
} else {
    $fail++;
    echo '[FAIL] demote kat_jabatan = '.$demoted['kat_jabatan'].PHP_EOL;
}
if ((int) $demoted['status'] === 3) {
    echo "[OK] demote: status tetap 3\n";
} else {
    $fail++;
    echo '[FAIL] demote mengubah status menjadi '.$demoted['status'].PHP_EOL;
}

// --- 6. ex-kepala tidak lagi terhitung sebagai kepala ---
$heads = DB::table('users')->where('dept_id', $deptId)->where('kat_jabatan', 'kepala')->where('status', 3)->pluck('id')->all();
if (! in_array($calon->id, $heads)) {
    echo "[OK] ex-kepala tidak masuk daftar kepala\n";
} else {
    $fail++;
    echo '[FAIL] ex-kepala masih terhitung sebagai kepala'.PHP_EOL;
}

// --- 7. promote lagi works dari ex-kepala ---
$request = Request::create("/admin/units/{$deptId}/kepala", 'PUT', ['kepala_ids' => [$calon->id]]);
$request->setLaravelSession(app('session.store'));
$controller->updateKepala($request, $deptId);
$repromoted = DB::table('users')->where('id', $calon->id)->first();
if ($repromoted->kat_jabatan === 'kepala') {
    echo "[OK] re-promote dari ex-kepala\n";
} else {
    $fail++;
    echo '[FAIL] re-promote'.PHP_EOL;
}

// --- 8. PLT/PLH add + remove ---
$pltUser = DB::table('users')->where('status', 3)->where('dept_id', '!=', $deptId)->orderBy('id')->first();
$request = Request::create("/admin/units/{$deptId}/plt-plh", 'POST', ['user_id' => $pltUser->id]);
$request->setLaravelSession(app('session.store'));
$controller->storePltPlh($request, $deptId);
$row = DB::table('plt_plh')->where('dept_id_plh', $deptId)->where('user_id', $pltUser->id)->first();
if ($row) {
    echo "[OK] PLT/PLH ditambahkan (user status 3 dari unit lain: {$pltUser->id})\n";
    $request3 = Request::create("/admin/units/{$deptId}/plt-plh/{$row->id}", 'DELETE');
    $request3->setLaravelSession(app('session.store'));
    $controller->destroyPltPlh($deptId, $row->id);
    echo (DB::table('plt_plh')->where('id', $row->id)->count() === 0) ? "[OK] PLT/PLH dihapus\n" : "[FAIL] hapus PLT/PLH\n";
    if (DB::table('plt_plh')->where('id', $row->id)->count() !== 0) {
        $fail++;
    }
} else {
    $fail++;
    echo '[FAIL] PLT/PLH tidak ditambahkan'.PHP_EOL;
}

// --- 9. index + create masih aman ---
try {
    $controller->index(Request::create('/admin/units', 'GET'))->render();
    $controller->create()->render();
    echo "[OK] index & create render\n";
} catch (\Throwable $e) {
    $fail++;
    echo '[FAIL] index/create: '.$e->getMessage().PHP_EOL;
}

// --- restore ---
DB::table('users')->where('id', $calon->id)->update([
    'kat_jabatan' => $before['kat_jabatan'],
    'status' => $before['status'],
]);

echo $fail === 0 ? "\nALL CHECKS PASSED\n" : "\n{$fail} CHECK(S) FAILED\n";
