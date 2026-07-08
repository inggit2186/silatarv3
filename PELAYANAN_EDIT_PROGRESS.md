# Progress Edit Halaman Pelayanan

## Overview

Mengedit halaman /pelayanan untuk setiap layanan agar saat klik "Ajukan" muncul form yang sesuai dengan kebutuhan layanan.

---

## 1. Pemberkasan Pencairan Tunjangan/TPG (/Semester)

**Service ID:** 1037
**Status:** ✅ SELESAI

### Deskripsi Singkat

Layanan pemberkasan pencairan Tunjangan Profesi Guru (TPG) berbasis semester. User memilih Tahun Pelajaran dan Semester sebelum mengisi form. Data disimpan dalam format JSON Snapshot untuk preservasi state saat diajukan.

### Alur Kerja (User Flow)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              USER FLOW                                       │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  1. User buka /pelayanan                                                     │
│           ↓                                                                  │
│  2. Klik tombol "Ajukan" pada layanan "Pemberkasan TPG" (ID: 1037)          │
│           ↓                                                                  │
│  3. Modal popup muncul dengan pilihan:                                        │
│     - Tahun Pelajaran (e.g., "2026/2027")                                   │
│     - Semester (e.g., "Ganjil" / "Genap")                                   │
│           ↓                                                                  │
│  4. Klik "Ajukan Sekarang"                                                   │
│           ↓                                                                  │
│  5. Redirect ke /pelayanan/ajukan/1037?tahun_pelajaran=2026/2027&semester=Ganjil│
│           ↓                                                                  │
│  6. Form service-request.blade.php tampil dengan:                            │
│     - Hidden fields (tahun_pelajaran, semester)                              │
│     - Kolom upload file sesuai syarat layanan                                │
│     - Info periode (jika sudah pernah diajukan)                             │
│           ↓                                                                  │
│  7. User upload file-file yang diperlukan                                   │
│           ↓                                                                  │
│  8. Klik "Ajukan"                                                           │
│           ↓                                                                  │
│  9. Data tersimpan ke satker_pemberkasan dengan JSON Snapshot               │
│           ↓                                                                  │
│  10. Redirect ke /pengajuan-saya dengan pesan sukses                        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Alur Data (Data Flow)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              DATA FLOW                                       │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  INPUT FORM                                                                  │
│  ├── tahun_pelajaran: "2026/2027"                                           │
│  ├── semester: "Ganjil"                                                     │
│  └── file uploads (req_{syarat_id})                                        │
│           ↓                                                                  │
│  PAGE CONTROLLER - submitTpgRequest()                                        │
│  ├── Generate noreq: "PAIS-TPG-SEMESTER-{user_id}-{tp}-{semester}"        │
│  ├── Calculate item_id: 1 (Ganjil) / 2 (Genap)                             │
│  ├── Calculate waktu: tanggal mulai semester                                  │
│  └── Build JSON snapshots                                                   │
│           ↓                                                                  │
│  STORAGE                                                                     │
│  └── storage/app/users_berkas/{nomor_induk}/{filename}                     │
│           ↓                                                                  │
│  DATABASE - satker_pemberkasan                                               │
│  ├── tipe: "PAIS-TPG-SEMESTER"                                              │
│  ├── noreq: "PAIS-TPG-SEMESTER-45-2026/2027-GANJIL"                        │
│  ├── files: [JSON array of uploaded files]                                 │
│  ├── metadata: [JSON object with tahun_pelajaran, semester, etc]            │
│  └── requirements_snapshot: [JSON array of requirements at submission time] │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## Struktur Teknis

### Database Schema

#### Table: `satker_pemberkasan`

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | BIGINT | NO | AUTO | Primary key |
| tipe | VARCHAR(255) | NO | - | Kategori layanan (e.g., PAIS-TPG-SEMESTER) |
| layanan_id | BIGINT | NO | - | Service ID (1037) |
| user_id | BIGINT | NO | - | User ID yang mengajukan |
| dept_id | BIGINT | NO | - | Department ID user |
| waktu | DATE | NO | - | Tanggal mulai periode (Jul/Gan atau Jan/Gen) |
| item_id | INT | NO | - | 1=Ganjil, 2=Genap |
| noreq | VARCHAR(255) | YES | - | Unique request number |
| keterangan | TEXT | YES | - | Keterangan dari user |
| deskripsi | VARCHAR(255) | YES | - | Deskripsi auto-generated |
| files | LONGTEXT | YES | - | JSON array of uploaded files |
| metadata | LONGTEXT | YES | - | JSON object with submission metadata |
| requirements_snapshot | LONGTEXT | YES | - | JSON array of requirements at submission time |
| status | VARCHAR(255) | NO | 'DRAFT' | Status pengajuan |
| is_migrated | TINYINT(1) | NO | 0 | Flag migration |
| migrated_at | TIMESTAMP | YES | - | Timestamp migration |
| verifikator_id | BIGINT | NO | 999 | ID verifikator |
| created_at | DATETIME | NO | CURRENT | Created timestamp |
| updated_at | DATETIME | NO | CURRENT | Updated timestamp |

