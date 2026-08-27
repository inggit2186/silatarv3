# Plan: Perindah & Simplifikasi Sidebar Admin

## Status: DRAFT

---

## Analisis Masalah

**Saat ini: 13 menu item tanpa grouping**
- Dashboard
- Pengguna
- Layanan
- Unit Kerja
- Pengajuan
- Verif TPG
- Laporan
- Laporan Madrasah
- Berita
- Janji Temu
- Acara
- Ubah Password
- Lihat Website

**Masalah utama:**
1. Terlalu banyak item flat (tidak ada hierarki)
2. Item sejenis tidak dikelompokkan
3. Visual terlihat ramai dan overwhelming
4. Membingungkan untuk user baru

---

## Solution: Collapsible Menu Groups + Visual Hierarchy

### Concept

**Ubah menu flat menjadi collapsible groups dengan:**
1. Visual grouping dengan dividers dan section headers
2. Collapsible sections (click untuk expand/collapse)
3. Consolidation items sejenis
4. Clean visual design dengan icons yang konsisten

---

## Reorganisasi Menu

### Group 1: 🏠 Main (2 items)
- **Dashboard** (Admin Panel)
- **Pengajuan** (Layanan Pengajuan)

> **Mengapa:** Dua hal utama yang paling sering diakses

### Group 2: 📋 Kelola (6 items)
**Admin/Superadmin Only:**
- Pengguna (Users Management)
- Layanan (Services Management)
- Unit Kerja (Work Units)
- Verif TPG
- Laporan
- Laporan Madrasah

> **Mengapa:** Semua fitur manajemen administrasi dikomploskan

### Group 3: 📰 Publikasi (3 items)
**Semua user yang login:**
- Berita (News)
- Janji Temu (Appointments)
- Acara (Events)

> **Mengapa:** Konten publikasi dikomploskan bersama

### Group 4: ⚙️ System (2 items)
- Ubah Password
- Lihat Website

> **Mengapa:** Aksi sistem, bukan content management

---

## Implementation Plan

### Phase 1: Restructure HTML Sidebar

**File:** `resources/views/admin/layouts/app.blade.php`

#### Update Structure:
```html
<aside class="admin-sidebar" id="adminSidebar">
  <!-- Logo Section -->
  <div class="sidebar-header">
    <div class="logo-section">
      <!-- existing logo -->
    </div>
  </div>

  <!-- Navigation Groups -->
  <nav class="sidebar-nav">

    <!-- GROUP: Main -->
    <div class="menu-group">
      <div class="menu-group-header" data-toggle="main">
        <svg><!-- arrow icon --></svg>
        <span>Main</span>
      </div>
      <div class="menu-group-items" id="menu-main">
        <a href="route('admin.dashboard')" class="menu-item">
          <!-- existing content -->
        </a>
        <a href="route('admin.requests.index')" class="menu-item">
          <!-- existing content -->
        </a>
      </div>
    </div>

    <!-- GROUP: Kelola -->
    <div class="menu-group">
      <div class="menu-group-header" data-toggle="kelola">
        <svg><!-- arrow icon --></svg>
        <span>Kelola</span>
      </div>
      <div class="menu-group-items" id="menu-kelola">
        <!-- 6 admin items here -->
      </div>
    </div>

    <!-- GROUP: Publikasi -->
    <div class="menu-group">
      <div class="menu-group-header" data-toggle="publikasi">
        <svg><!-- arrow icon --></svg>
        <span>Publikasi</span>
      </div>
      <div class="menu-group-items" id="menu-publikasi">
        <!-- 3 content items here -->
      </div>
    </div>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- GROUP: System -->
    <div class="menu-group">
      <div class="menu-group-items" id="menu-system">
        <!-- 2 system items here -->
      </div>
    </div>
  </nav>

  <!-- Footer Section (unchanged) -->
  <div class="sidebar-footer">
    <!-- existing footer -->
  </div>
</aside>
```

