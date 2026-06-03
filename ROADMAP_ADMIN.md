# SILATAR - Roadmap & Progress Tracker
## Fitur 1: Dashboard Admin

**Dibuat:** 2026-06-02  
**Target Selesai:** TBD  
**Developer:** Tim SILATAR  

---

## 📋 Deskripsi Fitur

Dashboard Admin adalah halaman utama untuk mengelola seluruh sistem SILATAR. Fitur ini menyediakan overview statistik, quick actions, dan navigasi ke modul-modul administrasi.

---

## 🎯 Cakupan Fitur (Scope)

### A. Halaman Dashboard Admin
- [ ] **Statistik Overview**
  - [ ] Total pengguna (pegawai)
  - [ ] Total pengajuan layanan
  - [ ] Pengajuan berdasarkan status (pending, proses, selesai, tolak)
  - [ ] Total unit kerja
  - [ ] Total layanan aktif
  - [ ] Aktivitas terbaru (log)

- [ ] **Quick Actions**
  - [ ] Tambah user baru
  - [ ] Tambah layanan baru
  - [ ] Lihat semua pengajuan
  - [ ] Export laporan

- [ ] **Chart/Visualisasi**
  - [ ] Grafik pengajuan per bulan
  - [ ] Grafik distribusi status pengajuan
  - [ ] Grafik layanan paling populer

### B. Navigasi & Layout
- [ ] **Admin Sidebar**
  - [ ] Logo SILATAR
  - [ ] Menu item (Dashboard, User, Layanan, Unit Kerja, Pengajuan, Laporan)
  - [ ] User profile dropdown
  - [ ] Collapse/expand functionality

- [ ] **Admin Header**
  - [ ] Breadcrumb
  - [ ] Search bar
  - [ ] Notification bell
  - [ ] User avatar & name

### C. Komponen UI Global
- [ ] **Cards**
  - [ ] Stat card (icon, value, label, trend)
  - [ ] Action card
  - [ ] Info card

- [ ] **Tables**
  - [ ] Data table dengan pagination
  - [ ] Sorting columns
  - [ ] Search filter
  - [ ] Bulk actions
  - [ ] Row actions (edit, delete, view)

- [ ] **Forms**
  - [ ] Input field dengan validasi
  - [ ] Select dropdown
  - [ ] File upload
  - [ ] Date picker

- [ ] **Modals**
  - [ ] Confirm dialog
  - [ ] Form modal
  - [ ] Detail modal

- [ ] **Alerts & Toasts**
  - [ ] Success alert
  - [ ] Error alert
  - [ ] Warning alert
  - [ ] Info alert

---

## 🔧 Technical Implementation

### File yang akan dibuat/dimodifikasi:

#### 1. **Layout & Components**
```
resources/views/admin/
├── layouts/
│   ├── app.blade.php          ← Admin layout utama
│   ├── sidebar.blade.php      ← Sidebar navigation
│   └── header.blade.php       ← Header dengan breadcrumb
├── components/
│   ├── stat-card.blade.php    ← Card statistik
│   ├── data-table.blade.php   ← Table dengan pagination
│   ├── action-modal.blade.php ← Modal untuk actions
│   └── toast.blade.php        ← Toast notifications
├── dashboard.blade.php        ← Halaman dashboard
├── users/
│   ├── index.blade.php       ← List users
│   ├── create.blade.php      ← Form tambah user
│   └── edit.blade.php        ← Form edit user
├── services/
│   ├── index.blade.php       ← List layanan
│   ├── create.blade.php      ← Form tambah layanan
│   └── edit.blade.php        ← Form edit layanan
├── units/
│   ├── index.blade.php       ← List unit kerja
│   ├── create.blade.php      ← Form tambah unit
│   └── edit.blade.php        ← Form edit unit
└── requests/
    ├── index.blade.php       ← List pengajuan
    └── detail.blade.php      ← Detail pengajuan
```

#### 2. **Controllers**
```
app/Http/Controllers/
├── Admin/
│   ├── DashboardController.php ← Dashboard stats
│   ├── UserController.php       ← CRUD User
│   ├── ServiceController.php    ← CRUD Layanan
│   ├── UnitController.php      ← CRUD Unit Kerja
│   └── RequestController.php   ← Manajemen Pengajuan
```

