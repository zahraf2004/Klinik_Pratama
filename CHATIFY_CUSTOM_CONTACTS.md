# Custom Contact Management - Chatify

## Fitur
Sistem contact management yang berbeda untuk pasien dan dokter:

### Untuk Pasien
- **Tampilkan semua dokter** di sidebar (hanya role dokter)
- Dokter yang sudah pernah di-chat muncul di **posisi paling atas** (berdasarkan pesan terakhir)
- Dokter yang belum pernah di-chat muncul di bawah (diurutkan berdasarkan nama)
- **Chat history tetap ada**, tidak hilang saat ada pesan baru

### Untuk Dokter
- **Hanya tampilkan pasien** yang sudah pernah mengirim pesan
- Diurutkan berdasarkan pesan terakhir (terbaru di atas)
- Chat history tetap tersimpan

### Pembatasan Role
- **Hanya dokter dan pasien** yang bisa menggunakan fitur chat
- Role lain (admin, bidan, perawat) tidak bisa akses chat

## File yang Dimodifikasi

### 1. Backend (Laravel)
- **`app/Http/Controllers/CustomChatifyController.php`** (BARU)
  - Controller custom untuk override behavior Chatify
  - Method `getContacts()` - ambil daftar kontak berdasarkan role
  - Method `updateContacts()` - update kontak saat ada pesan baru
  - Method `getContactsForPatient()` - logika khusus untuk pasien
  - Method `getContactsForDoctor()` - logika khusus untuk dokter
  - Method `renderContactItem()` - render HTML untuk item kontak
  - Method `timeAgo()` - format waktu relatif

- **`routes/web.php`**
  - Tambah route custom sebelum route Chatify default:
    ```php
    Route::get('/chatify/getContacts', [CustomChatifyController::class, 'getContacts']);
    Route::post('/chatify/updateContacts', [CustomChatifyController::class, 'updateContacts']);
    ```

### 2. Frontend (JavaScript)
- **`public/js/chatify/custom-contacts.js`** (BARU)
  - Override fungsi `updateContactItem()` untuk memastikan chat tidak hilang
  - Override fungsi `getContacts()` untuk menggunakan endpoint custom
  - Logika: hapus item lama → tambahkan di posisi atas (prepend)

- **`resources/views/vendor/Chatify/layouts/footerLinks.blade.php`**
  - Include script custom: `custom-contacts.js`

### 3. View (Blade)
- **`resources/views/vendor/Chatify/pages/app.blade.php`**
  - Hapus section "Saved Messages" (Your Space)
  - Sesuaikan tinggi container daftar kontak

## Cara Kerja

### Flow untuk Pasien
1. Pasien login → buka halaman chat
2. System load semua dokter/nakes dari database
3. System cek pesan terakhir dengan setiap dokter
4. Dokter dengan pesan terakhir ditampilkan di atas
5. Dokter tanpa pesan ditampilkan di bawah (sorted by name)
6. Saat ada pesan baru → kontak pindah ke atas (tidak hilang)

### Flow untuk Dokter
1. Dokter login → buka halaman chat
2. System hanya load pasien yang sudah pernah chat
3. Diurutkan berdasarkan pesan terakhir
4. Saat ada pesan baru dari pasien → kontak pindah ke atas

## Testing

### Test sebagai Pasien
1. Login sebagai pasien
2. Buka `/chatify`
3. Cek apakah semua dokter muncul di sidebar
4. Chat dengan dokter A
5. Chat dengan dokter B
6. Dokter B harus muncul di atas dokter A
7. Dokter yang belum pernah di-chat tetap muncul di bawah

### Test sebagai Dokter
1. Login sebagai dokter
2. Buka `/chatify`
3. Sidebar harus kosong (jika belum ada pasien yang chat)
4. Minta pasien kirim pesan
5. Pasien harus muncul di sidebar
6. Chat history tetap ada saat refresh

## Troubleshooting

### Kontak tidak muncul
- Cek apakah route custom sudah terdaftar: `php artisan route:list | grep chatify`
- Cek console browser untuk error JavaScript
- Cek log Laravel: `tail -f storage/logs/laravel.log`

### Chat hilang saat ada pesan baru
- Pastikan `custom-contacts.js` sudah di-load
- Cek fungsi `updateContactItem()` di console browser
- Pastikan endpoint `/chatify/updateContacts` return data yang benar

### Dokter tidak muncul untuk pasien
- Cek role dokter di database: `SELECT id, name, role FROM users WHERE role IN ('dokter', 'bidan', 'perawat')`
- Cek method `getContactsForPatient()` di controller

## Update Future
- [ ] Tambah badge "Online" untuk dokter yang sedang aktif
- [ ] Tambah filter dokter berdasarkan spesialisasi
- [ ] Tambah search untuk cari dokter tertentu
- [ ] Tambah notifikasi push saat ada pesan baru
