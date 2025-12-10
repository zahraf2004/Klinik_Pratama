# 💬 Sistem Chat dengan Pembayaran Berlangganan

## 📋 Overview

Sistem ini mengintegrasikan chat Chatify dengan sistem pembayaran Midtrans untuk memberikan pengalaman chat premium kepada pasien. Pasien mendapat **3 pesan gratis**, setelah itu harus berlangganan untuk melanjutkan chat.

---

## 🎯 Fitur Utama

### Untuk Pasien:
- ✅ **3 pesan gratis** untuk setiap dokter
- ✅ **Popup pembayaran otomatis** saat limit tercapai
- ✅ **Subscription bulanan/tahunan** untuk chat unlimited
- ✅ **Counter pesan** yang tersisa
- ✅ **Block input** saat limit tercapai

### Untuk Dokter:
- ✅ **Tombol "End Session"** untuk mengakhiri sesi chat
- ✅ **Unlimited chat** (tidak ada limit)
- ✅ **Melihat status subscription pasien**

### Untuk Admin:
- ✅ **Monitor semua transaksi**
- ✅ **Laporan subscription**
- ✅ **Manage chat sessions**

---

## 🏗️ Struktur Database

### Tabel `chat_sessions`
```sql
- id (primary key)
- patient_id (foreign key ke users)
- doctor_id (foreign key ke users)
- message_count (jumlah pesan dari pasien)
- is_premium (boolean - apakah sudah berlangganan)
- is_active (boolean - session masih aktif)
- started_at (waktu mulai session)
- ended_at (waktu akhir session)
```

### Tabel `subscriptions`
```sql
- id (primary key)
- user_id (foreign key ke users)
- plan_name (monthly/yearly)
- price (harga subscription)
- status (active/expired/cancelled)
- starts_at (tanggal mulai)
- expires_at (tanggal berakhir)
- transaction_id (link ke transaction)
```

### Tabel `transactions`
```sql
- id (primary key)
- order_id (unique order ID)
- user_id (foreign key ke users)
- transaction_id (dari Midtrans)
- gross_amount (jumlah pembayaran)
- payment_type (metode pembayaran)
- transaction_status (status transaksi)
- midtrans_response (response dari Midtrans)
```

---

## 🔄 Flow Sistem

### 1. Chat Gratis (Pesan 1-3)
```
Pasien → Pilih Dokter → Mulai Chat → Counter: 3/3, 2/3, 1/3
```

### 2. Limit Tercapai (Pesan ke-4)
```
Pasien → Coba Kirim Pesan → Popup Pembayaran Muncul
```

### 3. Proses Pembayaran
```
Pilih Paket → Midtrans Payment → Success → Subscription Aktif → Chat Unlimited
```

### 4. End Session (Dokter)
```
Dokter → Klik "End Session" → Session Berakhir → Reset Counter
```

---

## 🛠️ Implementasi Teknis

### 1. JavaScript Integration

**File:** `public/js/chatify/custom-chatify.js`

**Fungsi Utama:**
- `loadChatSession()` - Load session saat buka chat
- `updateMessageCounter()` - Update counter pesan
- `blockMessageInput()` - Block input saat limit
- `showPaymentModal()` - Tampilkan popup pembayaran

### 2. Backend Controllers

**PaymentController:**
- Handle pembayaran subscription
- Aktivasi subscription otomatis
- Webhook Midtrans

**CustomChatifyController:**
- Manage chat sessions
- Increment message count
- End session (dokter only)

**SubscriptionController:**
- Manage subscription plans
- Aktivasi/deaktivasi subscription
- History subscription

### 3. Models & Relationships

**User Model:**
```php
// Cek subscription aktif
$user->hasActiveSubscription()

// Sisa chat gratis
$user->getRemainingFreeChats()

// Bisa chat atau tidak
$user->canChat()
```

**ChatSession Model:**
```php
// Cek limit tercapai
$session->hasReachedLimit()

// Increment counter
$session->incrementMessageCount()

// End session
$session->endSession()
```

---

## 🎨 UI/UX Components

### 1. Payment Modal
- **Lokasi:** `resources/views/components/payment-modal.blade.php`
- **Fitur:** Pilih paket, form pembayaran, integrasi Midtrans
- **Style:** Bootstrap 5, responsive design

### 2. Message Counter
- **Tampil:** Di atas input chat (pasien only)
- **Info:** Sisa pesan gratis, status limit
- **Action:** Tombol upgrade premium

### 3. End Session Button
- **Tampil:** Floating button (dokter only)
- **Fungsi:** Mengakhiri sesi chat
- **Konfirmasi:** SweetAlert2

---