#### 3. **Models**
```
app/Models/
├── User.php                    ← Sudah ada, perlu diupdate
├── Department.php             ← Model baru (ktd_department)
├── Service.php                ← Model baru (ktd_layanan)
├── ServiceRequirement.php     ← Model baru (ktd_syarat)
├── ServiceRequest.php         ← Model baru (users_request)
└── ServiceRequestAnswer.php   ← Model baru (users_request_answers)
```

#### 4. **Middleware**
```
app/Http/Middleware/
├── AdminAccess.php            ← Cek role admin
└── SuperAdminAccess.php       ← Cek role superadmin
```

#### 5. **Routes**
```
routes/admin.php               ← File route baru untuk admin
```

#### 6. **CSS/Assets**
```
resources/css/
├── admin.css                  ← Global admin styles (BARU)
└── app.css                    ← Sudah ada, perlu ditambahkan admin styles

resources/js/
├── admin.js                   ← Admin specific JS (BARU)
└── app.js                     ← Sudah ada

public/assets/
├── img/admin/                 ← Admin images
├── icons/                     ← Admin icons (SVG)
└── vendor/                   ← Third-party plugins
```

---

## 📊 Progress Tracker

### Sprint 1: Core Dashboard (IMPLEMENTED ✅)
| Task | Status | Assigned | Notes |
|------|--------|----------|-------|
| Roadmap & Documentation | ✅ DONE | AI | File: ROADMAP_ADMIN.md |
| Global Admin CSS/Styles | ✅ DONE | AI | File: resources/css/admin.css |
| Admin Layout (Sidebar, Header) | ✅ DONE | AI | File: admin/layouts/app.blade.php |
| Dashboard Controller | ✅ DONE | AI | File: DashboardController.php |
| Dashboard View | ✅ DONE | AI | File: admin/dashboard.blade.php |
| Admin Routes & Middleware | ✅ DONE | AI | File: routes/admin.php |
| Route Protection | ✅ DONE | AI | File: AdminAccess.php middleware |

### Sprint 2: User Management (IMPLEMENTED ✅)
| Task | Status | Assigned | Notes |
|------|--------|----------|-------|
| User Model & Repository | ✅ DONE | AI | Direct DB queries via UserController |
| User CRUD Controller | ✅ DONE | AI | File: UserController.php |
| User List View | ✅ DONE | AI | File: admin/users/index.blade.php |
| User Create/Edit View | ✅ DONE | AI | Files: create.blade.php, edit.blade.php |
| User Profile Page | ✅ DONE | AI | File: admin/profile.blade.php |

### Sprint 3: Service Management (Placeholder)
| Task | Status | Assigned | Notes |
|------|--------|----------|-------|
| Service Model | 📋 TODO | - | - |
| Service CRUD Controller | 📋 TODO | - | - |
| Service List View | 📋 TODO | - | Placeholder created |
| Service Create/Edit View | 📋 TODO | - | Placeholder created |
| Requirements Management | 📋 TODO | - | - |

### Sprint 4: Unit Management (Placeholder)
| Task | Status | Assigned | Notes |
|------|--------|----------|-------|
| Unit Model | 📋 TODO | - | - |
| Unit CRUD Controller | 📋 TODO | - | - |
| Unit List View | 📋 TODO | - | Placeholder created |
| Unit Create/Edit View | 📋 TODO | - | Placeholder created |

### Sprint 5: Request Management (Placeholder)
| Task | Status | Assigned | Notes |
|------|--------|----------|-------|
| Request Controller | 📋 TODO | - | - |
| Request List View | 📋 TODO | - | Placeholder created |
| Request Detail View | 📋 TODO | - | Placeholder created |
| Approval Workflow | 📋 TODO | - | - |

---

## 🗂️ Database Schema Reference

### Tables yang digunakan:
- `users` - Data user/pegawai
- `ktd_department` - Unit kerja
- `ktd_layanan` - Layanan
- `ktd_syarat` - Persyaratan layanan
- `users_request` - Pengajuan layanan
- `users_request_answers` - Jawaban persyaratan
- `users_berkas` - File lampiran

### Important Notes:
- Role user: superadmin, admin, frontdesk, kasubbag, kepala, kasi, petugas, pegawai, other, pensiun, pindah
- Dept categories: kantor, kua, min, mtsn, man, other
- Request statuses: DRAFT, UNCHECK, PENDING, DITERIMA, DIPROSES, SUKSES, DITOLAK, BATAL

---

