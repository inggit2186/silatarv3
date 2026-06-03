# SILATAR V2 - Project Roadmap & Documentation

**Versi:** 2.0  
**Dibuat:** 2026-06-03  
**Project:** Sistem Layanan Online Kementerian Agama Tanjung Dinang  
**Tech Stack:** Laravel 13.7 + Tailwind CSS 4 + Alpine.js

---

## 📋 Ringkasan Project

SILATAR V2 adalah portal layanan pemerintahan online untuk Kementerian Agama Tanjung Dinang yang memungkinkan warga negara mengajukan berbagai layanan administrasi secara digital.

### Statistik Project
| Aspek | Detail |
|-------|--------|
| Total Routes | 25+ routes publik, 15+ routes admin |
| Controllers | 4 controllers utama |
| Database Tables | 12+ tabel legacy (MySQL) |
| Views | 40+ Blade templates |
| Status | Active Development |

---

## ✅ Fitur yang Sudah Ada

### 1. Frontend - Public Portal

#### Halaman Utama
- [x] **Homepage** (`/`) - Landing page dengan informasi layanan
- [x] **Pelayanan** (`/pelayanan`) - Katalog layanan pemerintah
- [x] **Satuan Kerja** (`/satuan-kerja`) - Daftar unit kerja (KUA, MIN, MTSN, MAN)
- [x] **Detail Unit Kerja** (`/satuan-kerja/{id}`) - Informasi detail unit

#### Fitur User
- [x] **Login/Register** (`/login`, `/register`) - Autentikasi pengguna
- [x] **Ajukan Layanan** (`/pelayanan/ajukan/{id}`) - Form pengajuan layanan
- [x] **Pengajuan Saya** (`/pengajuan-saya`) - Tracking status pengajuan
- [x] **Edit Pengajuan** (`/pengajuan-saya/{id}/edit`) - Edit pengajuan yang belum diproses
- [x] **Preview File** - Preview dokumen yang diupload
- [x] **Laporan Kinerja** (`/laporan-kinerja`) - Input kinerja harian & bulanan
  - [x] Tab Harian
  - [x] Tab Bulanan
  - [x] Tab Hummas/PIC
- [x] **Rekap Laporan** (`/laporan-kinerja/rekap`) - View rekapitulasi

### 2. Backend - Admin Panel

#### Dashboard Admin
- [x] **Dashboard Overview** (`/admin/dashboard`)
  - [x] Statistik total pengguna
  - [x] Statistik pengajuan layanan
  - [x] Distribusi status pengajuan
  - [x] Quick actions
  - [x] Recent activities log

#### User Management
- [x] **List Users** (`/admin/users`) - Tabel dengan search, filter, pagination
- [x] **Create User** (`/admin/users/create`) - Form tambah user
- [x] **Edit User** (`/admin/users/{id}`) - Form edit user
- [x] **Delete User** - Konfirmasi modal dengan proteksi
- [x] **Toggle Status** - Aktif/nonaktifkan user langsung
- [x] **User Profile** (`/admin/profile`) - Halaman profil admin

#### Placeholder Modules
- [x] **Services Management** (`/admin/services/*`) - Placeholder views
- [x] **Units Management** (`/admin/units/*`) - Placeholder views
- [x] **Requests Management** (`/admin/requests/*`) - Placeholder views
- [x] **Reports** (`/admin/reports`) - Placeholder view

### 3. Sistem & Infrastructure

#### Authentication & Authorization
- [x] Login/Logout system
- [x] Session-based authentication
- [x] Role-based access control (RBAC)
- [x] Admin middleware protection
- [x] Multiple roles: superadmin, admin, frontdesk, kasubbag, kepala, kasi, petugas, pegawai, other, pensiun, pindah

#### Database & Storage
- [x] MySQL database integration
- [x] File storage (public disk)
- [x] Session management
- [x] Cache support

#### Frontend Stack
- [x] Tailwind CSS 4.0 dengan Vite
- [x] Alpine.js untuk interactivity
- [x] Theme system (light/dark mode)
- [x] WCAG AA compliant colors
- [x] Responsive design
- [x] Custom admin CSS

#### PDF Generation
- [x] Laravel Dompdf integration

---

## 🎯 Roadmap Pengembangan

### Fase 1: Core Admin Modules (Priority: HIGH)

#### Sprint 1.1: Service Management
**Target:** 2-3 minggu  
**Assigned to:** Developer A

| Task | Estimasi | Priority |
|------|----------|----------|
| Service Model & Repository | 3 days | P0 |
| ServiceController CRUD | 4 days | P0 |
| Service List View (filter, pagination) | 2 days | P0 |
| Service Create/Edit Form | 3 days | P1 |
| Requirements Management (ktd_syarat) | 3 days | P1 |
| Unit-specific Services | 2 days | P2 |

#### Sprint 1.2: Unit Management
**Target:** 2 minggu  
**Assigned to:** Developer B

