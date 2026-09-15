# Jodit Toolbar Debug

## Problem

Image upload button tidak muncul di toolbar meskipun sudah dikonfigurasi.

## Root Cause

Jodit's default toolbar mungkin tidak include image button, atau image button hidden.

## Solution

Gunakan explicit `buttons` configuration untuk memastikan image button muncul:

```javascript
toolbar: true,
toolbarButtonSize: 'middle',
buttons: [
    'bold', 'italic', 'underline', 'strikethrough',
    'font', 'fontsize',
    'color',
    'orderedlist', 'unorderedlist',
    'left', 'center', 'right', 'justify',
    'image', 'link',  // ← Image button explicitly added
    'undo', 'redo',
    'source', 'fullsize'
],
```

## Testing Steps

1. Clear cache:
```bash
php artisan view:clear
```

2. Test page:
```
http://localhost:8000/test-jodit-buttons.html
```

3. Check:
- Status shows "Jodit loaded"
- Button list shows all buttons
- Image button found indicator

4. Test admin:
```
http://localhost:8000/admin/ppid/{slug}/edit-section/{sectionId}
```

5. Verify:
- Image button visible in toolbar
- Click image button opens file dialog
- Upload works with toast

## Expected Toolbar

```
Row 1: [B] [I] [U] [S] | [Font] [Size] | [Color]
Row 2: [1.] [•] | [◀] [◫] [▶] [≡] | [🖼️] [🔗] | [↩] [↪] | [</>] [⛶]
```

Image button (🖼️) should be in Row 2, between alignment buttons and link button.

## Status

Ready for testing