### Phase 2: Add New CSS Styles

**File:** `resources/css/admin-new.css`

#### Menu Group Styles:
```css
/* Menu Group Container */
.menu-group {
  margin-bottom: 8px;
}

/* Menu Group Header - Clickable Section */
.menu-group-header {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  cursor: pointer;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--text-tertiary);
  border-radius: 8px;
  transition: all 0.2s ease;
  user-select: none;
}

.menu-group-header:hover {
  background: var(--secondary);
  color: var(--text-primary);
}

/* Arrow Icon */
.menu-group-header svg {
  width: 16px;
  height: 16px;
  transition: transform 0.2s ease;
}

/* Collapsed State */
.menu-group.collapsed .menu-group-header svg {
  transform: rotate(-90deg);
}

/* Menu Group Items Container */
.menu-group-items {
  overflow: hidden;
  transition: max-height 0.3s ease, opacity 0.2s ease;
  max-height: 500px;
  opacity: 1;
}

.menu-group.collapsed .menu-group-items {
  max-height: 0;
  opacity: 0;
  padding: 0;
}

/* Section Divider */
.sidebar-divider {
  border: none;
  height: 1px;
  background: var(--border);
  margin: 16px 12px;
}

/* Compact Menu Item within Group */
.menu-group-items .menu-item {
  padding: 8px 12px 8px 24px; /* indent to show hierarchy */
  font-size: 13px; /* slightly smaller */
}

/* Badge/Counter for items */
.menu-badge {
  background: var(--primary);
  color: white;
  font-size: 11px;
  padding: 2px 6px;
  border-radius: 10px;
  margin-left: auto;
}

/* Active state when group has active item */
.menu-group.has-active .menu-group-header {
  color: var(--primary-dark);
  background: var(--primary-50);
}
```

#### Responsive Improvements:
```css
@media (max-width: 768px) {
  .sidebar-nav {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }

  .menu-group-items {
    max-height: none !important; /* always expanded on mobile */
    opacity: 1 !important;
  }
}
```

### Phase 3: Add JavaScript Toggle

**File:** `resources/views/admin/layouts/app.blade.php` (in `<script>` section)

```javascript
// Menu Group Toggle
document.addEventListener('DOMContentLoaded', function() {
  const groupHeaders = document.querySelectorAll('.menu-group-header');

  groupHeaders.forEach(header => {
    header.addEventListener('click', function() {
      const group = this.closest('.menu-group');
      group.classList.toggle('collapsed');

      // Save state to localStorage
      const groupId = this.getAttribute('data-toggle');
      const isCollapsed = group.classList.contains('collapsed');
      localStorage.setItem(`sidebar-group-${groupId}`, isCollapsed);
    });
  });

  // Restore state from localStorage
  groupHeaders.forEach(header => {
    const groupId = header.getAttribute('data-toggle');
    const isCollapsed = localStorage.getItem(`sidebar-group-${groupId}`) === 'true';
    if (isCollapsed) {
      header.closest('.menu-group').classList.add('collapsed');
    }
  });

  // Auto-expand group with active item
  document.querySelectorAll('.menu-group').forEach(group => {
    if (group.querySelector('.menu-item.active')) {
      group.classList.remove('collapsed');
      group.classList.add('has-active');
    }
  });
});
```

### Phase 4: Visual Refinement

#### Icon Improvements:
- Standardize icon sizes (20px x 20px)
- Add consistent opacity for inactive state
- Use color-coding by group type:
  - Main: Primary color (cyan)
  - Kelola: Neutral gray
  - Publikasi: Blue
  - System: Gray

#### Typography:
- Group headers: 11px, uppercase, letter-spacing 0.5px
- Menu items: 14px, font-weight 500
- Subtle indent for hierarchy (24px left padding)

#### Animation:
- Smooth 200ms transition for collapse/expand
- Subtle scale transform on hover (1.02)
- Fade in/out for group items

