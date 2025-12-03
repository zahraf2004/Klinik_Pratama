# Testing Avatar Chatify

## Cara Test Avatar

### 1. Test Avatar Dokter

**Login sebagai dokter:**
- Email: ahmad.fauzi@klinik.com
- Password: password123

**Cek di Chatify:**
1. Buka `/chatify`
2. Avatar dokter harus muncul di header (pojok kiri atas)
3. Jika dokter punya foto di database, foto tersebut akan muncul
4. Jika tidak ada foto, akan muncul avatar default

### 2. Test Avatar Pasien

**Login sebagai pasien:**
- Buat akun pasien baru atau gunakan akun yang sudah ada
- Upload foto di halaman Profil (`/profil`)

**Cek di Chatify:**
1. Buka `/chatify`
2. Avatar pasien harus muncul
3. Foto yang di-upload di profil harus muncul di Chatify

### 3. Test Chat Antar User

**Scenario 1: Pasien chat dengan Dokter**
1. Login sebagai pasien
2. Buka halaman Konsultasi (`/konsultasi`)
3. Klik tombol "Chat Sekarang" pada salah satu dokter
4. Chatify akan terbuka dengan percakapan dokter tersebut
5. Avatar dokter harus muncul di header chat
6. Kirim pesan ke dokter

**Scenario 2: Dokter melihat pesan dari Pasien**
1. Login sebagai dokter
2. Buka `/chatify`
3. Daftar kontak harus menampilkan pasien yang mengirim pesan
4. Avatar pasien harus muncul di daftar kontak
5. Klik kontak pasien untuk membuka percakapan
6. Avatar pasien harus muncul di header chat

### 4. Test Update Foto

**Update foto dokter:**
1. Login sebagai admin
2. Buka Data Nakes
3. Edit dokter dan upload foto baru
4. Login sebagai pasien
5. Buka chat dengan dokter tersebut
6. Avatar dokter harus ter-update dengan foto baru

**Update foto pasien:**
1. Login sebagai pasien
2. Buka halaman Profil
3. Upload foto baru
4. Buka Chatify
5. Avatar di header harus ter-update
6. Login sebagai dokter dan cek chat dengan pasien tersebut
7. Avatar pasien harus ter-update

## Troubleshooting

### Avatar tidak muncul / error 404
**Solusi:**
```bash
php artisan storage:link
```

### Avatar masih default padahal sudah upload foto
**Cek:**
1. Pastikan foto tersimpan di database (cek tabel `tenaga_kesehatan` atau `profil_pasien`)
2. Pastikan path foto benar
3. Cek permission folder `storage/app/public`
4. Clear cache: `php artisan cache:clear`

### Avatar dokter tidak muncul
**Cek:**
1. Pastikan dokter punya `user_id` di tabel `tenaga_kesehatan`
2. Jalankan seeder jika perlu: `php artisan db:seed --class=TenagaKesehatanSeeder`

## Expected Results

✅ Avatar dokter muncul dari foto di tabel `tenaga_kesehatan`
✅ Avatar pasien muncul dari foto di tabel `profil_pasien`
✅ Avatar default muncul jika tidak ada foto
✅ Avatar ter-update otomatis setelah upload foto baru
✅ Avatar muncul di semua tempat di Chatify (daftar kontak, header, info user)