#### Table: `users_berkas` (existing, untuk layanan lain)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | Primary key |
| no_req | VARCHAR | Request number |
| layanan_id | INT | Service ID |
| syarat_id | INT | Requirement ID |
| filename | VARCHAR | File name |
| filetype | VARCHAR | File extension |
| size | VARCHAR | File size |
| status | INT | Upload status |
| created_at | DATETIME | Created timestamp |
| updated_at | DATETIME | Updated timestamp |

### Routes

| Method | URI | Controller@Method | Description |
|--------|-----|-------------------|-------------|
| GET | `/pelayanan/ajukan/{serviceId}` | PageController@requestService | Form ajukan layanan |
| POST | `/pelayanan/ajukan/{serviceId}` | PageController@submitServiceRequest | Submit layanan biasa |
| POST | `/pelayanan/ajukan/tpg/{serviceId}` | PageController@submitTpgRequest | Submit layanan TPG (1037) |
| GET | `/pelayanan/tpg/{pemberkasanId}/file/{syaratId}/preview` | PageController@previewTpgFile | Preview file TPG |

### Files yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/PageController.php` | RequestService, SubmitTpgRequest, PreviewTpgFile |
| `app/Models/SatkerPemberkasan.php` | Eloquent model baru |
| `resources/views/pelayanan.blade.php` | Modal selection tahun/semester |
| `resources/views/service-request.blade.php` | Info banner, hidden fields |
| `routes/web.php` | Route baru untuk TPG |
| `config/filesystems.php` | Disk users_berkas |
| `resources/css/neo-mirai-home.css` | Style clickable file preview |

### Files Baru

| File | Purpose |
|------|---------|
| `app/Models/SatkerPemberkasan.php` | Eloquent model untuk satker_pemberkasan |
| `public/storage/users_berkas` | Junction link ke storage |

---

## JSON Snapshot Structure

### 1. `files` - Files Snapshot

Menyimpan informasi semua file yang diupload/diperlukan.

```json
[
  {
    "syarat_id": 480,
    "title": "Surat Keterangan Melaksanakan Tugas (SKMT)",
    "type": "file",
    "is_required": true,
    "filename": "PAIS-TPG-SEMESTER-45-2026-2027-GANJIL.45.skmt.pdf",
    "filetype": "pdf",
    "size": "1024000",
    "status": 1,
    "uploaded_at": "2026-07-08T10:30:00Z"
  },
  {
    "syarat_id": 517,
    "title": "SPTJM",
    "type": "file",
    "is_required": true,
    "filename": "NONE",
    "filetype": null,
    "size": null,
    "status": 0,
    "uploaded_at": null
  }
]
```

**Field Description:**
- `syarat_id`: ID dari ktd_syarat
- `title`: Judul persyaratan
- `type`: Tipe input (selalu "file" untuk snapshot ini)
- `is_required`: Apakah wajib diupload
- `filename`: Nama file yang tersimpan, atau "NONE" jika belum upload
- `filetype`: Extension file (pdf, jpg, png)
- `size`: Ukuran file dalam bytes
- `status`: 0=belum upload, 1=sudah upload
- `uploaded_at`: Timestamp upload

### 2. `metadata` - Metadata Submission

Menyimpan informasi meta submission.

```json
{
  "tahun_pelajaran": "2026/2027",
  "semester": "Ganjil",
  "kategori": "PAIS-TPG-SEMESTER",
  "tahun_ajaran": 2026,
  "submitted_at": "2026-07-08T10:30:00Z",
  "is_draft": false
}
```

**Field Description:**
- `tahun_pelajaran`: Tahun pelajaran (format "2026/2027")
- `semester`: Semester ("Ganjil" atau "Genap")
- `kategori`: Kategori layanan (selalu "PAIS-TPG-SEMESTER")
- `tahun_ajaran`: Tahun ajaran pertama (di-parse dari tahun_pelajaran)
- `submitted_at`: Timestamp submission
- `is_draft`: Apakah ini draft atau submit正式

### 3. `requirements_snapshot` - Requirements Snapshot

