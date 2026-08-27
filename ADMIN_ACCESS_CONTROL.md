# Dokumentasi Hak Akses Admin Panel

## Overview
Sistem hak akses admin panel menggunakan **2 sistem**:
1. **`users.role`** - Menentukan apakah user BISA mengakses admin panel
2. **`hak_akses` table** - Menentukan menu apa saja yang BISA diakses di dalam admin panel

---

## Sistem Hak Akses

### **Level 1: Akses ke Admin Panel (berdasarkan `users.role`)**
Didefinisikan di `resources/views/admin/layouts/app.blade.php` line 8:

```php
$canAccessAdminPanel = in_array(strtolower($userRole), [
    'admin', 'superadmin', 'petugas', 'kasi', 'kasubag', 'kasubbag', 'kepala'
]);
```

**Role yang BISA akses admin panel:**
- ✅ admin
- ✅ superadmin
- ✅ **petugas**
- ✅ kasi
- ✅ kasubag
- ✅ kasubbag
- ✅ kepala

**Role yang TIDAK BISA akses admin panel:**
- ❌ user
- ❌ pegawai
- ❌ frontdesk
- ❌ other
- ❌ pensiun
- ❌ pindah

---

### **Level 2: Menu yang Bisa Diakses (berdasarkan `hak_akses` table)**
Didefinisikan di `app/Http/Middleware/AdminAccess.php`:

```php
// Cek apakah user adalah admin atau superadmin
$isAdmin = in_array('admin', $userAccess) || in_array('superadmin', $userAccess);

// Menu yang boleh diakses semua role (termasuk humas, petugas, dll)
$allowedForAll = ['dashboard', 'news', 'profile'];

// Jika bukan admin dan akses ke menu selain yang diizinkan, tolak
if (!in_array($module, $allowedForAll) && !$isAdmin) {
    return redirect()->route('admin.dashboard')
        ->with('error', 'Anda tidak memiliki akses ke menu tersebut.');
}
```

**Menu yang BISA diakses SEMUA role** (termasuk petugas, humas, keuangan, dll):
- ✅ Dashboard (`/admin/dashboard`)
- ✅ News Management (`/admin/news`)
- ✅ Profile

**Menu yang HANYA bisa diakses admin/superadmin:**
- ❌ Users Management
- ❌ Services Management
- ❌ Units Management
- ❌ Requests Management
- ❌ TPG Management
- ❌ Reports
- ❌ Madrasah Laporan
- ❌ Janji Temu
- ❌ Acara

---

## Data di Database

### **Jumlah User per Role:**
```
superadmin: 1 users
admin: 1 users
petugas: 55 users
kasi: 5 users
kasubbag: 1 users
kepala: 1 users
frontdesk: 4 users
pegawai: 1660 users
user: 121 users
other: 2 users
pensiun: 72 users
pindah: 79 users
(empty): 35 users
```

### **Petugas dengan Hak Akses:**
Dari 55 petugas, hanya **12 yang punya record di `hak_akses`**:
- User 10: admin, keuangan, subbagtu
- User 12: admin, keuangan, subbagtu
- User 1044: keuangan, subbagtu
- User 23: keuangan, subbagtu
- User 22: keuangan, subbagtu
- User 21: keuangan, subbagtu
- User 13: keuangan, subbagtu
- User 16: keuangan, subbagtu
- User 27: keuangan, subbagtu
- User 2549: subbagtu, humas
- User 1042: p3h
- User 2020: keuangan, subbagtu

### **Petugas TANPA Hak Akses:**
**43 petugas** tidak punya record di `hak_akses` → TIDAK BISA akses admin panel

---

## Alur Autentikasi Admin

```
User Login
    ↓
Cek users.role
    ↓
┌─────────────────────────────────────────┐
│ Role = admin/superadmin/petugas/kasi/  │
│        kasubag/kasubbag/kepala?         │
├─────────────────────────────────────────┤
│ YA → Bisa akses /admin                 │
│ TIDAK → Redirect ke /pelayanan         │
└─────────────────────────────────────────┘
    ↓
Cek hak_akses table
    ↓
┌─────────────────────────────────────────┐
│ Ada record di hak_akses?               │
├─────────────────────────────────────────┤
│ YA → Decode JSON akses                 │
│ TIDAK → Redirect ke /pelayanan         │
│         "Tidak memiliki akses"         │
└─────────────────────────────────────────┘
    ↓
Cek apakah admin/superadmin
    ↓
┌─────────────────────────────────────────┐
│ admin OR superadmin di hak_akses?      │
├─────────────────────────────────────────┤
│ YA → Full akses ke semua menu          │
│ TIDAK → Hanya akses:                   │
│         - Dashboard                    │
│         - News                         │
│         - Profile                      │
└─────────────────────────────────────────┘
```

---

## Contoh Kasus

### **Kasus 1: Petugas dengan hak_akses "admin"**
- **User:** ID 10, Role: petugas
- **Hak Akses:** `["admin", "keuangan", "subbagtu"]`
- **Hasil:** ✅ Bisa akses SEMUA menu admin (karena punya "admin" di hak_akses)

### **Kasus 2: Petugas dengan hak_akses "keuangan" saja**
- **User:** ID 1044, Role: petugas
- **Hak Akses:** `["keuangan", "subbagtu"]`
- **Hasil:** ⚠️ Bisa akses admin panel, TAPI hanya menu Dashboard, News, Profile

