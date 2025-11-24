# Perubahan Struktur Data Tenaga Kesehatan

## Ringkasan Perubahan

Struktur data tenaga kesehatan telah diperbarui untuk lebih profesional dan sesuai dengan kebutuhan klinik. Perubahan ini menghapus field yang kurang relevan dan menambahkan informasi penting untuk kredibilitas dan penjadwalan.

## Field yang Dihapus

- `tanggal_lahir` - Tidak relevan untuk profil profesional
- `alumnus` - Diganti dengan informasi yang lebih kredibel
- `profesi` - Diganti dengan `role` yang lebih terorganisir

## Field Baru yang Ditambahkan

1. **STR (Surat Tanda Registrasi)**
   - Nomor registrasi resmi tenaga kesehatan
   - Format: String, max 50 karakter
   - Contoh: "123456789012345"

2. **SIP (Surat Izin Praktik)**
   - Nomor izin praktik resmi
   - Format: String, max 50 karakter
   - Contoh: "503/SIP/2024/001"

3. **Pengalaman**
   - Deskripsi pengalaman kerja
   - Format: Text (panjang)
   - Contoh: "10 tahun di RS Umum Jakarta, spesialisasi penyakit dalam"

4. **Tahun Mulai**
   - Tahun mulai praktik
   - Format: Integer (1980-2099)
   - Contoh: 2020

5. **Role**
   - Role/jabatan dalam sistem
   - Pilihan: `dokter_umum`, `admin`, `superadmin`
   - Default: `dokter_umum`

6. **Jadwal Shift**
   - Jadwal praktik berdasarkan range tanggal
   - Format: JSON Array
   - Lebih fleksibel untuk pergantian shift yang dinamis
   - Struktur:
     ```json
     [
       {
         "tanggal_mulai": "2025-11-01",
         "tanggal_selesai": "2025-11-15",
         "jam_mulai": "08:00",
         "jam_selesai": "14:00"
       },
       {
         "tanggal_mulai": "2025-11-16",
         "tanggal_selesai": "2025-11-30",
         "jam_mulai": "14:00",
         "jam_selesai": "20:00"
       }
     ]
     ```

## Perubahan pada Form

### Form Input (Modal)
- Tambah input STR dan SIP
- Tambah textarea Pengalaman
- Tambah input Tahun Mulai
- Tambah dropdown Role
- Tambah dynamic form untuk Jadwal Shift (bisa tambah/hapus hari)

### Tabel Display
Kolom yang ditampilkan:
- No
- Foto
- Nama
- Email
- Handphone
- STR
- SIP
- Role (dengan badge warna)
- Tahun Mulai
- Aksi (Edit, Detail, Hapus)

## Fitur Baru

### 1. Dynamic Jadwal Shift Berbasis Tanggal
- User bisa menambah multiple periode shift
- Setiap periode memiliki tanggal mulai dan selesai
- Lebih fleksibel untuk pergantian shift mingguan/bulanan
- Tombol "Tambah Periode Shift" untuk menambah periode baru
- Tombol hapus untuk menghapus periode tertentu
- Minimal 1 periode shift harus ada

### 2. Detail View
- Tombol "Detail" (icon mata) untuk melihat informasi lengkap
- Menampilkan semua data termasuk jadwal shift dalam format yang rapi

### 3. Role Management
- Role tenaga kesehatan: `dokter_umum`, `admin`, `superadmin`
- Otomatis sync dengan role user saat create/update
- Mapping: `dokter_umum` → `dokter` di user table

## Migration

File migration: 
1. `2025_11_24_094124_update_tenaga_kesehatan_table_structure.php` - Tambah field baru
2. `2025_11_24_100224_update_tenaga_kesehatan_remove_profesi_change_shift.php` - Hapus profesi

Untuk menjalankan:
```bash
php artisan migrate
```

Untuk rollback:
```bash
php artisan migrate:rollback --step=2
```

## Seeder

File seeder: `TenagaKesehatanSeeder.php`

Berisi 3 data contoh dengan jadwal shift berbasis tanggal:
1. Dr. Ahmad Fauzi (Dokter Umum) - 2 periode shift
2. Dr. Siti Nurhaliza (Admin) - 1 periode shift full month
3. Bidan Rina Wati (Dokter Umum) - 2 periode shift bergantian

Untuk menjalankan:
```bash
php artisan db:seed --class=TenagaKesehatanSeeder
```

## File yang Diubah

### Backend
1. `database/migrations/2025_11_24_094124_update_tenaga_kesehatan_table_structure.php` - Migration tambah field
2. `database/migrations/2025_11_24_100224_update_tenaga_kesehatan_remove_profesi_change_shift.php` - Migration hapus profesi
3. `app/Models/TenagaKesehatan.php` - Update fillable, casts, dan helper methods
4. `app/Http/Controllers/TenagaKesehatanController.php` - Handle jadwal_shift JSON & filter role
5. `app/Http/Requests/StoreTenagaKesehatanRequest.php` - Update validation rules
6. `app/Http/Requests/UpdateTenagaKesehatanRequest.php` - Update validation rules

### Frontend
1. `resources/views/adminDataNakes/DataNakes.blade.php` - Update tabel header
2. `resources/views/adminDataNakes/_modalForm.blade.php` - Update form fields
3. `public/js/data_dokter.js` - Update AJAX handling & rendering

## Cara Menggunakan

### Menambah Data Baru
1. Klik tombol "+ Tambah Data"
2. Isi form dengan data lengkap
3. Untuk jadwal shift:
   - Pilih tanggal mulai dan tanggal selesai periode shift
   - Isi jam mulai dan jam selesai
   - Klik "Tambah Periode Shift" untuk menambah periode lain
   - Contoh: Shift pagi 1-15 Nov, shift sore 16-30 Nov
4. Klik "Simpan"

### Mengedit Data
1. Klik tombol Edit (icon pensil)
2. Form akan terisi dengan data existing
3. Ubah data yang diperlukan
4. Jadwal shift akan dimuat otomatis dan bisa diedit
5. Klik "Simpan"

### Melihat Detail
1. Klik tombol Detail (icon mata)
2. Popup akan menampilkan semua informasi lengkap
3. Jadwal shift ditampilkan dalam format list yang rapi

## Integrasi dengan Fitur Lain

### Dashboard User
Data STR, SIP, pengalaman, dan jadwal shift bisa ditampilkan di profil dokter untuk meningkatkan kepercayaan pasien.

### Janji Temu (Appointment)
Jadwal shift bisa digunakan untuk:
- Validasi ketersediaan dokter berdasarkan tanggal
- Menampilkan slot waktu yang tersedia pada periode tertentu
- Cek apakah dokter sedang shift pada tanggal appointment
- Helper method: `isAvailableOnDate($tanggal)` dan `getJadwalByTanggal($tanggal)`

### Chat/Telemedicine
Informasi jadwal bisa ditampilkan untuk memberitahu pasien kapan dokter tersedia.

## Notes

- Field `foto`, `str`, `sip`, `pengalaman`, `tahun_mulai`, dan `jadwal_shift` bersifat opsional
- Field `nama`, `email`, `hp`, dan `role` wajib diisi
- Field `profesi` sudah dihapus, diganti dengan `role` yang lebih terorganisir
- Jadwal shift berbasis range tanggal untuk fleksibilitas pergantian shift
- Jadwal shift disimpan dalam format JSON untuk fleksibilitas
- Role otomatis sync dengan user table untuk akses kontrol
- Helper methods tersedia: `getJadwalByTanggal()`, `isAvailableOnDate()`, `getCurrentShift()`
