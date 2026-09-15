# PPID Route Fix - RouteNotFoundException

## Issue
Ketika mengakses `/admin/ppid`, muncul error:
```
Symfony\Component\Routing\Exception\RouteNotFoundException
Route [ppid.index] not defined.
```

## Root Cause
Route untuk halaman index PPID di `routes/web.php` didefinisikan sebagai:
```php
Route::get('/ppid', [PpidController::class, 'index'])->name('ppid');
```

Bukan `ppid.index`, tapi hanya `ppid`.

Sedangkan admin view menggenerate route dengan:
```php
route('ppid.' . $page->slug)  // → route('ppid.index') untuk slug='index'
```

## Solution

### 1. Fix Route Generation in Admin Views

**File:** `resources/views/admin/ppid/index.blade.php`
```php
@php
    $publicRoute = $page->slug === 'index' ? 'ppid' : 'ppid.' . $page->slug;
@endphp
<a href="{{ route($publicRoute) }}" target="_blank">
```

**File:** `resources/views/admin/ppid/edit.blade.php`
```php
@php
    $publicRoute = $page->slug === 'index' ? 'ppid' : 'ppid.' . $page->slug;
@endphp
<a href="{{ route($publicRoute) }}" target="_blank">
```

Applied to:
- Line 150-155 (edit view - URL Publik info)
- Line 194-201 (edit view - Quick Actions)

### 2. Add PPID Menu to Admin Panel

**File:** `resources/views/admin/layouts/app.blade.php`

Added PPID menu item in the "Kelola" section:
```php
@if($isAdmin)
<a href="{{ route('admin.ppid.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.ppid.*') ? 'active' : '' }}">
    <div class="sidebar-nav-icon-wrap orange">
        <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </div>
    <span>PPID</span>
</a>
@endif
```

Updated menu group check:
```php
<div class="menu-group {{ request()->routeIs('admin.users.*', 'admin.services.*', 'admin.units.*', 'admin.requests.*', 'admin.tpg.*', 'admin.reports.*', 'admin.madrasah.laporan.*', 'admin.import-asn.*', 'admin.ppid.*') ? 'has-active' : '' }}">
```

## Menu Structure (Updated)

```
Kelola (admin menu group)
├── Pengguna
├── Import ASN (admin only)
├── Layanan
├── Unit Kerja (admin only)
├── Pengajuan
├── Surat Manual (dept 4 only)
├── Verif TPG
├── Laporan
├── Laporan Madrasah (admin or dept 7)
├── Laporan CKH
├── Rekap Presensi (admin or dept 4)
└── PPID (admin only) ← NEW
```

## Testing

### Clear Cache
```bash
php artisan route:clear
```

### Test Admin Panel
1. Login ke `/admin`
2. Lihat sidebar → Menu "PPID" muncul di bagian "Kelola"
3. Klik "PPID" → Navigate ke `/admin/ppid`
4. Lihat daftar 22 halaman PPID
5. Klik "Edit" pada salah satu halaman
6. Lihat link "Lihat di Website" di sidebar kanan → Link benar

### Test Public Routes
```bash
php artisan route:list --name=ppid | grep index
```
Harusnya tidak ada route `ppid.index` - hanya `ppid` untuk halaman beranda.

## Files Modified

| File | Changes |
|------|---------|
| `resources/views/admin/ppid/index.blade.php` | Fixed route generation for public link |
| `resources/views/admin/ppid/edit.blade.php` | Fixed route generation for public link (2 places) |
| `resources/views/admin/layouts/app.blade.php` | Added PPID menu item and route check |

## Summary

✅ Fixed RouteNotFoundException for `ppid.index` route
✅ Added PPID menu item to admin sidebar (accessible by admin/superadmin/kepala)
✅ Route detection works correctly for active menu highlighting
✅ All links in admin panel now generate correct public URLs