---

## User Experience Improvements

### 1. Collapsible Groups
- Users can collapse groups they don't use often
- State persists across page loads (localStorage)
- Auto-expand group with active page

### 2. Visual Hierarchy
- Clear section headers separate concerns
- Indentation shows parent-child relationship
- Consistent color coding for different types

### 3. Reduced Cognitive Load
- 4 clear groups instead of 13 flat items
- Group names are intuitive: Main, Kelola, Publikasi, System
- Progressive disclosure - users see categories first, then drill down

### 4. Better Organization
- **Main**: Daily-use items (dashboard + submissions)
- **Kelola**: Admin management (6 items → one collapsed group)
- **Publikasi**: Content management (3 items → one collapsed group)
- **System**: Account actions (2 items → always visible at bottom)

---

## Before vs After Comparison

### Before (13 items, flat):
```
■ Dashboard
■ Pengguna
■ Layanan
■ Unit Kerja
■ Pengajuan
■ Verif TPG
■ Laporan
■ Laporan Madrasah
■ Berita
■ Janji Temu
■ Acara
■ ─────────
■ Ubah Password
■ Lihat Website
```
**Total: 13 items, no grouping, overwhelming**

### After (4 groups, collapsible):
```
▸ Main (collapsed)
  ■ Dashboard
  ■ Pengajuan

▸ Kelola (collapsed)
  ■ Pengguna
  ■ Layanan
  ■ Unit Kerja
  ■ Verif TPG
  ■ Laporan
  ■ Laporan Madrasah

▸ Publikasi (collapsed)
  ■ Berita
  ■ Janji Temu
  ■ Acara

▸ ─────────
■ Ubah Password
■ Lihat Website
```
**Total: 4 groups, progressive disclosure, clean hierarchy**

---

## Accessibility

- Keyboard navigation: Tab + Enter to toggle groups
- Screen reader: aria-expanded, aria-controls attributes
- Contrast: Minimum 4.5:1 for text
- Focus states: Visible outlines for keyboard users

---

## Testing Checklist

- [ ] Desktop responsive (1024px+)
- [ ] Tablet responsive (768px - 1024px)
- [ ] Mobile responsive (< 768px)
- [ ] Dark mode support
- [ ] Collapse/expand animation smooth
- [ ] localStorage persistence works
- [ ] Active item highlighting in collapsed group
- [ ] Keyboard accessibility
- [ ] Screen reader friendly
- [ ] Performance (no lag on toggle)

---

## Files yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `resources/views/admin/layouts/app.blade.php` | Restructure sidebar HTML into groups |
| `resources/css/admin-new.css` | Add menu group styles, animations |
| `resources/css/admin.css` | (optional) Sync updates |

---

## Implementation Timeline

### Fase 1: Structure (15 min)
- [ ] Create menu group HTML structure
- [ ] Add data-toggle attributes
- [ ] Move items into groups

### Fase 2: Styling (20 min)
- [ ] Add menu-group CSS classes
- [ ] Add collapsible animations
- [ ] Add responsive styles

### Fase 3: JavaScript (10 min)
- [ ] Add toggle click handler
- [ ] Add localStorage persistence
- [ ] Add auto-expand active group

### Fase 4: Polish (10 min)
- [ ] Test all states
- [ ] Refine animations
- [ ] Check dark mode

**Total: ~55 minutes**

---

## Expected Outcome

1. **Visual**: Clean, organized sidebar with clear sections
2. **UX**: Users find items faster through grouping
3. **Mobile**: Better scrolling experience
4. **Accessibility**: Keyboard and screen reader support
5. **Professional**: More polished, modern look

---

## Notes

- Keep existing access control (isAdmin, canAccessAdminPanel) intact
- Maintain all existing menu item functionality
- Ensure dark mode compatibility
- Test on all screen sizes before deploying

---

**Created:** 2026-08-15
**Status:** Ready for implementation
