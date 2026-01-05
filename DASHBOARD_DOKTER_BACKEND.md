# Backend Dashboard Dokter - Dokumentasi

## Overview
Backend untuk halaman dashboard dokter yang menampilkan jadwal hari ini dan riwayat janji temu berdasarkan data dari database.

## Fitur yang Telah Dibuat

### 1. Controller: DashboardDokterController
**File:** `app/Http/Controllers/DashboardDokterController.php`

#### Methods Utama:
- `dokter()` - Method utama untuk menampilkan dashboard
- `getJadwalHariIni()` - Mengambil jadwal appointment hari ini
- `getRiwayatJanjiTemu()` - Mengambil 5 riwayat janji temu terakhir
- `getStatistikHariIni()` - Mengambil statistik hari ini

#### API Endpoints:
- `getJadwalHariIniApi()` - API untuk jadwal hari ini (AJAX)
- `getRiwayatJanjiTemuApi()` - API untuk riwayat janji temu (AJAX)
- `getDetailAppointment($id)` - API untuk detail appointment
- `getJadwalByDateRange()` - API untuk jadwal berdasarkan range tanggal
- `getStatistikMingguan()` - API untuk statistik mingguan

### 2. Routes
**File:** `routes/web.php`

#### Routes yang Ditambahkan:
```php
// Dashboard utama
Route::get('/nakes/dashboard', [DashboardDokterController::class, 'dokter'])

// API endpoints
Route::prefix('api/dokter')->group(function () {
    Route::get('/jadwal-hari-ini', [DashboardDokterController::class, 'getJadwalHariIniApi']);
    Route::get('/riwayat-janji-temu', [DashboardDokterController::class, 'getRiwayatJanjiTemuApi']);
    Route::get('/appointment/{id}', [DashboardDokterController::class, 'getDetailAppointment']);
    Route::get('/jadwal-range', [DashboardDokterController::class, 'getJadwalByDateRange']);
    Route::get('/statistik-mingguan', [DashboardDokterController::class, 'getStatistikMingguan']);
});
```

### 3. Views yang Diupdate
**File:** `resources/views/dokter/partials/bagian2.blade.php`

#### Fitur Frontend:
- Menampilkan jadwal hari ini dari database
- Menampilkan riwayat janji temu terakhir
- Modal detail appointment (clickable)
- Auto-refresh setiap 5 menit
- Hover effects dan interaktivitas

**File:** `resources/views/dokter/partials/statistikDokter.blade.php`
- Statistik hari ini (total jadwal, menunggu, disetujui)

### 4. CSS Styling
**File:** `public/css/dokter.css`
- Style untuk schedule list
- Hover effects
- Modal styling
- Empty state styling

## Data yang Ditampilkan

### Jadwal Hari Ini
- Jam appointment (dengan durasi 30 menit)
- Nama pasien
- Status (Menunggu/Disetujui)
- Keluhan pasien
- Clickable untuk melihat detail

### Riwayat Janji Temu
- 5 data terakhir
- Nama pasien
- Tanggal appointment
- Status (Disetujui/Selesai/Dibatalkan)
- Clickable untuk melihat detail

### Statistik
- Total jadwal hari ini
- Jumlah yang menunggu konfirmasi
- Jumlah yang sudah disetujui

## Fitur Interaktif

### Modal Detail Appointment
Menampilkan informasi lengkap:
- Informasi pasien (nama, HP, tanggal lahir, alamat)
- Informasi janji temu (tanggal, jam, status)
- Keluhan pasien
- Catatan admin (jika ada)

### Auto-Refresh
- Data di-refresh otomatis setiap 5 menit
- Menggunakan AJAX untuk update tanpa reload halaman

## Database yang Digunakan
**Tabel:** `appointment`

### Kolom yang Digunakan:
- `id` - ID appointment
- `nama` - Nama pasien
- `no_hp` - Nomor HP pasien
- `tanggal_lahir` - Tanggal lahir pasien
- `alamat` - Alamat pasien
- `keluhan` - Keluhan pasien
- `tanggal` - Tanggal appointment
- `jam` - Jam appointment
- `status` - Status (Menunggu, Disetujui, Selesai, Dibatalkan)
- `admin_notes` - Catatan dari admin

## Cara Penggunaan

1. **Akses Dashboard:** Login sebagai dokter dan akses `/nakes/dashboard`
2. **Lihat Jadwal:** Jadwal hari ini otomatis ditampilkan
3. **Lihat Detail:** Klik pada item jadwal atau riwayat untuk melihat detail
4. **Auto-Update:** Data akan ter-update otomatis setiap 5 menit

## API Endpoints untuk Integrasi

### GET `/api/dokter/jadwal-hari-ini`
Response: Array jadwal hari ini

### GET `/api/dokter/riwayat-janji-temu`
Response: Array riwayat 5 terakhir

### GET `/api/dokter/appointment/{id}`
Response: Detail appointment

### GET `/api/dokter/jadwal-range?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD`
Response: Jadwal berdasarkan range tanggal

### GET `/api/dokter/statistik-mingguan`
Response: Statistik mingguan

## Keamanan
- Semua routes dilindungi middleware `auth` dan `role:dokter,bidan,perawat`
- Validasi ID appointment sebelum menampilkan detail
- Error handling untuk data tidak ditemukan

## Performa
- Query database dioptimasi dengan limit dan ordering
- Auto-refresh menggunakan AJAX untuk menghindari full page reload
- CSS transitions untuk smooth user experience