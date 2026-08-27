# Dapatkan OAuth2 Token untuk Google Drive

## Step-by-Step Guide

### Langkah 1: Buat OAuth 2.0 Client ID

1. Buka [Google Cloud Console](https://console.cloud.google.com)
2. Pilih project **SILATAR**
3. Go to **APIs & Services** → **Credentials**
4. Klik **"+ Create Credentials"** → **"OAuth client ID"**
5. Isi form:
   - **Application type**: Web application
   - **Name**: SILATAR V2
   - **Authorized redirect URIs**: Tambahkan:
     ```
     http://localhost:8000/auth/google/callback
     ```

6. Klik **"Create"**
7. **Copy Client ID** dan **Client Secret** (simpan dulu)

---

### Langkah 2: Setup OAuth Consent Screen

1. Go to **APIs & Services** → **OAuth consent screen**
2. Isi:
   - **User type**: External
   - **App name**: SILATAR V2
   - **User support email**: email Anda
   - **Developer contact email**: email Anda
3. Klik **"Save and Continue"**
4. **Scopes**: Klik **"Add or Remove Scopes"**, tambahkan:
   - `https://www.googleapis.com/auth/drive.file`
   - `https://www.googleapis.com/auth/drive`
5. Klik **"Save and Continue"**
6. **Test users**: Klik **"Add Users"**, tambahkan email Google Drive yang akan digunakan
7. Klik **"Save and Continue"**

---

### Langkah 3: Dapatkan Refresh Token

#### Method A: Manual (Quick)

1. **Buka browser** dan akses URL berikut (ganti `{CLIENT_ID}` dengan Client ID Anda):

```
https://accounts.google.com/o/oauth2/auth?client_id={CLIENT_ID}&redirect_uri=http://localhost:8000/auth/google/callback&response_type=code&scope=https://www.googleapis.com/auth/drive%20https://www.googleapis.com/auth/drive.file&access_type=offline&prompt=consent
```

2. **Login** dengan akun Google yang punya Google Drive (100GB)
3. **Authorize** aplikasi
4. Setelah redirect, browser akan ke URL seperti:
```
http://localhost:8000/auth/google/callback?code=4/0AX4XfWh...long_code...
```
5. **Copy authorization code** dari URL (bagian setelah `code=`)

6. **Tukar code dengan token** menggunakan curl atau Postman:

```bash
curl -X POST https://oauth2.googleapis.com/token \
  -d "code={YOUR_AUTHORIZATION_CODE}" \
  -d "client_id={YOUR_CLIENT_ID}" \
  -d "client_secret={YOUR_CLIENT_SECRET}" \
  -d "redirect_uri=http://localhost:8000/auth/google/callback" \
  -d "grant_type=authorization_code"
```

7. Response akan seperti:
```json
{
  "access_token": "ya29.a0AfH6SMBx...",
  "expires_in": 3599,
  "scope": "https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/drive",
  "token_type": "Bearer",
  "refresh_token": "1//0g...long_refresh_token..."
}
```

8. **Copy refresh_token** (ini yang Anda butuhkan!)

---

#### Method B: Quick PHP Script

Buat file `get_token.php` di public folder:

```php
<?php
// get_token.php

$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';
$redirectUri = 'http://localhost:8000/auth/google/callback';

if (!isset($_GET['code'])) {
    // Redirect ke authorization URL
    $authUrl = "https://accounts.google.com/o/oauth2/auth?" . http_build_query([
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => 'https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/drive.file',
        'access_type' => 'offline',
        'prompt' => 'consent',
    ]);
    header("Location: {$authUrl}");
    exit;
}

// Tukar code dengan token
$code = $_GET['code'];
$tokenUrl = 'https://oauth2.googleapis.com/token';
$data = [
    'code' => $code,
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectUri,
    'grant_type' => 'authorization_code',
];

$response = file_get_contents($tokenUrl, false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => http_build_query($data),
    ],
]));

echo "<pre>" . print_r(json_decode($response, true), true) . "</pre>";
echo "<h3>Refresh Token:</h3>";
echo "<code>" . json_decode($response, true)['refresh_token'] ?? 'ERROR' . "</code>";
```

Buka: `http://localhost:8000/get_token.php`

---

### Langkah 4: Update .env

Setelah mendapatkan token, update `.env`:

```env
# Google Drive OAuth2 Configuration
GOOGLE_DRIVE_CLIENT_ID=xxx.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=xxx
GOOGLE_DRIVE_REFRESH_TOKEN=1//0g...long_token...
GOOGLE_DRIVE_FOLDER_ID=1p8tupdHSriouRTfby-IjoFLSv755RCKu
```

---

### Langkah 5: Test

```bash
php artisan config:clear
php artisan gdrive:test
```

Jika berhasil, Anda akan melihat:
```
✅ Configuration found
✅ Service initialized successfully
✅ Folder created/found
✅ File uploaded successfully
✅ File downloaded
✅ All Google Drive tests passed!
```

---

## Troubleshooting

### Error: "redirect_uri_mismatch"
- Pastikan redirect URI di Google Cloud Console sama persis dengan yang digunakan
- Harus: `http://localhost:8000/auth/google/callback` ( dengan `http://`)

### Error: "invalid_grant"
- Authorization code sudah expired (hanya berlaku beberapa menit)
- Dapatkan code baru dengan menjalankan authorization flow lagi

### Error: "access_not_configured"
- Google Drive API belum di-enable
- Buka Google Cloud Console → APIs & Services → Library → Search "Google Drive API" → Enable

### Error: "consent_required"
- Aplikasi masih dalam mode "Testing"
- Untuk production, publish app di OAuth consent screen

---

## Referensi

- [Google OAuth2 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Google Drive API Scopes](https://developers.google.com/drive/api/v3/about-auth)
- [Offline Access](https://developers.google.com/identity/protocols/oauth2#offline)
