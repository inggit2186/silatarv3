# SILATAR V2 - Project Context

## Overview

SILATAR V2 adalah portal layanan online untuk **Kantor Kementerian Agama Tanah Datar** (KEMENAG-TD), Indonesia. Aplikasi ini memungkinkan warga negara mengajukan layanan administrasi pemerintahan secara digital.

## Tech Stack

| Component | Technology |
|-----------|------------|
| Backend | Laravel 13.7 (PHP 8.3+) |
| Frontend | Tailwind CSS 4 + Alpine.js + Vite |
| Database | MySQL/MariaDB (database: `kemenagtd_db`) |
| PDF | barryvdh/laravel-dompdf |
| Auth | Session-based (Laravel Breeze pattern) |

## Project Structure

```
silatarV2/
├── app/Http/Controllers/
│   ├── AuthController.php      # Login, logout, register
│   ├── PageController.php      # Public pages (home, pelayanan, laporan-kinerja, etc.)
│   └── Admin/                  # Admin panel controllers
├── resources/views/
│   ├── admin/                   # Admin panel views
│   ├── auth/                    # Login/register views
│   └── ...                      # Public views
├── routes/
│   ├── web.php                  # Public routes
│   └── admin.php               # Admin routes
└── database/
    └── kemenagt_db.sql          # Database dump (legacy tables)
```

## Database Schema

### Legacy Tables (from kemenagt_db.sql)
- `users` - User accounts
- `ktd_department` - Unit kerja (KUA, MIN, MTSN, MAN, dll)
- `ktd_layanan` - Layanan/pelayanan pemerintah
- `ktd_syarat` - Persyaratan layanan
- `users_request` - Pengajuan layanan dari warga
- `users_request_answers` - Jawaban persyaratan
- `users_berkas` - File lampiran
- `activities` - Log aktivitas

### User Roles
```
superadmin, admin, frontdesk, kasubbag, kepala, kasi, petugas, pegawai, other, pensiun, pindah
```

### Request Statuses
```
DRAFT, UNCHECK, PENDING, DITERIMA, DIPROSES, SUKSES, DITOLAK, BATAL
```

## Key Routes

### Public Routes
| Route | Controller@Method | Description |
|-------|-------------------|-------------|
| `/` | PageController@home | Homepage |
| `/pelayanan` | PageController@pelayanan | Katalog layanan |
| `/pelayanan/ajukan/{id}` | PageController@requestService | Form ajukan layanan |
| `/pengajuan-saya` | PageController@myRequests | Tracking pengajuan |
| `/laporan-kinerja` | PageController@laporanKinerja | Input kinerja |
| `/satuan-kerja` | PageController@satuanKerja | Daftar unit kerja |
| `/login` | AuthController@showLogin | Halaman login |

### Admin Routes (prefix: /admin)
| Route | Controller@Method | Description |
|-------|-------------------|-------------|
| `/admin/dashboard` | DashboardController@index | Dashboard admin |
| `/admin/users` | UserController@index | Manajemen user |
| `/admin/users/{id}` | UserController@edit | Edit user |
| `/admin/services` | (placeholder) | Manajemen layanan |
| `/admin/units` | (placeholder) | Manajemen unit |
| `/admin/requests` | (placeholder) | Manajemen pengajuan |

## Important Patterns

### Database Access
Most database access is done via `DB::table()` facade instead of Eloquent models:
```php
DB::table('users_request')
    ->leftJoin('ktd_layanan', 'layanan.id', '=', 'ur.layanan_id')
    ->select([...])
    ->paginate(12);
```

### Admin Middleware
All admin routes are protected by `admin` middleware (defined in `AdminAccess.php`):
```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes here
});
```

### Theme System
CSS uses CSS custom properties for theming:
```css
:root { --color-primary: #0891b2; }
.dark { --color-primary: #06b6d4; }
```

## Naming Conventions

- **Controllers**: PascalCase, suffixed with `Controller`
- **Views**: snake_case.blade.php
- **Routes**: kebab-case
- **Database tables**: snake_case (legacy) + ktd_ prefix for department tables

## Important Files