| Task | Estimasi | Priority |
|------|----------|----------|
| Department Model & Repository | 2 days | P0 |
| UnitController CRUD | 3 days | P0 |
| Unit List View with hierarchy | 2 days | P0 |
| Unit Create/Edit Form | 3 days | P1 |
| Unit assignment to services | 2 days | P1 |

#### Sprint 1.3: Request Management
**Target:** 3-4 minggu  
**Assigned to:** Developer A

| Task | Estimasi | Priority |
|------|----------|----------|
| RequestController CRUD | 4 days | P0 |
| Request List View with filters | 3 days | P0 |
| Request Detail View | 3 days | P0 |
| Approval Workflow | 5 days | P1 |
| Status transitions | 3 days | P1 |
| File upload handling | 2 days | P1 |
| Notification system | 3 days | P2 |

### Fase 2: Advanced Features (Priority: MEDIUM)

#### Sprint 2.1: Reports & Analytics
**Target:** 2-3 minggu  
**Assigned to:** Developer B

| Task | Estimasi | Priority |
|------|----------|----------|
| Dashboard charts (Chart.js/Recharts) | 3 days | P0 |
| Monthly statistics report | 3 days | P0 |
| Service popularity report | 2 days | P1 |
| Export to PDF/Excel | 3 days | P1 |
| Date range filter | 2 days | P1 |

#### Sprint 2.2: User Management Enhancement
**Target:** 1-2 minggu  
**Assigned to:** Developer C

| Task | Estimasi | Priority |
|------|----------|----------|
| Bulk user import (CSV) | 3 days | P1 |
| User role assignment | 2 days | P0 |
| Password reset functionality | 2 days | P1 |
| User activity log | 3 days | P2 |
| User department assignment | 2 days | P1 |

#### Sprint 2.3: Document Management
**Target:** 2 minggu  
**Assigned to:** Developer A

| Task | Estimasi | Priority |
|------|----------|----------|
| Document templates | 3 days | P1 |
| Auto-generate document | 3 days | P1 |
| Document preview (PDF viewer) | 2 days | P1 |
| Document versioning | 2 days | P2 |
| Digital signature | 3 days | P2 |

### Fase 3: UX/UI Enhancements (Priority: MEDIUM)

#### Sprint 3.1: Public Portal
**Target:** 2 minggu  
**Assigned to:** Developer B

| Task | Estimasi | Priority |
|------|----------|----------|
| Service search & filter | 3 days | P1 |
| Request tracking improvement | 3 days | P0 |
| Mobile-responsive design | 4 days | P1 |
| Toast notifications | 2 days | P2 |

#### Sprint 3.2: Admin UI Enhancement
**Target:** 2 minggu  
**Assigned to:** Developer C

| Task | Estimasi | Priority |
|------|----------|----------|
| Advanced data tables (Livewire) | 4 days | P1 |
| Drag-drop file upload | 2 days | P1 |
| Keyboard shortcuts | 2 days | P2 |
| Dark mode toggle | 2 days | P2 |

### Fase 4: Integration & Deployment (Priority: LOW)

#### Sprint 4.1: System Integration
**Target:** 3-4 minggu

| Task | Estimasi | Priority |
|------|----------|----------|
| Email notifications | 3 days | P2 |
| SMS notifications (Twilio) | 3 days | P2 |
| WhatsApp integration | 5 days | P2 |
| API endpoints (REST) | 5 days | P2 |

#### Sprint 4.2: Deployment & Monitoring
**Target:** 1-2 minggu

| Task | Estimasi | Priority |
|------|----------|----------|
| CI/CD pipeline | 3 days | P1 |
| Log management | 2 days | P2 |
| Performance monitoring | 2 days | P2 |
| Backup system | 2 days | P1 |

---

## 📁 Struktur Folder yang Direkomendasikan