Menyimpan state syarat layanan SAAT dilakukan submission.

```json
[
  {
    "id": 480,
    "title": "Surat Keterangan Melaksanakan Tugas (SKMT)",
    "note": "Wajib diupload",
    "is_required": true,
    "type": "file"
  },
  {
    "id": 517,
    "title": "SPTJM",
    "note": "Surat Pertanggungjawaban Mutlak",
    "is_required": true,
    "type": "file"
  }
]
```

**Kegunaan:**
- Jika admin ubah syarat setelah user submit, data ini menunjukkan syarat apa YANG SEHARUSNYA.
- Untuk admin arsip/verifikasi: bisa lihat apa yang user isi saat itu.
- User saat edit tetap pakai syarat terbaru (sesuai request).

---

## noreq (Request Number) Format

### Format
```
PAIS-TPG-SEMESTER-{USER_ID}-{TAHUN_PELAJARAN}-{SEMESTER}
```

### Contoh
```
PAIS-TPG-SEMESTER-45-2026/2027-GANJIL
```

### Breakdown
| Segment | Description |
|---------|-------------|
| PAIS-TPG-SEMESTER | Prefix kategori |
| 45 | User ID |
| 2026/2027 | Tahun Pelajaran |
| GANJIL | Semester (uppercase) |

---

## File Storage

### Struktur Direktori

```
storage/app/users_berkas/
└── {nomor_induk}/
    ├── PAIS-TPG-SEMESTER-45-2026-2027-GANJIL.45.skmt.pdf
    ├── PAIS-TPG-SEMESTER-45-2026-2027-GANJIL.45.sptjm.pdf
    └── ...

public/storage/users_berkas → junction → storage/app/users_berkas
```

### Akses File

```
URL: https://domain.com/storage/users_berkas/{nomor_induk}/{filename}
```

### Konfigurasi Filesystem

File: `config/filesystems.php`

```php
'users_berkas' => [
    'driver' => 'local',
    'root' => storage_path('app/users_berkas'),
    'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage/users_berkas',
    'throw' => false,
    'report' => false,
],
```

---

## Code Snippets

### Controller: requestService()

```php
public function requestService(int $serviceId, Request $request = null)
{
    $service = $this->serviceDetail($serviceId);
    $requester = auth()->user();

    $req = $request ?? request();
    $tahunPelajaran = $req->query('tahun_pelajaran');
    $semester = $req->query('semester');

    // Check for existing submission
    $existingSubmission = null;
    $existingFiles = [];
    if ($serviceId === 1037 && $tahunPelajaran && $semester) {
        $existingNoreq = strtoupper("PAIS-TPG-SEMESTER-{$requester->id}-{$tahunPelajaran}-{$semester}");
        $existingSubmission = DB::table('satker_pemberkasan')
            ->where('noreq', $existingNoreq)
            ->first();

        if ($existingSubmission) {
            // Decode files (handle double-encoded JSON)
            $filesRaw = $existingSubmission->files ?? null;
            $filesData = [];
            if (is_array($filesRaw)) {
                $filesData = $filesRaw;
            } elseif (is_string($filesRaw)) {
                $decoded = json_decode($filesRaw, true);
                if (is_string($decoded)) {
                    $decoded = json_decode($decoded, true);
                }
                $filesData = is_array($decoded) ? $decoded : [];
            }

            foreach ($filesData as $file) {
                $syaratId = $file['syarat_id'] ?? 0;
                if ($file['filename'] && $file['filename'] !== 'NONE') {
                    $existingFiles[$syaratId] = [
                        'filename' => $file['filename'],
                        'filetype' => $file['filetype'] ?? null,
                        'size' => $file['size'] ?? null,
                        'url' => route('pelayanan.tpg.preview-file', [
                            'pemberkasanId' => $existingSubmission->id,
                            'syaratId' => $syaratId,
                        ]),
                    ];
                }
            }
        }
    }

    return view('service-request', array_merge(
        $this->requestFormViewData($service, $requester, false, $existingSubmission, [], $existingFiles),
        [
            'service' => $service,
            'tahunPelajaran' => $tahunPelajaran,
            'semester' => $semester,
            'existingSubmission' => $existingSubmission,
        ]
    ));
}
```

### Controller: submitTpgRequest()

