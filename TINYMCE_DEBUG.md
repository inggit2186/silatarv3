# TinyMCE Debug & Troubleshooting

## Issue: Editor Not Showing

### Root Causes

**1. JavaScript not loading:**
- File path incorrect
- File permissions issue
- CDN blocked (but we're self-hosted, so this shouldn't happen)

**2. Initialization errors:**
- DOM not ready
- Conflicting scripts
- Missing dependencies

**3. CSS issues:**
- Editor hidden by CSS
- Container not visible

---

## Debug Steps

### Step 1: Verify File Exists
```bash
ls -lh public/js/tinymce/tinymce.min.js
# Should show ~476KB file
```

**Result:** ✅ File exists (476KB)

### Step 2: Check File Access
Open browser console and check:
```
http://localhost:8000/js/tinymce/tinymce.min.js
```
Should load without 404 error

### Step 3: Check JavaScript Console
Open browser DevTools (F12) → Console tab

Look for:
- ✅ No errors = Good
- ❌ "TinyMCE not loaded" = File not accessible
- ❌ "Cannot read property" = Initialization error

---

## Fixes Applied

### Fix 1: Textarea Display

**Before:**
```html
<textarea id="{{ $id }}" style="display:none;">{!! $content !!}</textarea>
```

**After:**
```html
<textarea id="{{ $id }}" class="form-input" style="min-height: {{ $height }}px;">{!! $content !!}</textarea>
```

**Why:** TinyMCE needs textarea visible in DOM to initialize properly

### Fix 2: Script Loading Order

**Added check:**
```javascript
if (typeof tinymce === 'undefined') {
    console.error('TinyMCE not loaded');
    return;
}
```

**Why:** Prevents initialization errors if script fails to load

### Fix 3: CSS Content Styles

**Added:**
```html
<link rel="stylesheet" href="{{ asset('js/tinymce/skins/content/default/content.min.css') }}">
```

**Why:** Ensures editor styling loads properly

---

## Manual Test

### Create Simple Test Page

Create file: `public/test-tinymce.html`

```html
<!DOCTYPE html>
<html>
<head>
    <title>TinyMCE Test</title>
    <script src="/js/tinymce/tinymce.min.js"></script>
</head>
<body>
    <h1>TinyMCE Test</h1>
    <textarea id="test-editor" style="width:100%;height:300px;">
        <p>Hello World!</p>
    </textarea>

    <script>
        tinymce.init({
            selector: '#test-editor',
            height: 300,
            plugins: 'lists link image table code help wordcount',
            toolbar: 'undo redo | blocks | bold italic | bullist numlist | link image | help'
        });
    </script>
</body>
</html>
```

**Test URL:** `http://localhost:8000/test-tinymce.html`

**Expected:** Editor should load and be editable

---

## Common Issues & Solutions

### Issue 1: "Failed to load plugin resource"

**Cause:** Missing plugin files
**Solution:**
```bash
ls public/js/tinymce/plugins/
# Should list: lists, link, image, table, code, help, wordcount, etc.
```

### Issue 2: "editor.ui is not defined"

**Cause:** Skin files missing
**Solution:**
```bash
ls public/js/tinymce/skins/
# Should have: ui/, content/
```

### Issue 3: "Cannot read property 'addButton'"

**Cause:** Script loaded multiple times
**Solution:**
- Check if TinyMCE script included multiple times
- Remove duplicate script tags

### Issue 4: Editor loads but no toolbar

**Cause:** CSS issue
**Solution:**
```css
.tox-tinymce {
    border-radius: 0.5rem !important;
    border: 1px solid var(--border) !important;
}
```

---

## Verify Setup

### Check These Files Exist:
```
public/js/tinymce/
├── tinymce.min.js ✅
├── plugins/
│   ├── lists/
│   ├── link/
│   ├── image/
│   ├── table/
│   ├── code/
│   ├── help/
│   └── wordcount/
├── skins/
│   ├── ui/
│   │   └── oxide/
│   └── content/
│       └── default/
├── themes/
│   └── silver/
└── icons/
    └── default/
```

### Check File Sizes:
```
tinymce.min.js: ~476KB
plugins/: ~500KB
skins/: ~350KB
themes/: ~200KB
icons/: ~100KB
Total: ~1.6MB
```

---

## Browser Console Test

Open browser DevTools (F12) → Console

**Run these checks:**
```javascript
// Check 1: TinyMCE loaded?
console.log('TinyMCE loaded:', typeof tinymce !== 'undefined');

// Check 2: Version
console.log('Version:', tinymce.majorVersion + '.' + tinymce.minorVersion);

// Check 3: Editor initialized?
console.log('Editor count:', tinymce.editors.length);
```

**Expected Output:**
```
TinyMCE loaded: true
Version: 6.x.x
Editor count: 1
```

---

## Server-Side Check

### Verify Route Exists:
```bash
php artisan route:list | grep "admin.ppid.upload-image"
```

Should show: `POST admin/ppid/upload-image`

### Verify Controller Method:
```bash
php artisan tinker
>>> app(App\Http\Controllers\Admin\PpidController::class)->uploadImage(request());
```

---

## Quick Fix Checklist

If editor not showing, check:

- [ ] `public/js/tinymce/tinymce.min.js` exists
- [ ] No 404 errors in browser Network tab
- [ ] No JavaScript errors in browser Console
- [ ] Textarea element exists in DOM
- [ ] No duplicate TinyMCE script tags
- [ ] CSS files loading (check Network tab)
- [ ] Plugin files exist in `public/js/tinymce/plugins/`

---

## Status

✅ **Fixes Applied:**
- Textarea now visible in DOM
- Added script load verification
- Added content CSS link
- Improved error handling

**Next Steps:**
1. Clear browser cache (Ctrl+Shift+R)
2. Clear Laravel view cache
3. Test editor again
4. Check browser console for errors
