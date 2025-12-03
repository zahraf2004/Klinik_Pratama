# 🔧 Chatify Troubleshooting Guide

## ❌ Problem: Pesan Tidak Terkirim / to_id NULL

### Error Message
```
SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'to_id' cannot be null
```

### Penyebab
1. Broadcast connection tidak diset ke Pusher
2. JavaScript tidak bisa detect user ID
3. Meta tag ID tidak ter-set

### ✅ Solusi

#### 1. Update .env
```env
# Ubah dari:
BROADCAST_CONNECTION=log

# Menjadi:
BROADCAST_CONNECTION=pusher
```

#### 2. Clear Config Cache
```bash
php artisan config:clear
php artisan cache:clear
```

#### 3. Restart Server
```bash
# Stop server (Ctrl+C)
# Start lagi
php artisan serve
```

## ❌ Problem: Dokter Tidak Bisa Balas Pesan

### Penyebab
1. Pusher tidak aktif
2. Real-time connection gagal
3. User tidak ter-subscribe ke channel

### ✅ Solusi

#### 1. Cek Pusher Credentials
Di file `.env`:
```env
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=ap1
```

#### 2. Install Pusher PHP SDK
```bash
composer require pusher/pusher-php-server
```

#### 3. Update broadcasting.php
File: `config/broadcasting.php`
```php
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true,
    ],
],
```

## ❌ Problem: Dokter Tidak Bisa Cari Pasien

### Penyebab
1. Search function hanya cari berdasarkan nama user
2. Pasien belum pernah chat dengan dokter
3. Database tidak ada record percakapan

### ✅ Solusi

#### Cara 1: Pasien Harus Chat Duluan
1. Login sebagai pasien
2. Buka halaman konsultasi
3. Klik "Chat Sekarang" pada dokter
4. Kirim pesan pertama
5. Sekarang dokter bisa lihat pasien di daftar kontak

#### Cara 2: Manual Insert (Testing)
```sql
-- Insert dummy message untuk testing
INSERT INTO ch_messages (id, from_id, to_id, body, created_at, updated_at)
VALUES (
    UUID(),
    5, -- ID pasien
    3, -- ID dokter
    'Halo dok, saya ingin konsultasi',
    NOW(),
    NOW()
);
```

## ❌ Problem: Avatar Tidak Muncul

### Penyebab
1. Storage link belum dibuat
2. Foto path salah
3. Permission folder salah

### ✅ Solusi

```bash
# 1. Create storage link
php artisan storage:link

# 2. Check permission (Windows)
icacls storage /grant Users:F /T

# 3. Clear cache
php artisan cache:clear
```

## ❌ Problem: Daftar Dokter Tidak Muncul

### Penyebab
1. User role bukan 'pasien'
2. Dokter tidak punya user_id
3. CSS tidak load

### ✅ Solusi

#### 1. Cek Role User
```sql
SELECT id, name, email, role FROM users WHERE id = YOUR_USER_ID;
```

#### 2. Cek Dokter user_id
```sql
SELECT id, nama, user_id FROM tenaga_kesehatan WHERE role = 'dokter_umum';
```

Jika user_id NULL, jalankan:
```bash
php artisan db:seed --class=TenagaKesehatanSeeder
```

#### 3. Cek CSS Load
Buka browser DevTools (F12) → Network → Cek `doctors-list.css` loaded

## ❌ Problem: Pesan Tidak Real-time

### Penyebab
1. Pusher tidak aktif
2. JavaScript error
3. Browser tidak support WebSocket

### ✅ Solusi

#### 1. Test Pusher Connection
Buka browser console (F12) dan cek:
```javascript
// Harus ada log seperti ini:
Pusher : State changed : connecting -> connected
```

#### 2. Cek JavaScript Error
Buka Console (F12) dan lihat apakah ada error merah

#### 3. Test Manual
```javascript
// Di console browser
console.log(window.chatify);
console.log(Pusher);
```

## 🔍 Debug Mode

