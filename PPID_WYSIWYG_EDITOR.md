# PPID WYSIWYG Editor Implementation

## Overview

Section editing sekarang menggunakan **Quill.js WYSIWYG Editor** untuk section type `text`, sehingga user bisa editing konten tanpa perlu tahu HTML.

---

## Features

### For Text Sections
✅ **WYSIWYG Editor** - Rich text editing tanpa HTML
✅ **Toolbar Lengkap:**
- Headers (H1, H2, H3)
- Bold, Italic, Underline, Strike
- Text colors & background colors
- Text alignment
- Ordered & unordered lists
- Indent & outdent
- Links
- Images (upload & drag-drop)
- Block quotes
- Code blocks
- Clear formatting

✅ **Image Upload:**
- Drag & drop images
- Click toolbar button to upload
- Paste images from clipboard
- Auto-resize & compress

✅ **User-Friendly:**
- Visual editing (no HTML needed)
- Real-time preview
- Undo/redo support
- Keyboard shortcuts

---

## How to Use

### Editing Text Section

1. **Go to Admin Panel**
   ```
   http://localhost:8000/admin/ppid
   ```

2. **Select Page to Edit**
   - Click "Edit" on any page

3. **Find Text Section**
   - Look for sections with type "text"
   - Click "Edit" button

4. **Edit Content with WYSIWYG**
   - Use toolbar to format text
   - Add headers, bold, italic, etc.
   - Insert images by dragging or clicking toolbar
   - Add links

5. **Save Changes**
   - Click "Simpan Perubahan"
   - ✅ Content updated!

---

## Toolbar Reference

### Text Formatting
| Button | Function | Shortcut |
|--------|----------|----------|
| **H1** | Header 1 | - |
| **H2** | Header 2 | - |
| **H3** | Header 3 | - |
| **B** | Bold | Ctrl+B |
| *I* | Italic | Ctrl+I |
| <u>U</u> | Underline | Ctrl+U |
| ~~S~~ | Strikethrough | - |

### Lists & Indent
| Button | Function |
|--------|----------|
| 1. | Ordered list |
| • | Unordered list |
| ⏎ | Indent |
| ⏏ | Outdent |

### Colors & Alignment
| Button | Function |
|--------|----------|
| A (color) | Text color |
| A (background) | Background color |
| ≡ | Left align |
| ≡ | Center align |
| ≡ | Right align |
| ≡ | Justify |

### Media & Links
| Button | Function |
|--------|----------|
| 🔗 | Insert link |
| 🖼️ | Insert image |
| " | Block quote |
| <> | Code block |
| ❌ | Clear formatting |

### History
| Button | Function | Shortcut |
|--------|----------|----------|
| ↩️ | Undo | Ctrl+Z |
| ↪️ | Redo | Ctrl+Y |

---

## Image Upload

### Method 1: Drag & Drop
1. Find image on your computer
2. Drag image to editor area
3. ✅ Image uploads automatically
4. Image appears in editor

### Method 2: Toolbar Button
1. Click 🖼️ button in toolbar
2. Select image from computer
3. ✅ Image uploads
4. Image appears in editor

### Method 3: Paste from Clipboard
1. Copy image from website/screenshot
2. Press Ctrl+V in editor
3. ✅ Image pastes automatically
4. Image appears in editor

---

## Technical Details

### Editor Component
**File:** `resources/views/components/ui/quill-editor.blade.php`

**Usage:**
```blade
<x-ui.quill-editor
    :name="'content'"
    :id="'quill-editor-content'"
    :label="'Konten Section'"
    :content="old('content', $section->content ?? '')"
/>
```

**Features:**
- Snow theme (clean, modern)
- Highlight.js for code syntax
- Image upload endpoint
- Auto-sync to hidden textarea
- Responsive design

### Backend Handling
**Controller:** `app/Http/Controllers/Admin/PpidController.php`

**Method:** `updateSection()`

- Receives HTML content from Quill editor
- Saves to database `content` column
- Handles metadata JSON updates
- Clears page cache

**Database:**
```sql
ppid_sections.content LONGTEXT  -- Stores HTML from Quill editor
```

