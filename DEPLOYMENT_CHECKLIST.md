# ✅ Deployment Checklist - Avatar & Chatify Integration

## 📋 Pre-Deployment

### 1. Backup Database
```bash
# Backup database
mysqldump -u root -p nama_database > backup_before_avatar_$(date +%Y%m%d).sql

# Atau copy file SQLite
copy database\database.sqlite database\database.sqlite.backup
```

### 2. Backup Files
```bash
# Backup storage folder
xcopy storage backup\storage /E /I /Y

# Backup .env
copy .env .env.backup
```

### 3. Test di Development
- [ ] Test login sebagai admin
- [ ] Test login sebagai dokter
- [ ] Test login sebagai pasien
- [ ] Test upload foto dokter
- [ ] Test upload foto pasien
- [ ] Test chat pasien ke dokter
- [ ] Test avatar muncul di navbar
- [ ] Test avatar muncul di Chatify

## 🚀 Deployment Steps

### Step 1: Update Code
```bash
# Pull latest code (jika pakai Git)
git pull origin main

# Atau copy files manual
# - app/Models/User.php
# - app/Models/ProfilPasien.php
# - resources/views/partials/navbar.blade.php
# - resources/views/partials/navdokter.blade.php
# - resources/views/partials/nav.blade.php
# - resources/views/konsultasi/konsultasiNakes.blade.php
# - routes/web.php
# - database/seeders/TenagaKesehatanSeeder.php
```

### Step 2: Install Dependencies
```bash
# Update Composer (jika ada perubahan)
composer install --no-dev --optimize-autoloader

# Update NPM (jika ada perubahan)
npm install
npm run build
```

### Step 3: Storage Link
```bash
# PENTING: Link storage
php artisan storage:link
```

### Step 4: Database Update
```bash
# Jika ada migration baru
php artisan migrate

# Jalankan seeder (HATI-HATI: Cek dulu apakah akan duplicate data)
# php artisan db:seed --class=TenagaKesehatanSeeder
```

### Step 5: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Step 6: Optimize untuk Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Set Permissions (Linux/Mac)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### Step 8: Set Permissions (Windows)
```bash
# Run as Administrator
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

## 🧪 Post-Deployment Testing

### Test 1: Storage Link
```bash
# Cek apakah symbolic link berhasil
# Windows
dir public\storage

# Linux/Mac
ls -la public/storage
```

**Expected**: Folder `storage` harus ada di `public/`

### Test 2: Avatar Default
1. Buka website
2. Login sebagai admin (yang belum punya foto)
3. ✅ Avatar default harus muncul di navbar

### Test 3: Avatar Dokter
1. Login sebagai dokter yang sudah punya foto
2. ✅ Foto dokter harus muncul di navbar
3. Buka Chatify
4. ✅ Foto dokter harus muncul di Chatify

### Test 4: Avatar Pasien
1. Login sebagai pasien
2. Upload foto di halaman Profil
3. ✅ Foto harus muncul di navbar dengan border biru
4. Buka Chatify
5. ✅ Foto harus muncul di Chatify

### Test 5: Chat Integration
1. Login sebagai pasien
2. Buka halaman Konsultasi
3. ✅ Lihat daftar dokter dengan badge hijau
4. Klik "Chat Sekarang"
5. ✅ Chatify terbuka dengan percakapan dokter
6. ✅ Avatar dokter muncul di header

### Test 6: Upload Foto
1. Login sebagai pasien
2. Upload foto baru
3. ✅ Foto ter-update di navbar
4. ✅ Foto ter-update di Chatify
5. Login sebagai dokter
6. Buka chat dengan pasien tersebut
7. ✅ Foto pasien ter-update

## 🐛 Troubleshooting

### Issue 1: Avatar tidak muncul (404)
**Diagnosis:**
```bash
# Cek symbolic link
ls -la public/storage  # Linux/Mac
dir public\storage     # Windows
```

**Solution:**
```bash
php artisan storage:link
```

### Issue 2: Permission Denied
**Diagnosis:**
```bash
# Cek permission
ls -la storage/  # Linux/Mac
```

**Solution:**
```bash
# Linux/Mac
chmod -R 775 storage
chown -R www-data:www-data storage

# Windows (Run as Admin)
icacls storage /grant Users:F /T
```

### Issue 3: Avatar masih default
**Diagnosis:**
1. Cek database: Apakah foto tersimpan?
2. Cek file: Apakah file ada di storage?
3. Cek browser: Clear cache

**Solution:**
```bash
# Clear Laravel cache
php artisan cache:clear
php artisan view:clear

