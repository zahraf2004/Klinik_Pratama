# 💬 Integrasi Chatify dengan Halaman Konsultasi

> Dokumentasi lengkap integrasi sistem chat Chatify dengan halaman konsultasi dokter

## 📖 Daftar Dokumentasi

- **[QUICK_START_CHATIFY.md](QUICK_START_CHATIFY.md)** - Panduan setup cepat (5 menit)
- **[INTEGRASI_CHATIFY.md](INTEGRASI_CHATIFY.md)** - Dokumentasi teknis lengkap (file ini)
- **[TEST_AVATAR_CHATIFY.md](TEST_AVATAR_CHATIFY.md)** - Panduan testing avatar
- **[NAVBAR_AVATAR_UPDATE.md](NAVBAR_AVATAR_UPDATE.md)** - Dokumentasi avatar di navbar
- **[COMMANDS_CHATIFY.md](COMMANDS_CHATIFY.md)** - Referensi command
- **[RINGKASAN_PERUBAHAN_CHATIFY.md](RINGKASAN_PERUBAHAN_CHATIFY.md)** - Daftar perubahan

---

# Integrasi Chatify dengan Halaman Konsultasi

## Perubahan yang Dilakukan

### 1. Update View Konsultasi (`resources/views/konsultasi/konsultasiNakes.blade.php`)
- Tombol "Chat Sekarang" sekarang mengarah langsung ke chat dengan dokter yang dipilih
- Menggunakan `user_id` dari dokter untuk membuka chat Chatify
- Jika dokter belum punya `user_id`, tombol akan disabled dengan pesan "Chat Tidak Tersedia"

### 2. Update Routes (`routes/web.php`)
- Menambahkan route baru: `/chatify/{id}` dengan nama `chatify.user`
- Route ini memungkinkan membuka chat langsung dengan user tertentu berdasarkan ID

### 3. Update Seeder (`database/seeders/TenagaKesehatanSeeder.php`)
- Setiap dokter sekarang otomatis dibuatkan akun User
- User ID tersimpan di field `user_id` pada tabel `tenaga_kesehatan`
- Password default: nomor HP dokter

## Cara Kerja

1. Ketika pasien klik tombol "Chat Sekarang" pada card dokter
2. Sistem akan redirect ke `/chatify/{user_id_dokter}`
3. Chatify akan otomatis membuka percakapan dengan dokter tersebut
4. Pasien bisa langsung mulai chat dengan dokter

## Fitur Tambahan

### Indikator Visual
- Dokter yang tersedia untuk chat akan memiliki **badge hijau** di foto profil mereka
- Tombol "Chat Sekarang" akan aktif (biru) jika dokter tersedia
- Tombol akan disabled (abu-abu) jika dokter belum terhubung ke sistem chat

## Testing

### Untuk Data Lama (Jika Ada Dokter Tanpa user_id)
Jalankan seeder ulang untuk membuat user untuk semua dokter:
```bash
php artisan db:seed --class=TenagaKesehatanSeeder
```

### Untuk Dokter Baru
Ketika admin menambah dokter baru melalui form, sistem otomatis:
- Membuat akun User dengan email dan nama dokter
- Password default: nomor HP dokter
- Menyimpan user_id ke data tenaga kesehatan

## Kredensial Login Dokter (Dari Seeder)

1. **Dr. Ahmad Fauzi**
   - Email: ahmad.fauzi@klinik.com
   - Password: password123

2. **Dr. Siti Nurhaliza**
   - Email: siti.nurhaliza@klinik.com
   - Password: password123

3. **Bidan Rina Wati**
   - Email: rina.wati@klinik.com
   - Password: password123

## Avatar/Foto Profil di Chatify

### Implementasi
Chatify sekarang menggunakan foto dari database untuk setiap user:

1. **Dokter/Nakes**: Foto diambil dari tabel `tenaga_kesehatan` (field `foto_path`)
2. **Pasien**: Foto diambil dari tabel `profil_pasien` (field `foto`)
3. **Default**: Jika tidak ada foto, menggunakan `assets/img/avatar/avatar-1.png`

### Cara Kerja
- Method `getAvatarAttribute()` di model `User` otomatis dipanggil oleh Chatify
- Method ini mengecek role user dan mengambil foto dari tabel yang sesuai
- Foto otomatis muncul di:
  - Daftar kontak chat
  - Header percakapan
  - Bubble pesan (jika ada)
  - Info user

### Update Foto
- **Dokter**: Admin update foto melalui halaman Data Nakes
- **Pasien**: Update foto melalui halaman Profil Pasien
- Foto akan otomatis ter-update di:
  - Chatify (daftar kontak, header, info user)
  - Navbar (pojok kanan atas)
  - Halaman konsultasi (card dokter)

## Catatan Penting

- Pastikan Chatify sudah terinstall dan dikonfigurasi dengan benar
- Semua user (pasien dan dokter) harus login untuk bisa menggunakan fitur chat
- Tombol chat hanya muncul aktif jika dokter sudah punya akun user (user_id tidak null)
- Pastikan folder `storage/app/public` sudah di-link dengan `php artisan storage:link`
