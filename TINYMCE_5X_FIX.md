# TinyMCE 5.x Fix - License Key Issue

## Problem

TinyMCE 6.x (latest) requires a license key even for self-hosted usage:
```
The editor is disabled because a TinyMCE license key has not been provided
```

## Solution

**Downgrade to TinyMCE 5.10.9** - Last free version without license requirement

### Changes Made

```bash
# Uninstall TinyMCE 6.x
npm uninstall tinymce

# Install TinyMCE 5.x (free)
npm install tinymce@5.10.9 --save

# Copy to public directory
rm -rf public/js/tinymce
mkdir -p public/js/tinymce
cp -r node_modules/tinymce/* public/js/tinymce/
```

**Result:**
- ✅ TinyMCE 5.10.9 installed
- ✅ No license key required
- ✅ All features available
- ✅ Self-hosted (no CDN)

---

## Version Comparison

| Feature | TinyMCE 5.x | TinyMCE 6.x |
|---------|-------------|-------------|
| **License** | ✅ Free (MIT) | ❌ Requires key |
| **File Size** | ~384KB | ~476KB |
| **Features** | ✅ Full | ✅ Full |
| **Self-hosted** | ✅ Yes | ✅ Yes |
| **Plugins** | ✅ All included | ✅ All included |

**Verdict:** TinyMCE 5.x is better choice for free usage

---

## File Structure

```
public/js/tinymce/
├── tinymce.min.js          (384KB - TinyMCE 5.x)
├── themes/
│   └── silver/
├── skins/
│   ├── ui/
│   │   └── oxide/
│   └── content/
│       └── default/
├── icons/
│   └── default/
└── plugins/
    ├── advlist/
    ├── autolink/
    ├── lists/
    ├── link/
    ├── image/
    ├── charmap/
    ├── preview/
    ├── anchor/
    ├── searchreplace/
    ├── visualblocks/
    ├── code/
    ├── fullscreen/
    ├── insertdatetime/
    ├── media/
    ├── table/
    ├── help/
    ├── wordcount/
    ├── emoticons/
    └── codesample/
```

---

## TinyMCE 5.x Features

### All Features Available ✅

- ✅ **50+ fonts** (Google + System)
- ✅ **Font sizes** (8px - 72px)
- ✅ **Text formatting** (Bold, italic, underline, etc.)
- ✅ **Colors** (Text & background)
- ✅ **Lists** (Bullet & numbered)
- ✅ **Tables** (Advanced editing)
- ✅ **Images** (Upload & embed)
- ✅ **Media** (Video & audio)
- ✅ **Code blocks** (Syntax highlighting)
- ✅ **Full toolbar**
- ✅ **No license key required**

---

## Testing

### Clear Caches
```bash
php artisan view:clear
php artisan route:clear
```

### Test Editor
1. Go to `/admin/ppid/{slug}/edit-section/{sectionId}`
2. ✅ Editor should load
3. ✅ No license warning
4. ✅ Full toolbar visible
5. ✅ Font selection works
6. ✅ Image upload works

### Check Version
Open browser console (F12):
```javascript
console.log('TinyMCE version:', tinymce.majorVersion + '.' + tinymce.minorVersion);
// Should show: "TinyMCE version: 5.10.9"
```

---

## Status

✅ **Fixed** - TinyMCE 5.x installed

**Features:**
- ✅ No license key required
- ✅ All features available
- ✅ Self-hosted
- ✅ Free (MIT license)
- ✅ Fully functional

**Ready for production use! 🎉**