```php
public function submitTpgRequest(Request $request, int $serviceId): \Illuminate\Http\RedirectResponse
{
    $service = $this->serviceDetail($serviceId);
    $requirements = $service['requirements'];
    $requester = $request->user();
    $isDraft = $request->input('submit_action') === 'draft';

    abort_unless($requester, 403);

    // Validate required fields
    $validated = $request->validate([
        'tahun_pelajaran' => ['required', 'string'],
        'semester' => ['required', 'string'],
    ]);

    // Fixed kategori for service 1037
    $kategori = 'PAIS-TPG-SEMESTER';
    $tahunPelajaran = $validated['tahun_pelajaran'];
    $semester = $validated['semester'];

    // item_id: 1=Ganjil, 2=Genap
    $itemId = strtoupper($semester) === 'GENAP' ? 2 : 1;

    // Parse tahun from tahun_pelajaran (e.g., "2026/2027" -> 2026)
    $tahunParts = explode('/', $tahunPelajaran);
    $tahun = (int) ($tahunParts[0] ?? date('Y'));

    // waktu: Ganjil=July, Genap=January (tahun berikutnya)
    $waktuBulan = $itemId === 1 ? 7 : 1;
    $waktuTahun = $itemId === 1 ? $tahun : $tahun + 1;
    $waktuDate = Carbon::createFromDate($waktuTahun, $waktuBulan, 1)->startOfMonth();

    // Generate noreq
    $noreq = strtoupper("{$kategori}-{$requester->id}-{$tahunPelajaran}-{$semester}");

    // Generate deskripsi
    $deskripsi = "[{$kategori}] Semester {$semester} TP. {$tahunPelajaran}";

    // Build files snapshot
    $filesSnapshot = $this->buildFilesSnapshot($requester, $serviceId, $noreq, $requirements, $request);

    // Build metadata
    $metadata = [
        'tahun_pelajaran' => $tahunPelajaran,
        'semester' => $semester,
        'kategori' => $kategori,
        'tahun_ajaran' => $tahun,
        'submitted_at' => now()->toIso8601String(),
        'is_draft' => $isDraft,
    ];

    // Build requirements snapshot
    $requirementsSnapshot = collect($requirements)->map(function ($req) {
        return [
            'id' => $req['id'],
            'title' => $req['title'],
            'note' => $req['note'],
            'is_required' => $req['is_required'],
            'type' => $req['type_normalized'],
        ];
    })->toArray();

    // Save to database
    SatkerPemberkasan::updateOrCreate(
        ['noreq' => $noreq],
        [
            'user_id' => $requester->id,
            'tipe' => $kategori,
            'layanan_id' => $serviceId,
            'dept_id' => (string) $requester->dept_id,
            'waktu' => $waktuDate,
            'item_id' => $itemId,
            'deskripsi' => $deskripsi,
            'keterangan' => $request->input('deskripsi') ?? '<NoKomen>',
            'status' => $isDraft ? 'DRAFT' : 'SUBMITTED',
            'files' => json_encode($filesSnapshot),
            'metadata' => json_encode($metadata),
            'requirements_snapshot' => json_encode($requirementsSnapshot),
            'is_migrated' => true,
            'migrated_at' => now(),
        ]
    );

    $message = $isDraft
        ? "Draft {$service['title']} sudah disimpan."
        : "Pengajuan {$service['title']} sudah diterima.";

    return redirect()
        ->route('pengajuan-saya')
        ->with('success', $message);
}
```

### Controller: previewTpgFile()

```php
public function previewTpgFile(int $pemberkasanId, int $syaratId)
{
    $user = auth()->user();
    abort_unless($user, 403);

    $pemberkasan = DB::table('satker_pemberkasan')
        ->where('id', $pemberkasanId)
        ->where('user_id', $user->id)
        ->first();

    abort_unless($pemberkasan, 404);

    // Decode files (handle double-encoded JSON)
    $filesRaw = $pemberkasan->files ?? null;
    $filesData = [];
    if (is_string($filesRaw)) {
        $decoded = json_decode($filesRaw, true);
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }
        $filesData = is_array($decoded) ? $decoded : [];
    } elseif (is_array($filesRaw)) {
        $filesData = $filesRaw;
    }

    $fileEntry = collect($filesData)->firstWhere('syarat_id', $syaratId);
    abort_unless($fileEntry && !empty($fileEntry['filename']) && $fileEntry['filename'] !== 'NONE', 404);

    $path = "{$user->nomor_induk}/{$fileEntry['filename']}";
    abort_unless(Storage::disk('users_berkas')->exists($path), 404);

    return Storage::disk('users_berkas')->response($path);
}
```