### **Kasus 3: Petugas TANPA hak_akses**
- **User:** ID 9, Role: petugas
- **Hak Akses:** (tidak ada)
- **Hasil:** ❌ TIDAK BISA akses admin panel sama sekali

### **Kasus 4: Pegawai (role = pegawai)**
- **User:** ID 100, Role: pegawai
- **Hak Akses:** (apapun)
- **Hasil:** ❌ TIDAK BISA akses admin panel (role tidak diizinkan)

---

## Ringkasan per Role

### **Role yang BISA Akses Admin Panel:**
| Role | Bisa Akses Admin Panel | Menu yang Bisa Diakses |
|------|------------------------|------------------------|
| superadmin | ✅ | Semua menu |
| admin | ✅ | Semua menu |
| **petugas** | ✅ | Dashboard, News, Profile* |
| kasi | ✅ | Dashboard, News, Profile* |
| kasubag | ✅ | Dashboard, News, Profile* |
| kasubbag | ✅ | Dashboard, News, Profile* |
| kepala | ✅ | Dashboard, News, Profile* |

*Catatan: Petugas/kasi/dll bisa akses SEMUA menu JIKA punya "admin" di hak_akses

### **Role yang TIDAK BISA Akses Admin Panel:**
| Role | Keterangan |
|------|-----------|
| user | Role masyarakat umum |
| pegawai | Role pegawai biasa |
| frontdesk | Role frontdesk |
| other | Role lainnya |
| pensiun | Sudah pensiun |
| pindah | Sudah pindah |

---

## Menu Admin & Hak Akses

### **Menu yang Bisa Diakses Semua Role:**
| Menu | Route | Keterangan |
|------|-------|-----------|
| Dashboard | `/admin/dashboard` | ✅ Semua role |
| News | `/admin/news` | ✅ Semua role |
| Profile | `/admin/profile` | ✅ Semua role |

### **Menu yang HANYA Bisa Diakses Admin/Superadmin:**
| Menu | Route | Keterangan |
|------|-------|-----------|
| Users | `/admin/users` | ❌ Hanya admin/superadmin |
| Services | `/admin/services` | ❌ Hanya admin/superadmin |
| Units | `/admin/units` | ❌ Hanya admin/superadmin |
| Requests | `/admin/requests` | ❌ Hanya admin/superadmin |
| TPG | `/admin/tpg` | ❌ Hanya admin/superadmin |
| Reports | `/admin/reports` | ❌ Hanya admin/superadmin |
| Madrasah Laporan | `/admin/madrasah/laporan` | ❌ Hanya admin/superadmin |
| Janji Temu | `/admin/janji-temu` | ❌ Hanya admin/superadmin |
| Acara | `/admin/acara` | ❌ Hanya admin/superadmin |

---

## Implementasi di Code

### **Level 1: Layout (`admin/layouts/app.blade.php`)**
```php
// Cek role user
$userRole = auth()->user()->role ?? '';
$canAccessAdminPanel = in_array(strtolower($userRole), [
    'admin', 'superadmin', 'petugas', 'kasi', 'kasubag', 'kasubbag', 'kepala'
]);

// Menu hanya ditampilkan jika role diizinkan
@if($canAccessAdminPanel)
    <div class="menu-group" data-group="kelola">
        <a href="{{ route('admin.users.index') }}">Users</a>
        <!-- etc -->
    </div>
@endif
```

### **Level 2: Middleware (`AdminAccess.php`)**
```php
// Ambil hak_akses dari database
$hakAkses = DB::table('hak_akses')->where('user_id', $user->id)->first();
if (!$hakAkses) {
    return redirect()->route('pelayanan')
        ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
}

// Decode JSON
$userAccess = json_decode($hakAkses->akses, true);

// Cek apakah admin/superadmin
$isAdmin = in_array('admin', $userAccess) || in_array('superadmin', $userAccess);

// Menu yang boleh diakses semua role
$allowedForAll = ['dashboard', 'news', 'profile'];

// Tolak jika bukan admin dan akses ke menu terlarang
if (!in_array($module, $allowedForAll) && !$isAdmin) {
    return redirect()->route('admin.dashboard')
        ->with('error', 'Anda tidak memiliki akses ke menu tersebut.');
}
```

---

## Catatan Penting

1. **Petugas BISA akses admin panel** jika role = 'petugas'
2. **Petugas TIDAK BISA akses menu kelola** kecuali punya "admin" di hak_akses
3. **43 petugas** tidak punya hak_akses → tidak bisa akses admin panel
4. **12 petugas** punya hak_akses → bisa akses admin panel dengan menu terbatas
5. **Superadmin** selalu punya akses penuh ke semua menu
6. **User tanpa record di hak_akses** akan di-redirect ke /pelayanan

---

## File Terkait

- `app/Http/Middleware/AdminAccess.php` - Middleware untuk kontrol akses
- `resources/views/admin/layouts/app.blade.php` - Layout admin dengan menu (line 8: $canAccessAdminPanel)
- `routes/admin.php` - Route admin dengan middleware
- `database/migrations/*_create_hak_akses_table.php` - Tabel hak_akses

---

## Update Terakhir
**Tanggal:** 2026-08-15
**Oleh:** Claude Haiku 4.5
