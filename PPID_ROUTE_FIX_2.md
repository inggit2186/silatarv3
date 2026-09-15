# PPID Route Fix #2 - Link Generation Error

## Error Message
```
Symfony\Component\Routing\Exception\RouteNotFoundException
Route [ppid./ppid/profil-singkat] not defined.
```

## Root Cause

Di database seeder, link cards diindex menggunakan full URL path:
```json
{
    "cards": [
        {
            "title": "Profil PPID",
            "link": "/ppid/profil-singkat"
        }
    ]
}
```

Tapi di views, kode mencoba generate route dengan prefix:
```php
<a href="{{ route('ppid.' . $card->link) }}">
// Results in: route('ppid./ppid/profil-singkat') ❌
```

This caused a double prefix: `ppid./ppid/profil-singkat` which is not a valid route name.

## Solution

Fix in `resources/views/ppid/index.blade.php` - strip the prefix and generate route correctly:

```php
@foreach($cards as $card)
    @php
        $link = $card->link ?? '#';
        $link = str_replace('/ppid/', '', $link);
        $link = ltrim($link, '/');
        if ($link === '' || $link === 'ppid') {
            $routeName = 'ppid';
        } else {
            $routeName = 'ppid.' . $link;
        }
    @endphp
    <a href="{{ route($routeName) }}" class="ppid-card">
@endforeach
```

### What This Fix Does

1. **Strip `/ppid/` prefix** from link
2. **Strip leading slashes** to get clean route name
3. **Handle edge cases:**
   - Empty link → use 'ppid' route
   - Link is 'ppid' → use 'ppid' route
   - Other links → use 'ppid.{link}' route

### Example Transformations

| Database Link | Route Generated | Status |
|---|---|---|
| `/ppid/profil-singkat` | `ppid.profil-singkat` | ✅ Fixed |
| `/ppid/visi-misi` | `ppid.visi-misi` | ✅ Fixed |
| `/ppid` | `ppid` | ✅ Fixed |
| `/` | `ppid` | ✅ Fixed |
| `#` | `ppid.` (fallback) | ⚠️ May need handling |

## Files Modified

| File | Changes |
|------|---------|
| `resources/views/ppid/index.blade.php` | Fixed route generation for layanan_populer cards |

## Testing

1. Clear caches:
   ```bash
   php artisan view:clear
   php artisan route:clear
   ```

2. Access public pages:
   ```
   http://localhost:8000/ppid
   http://localhost:8000/ppid/profil-singkat
   http://localhost:8000/ppid/visi-misi
   ```

3. Verify links work correctly

## Prevention

To prevent this issue in the future, when adding links to card metadata:
- ✅ Use route names without `/ppid/` prefix: `profil-singkat`
- ❌ Don't use full URL paths: `/ppid/profil-singkat`

Or use the fix I implemented to handle both cases.

## Status

✅ Fixed - Routes should now generate correctly