### Image Upload
**Route:** `POST /admin/news/upload-image`

**Process:**
1. Receive uploaded image
2. Resize to max 1200px width
3. Convert to WebP format
4. Save to `storage/app/public/news/content/`
5. Return image URL

---

## Example Workflows

### Example 1: Create Welcome Section

1. Add new section with:
   - Section Key: `welcome`
   - Section Type: `text`
   - Title: `Selamat Datang`

2. Edit content with WYSIWYG:
   ```
   Selamat datang di portal PPID!

   Kami menyediakan layanan informasi publik yang:
   - Transparan
   - Akuntabel
   - Mudah diakses
   ```

3. Format with toolbar:
   - Select "Selamat datang" → Click H1
   - Select list items → Click bullet list button

4. Save → ✅ Welcome section created!

---

### Example 2: Create About Section with Image

1. Add new section:
   - Section Key: `about`
   - Section Type: `text`
   - Title: `Tentang Kami`

2. Edit content:
   - Type paragraph text
   - Click 🖼️ button
   - Select image from computer
   - Image uploads and appears
   - Add more text below image

3. Save → ✅ About section with image created!

---

### Example 3: Edit Existing Text Section

1. Find section with type "text"
2. Click "Edit"
3. WYSIWYG editor opens with existing content
4. Make changes:
   - Edit text
   - Change formatting
   - Add/remove images
   - Update links
5. Save → ✅ Section updated!

---

## Comparison: Before vs After

### Before (Plain Textarea)
```html
<div class="form-group">
    <label class="form-label">Content (HTML)</label>
    <textarea name="content" class="form-input" rows="10">
        <p>Selamat datang di portal PPID</p>
        <ul>
            <li>Item 1</li>
            <li>Item 2</li>
        </ul>
    </textarea>
</div>

❌ User needs to know HTML
❌ Error-prone (missing tags)
❌ No visual preview
❌ Slow editing
```

### After (WYSIWYG Editor)
```html
<div class="form-group">
    <label class="form-label">Content</label>
    <x-ui.quill-editor
        :name="'content'"
        :id="'quill-editor-content'"
        :label="'Konten Section'"
        :content="$section->content"
    />
</div>

✅ User-friendly visual editor
✅ Real-time preview
✅ No HTML knowledge needed
✅ Fast editing with toolbar
✅ Image upload support
✅ Professional output
```

---

## Benefits

### For Non-Technical Users
✅ No need to learn HTML
✅ Visual editing like Word/Google Docs
✅ Instant preview of changes
✅ Easy image upload
✅ Professional-looking content

### For Content Managers
✅ Faster content creation
✅ Consistent formatting
✅ Easy updates
✅ No developer needed
✅ Reduced errors

### For Developers
✅ Clean HTML output
✅ Consistent markup
✅ Image handling included
✅ Reusable component
✅ Well-tested library

---

## Troubleshooting

### Issue: Editor Not Loading
**Check:**
- Quill.js CDN is accessible
- No JavaScript errors in console
- Component is properly included

**Fix:**
```bash
php artisan view:clear
```

### Issue: Images Not Uploading
**Check:**
- File size < 10MB
- File type is image (jpg, png, webp)
- Storage directory is writable
- Upload route exists

**Fix:**
```bash
chmod -R 775 storage/app/public
php artisan storage:link
```

### Issue: Content Not Saving
**Check:**
- Form is submitting
- No validation errors
- Controller method exists
- Database connection works

**Fix:**
```bash
php artisan route:clear
php artisan cache:clear
```

---

## Status

✅ **Implemented** - WYSIWYG editor now available for text sections

**Features:**
- ✅ Rich text editing
- ✅ Toolbar with all formatting options
- ✅ Image upload (drag-drop, toolbar, paste)
- ✅ Code syntax highlighting
- ✅ Undo/redo support
- ✅ Keyboard shortcuts
- ✅ Responsive design
- ✅ Clean HTML output

**User Experience:**
- ✅ No HTML knowledge required
- ✅ Visual editing like Word
- ✅ Real-time preview
- ✅ Professional content creation

**Ready for Production Use! 🎉**
