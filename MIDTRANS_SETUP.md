# Panduan Setup Midtrans Payment Gateway

## 📋 Daftar Isi
1. [Instalasi](#instalasi)
2. [Konfigurasi Akun Midtrans](#konfigurasi-akun-midtrans)
3. [Konfigurasi Aplikasi](#konfigurasi-aplikasi)
4. [Testing](#testing)
5. [Production](#production)

---

## 🚀 Instalasi

### 1. Install Midtrans SDK
```bash
composer require midtrans/midtrans-php
```

### 2. Jalankan Migration
Pastikan database MySQL sudah running, kemudian jalankan:
```bash
php artisan migrate
```

Ini akan membuat tabel `transactions` untuk menyimpan data pembayaran.

---

## 🔑 Konfigurasi Akun Midtrans

### Sandbox (Testing)

1. **Daftar Akun Sandbox**
   - Kunjungi: https://dashboard.sandbox.midtrans.com/register
   - Isi form registrasi dengan data perusahaan
   - Verifikasi email Anda

2. **Dapatkan API Keys**
   - Login ke dashboard sandbox
   - Pergi ke **Settings** → **Access Keys**
   - Copy **Server Key** dan **Client Key**
   - Server Key biasanya dimulai dengan `SB-`

3. **Konfigurasi Webhook**
   - Pergi ke **Settings** → **Configuration**
   - Set **Payment Notification URL**: `https://your-domain.com/payment/webhook`
   - Set **Finish Redirect URL**: `https://your-domain.com/payment/success/{order_id}`
   - Set **Unfinish Redirect URL**: `https://your-domain.com/payment/index`
   - Set **Error Redirect URL**: `https://your-domain.com/payment/failed/{order_id}`

### Production (Live)

1. **Daftar Akun Production**
   - Kunjungi: https://dashboard.midtrans.com/register
   - Lengkapi dokumen bisnis:
     - SIUP (Surat Izin Usaha Perdagangan)
     - NPWP Perusahaan
     - KTP Direktur
     - Akta Pendirian
   - Proses verifikasi: 1-3 hari kerja

2. **Dapatkan Production Keys**
   - Setelah disetujui, login ke dashboard production
   - Pergi ke **Settings** → **Access Keys**
   - Copy **Server Key** dan **Client Key** production

---

## ⚙️ Konfigurasi Aplikasi

### 1. Update File .env

Buka file `.env` dan update konfigurasi Midtrans:

```env
# Midtrans Configuration
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Penjelasan:**
- `MIDTRANS_SERVER_KEY`: Server key dari dashboard Midtrans
- `MIDTRANS_CLIENT_KEY`: Client key dari dashboard Midtrans
- `MIDTRANS_IS_PRODUCTION`: `false` untuk sandbox, `true` untuk production
- `MIDTRANS_IS_SANITIZED`: Sanitasi input otomatis
- `MIDTRANS_IS_3DS`: Aktifkan 3D Secure untuk keamanan tambahan

### 2. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🧪 Testing

### Test Cards (Sandbox)

Gunakan kartu test berikut untuk testing di sandbox:

#### Kartu Kredit Berhasil
- **Visa**: `4811 1111 1111 1114`
- **Mastercard**: `5264 2210 3887 4659`
- **JCB**: `3528 2033 2456 4357`
- **CVV**: `123`
- **Exp Date**: `01/25` atau bulan/tahun di masa depan
- **OTP/3DS**: `112233`

#### Kartu Kredit Gagal
- **Denied**: `4911 1111 1111 1113`
- **Challenge by FDS**: `4411 1111 1111 1118`

#### Bank Transfer
- **BCA Virtual Account**: Akan generate VA number
- **Mandiri Bill**: Akan generate bill code
- **BNI Virtual Account**: Akan generate VA number

#### E-Wallet
- **GoPay**: Akan generate QR code
- **ShopeePay**: Akan redirect ke app

### Cara Testing

1. **Login sebagai Pasien**
   - Buka aplikasi dan login dengan akun pasien

2. **Akses Menu Pembayaran**
   - Klik menu **Pembayaran** di sidebar
   - Atau akses: `http://localhost:8000/payment`

3. **Buat Transaksi Test**
   - Masukkan jumlah: `50000` (minimal Rp 1.000)
   - Masukkan deskripsi: `Test Pembayaran Konsultasi`
   - Klik **Bayar Sekarang**

4. **Pilih Metode Pembayaran**
   - Popup Midtrans akan muncul
   - Pilih metode pembayaran (Credit Card, Bank Transfer, dll)
   - Gunakan test card di atas

5. **Verifikasi Hasil**
   - Cek halaman success/failed
   - Cek riwayat pembayaran
   - Cek database tabel `transactions`

---

## 🌐 Production

### Checklist Sebelum Go-Live

- [ ] Akun Midtrans production sudah disetujui
- [ ] Production keys sudah didapat
- [ ] Update `.env` dengan production keys
- [ ] Set `MIDTRANS_IS_PRODUCTION=true`
- [ ] Domain sudah live dan accessible
- [ ] SSL certificate sudah terpasang (HTTPS)
- [ ] Webhook URL sudah dikonfigurasi di dashboard
- [ ] Testing menyeluruh di production environment
- [ ] Monitoring dan logging sudah aktif

### Update untuk Production

1. **Update .env**
```env
MIDTRANS_SERVER_KEY=Mid-server-xxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=true
```

2. **Update View (Snap.js URL)**

Buka `resources/views/payment/index.blade.php`, ganti:
```html
<!-- Dari -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" ...>

<!-- Menjadi -->
<script src="https://app.midtrans.com/snap/snap.js" ...>
```

3. **Clear Cache**
```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize
```

---

## 📁 Struktur File

```
app/
├── Http/Controllers/
│   └── PaymentController.php          # Controller pembayaran
├── Models/
│   └── Transaction.php                # Model transaksi
config/
└── midtrans.php                       # Konfigurasi Midtrans
database/
└── migrations/
    └── xxxx_create_transactions_table.php
resources/
└── views/
    └── payment/
        ├── index.blade.php            # Halaman pembayaran
        ├── success.blade.php          # Halaman sukses
        ├── failed.blade.php           # Halaman gagal
        └── history.blade.php          # Riwayat pembayaran
routes/
└── web.php                            # Routes pembayaran
```

---

## 🔗 Routes

| Method | URL | Nama Route | Deskripsi |
|--------|-----|------------|-----------|
| GET | `/payment` | `payment.index` | Halaman pembayaran |
| POST | `/payment/process` | `payment.process` | Proses pembayaran |
| POST | `/payment/callback` | `payment.callback` | Webhook Midtrans |
| GET | `/payment/success/{orderId}` | `payment.success` | Halaman sukses |
| GET | `/payment/failed/{orderId}` | `payment.failed` | Halaman gagal |
| GET | `/payment/history` | `payment.history` | Riwayat pembayaran |

---

## 🐛 Troubleshooting

### Error: "Class 'Midtrans\Config' not found"
**Solusi:**
```bash
composer dump-autoload
php artisan config:clear
```

### Error: "SQLSTATE[HY000] [2002] No connection"
**Solusi:**
- Pastikan MySQL sudah running
- Cek konfigurasi database di `.env`
- Jalankan: `php artisan migrate`

### Webhook tidak berfungsi
**Solusi:**
- Pastikan URL webhook accessible dari internet
- Gunakan ngrok untuk testing local: `ngrok http 8000`
- Update webhook URL di dashboard Midtrans

### Popup Midtrans tidak muncul
**Solusi:**
- Cek console browser untuk error JavaScript
- Pastikan Snap.js sudah ter-load
- Cek Client Key sudah benar

---

## 📞 Support

- **Midtrans Documentation**: https://docs.midtrans.com
- **Midtrans Support**: support@midtrans.com
- **Midtrans Slack**: https://midtrans.com/slack

---

## 📝 Catatan Penting

1. **Jangan commit API keys** ke repository
2. **Gunakan .env** untuk menyimpan credentials
3. **Testing menyeluruh** di sandbox sebelum production
4. **Monitor transaksi** secara berkala
5. **Backup database** secara rutin
6. **Log semua transaksi** untuk audit trail

---

**Selamat! Sistem pembayaran Midtrans sudah siap digunakan! 🎉**
