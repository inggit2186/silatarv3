# Google Drive OAuth2 Setup - SILATAR V2

## Problem
Service Account tidak punya storage sendiri. Harus menggunakan OAuth2 dengan akun Google Drive yang sudah ada (100GB).

---

## Langkah Setup

### 1. Buat OAuth2 Credential di Google Cloud Console

1. Buka [Google Cloud Console](https://console.cloud.google.com)
2. Pilih project **SILATAR**
3. Go to **APIs & Services** → **Credentials**
4. Klik **"+ Create Credentials"** → **"OAuth client ID"**
5. Isi:
   - **Application type**: Web application
   - **Name**: SILATAR V2
   - **Authorized redirect URIs**: Tambahkan:
     ```
     http://localhost:8000/auth/google/callback
     http://your-production-domain.com/auth/google/callback
     ```
6. Klik **"Create"**
7. Copy **Client ID** dan **Client Secret**

---

### 2. Setup OAuth Consent Screen

1. Go to **APIs & Services** → **OAuth consent screen**
2. Isi:
   - **User type**: External (atau Internal jika ada Google Workspace)
   - **App name**: SILATAR V2
   - **User support email**: email Anda
   - **Developer contact email**: email Anda
3. **Scopes**: Tambahkan:
   - `https://www.googleapis.com/auth/drive.file`
   - `https://www.googleapis.com/auth/drive`
4. **Test users**: Tambahkan akun Google Drive yang akan digunakan

---

### 3. Dapatkan Refresh Token

Untuk mendapatkan refresh token, ikuti langkah ini:

#### Option A: Quick Setup (Manual)
1. Buka browser dan akses URL ini (ganti `{CLIENT_ID}`):
   ```
   https://accounts.google.com/o/oauth2/auth?client_id={CLIENT_ID}&redirect_uri=http://localhost:8000/auth/google/callback&response_type=code&scope=https://www.googleapis.com/auth/drive&access_type=offline&prompt=consent
   ```
2. Login dan authorize aplikasi
3. Copy **authorization code** dari redirect URL
4. Tukar code dengan token menggunakan curl/script

#### Option B: Use Existing Refresh Token
Jika sudah punya refresh token dari setup sebelumnya, gunakan itu.

---

### 4. Update .env File

Tambahkan/edit variabel berikut di `.env`:

```env
# Google Drive OAuth2 Configuration
GOOGLE_DRIVE_CLIENT_ID=xxx.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=xxx
GOOGLE_DRIVE_REFRESH_TOKEN=xxx
GOOGLE_DRIVE_FOLDER_ID=xxx
```

**Catatan:**
- `GOOGLE_DRIVE_FOLDER_ID`: ID folder di Google Drive tempat file akan disimpan
  - Buka folder di Google Drive → Copy ID dari URL (bagian setelah `/folders/`)
  - Contoh URL: `https://drive.google.com/drive/folders/1p8tupdHSriouRTfby-IjoFLSv755RCKu`
  - ID: `1p8tupdHSriouRTfby-IjoFLSv755RCKu`

---

### 5. Test Integrasi

```bash
php artisan gdrive:test
```

Jika berhasil, outputnya akan seperti:
```
✅ Service initialized successfully
✅ File uploaded to Google Drive
✅ File download successful
```

---

## Troubleshooting

### Error: "invalid_grant"
- Refresh token expired atau tidak valid
- Dapatkan refresh token baru dengan mengikuti langkah di atas

### Error: "access_not_configured"
- Google Drive API belum di-enable
- Buka Google Cloud Console → APIs & Services → Library → Enable Google Drive API

### Error: "insufficient permissions"
- Aplikasi belum di-authorize untuk akses Google Drive
- Jalankan authorization flow lagi dan berikan permission

---

## Alternative: Use Service Account dengan Shared Drive

Jika ingin tetap menggunakan Service Account, buat **Shared Drive** (Team Drive) di Google Drive:

1. Buka Google Drive
2. Klik "+" → "Create shared drive"
3. Nama: "SILATAR Uploads"
4. Tambahkan Service Account email sebagai "Content manager"
5. Gunakan ID Shared Drive sebagai `GOOGLE_DRIVE_FOLDER_ID`

**Shared Drive memberikan storage quota tersendiri, sehingga Service Account bisa menggunakannya.**

---

## Referensi

- [Google Drive API Quickstart](https://developers.google.com/drive/api/quickstart/php)
- [OAuth2 for Web Apps](https://developers.google.com/identity/protocols/oauth2/web-server)
- [Laravel Google Drive Storage Package](https://github.com/yaza-putu/laravel-google-drive-storage)
