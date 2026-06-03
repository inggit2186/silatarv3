# SILATAR V2 - Project Context

## Overview

SILATAR V2 adalah portal layanan online untuk **Kementerian Agama Tanjung Dinang** (KEMENAG-TD), Indonesia. Aplikasi ini memungkinkan warga negara mengajukan layanan administrasi pemerintahan secara digital.

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