| File | Purpose |
|------|---------|
| `app/Http/Controllers/PageController.php` | Main controller (65KB+ - contains most logic) |
| `app/Http/Middleware/AdminAccess.php` | Admin access control |
| `resources/views/admin/layouts/app.blade.php` | Admin layout |
| `resources/css/admin.css` | Admin styles |
| `database/kemenagtd_db.sql` | Database schema & data |

## Development Notes

### Admin Panel Status
The admin panel is in active development. Sprint 1 (Dashboard & User Management) is complete. Sprint 2 (Service, Unit, Request Management) is in progress.

### Code Style
- Indonesian language is used throughout the codebase (variable names, comments, UI text)
- Use Laravel Pint for formatting: `composer pint`

### Testing
Tests are in `tests/` directory. Run with: `php artisan test`

## Team Workflow

1. **Branching**: `feature/<module-name>` or `bugfix/<issue-name>`
2. **Commits**: `[type] description (#issue)`
3. **PRs**: Create PR to `main` branch
4. **Reviews**: Required before merge

## Resources

- [PROJECT_ROADMAP.md](PROJECT_ROADMAP.md) - Full roadmap dengan timeline
- [ROADMAP_ADMIN.md](ROADMAP_ADMIN.md) - Detail admin panel development
- [docs/THEME_SYSTEM.md](docs/THEME_SYSTEM.md) - CSS theme documentation
- [PELAYANAN_EDIT_PROGRESS.md](PELAYANAN_EDIT_PROGRESS.md) - Progress edit layanan

## Progress Tracking

Setiap memulai feature/fitur baru, buat file progress dengan nama `{FEATURE}_PROGRESS.md` untuk mendokumentasikan perkembangan.

### Format Progress File

```markdown
# Progress [Nama Feature]

## Overview
Deskripsi singkat feature.

## Status: [DALAM PROGRES | SELESAI | Tunda]

## Checklist
- [ ] Task 1
- [ ] Task 2
- [x] Task 3

## Data Flow
```
Input -> Process -> Output
```

## Files yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| path/to/file.php | Deskripsi |

## Files Baru
| File | Purpose |
|------|---------|
| path/to/file.php | Deskripsi |

## TODO
- [ ] Task berikutnya

## Changelog
### YYYY-MM-DD
- Deskripsi perubahan
```

### Aturan Progress
1. Buat file progress SAAT MEMULAI feature baru
2. Update checklist setiap selesai task
3. Tulis changelog setiap selesai sesi
4. Jika feature selesai, pindahkan status ke SELESAI
5. Dokumentasikan semua files yang dimodifikasi/ditambahkan
6. Sertakan code snippets untuk logic penting


## UI/UX Styling Guidelines

### Tema: NEO MIRAI
Aplikasi menggunakan tema **NEO MIRAI** - desain modern, clean, dengan fokus pada keterbacaan dan konsistensi visual.

### Prioritas Styling

1. **Gunakan class CSS yang sudah ada** - Cek file CSS/theme yang tersedia sebelum membuat style baru
2. **Hindari inline CSS** - Kecuali untuk value dinamis dari server-side
3. **Komponen yang sudah tersedia:**
   - `.neo-card`, `.neo-btn`, `.neo-grid`, `.neo-empty`, `.neo-modal-*`
   - `.neo-upload-*`, `.neo-field-*`, `.neo-alert-*`
   - `.neo-badge`, `.neo-modal`, `.neo-form-*`

### File CSS Utama
```
resources/css/
├── neo-mirai-home.css    # Homepage styling
├── app.css              # Base Tailwind styles
└── (cek folder css/ untuk komponen spesifik)
```

### Checklist Styling Baru
- [ ] Cek file CSS yang ada untuk komponen yang diperlukan
- [ ] Gunakan class yang sudah ada jika memungkinkan
- [ ] Jika inline CSS diperlukan, catat di comments
- [ ] Jangan ulangi styling pattern yang sudah ada di tempat lain

### Contoh Pattern
```blade
<!-- ✅ Gunakan class yang ada -->
<div class="neo-card">

<!-- ✅ Inline CSS untuk value dinamis -->
<div style="background: {{ $color }}">

<!-- ❌ Hindari jika bisa gunakan class -->
<div style="display: flex; gap: 1rem; padding: 1rem; border-radius: 0.5rem;">
```

### Referensi Tema
- Desain sistem menggunakan CSS custom properties (variables)
- Palette warna ada di `:root` CSS