### Enable Debug
Di file `.env`:
```env
APP_DEBUG=true
PUSHER_APP_DEBUG=true
```

### Check Logs
```bash
# Windows
type storage\logs\laravel.log

# Atau buka di editor
code storage\logs\laravel.log
```

### Browser Console
1. Buka DevTools (F12)
2. Tab Console
3. Lihat error messages
4. Tab Network → Filter: WS (WebSocket)

## 📊 Checklist Troubleshooting

### Basic Checks
- [ ] `.env` BROADCAST_CONNECTION=pusher
- [ ] Pusher credentials benar
- [ ] `php artisan config:clear` sudah dijalankan
- [ ] Server sudah direstart
- [ ] Storage link sudah dibuat
- [ ] Browser cache sudah di-clear

### Database Checks
- [ ] Tabel `ch_messages` ada
- [ ] Tabel `ch_favorites` ada
- [ ] Tabel `users` ada
- [ ] User punya ID yang benar
- [ ] Dokter punya user_id

### Frontend Checks
- [ ] JavaScript tidak error (cek Console)
- [ ] CSS loaded (cek Network)
- [ ] Pusher connected (cek Console)
- [ ] Meta tag ID ada (cek Elements)

### Backend Checks
- [ ] Route `/chatify` accessible
- [ ] Route `/chatify/{id}` accessible
- [ ] Middleware auth berfungsi
- [ ] Pusher PHP SDK installed

## 🚀 Quick Fix Commands

```bash
# Full reset
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan storage:link

# Restart server
# Ctrl+C to stop
php artisan serve

# Hard refresh browser
# Ctrl+Shift+R
```

## 📞 Still Not Working?

### Step-by-Step Debug

#### 1. Test Basic Chat
```
1. Login sebagai User A
2. Buka /chatify
3. Cek apakah halaman load
4. Cek Console untuk error
```

#### 2. Test Send Message
```
1. Klik user di daftar kontak
2. Ketik pesan
3. Klik send
4. Cek Console untuk error
5. Cek Network tab untuk request
```

#### 3. Test Real-time
```
1. Buka 2 browser berbeda
2. Login User A di browser 1
3. Login User B di browser 2
4. User A kirim pesan ke User B
5. Cek apakah User B terima real-time
```

### Common Solutions

#### Solution 1: Pusher Free Tier Limit
Pusher free tier punya limit:
- 200,000 messages/day
- 100 concurrent connections

Jika limit tercapai, chat tidak akan real-time.

**Fix**: Tunggu 24 jam atau upgrade Pusher plan

#### Solution 2: Firewall Block
Firewall bisa block WebSocket connection.

**Fix**: 
- Disable firewall temporarily
- Add exception untuk port 443
- Test di network lain

#### Solution 3: Browser Cache
Old JavaScript bisa cause issue.

**Fix**:
```
1. Ctrl+Shift+Delete
2. Clear cache & cookies
3. Hard refresh (Ctrl+Shift+R)
```

## 📝 Testing Checklist

### Test 1: Pasien → Dokter
- [ ] Pasien bisa lihat daftar dokter
- [ ] Pasien bisa klik dokter
- [ ] Pasien bisa kirim pesan
- [ ] Dokter terima pesan real-time
- [ ] Dokter bisa balas

### Test 2: Dokter → Pasien
- [ ] Dokter bisa lihat daftar pasien
- [ ] Dokter bisa search pasien
- [ ] Dokter bisa kirim pesan
- [ ] Pasien terima pesan real-time
- [ ] Pasien bisa balas

### Test 3: Multiple Users
- [ ] 2 pasien chat dengan 1 dokter
- [ ] Pesan tidak tercampur
- [ ] Real-time berfungsi untuk semua
- [ ] Notification muncul

## 💡 Pro Tips

1. **Always Clear Cache** setelah update config
2. **Test di Incognito** untuk avoid cache issue
3. **Check Console** sebelum report bug
4. **Use 2 Browsers** untuk test real-time
5. **Read Logs** untuk detail error

---

**Last Updated**: [Current Date]
**Version**: 1.0.0