## 🎨 Design Guidelines

### Color Palette:
- Primary: Cyan-600 (#0891b2)
- Secondary: Slate-700 (#334155)
- Success: Emerald-500 (#10b981)
- Warning: Amber-500 (#f59e0b)
- Error: Rose-500 (#f43f5e)
- Info: Blue-500 (#3b82f6)

### Typography:
- Font: Inter (from Google Fonts)
- Headings: font-semibold, tracking-tight
- Body: text-sm, leading-relaxed

### Spacing:
- Container: max-w-7xl, mx-auto, px-6
- Cards: rounded-2xl, p-6, shadow-sm
- Buttons: rounded-full, px-4 py-2

### Icons:
- Use inline SVG with stroke-current
- Standard size: h-5 w-5 (20px)
- Color: Match parent text color

---

## 📝 Changelog

| Date | Version | Changes |
|------|---------|---------|
| 2026-06-02 | 1.0.0 | Initial roadmap created |
| 2026-06-02 | 1.1.0 | Sprint 1 completed - Full Dashboard Admin |
| 2026-06-02 | 1.2.0 | Sprint 2 completed - User Management CRUD |

---

## 📁 File Structure Created/Updated

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── DashboardController.php     ← Dashboard with statistics
│   │       └── UserController.php         ← User CRUD operations (NEW)
│   └── Middleware/
│       └── AdminAccess.php                  ← Role-based access control
├── Providers/
│   ├── AppServiceProvider.php             ← Admin route loading
│   └── ComponentServiceProvider.php         ← Admin component registration
routes/
└── admin.php                                 ← Admin routes (updated)
resources/
├── css/
│   ├── app.css                             ← Main CSS
│   └── admin.css                            ← Global admin styles
└── views/
    ├── admin/
    │   ├── layouts/
    │   │   └── app.blade.php                ← Main admin layout
    │   ├── components/
    │   │   └── stat-card.blade.php           ← Stat card component
    │   ├── dashboard.blade.php               ← Dashboard page
    │   ├── profile.blade.php                 ← User profile page
    │   ├── users/
    │   │   ├── index.blade.php              ← User list with CRUD (NEW)
    │   │   ├── create.blade.php             ← Create user form (NEW)
    │   │   └── edit.blade.php               ← Edit user form (NEW)
    │   ├── services/ (placeholder)
    │   ├── units/ (placeholder)
    │   ├── requests/ (placeholder)
    │   └── reports/ (placeholder)
    └── welcome.blade.php                    ← Homepage (existing)
```

---

## 🚀 Cara Penggunaan

### Akses Dashboard Admin
1. Login ke sistem menggunakan akun dengan role `admin` atau `superadmin`
2. Klik "Admin Panel" di menu dropdown user di header
3. Navigasi sidebar untuk mengakses modul lain

### Route Admin
- `/admin/dashboard` - Dashboard utama
- `/admin/users` - Manajemen pengguna (CRUD)
- `/admin/services` - Manajemen layanan
- `/admin/units` - Manajemen unit kerja
- `/admin/requests` - Manajemen pengajuan
- `/admin/reports` - Laporan
- `/admin/profile` - Profil user

### Middleware Protection
- Semua route admin dilindungi oleh middleware `admin`
- Hanya user dengan role `admin` atau `superadmin` yang dapat mengakses
- User lain akan diarahkan ke halaman pelayanan

---

## 🔧 Fitur User Management

### ✅ Yang Sudah Diimplementasi:
- **List User**: Tabel dengan search, filter (role, dept, status), sorting, pagination
- **Create User**: Form dengan validasi untuk tambah user baru
- **Edit User**: Form untuk update data user + password
- **Delete User**: Konfirmasi modal + proteksi hapus superadmin & self-delete
- **Toggle Status**: Aktif/Nonaktifkan user langsung dari list

### 📋 Fitur Pendukung:
- Collapsible section untuk informasi tambahan
- Breadcrumb navigation
- Toast notification untuk feedback
- CSRF token untuk AJAX requests

---

---

## 🤝 Contributing

1. Create a new branch: `git checkout -b feature/admin-dashboard`
2. Make your changes
3. Run tests: `php artisan test`
4. Commit: `git commit -m 'Add admin dashboard feature'`
5. Push: `git push origin feature/admin-dashboard`
6. Create Pull Request

---

**Last Updated:** 2026-06-02 08:45 WIB