### Model: SatkerPemberkasan.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatkerPemberkasan extends Model
{
    protected $table = 'satker_pemberkasan';

    protected $fillable = [
        'tipe',
        'layanan_id',
        'user_id',
        'dept_id',
        'waktu',
        'item_id',
        'noreq',
        'keterangan',
        'deskripsi',
        'files',
        'metadata',
        'requirements_snapshot',
        'status',
        'is_migrated',
        'migrated_at',
        'verifikator_id',
    ];

    protected $casts = [
        'waktu' => 'date',
        'files' => 'array',
        'metadata' => 'array',
        'requirements_snapshot' => 'array',
        'is_migrated' => 'boolean',
        'migrated_at' => 'datetime',
    ];
}
```

---

## Testing

### Manual Test Steps

1. **Test Modal Selection**
   ```
   - Buka /pelayanan
   - Klik "Ajukan" pada layanan "Pemberkasan Pencairan Tunjangan/TPG"
   - Modal muncul dengan dropdown Tahun Pelajaran dan Semester
   - Pilih tahun dan semester
   - Klik "Ajukan Sekarang"
   - Redirect ke form dengan query params
   ```

2. **Test Submission**
   ```
   - Buka /pelayanan/ajukan/1037?tahun_pelajaran=2026/2027&semester=Ganjil
   - Upload file-file yang diperlukan
   - Klik "Ajukan"
   - Redirect ke /pengajuan-saya dengan pesan sukses
   - Cek database: SELECT * FROM satker_pemberkasan WHERE noreq LIKE '%2026/2027%'
   ```

3. **Test Existing Submission Detection**
   ```
   - Submit pengajuan untuk TP 2026/2027 Ganjil
   - Buka lagi /pelayanan/ajukan/1037?tahun_pelajaran=2026/2027&semester=Ganjil
   - Banner "Pengajuan sebelumnya ditemukan" harus muncul
   - File yang sudah diupload harus tampil sebagai link
   ```

4. **Test File Preview**
   ```
   - Submit pengajuan dengan file
   - Buka form lagi
   - Klik link file yang sudah ada
   - File harus terbuka di tab baru
   ```

---

## Checklist Pengembangan Layanan Baru

Jika ingin menambahkan layanan baru dengan format serupa:

- [ ] 1. Identifikasi service ID
- [ ] 2. Cek ktd_layanan dan ktd_syarat
- [ ] 3. Tambah modal selection di pelayanan.blade.php (jika perlu parameter tambahan)
- [ ] 4. Buat method submit baru di PageController (atau reuse submitServiceRequest)
- [ ] 5. Tambah route baru jika perlu
- [ ] 6. Update view jika perlu
- [ ] 7. Test alur dari awal sampai selesai
- [ ] 8. Dokumentasi di section berikutnya

---

## Checklist Fitur yang Sudah Berjalan

### User View
- [x] Modal selection tahun/semester
- [x] Redirect dengan query params
- [x] Form dengan hidden fields
- [x] Info banner jika sudah ada submission
- [x] Link file yang sudah diupload (clickable)
- [x] File compression (client-side)

### Backend
- [x] Generate noreq yang unique
- [x] Calculate item_id dan waktu
- [x] Build JSON snapshots
- [x] File upload ke storage
- [x] Save ke satker_pemberkasan
- [x] Preview file dengan auth check

### Storage
- [x] Disk users_berkas dikonfigurasi
- [x] Symlink/junction dibuat
- [x] Path structure: storage/app/users_berkas/{nomor_induk}/{filename}

### JSON Handling
- [x] Handle single-encoded JSON
- [x] Handle double-encoded JSON
- [x] Handle null values

---

## TODO / Pending

- [ ] Tampilkan pengajuan TPG di /pengajuan-saya (belum terhubung)
- [ ] Fitur edit/update pengajuan TPG
- [ ] Admin panel untuk verifikasi pengajuan TPG
- [ ] Migration command untuk setup database (jika fresh install)

---

## Referensi

- [Laravel Filesystem](https://laravel.com/docs/10.x/filesystem)
- [JSON Schema](https://json-schema.org/)
- Tema NEO MIRAI: `resources/css/neo-mirai-home.css`

---

## Changelog

### 2026-07-08
- Implementasi lengkap service 1037 (Pemberkasan TPG)
- JSON Snapshot dengan files, metadata, requirements_snapshot
- Storage approach dengan symlink
- Preview file dengan auth check
- Detecting existing submission
- Clickable file preview di form

### 2026-07-05
- Migrate files command
- File compression dengan pdf-lib

### 2026-06-25
- Awal pengembangan service 1037
- Modal selection di pelayanan.blade.php

---

## 2. Layanan Berikutnya

Service ID:
- [ ] Service ID:
- [ ] ...