# Clear browser cache
Ctrl + Shift + R (Chrome/Firefox)
```

### Issue 4: Tombol chat disabled
**Diagnosis:**
```sql
-- Cek user_id dokter
SELECT id, nama, email, user_id FROM tenaga_kesehatan WHERE role = 'dokter_umum';
```

**Solution:**
```bash
# Jalankan seeder untuk create user
php artisan db:seed --class=TenagaKesehatanSeeder
```

### Issue 5: Error 500
**Diagnosis:**
```bash
# Cek log
tail -f storage/logs/laravel.log  # Linux/Mac
type storage\logs\laravel.log     # Windows
```

**Solution:**
- Cek error message di log
- Pastikan semua dependencies terinstall
- Pastikan .env sudah benar

## 📊 Monitoring

### Check Points (Setiap Hari)
- [ ] Avatar loading dengan cepat
- [ ] Tidak ada error 404 pada foto
- [ ] Chat berfungsi normal
- [ ] Upload foto berfungsi
- [ ] Storage tidak penuh

### Performance Metrics
```bash
# Cek ukuran storage
du -sh storage/app/public/*  # Linux/Mac
dir storage\app\public       # Windows

# Cek jumlah file
find storage/app/public -type f | wc -l  # Linux/Mac
```

### Database Check
```sql
-- Cek jumlah user dengan foto
SELECT 
    COUNT(*) as total_users,
    SUM(CASE WHEN tk.foto_path IS NOT NULL THEN 1 ELSE 0 END) as users_with_photo
FROM users u
LEFT JOIN tenaga_kesehatan tk ON u.id = tk.user_id
WHERE u.role IN ('dokter', 'bidan', 'perawat', 'admin');

-- Cek jumlah pasien dengan foto
SELECT 
    COUNT(*) as total_pasien,
    SUM(CASE WHEN pp.foto IS NOT NULL THEN 1 ELSE 0 END) as pasien_with_photo
FROM users u
LEFT JOIN profil_pasien pp ON u.id = pp.user_id
WHERE u.role = 'pasien';
```

## 🔐 Security Checklist

- [ ] File upload validation (max 2MB)
- [ ] File type validation (jpg, png, jpeg only)
- [ ] Storage folder tidak accessible via URL langsung
- [ ] CSRF protection enabled
- [ ] Authentication required untuk upload
- [ ] Authorization check (user hanya bisa update foto sendiri)

## 📝 Documentation

- [ ] Update README.md
- [ ] Update API documentation (jika ada)
- [ ] Update user manual
- [ ] Inform team tentang perubahan
- [ ] Training untuk admin (cara upload foto dokter)

## 🎯 Success Criteria

✅ Avatar muncul di navbar untuk semua role
✅ Avatar muncul di Chatify
✅ Avatar muncul di halaman konsultasi
✅ Upload foto berfungsi
✅ Chat langsung ke dokter berfungsi
✅ Tidak ada error 404 pada foto
✅ Performance tetap baik
✅ Storage link berfungsi

## 📞 Support

Jika ada masalah:
1. Cek dokumentasi di folder project
2. Cek log: `storage/logs/laravel.log`
3. Cek browser console untuk error JavaScript
4. Test di browser lain (Chrome, Firefox, Edge)
5. Clear cache browser dan Laravel

## 🔄 Rollback Plan

Jika terjadi masalah serius:

### Step 1: Restore Database
```bash
mysql -u root -p nama_database < backup_before_avatar_YYYYMMDD.sql
```

### Step 2: Restore Files
```bash
# Restore .env
copy .env.backup .env

# Restore storage
xcopy backup\storage storage /E /I /Y
```

### Step 3: Restore Code
```bash
# Jika pakai Git
git reset --hard HEAD~1

# Atau restore manual dari backup
```

### Step 4: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📅 Maintenance Schedule

### Daily
- Monitor error logs
- Check storage usage

### Weekly
- Backup database
- Backup storage folder
- Check performance metrics

### Monthly
- Clean old logs
- Optimize database
- Review and clean unused photos

## ✅ Final Checklist

Sebelum declare deployment sukses:

- [ ] All tests passed
- [ ] No errors in log
- [ ] Avatar muncul di semua tempat
- [ ] Upload foto berfungsi
- [ ] Chat berfungsi
- [ ] Performance OK
- [ ] Team informed
- [ ] Documentation updated
- [ ] Backup completed
- [ ] Rollback plan ready

---

**Deployment Date**: _____________
**Deployed By**: _____________
**Status**: ⬜ Success  ⬜ Failed  ⬜ Rolled Back
**Notes**: _____________________________________________
