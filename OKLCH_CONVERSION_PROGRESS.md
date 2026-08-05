# Progress: Konversi oklch() ke CSS Variables

## Overview
Menghapus semua inline `oklch()` dan menggantinya dengan CSS variables (`var(--xxx)`) yang sudah memiliki fallback HEX untuk kompatibilitas Chrome lama.

## Status: DALAM PROGRES

## Checklist

### Phase 1: Inventory CSS Variables
- [x] Identifikasi semua warna yang dipakai di CSS files
- [x] Identifikasi semua warna yang dipakai di Blade files
- [ ] Buat mapping warna oklch → CSS variable name
- [ ] Verifikasi semua CSS variables sudah punya fallback HEX

### Phase 2: CSS Files
- [x] Konversi inline oklch() di app.css (component overrides)
- [ ] Konversi inline oklch() di neo-mirai-home.css (bulk replacement)
- [ ] Konversi inline oklch() di admin.css (jika ada)

### Phase 3: Blade Files
- [ ] Konversi inline oklch() di resources/views/
- [ ] Test setiap page setelah konversi

### Phase 4: Verification
- [ ] Build test
- [ ] Visual check (Chrome baru)
- [ ] Commit & push

## Color Mapping Reference

| oklch() | CSS Variable | HEX Fallback |
|----------|--------------|--------------|
| oklch(94% 0.035 78) | --paper | #f7f6f3 |
| oklch(91% 0.045 78) | --paper-soft | #eceae6 |
| oklch(84% 0.06 73) | --paper-deep | #d6d3cb |
| oklch(18% 0.035 82) | --ink | #2d2824 |
| oklch(32% 0.045 80) | --ink-soft | #514c46 |
| oklch(54% 0.04 80) | --ash | #8a8580 |
| oklch(73% 0.055 77) | --line | #b8b5b0 |
| oklch(68% 0.145 74) | --gold | #d4a853 |
| oklch(76% 0.165 80) | --gold-bright | #e4c078 |
| oklch(64% 0.19 43) | --sun | #d68a3a |
| oklch(52% 0.17 38) | --sun-deep | #b86e28 |
| oklch(17% 0.035 185) | --night | #1a2c35 |
| oklch(24% 0.04 170) | --night-soft | #283040 |
| oklch(97% 0.02 82) | --rice | #f8f7f5 |
| oklch(58% 0.18 42) | --focus | #d4763a |

## Additional Colors (Komponen Spesifik)

| oklch() | Hex | Usage |
|----------|-----|-------|
| oklch(58% 0.15 30) | rgba(180,100,70,0.15) | Pending badge |
| oklch(72% 0.15 145) | rgba(80,160,100,0.15) | Approved/Sukses |
| oklch(72% 0.15 80) | rgba(200,160,80,0.15) | Sent/Warning |
| oklch(65% 0.15 25) | rgba(180,80,60,0.15) | Rejected/Danger |
| oklch(8% 0.15 190) | rgba(20,80,120,0.1) | PPID primary bg |
| oklch(50% 0.15 190) | rgba(10,100,150,0.3) | PPID shadows |

## Files yang Dimodifikasi

| resources/css/app.css | ✅ Done | HEX fallbacks untuk CSS vars + rgba() untuk components |
| resources/css/neo-mirai-home.css | ⏳ Pending | Bulk replacement needed |
| resources/views/*.blade.php | ⏳ Pending | Konversi inline style |

## Files Baru

| File | Purpose |
|------|---------|
| OKLCH_CONVERSION_PROGRESS.md | Progress tracking (file ini) |

## TODO

### Immediate Next Steps:
1. [ ] Konversi neo-mirai-home.css inline oklch() → var()
2. [ ] Konversi app.css inline oklch() → var()
3. [ ] Konversi blade files inline oklch() → var()

### After Conversion:
4. [ ] Remove manual oklch() fallback lines (CSS vars sudah handle)
5. [ ] Build verification
6. [ ] Commit & push

## Changelog

### 2026-08-05
- Inisiasi project konversi oklch() ke CSS variables
- app.css CSS variables sudah ditambahkan fallback HEX
- Build verified berhasil
