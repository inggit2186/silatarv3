# Jodit Toolbar Fix

## Problem

Toolbar buttons berkurang dan image button tidak muncul.

## Root Cause

Custom `buttons` configuration mengganti seluruh default toolbar Jodit, sehingga banyak button hilang.

## Solution

Remove custom `buttons` configuration and use Jodit's default toolbar:

```javascript
// Before (Wrong)
toolbar: true,
toolbarButtonSize: 'middle',
buttons: [
    'bold', 'italic', 'underline', 'strikethrough', '|',
    'font', 'fontsize', '|',
    // ... many buttons
    'image', 'link', '|',
    // ...
],

// After (Correct)
toolbar: true,
toolbarButtonSize: 'middle',
// No custom buttons - use default Jodit toolbar
```

## Default Jodit Toolbar

Jodit's default toolbar includes:

**Text Formatting:**
- Bold, Italic, Underline, Strikethrough
- Subscript, Superscript

**Font & Size:**
- Font family dropdown
- Font size dropdown

**Colors:**
- Text color
- Background color

**Alignment:**
- Align left, center, right, justify

**Lists:**
- Ordered list
- Unordered list

**Indent:**
- Indent, Outdent

**Insert:**
- Image ✅ (included in default)
- Link
- Horizontal rule
- Table

**History:**
- Undo
- Redo

**View:**
- Source code
- Fullscreen

## Image Button Location

In default Jodit toolbar, image button is in the "Insert" group:

```
[B] [I] [U] [S] | [Font] [Size] | [Colors] | [Align] | [Lists] | [Indent] | [🖼️ Image] [🔗 Link] [— HR] [📊 Table] | [↩ Undo] [↪ Redo] | [</> Source] [⛶ Fullscreen]
```

## Testing

1. Clear cache:
```bash
php artisan view:clear
```

2. Test editor:
```
http://localhost:8000/admin/ppid/{slug}/edit-section/{sectionId}
```

3. Verify:
- ✅ All default toolbar buttons visible
- ✅ Image button (🖼️) visible
- ✅ Click image button opens file dialog
- ✅ Upload works with toast

## Status

✅ Fixed - Using Jodit's default toolbar with all buttons
