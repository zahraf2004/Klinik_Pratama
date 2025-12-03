# 📋 Quick Reference Card

## 🔑 Kredensial Login

```
┌─────────────────────────────────────────────┐
│ DOKTER 1                                    │
├─────────────────────────────────────────────┤
│ Email    : ahmad.fauzi@klinik.com          │
│ Password : password123                      │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│ DOKTER 2                                    │
├─────────────────────────────────────────────┤
│ Email    : siti.nurhaliza@klinik.com       │
│ Password : password123                      │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│ DOKTER 3                                    │
├─────────────────────────────────────────────┤
│ Email    : rina.wati@klinik.com            │
│ Password : password123                      │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│ PASIEN                                      │
├─────────────────────────────────────────────┤
│ Register di: /registrasi                    │
└─────────────────────────────────────────────┘
```

## ⚡ Command Cepat

```bash
# Setup
php artisan storage:link
php artisan optimize:clear

# Seeder
php artisan db:seed --class=TenagaKesehatanSeeder

# Cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔍 Cek Avatar di Blade

```blade
<!-- Tampilkan avatar -->
{{ Auth::user()->avatar }}

<!-- Cek punya foto custom -->
@if(Auth::user()->hasCustomAvatar())
    <img src="{{ Auth::user()->avatar }}">
@else
    <i class="fa-solid fa-circle-user"></i>
@endif
```

## 📍 URL Penting

```
/login              - Login page
/registrasi         - Register pasien
/dashboard          - Dashboard pasien
/konsultasi         - Halaman konsultasi
/chatify            - Chatify messenger
/chatify/{user_id}  - Chat langsung dengan user
/profil             - Profil pasien
/data-nakes         - Data nakes (admin)
```

## 🎯 Lokasi Avatar

```
✅ Navbar Admin      → Pojok kanan atas
✅ Navbar Dokter     → Pojok kanan atas
✅ Navbar Pasien     → Pojok kanan atas
✅ Chatify List      → Daftar kontak
✅ Chatify Header    → Header percakapan
✅ Chatify Info      → Info panel
✅ Card Dokter       → Halaman konsultasi
```

## 📂 Path Foto

```
Dokter/Nakes:
storage/app/public/tenaga_kesehatan/

Pasien:
storage/app/public/patient-photos/

Default:
public/assets/img/avatar/avatar-1.png
```

## 🔧 Model Methods

```php
// Get avatar URL
Auth::user()->avatar

// Cek punya foto custom
Auth::user()->hasCustomAvatar()

// Get foto URL (ProfilPasien)
$profil->foto_url

// Get foto URL (TenagaKesehatan)
$nakes->foto_url
```

## 🐛 Troubleshooting Cepat

```
Avatar 404?
→ php artisan storage:link

Chat disabled?
→ php artisan db:seed --class=TenagaKesehatanSeeder

Error 500?
→ type storage\logs\laravel.log

Avatar tidak update?
→ Ctrl + Shift + R (clear browser cache)
```

## 📊 Database Check

```sql
-- Cek dokter dengan user_id
SELECT nama, email, user_id 
FROM tenaga_kesehatan 
WHERE user_id IS NOT NULL;

-- Cek pasien dengan foto
SELECT u.name, pp.foto 
FROM users u
LEFT JOIN profil_pasien pp ON u.id = pp.user_id
WHERE u.role = 'pasien' AND pp.foto IS NOT NULL;
```

## 🎨 Styling Quick

```css
/* Avatar bulat */
border-radius: 50%;
object-fit: cover;

/* Navbar Admin/Dokter */
width: 30px;
height: 30px;

/* Navbar Pasien */
width: 40px;
height: 40px;
border: 2px solid #4a83d3;

/* Badge online */
background: #4CAF50;
border-radius: 50%;
```

## 📝 Upload Foto

```php
// Controller
$foto = $request->file('foto')->store('tenaga_kesehatan', 'public');

// Validation
'foto' => 'nullable|image|max:2048'

// Delete old
Storage::disk('public')->delete($old_path);
```

## 🔄 Flow Singkat

```
User Login
    ↓
Auth::user()->avatar
    ↓
getAvatarAttribute()
    ↓
Cek role → Ambil foto dari DB
    ↓
Return URL atau default
    ↓
Tampil di view
```

## ✅ Test Checklist

```
□ Login admin → Avatar di navbar
□ Login dokter → Avatar di navbar
□ Login pasien → Avatar/icon di navbar
□ Upload foto pasien → Update di navbar
□ Upload foto dokter → Update di navbar
□ Buka /konsultasi → Badge hijau muncul
□ Klik "Chat Sekarang" → Chatify terbuka
□ Chatify → Avatar muncul di list
□ Chatify → Avatar muncul di header
```

## 📞 Emergency

```
Rollback:
1. Restore database backup
2. Restore .env.backup
3. git reset --hard HEAD~1
4. php artisan cache:clear

Support:
1. Cek storage/logs/laravel.log
2. Cek browser console (F12)
3. Test di browser lain
4. Clear cache browser & Laravel
```

## 📚 Dokumentasi

```
README_AVATAR_CHATIFY.md     → Main documentation
QUICK_START_CHATIFY.md       → Setup guide
INTEGRASI_CHATIFY.md         → Technical docs
NAVBAR_AVATAR_UPDATE.md      → Navbar docs
VISUAL_SUMMARY.md            → Visual guide
TEST_AVATAR_CHATIFY.md       → Testing guide
COMMANDS_CHATIFY.md          → Command reference
DEPLOYMENT_CHECKLIST.md      → Deployment guide
RINGKASAN_PERUBAHAN_CHATIFY.md → Change summary
```

---

**Print this card and keep it handy! 📌**