```
silatarV2/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── PageController.php
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       ├── UserController.php
│   │   │       ├── ServiceController.php    ← BARU
│   │   │       ├── DepartmentController.php  ← BARU
│   │   │       ├── RequestController.php     ← BARU
│   │   │       └── ReportController.php      ← BARU
│   │   └── Middleware/
│   │       └── AdminAccess.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Department.php                   ← BARU
│   │   ├── Service.php                      ← BARU
│   │   ├── ServiceRequirement.php           ← BARU
│   │   ├── ServiceRequest.php               ← BARU
│   │   └── ActivityLog.php                  ← BARU
│   ├── Services/                            ← BARU: Business logic
│   │   ├── UserService.php
│   │   ├── ServiceService.php
│   │   └── RequestService.php
│   └── Http/Requests/                       ← BARU: Form validation
│       ├── StoreUserRequest.php
│       ├── UpdateUserRequest.php
│       ├── StoreServiceRequest.php
│       └── StoreDepartmentRequest.php
├── config/
│   └── silatar.php                          ← BARU: Custom config
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── docs/
│   ├── ROADMAP_ADMIN.md
│   ├── ARCHITECTURE.md                      ← BARU
│   ├── API_DOCUMENTATION.md                 ← BARU
│   └── CONTRIBUTION_GUIDE.md                ← BARU
├── resources/
│   ├── css/
│   ├── js/
│   │   └── admin.js                         ← BARU
│   └── views/
│       ├── layouts/
│       ├── admin/
│       │   ├── components/
│       │   ├── layouts/
│       │   ├── dashboard.blade.php
│       │   ├── users/
│       │   ├── services/                    ← BARU
│       │   ├── departments/                 ← BARU
│       │   ├── requests/                   ← BARU
│       │   └── reports/                    ← BARU
│       └── ...
├── routes/
│   ├── web.php
│   ├── admin.php
│   └── api.php                              ← BARU
├── tests/
│   ├── Feature/
│   │   ├── AuthTest.php
│   │   ├── UserManagementTest.php
│   │   ├── ServiceManagementTest.php
│   │   └── RequestManagementTest.php
│   └── Unit/
│       ├── UserServiceTest.php
│       └── ServiceServiceTest.php
└── .env.example
```

---

## 👥 Panduan Kolaborasi Tim

### Git Workflow

```
1. Branch Naming Convention:
   - feature/admin-services
   - feature/unit-management
   - bugfix/user-login-issue
   - hotfix/production-error

2. Commit Message Format:
   [TYPE] Short description (#ISSUE)

   Types:
   - feat: New feature
   - fix: Bug fix
   - docs: Documentation
   - style: Code style (formatting)
   - refactor: Code refactoring
   - test: Testing
   - chore: Maintenance

   Example:
   [feat] Add service CRUD operations (#15)
   [fix] Fix user login validation (#22)

3. Pull Request Checklist:
   - Unit tests passing
   - Code follows style guide
   - Documentation updated
   - No conflicts with main branch
```

### Code Review Guidelines

| Area | Checklist |
|------|-----------|
| Security | Input validation, SQL injection prevention, XSS protection |
| Performance | Query optimization, lazy loading, caching |
| Maintainability | Clear naming, comments for complex logic, DRY principle |
| Testing | New features have unit tests |
| Documentation | Comments for public methods, README updates |

### Communication Protocol

| Channel | Use Case |
|---------|----------|
| Daily Standup | Progress update, blockers |
| Issue Tracker | Bug reports, feature requests |
| PR Reviews | Code review, feedback |
| Documentation | Architecture decisions, guides |

---

## 📊 Task Distribution Template

| Sprint | Module | Developer | Status | Due Date |
|--------|--------|-----------|--------|----------|
| 1.1 | Service Management | Developer A | Todo | TBD |
| 1.2 | Unit Management | Developer B | Todo | TBD |
| 1.3 | Request Management | Developer A | Todo | TBD |
| 2.1 | Reports & Analytics | Developer B | Todo | TBD |
| 2.2 | User Enhancement | Developer C | Todo | TBD |

---

## 🔧 Development Setup

### Requirements
- PHP 8.3+
- Composer 2.x
- Node.js 20+
- MySQL 8.0+ / MariaDB 10.4+
- Git

### Setup Commands
```bash
# Clone repository
git clone <repo-url>
cd silatarV2

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Configure database in .env
# DB_DATABASE=kemenagtd_db
# DB_USERNAME=root
# DB_PASSWORD=

# Generate app key
php artisan key:generate

# Run migrations (if needed)
php artisan migrate

# Seed database (if needed)
php artisan db:seed

# Start development server
php artisan serve
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

---

## 📝 Standards & Conventions

### PHP/Laravel
- PSR-12 coding standard
- Laravel naming conventions
- Use Eloquent ORM over raw queries
- Form Request classes for validation
- Service classes for business logic

### Frontend
- Tailwind CSS utility classes
- BEM naming for custom CSS
- Alpine.js for simple interactions
- Livewire for complex components

### Database
- Use migrations for schema changes
- No direct schema modification
- Index frequently queried columns
- Use foreign keys for relations

---

## 📅 Milestone Timeline

| Milestone | Target | Features |
|-----------|--------|----------|
| M1.0 - Core Complete | Week 4 | Service & Unit CRUD, basic Request management |
| M2.0 - Full Admin | Week 8 | All admin modules, reports, user management |
| M3.0 - Public Enhancement | Week 12 | Improved UX, notifications, mobile support |
| M4.0 - Production Ready | Week 16 | Integration, deployment, monitoring |

---

## 📞 Kontak & Resources

- **Project Lead:** [Nama Lead]
- **Tech Lead:** [Nama Tech Lead]
- **Documentation:** `docs/` folder
- **Issue Tracker:** GitHub Issues
- **Wiki:** GitHub Wiki

---

**Last Updated:** 2026-06-03  
**Maintainer:** SILATAR Development Team