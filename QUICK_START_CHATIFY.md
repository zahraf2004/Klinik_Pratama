# 🚀 Quick Start Guide - Chatify Integration

## ⚡ Setup Cepat (5 Menit)

### 1. Link Storage
```bash
php artisan storage:link
```

### 2. Jalankan Seeder (Opsional - Jika Database Kosong)
```bash
php artisan db:seed --class=TenagaKesehatanSeeder
```

### 3. Clear Cache
```bash
php artisan optimize:clear
```

### 4. Test!
Buka browser dan test fitur-fitur berikut:

## 🧪 Testing Cepat

### Test 1: Login Dokter (30 detik)
1. Buka `/login`
2. Login dengan:
   - Email: `ahmad.fauzi@klinik.com`
   - Password: `password123`
3. ✅ Cek navbar pojok kanan atas - avatar dokter harus muncul
4. Buka `/chatify`
5. ✅ Avatar dokter harus muncul di pojok kiri atas Chatify

### Test 2: Chat Pasien ke Dokter (1 menit)
1. Logout dari akun dokter
2. Login sebagai pasien (atau register akun baru)
3. Buka `/konsultasi`
4. ✅ Lihat daftar dokter dengan badge hijau
5. Klik tombol "Chat Sekarang" pada salah satu dokter
6. ✅ Chatify terbuka dengan percakapan dokter tersebut
7. Kirim pesan: "Halo dok, saya mau konsultasi"

### Test 3: Dokter Balas Pesan (1 menit)
1. Logout dari akun pasien
2. Login kembali sebagai dokter
3. Buka `/chatify`
4. ✅ Lihat pesan dari pasien di daftar kontak
5. ✅ Avatar pasien muncul (atau default jika belum upload foto)
6. Klik kontak pasien
7. Balas pesan: "Halo, ada yang bisa saya bantu?"

### Test 4: Upload Foto (2 menit)

**Upload Foto Pasien:**
1. Login sebagai pasien
2. Buka `/profil`
3. Upload foto profil
4. ✅ Cek navbar - foto harus ter-update dengan border biru
5. Buka `/chatify`
6. ✅ Foto ter-update di header Chatify

**Upload Foto Dokter:**
1. Login sebagai admin
2. Buka Data Nakes
3. Edit dokter dan upload foto
4. ✅ Cek navbar admin - foto default masih muncul (normal)
5. Login sebagai dokter yang baru di-update
6. ✅ Cek navbar dokter - foto baru harus muncul
7. Login sebagai pasien
8. Buka chat dengan dokter tersebut
9. ✅ Foto dokter ter-update di Chatify

## ✅ Checklist Fitur

Pastikan semua fitur berikut berfungsi:

- [ ] Tombol "Chat Sekarang" di halaman konsultasi
- [ ] Badge hijau di foto dokter yang tersedia
- [ ] Chat langsung ke dokter yang dipilih
- [ ] Avatar dokter muncul dari database
- [ ] Avatar pasien muncul dari database
- [ ] Avatar default jika tidak ada foto
- [ ] Update foto langsung ter-update di Chatify
- [ ] Daftar kontak menampilkan avatar yang benar
- [ ] Header chat menampilkan avatar yang benar
- [ ] **Avatar muncul di navbar admin**
- [ ] **Avatar muncul di navbar dokter**
- [ ] **Avatar muncul di navbar pasien (dengan border biru)**
- [ ] **Icon default muncul jika pasien belum upload foto**

## 🐛 Troubleshooting Cepat

### Avatar tidak muncul?
```bash
php artisan storage:link
```

### Tombol chat disabled?
Dokter belum punya user_id. Jalankan:
```bash
php artisan db:seed --class=TenagaKesehatanSeeder
```

### Error 404 pada foto?
Cek permission folder:
```bash
# Windows (Run as Administrator)
icacls storage\app\public /grant Users:F /T
```

### Chat tidak terbuka?
Clear cache:
```bash
php artisan optimize:clear
```

## 📋 Kredensial Default

### Dokter 1
- Email: `ahmad.fauzi@klinik.com`
- Password: `password123`

### Dokter 2
- Email: `siti.nurhaliza@klinik.com`
- Password: `password123`

### Dokter 3
- Email: `rina.wati@klinik.com`
- Password: `password123`

### Pasien
- Register akun baru di `/registrasi`
- Atau gunakan akun yang sudah ada

## 🎯 Next Steps

Setelah semua test berhasil:

1. **Customize Avatar Default**
   - Ganti file `public/assets/img/avatar/avatar-1.png`
   - Atau update path di `User::getAvatarAttribute()`

2. **Tambah Dokter Baru**
   - Login sebagai admin
   - Buka Data Nakes
   - Tambah dokter baru
   - Sistem otomatis buat user dan password = nomor HP

3. **Customize Chatify**
   - Edit view di `resources/views/vendor/Chatify/`
   - Edit config di `config/chatify.php`
   - Edit CSS di `public/css/`

4. **Production Ready**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## 📚 Dokumentasi Lengkap

- `INTEGRASI_CHATIFY.md` - Dokumentasi teknis lengkap
- `TEST_AVATAR_CHATIFY.md` - Panduan testing detail
- `COMMANDS_CHATIFY.md` - Referensi command
- `RINGKASAN_PERUBAHAN_CHATIFY.md` - Daftar perubahan

## 💡 Tips

1. **Development**: Gunakan `php artisan serve` untuk testing lokal
2. **Testing**: Buka 2 browser berbeda untuk test chat real-time
3. **Debug**: Cek `storage/logs/laravel.log` jika ada error
4. **Backup**: Backup database sebelum jalankan seeder di production

## 🎉 Selesai!

Jika semua test berhasil, integrasi Chatify sudah siap digunakan!

**Happy Coding! 🚀**