## 📱 Testing & Demo

### 1. Demo Pages
```
/demo/payment-modal - Test payment modal
/demo/chat-payment - Test chat dengan payment
```

### 2. Test Scenarios

**Scenario 1: Chat Gratis**
1. Login sebagai pasien
2. Pilih dokter
3. Kirim 3 pesan
4. Lihat counter berkurang

**Scenario 2: Limit Tercapai**
1. Lanjut dari scenario 1
2. Coba kirim pesan ke-4
3. Popup pembayaran muncul
4. Input chat ter-block

**Scenario 3: Subscription**
1. Pilih paket di popup
2. Bayar via Midtrans (gunakan test card)
3. Redirect ke success page
4. Chat menjadi unlimited

**Scenario 4: End Session**
1. Login sebagai dokter
2. Chat dengan pasien
3. Klik "End Session"
4. Session berakhir, counter reset

---

## 🔧 Konfigurasi

### 1. Environment Variables
```env
# Midtrans
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

# Chat Settings
CHATIFY_NAME="Klinik Chat"
```

### 2. Subscription Plans
```php
// Monthly: Rp 50.000 / bulan
// Yearly: Rp 500.000 / tahun (hemat Rp 100.000)
```

### 3. Free Chat Limit
```php
// Default: 3 pesan gratis per dokter
// Configurable di User model
```

---

## 🚀 Deployment Checklist

### Development
- [ ] Install Midtrans SDK: `composer require midtrans/midtrans-php`
- [ ] Run migrations: `php artisan migrate`
- [ ] Publish Chatify views: `php artisan vendor:publish --tag=chatify-views`
- [ ] Setup Midtrans sandbox keys
- [ ] Test dengan test cards

### Production
- [ ] Update Midtrans production keys
- [ ] Update Snap.js URL ke production
- [ ] Setup webhook URL di Midtrans dashboard
- [ ] Test payment flow
- [ ] Monitor transactions

---

## 🐛 Troubleshooting

### Payment Modal Tidak Muncul
**Solusi:**
1. Cek console browser untuk error JavaScript
2. Pastikan Bootstrap 5 ter-load
3. Cek function `showPaymentModal()` tersedia

### Counter Tidak Update
**Solusi:**
1. Cek AJAX request ke `/incrementMessageCount`
2. Pastikan CSRF token valid
3. Cek response dari server

### Subscription Tidak Aktif
**Solusi:**
1. Cek status transaksi di database
2. Pastikan webhook Midtrans berjalan
3. Cek method `activateSubscription()`

### Chat Masih Ter-block
**Solusi:**
1. Refresh halaman chat
2. Cek subscription status di database
3. Clear browser cache

---

## 📊 Monitoring & Analytics

### Key Metrics
- **Conversion Rate:** Free users → Paid subscribers
- **ARPU:** Average Revenue Per User
- **Churn Rate:** Subscription cancellation rate
- **Usage:** Messages per session

### Database Queries
```sql
-- Total subscribers aktif
SELECT COUNT(*) FROM subscriptions WHERE status = 'active' AND expires_at > NOW();

-- Revenue bulanan
SELECT SUM(price) FROM subscriptions WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH);

-- Top dokter berdasarkan chat sessions
SELECT doctor_id, COUNT(*) as sessions FROM chat_sessions GROUP BY doctor_id ORDER BY sessions DESC;
```

---

## 🔮 Future Enhancements

### Planned Features
- [ ] **Video Call Premium** - Video consultation untuk subscriber
- [ ] **Chat History Export** - Download riwayat chat
- [ ] **Reminder System** - Notifikasi untuk renewal subscription
- [ ] **Referral Program** - Bonus untuk referral
- [ ] **Family Plan** - Subscription untuk keluarga
- [ ] **Corporate Plan** - Paket untuk perusahaan

### Technical Improvements
- [ ] **Real-time Notifications** - WebSocket untuk notifikasi real-time
- [ ] **Chat Analytics** - Dashboard analytics untuk dokter
- [ ] **Auto-renewal** - Perpanjangan otomatis subscription
- [ ] **Promo Codes** - Sistem kode promo dan diskon

---

## 📞 Support

### Documentation
- **Midtrans Docs:** https://docs.midtrans.com
- **Chatify Docs:** https://github.com/munafio/chatify
- **Laravel Docs:** https://laravel.com/docs

### Contact
- **Developer:** [Your Name]
- **Email:** [your-email@domain.com]
- **Project Repository:** [GitHub URL]

---

**🎉 Sistem Chat dengan Pembayaran siap digunakan!**

*Pastikan untuk testing menyeluruh di sandbox sebelum go-live production.*