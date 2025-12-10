# 🔗 Midtrans Webhook Testing Guide

## 📋 Overview

Webhook adalah cara Midtrans memberitahu aplikasi kita tentang perubahan status pembayaran secara real-time. Ini lebih reliable daripada callback karena:

- ✅ **Signature verification** untuk keamanan
- ✅ **Retry mechanism** jika gagal
- ✅ **Independent dari user browser**
- ✅ **Real-time notification**

---

## 🛠️ Setup Webhook

### 1. URL Webhook
```
Production: https://your-domain.com/payment/webhook
Development: https://your-ngrok-url.ngrok.io/payment/webhook
```

### 2. Konfigurasi di Midtrans Dashboard
1. Login ke dashboard Midtrans
2. Pergi ke **Settings** → **Configuration**
3. Set **Payment Notification URL**: `https://your-domain.com/payment/webhook`
4. **Save** konfigurasi

### 3. CSRF Protection
Webhook sudah dikecualikan dari CSRF protection di `bootstrap/app.php`:
```php
$middleware->validateCsrfTokens(except: [
    'payment/webhook',
]);
```

---

## 🧪 Testing Webhook

### 1. Local Development dengan ngrok

**Install ngrok:**
```bash
# Download dari https://ngrok.com/
# Atau via npm
npm install -g ngrok
```

**Expose local server:**
```bash
# Jalankan Laravel
php artisan serve

# Di terminal lain, expose ke internet
ngrok http 8000
```

**Update webhook URL:**
- Copy URL ngrok (contoh: `https://abc123.ngrok.io`)
- Update di Midtrans dashboard: `https://abc123.ngrok.io/payment/webhook`

### 2. Manual Webhook Testing

**Buat test webhook dengan curl:**
```bash
curl -X POST https://your-domain.com/payment/webhook \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "ORDER-1234567890-ABC123",
    "status_code": "200",
    "gross_amount": "50000.00",
    "signature_key": "calculated_signature_here",
    "transaction_status": "settlement",
    "transaction_id": "test-transaction-123",
    "payment_type": "credit_card",
    "fraud_status": "accept"
  }'
```

### 3. Webhook Simulator

**Buat route untuk simulate webhook (development only):**
```php
// Tambah di routes/web.php (hanya untuk development)
Route::get('/test-webhook/{orderId}', function($orderId) {
    $serverKey = config('midtrans.server_key');
    $statusCode = '200';
    $grossAmount = '50000.00';
    $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
    
    $webhookData = [
        'order_id' => $orderId,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'signature_key' => $signature,
        'transaction_status' => 'settlement',
        'transaction_id' => 'test-' . time(),
        'payment_type' => 'credit_card',
        'fraud_status' => 'accept'
    ];
    
    $response = Http::post(url('/payment/webhook'), $webhookData);
    
    return response()->json([
        'webhook_data' => $webhookData,
        'response' => $response->json()
    ]);
});
```

---

## 🔍 Monitoring Webhook

### 1. Log Files
Webhook activity ter-log di `storage/logs/laravel.log`:
```
[2024-12-10 10:30:15] local.INFO: Midtrans Webhook Received {"order_id":"ORDER-123","transaction_status":"settlement"}
[2024-12-10 10:30:15] local.INFO: Payment successful {"order_id":"ORDER-123"}
```

### 2. Database Monitoring
Cek tabel `transactions` untuk melihat update status:
```sql
SELECT order_id, transaction_status, payment_type, updated_at 
FROM transactions 
ORDER BY updated_at DESC 
LIMIT 10;
```

### 3. Webhook Response Codes
- **200**: Webhook berhasil diproses
- **401**: Invalid signature
- **404**: Transaction tidak ditemukan
- **500**: Server error

---

## 🚨 Troubleshooting

### Webhook Tidak Diterima
**Kemungkinan Penyebab:**
1. URL webhook salah di dashboard Midtrans
2. Server tidak accessible dari internet
3. CSRF protection masih aktif
4. Firewall blocking request

**Solusi:**
```bash
# Cek apakah webhook URL accessible
curl -I https://your-domain.com/payment/webhook

# Cek log Laravel
tail -f storage/logs/laravel.log

# Test dengan ngrok untuk development
ngrok http 8000
```

### Invalid Signature Error
**Penyebab:** Server key tidak match atau format signature salah

**Solusi:**
```php
// Cek server key di .env
MIDTRANS_SERVER_KEY=your_correct_server_key

// Debug signature calculation
$calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
Log::info('Signature Debug', [
    'received' => $request->signature_key,
    'calculated' => $calculatedSignature,
    'server_key' => substr($serverKey, 0, 10) . '...'
]);
```

### Transaction Not Found
**Penyebab:** Order ID tidak ada di database

**Solusi:**
```php
// Cek apakah transaction ada
$transaction = Transaction::where('order_id', $orderId)->first();
if (!$transaction) {
    Log::error('Transaction not found', ['order_id' => $orderId]);
    // Buat transaction baru atau handle error
}
```

---

## 📊 Webhook Security

### 1. Signature Verification
Selalu verifikasi signature untuk memastikan webhook dari Midtrans:
```php
$serverKey = config('midtrans.server_key');
$calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

if ($calculatedSignature !== $request->signature_key) {
    return response()->json(['message' => 'Invalid signature'], 401);
}
```

### 2. IP Whitelist (Optional)
Untuk keamanan extra, whitelist IP Midtrans:
```php
$allowedIPs = [
    '103.208.23.0/24',
    '103.208.23.6',
    // IP ranges Midtrans lainnya
];

if (!in_array($request->ip(), $allowedIPs)) {
    return response()->json(['message' => 'Unauthorized IP'], 403);
}
```

### 3. Rate Limiting
Implementasi rate limiting untuk webhook:
```php
// Di routes/web.php
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
    ->middleware('throttle:60,1'); // 60 requests per minute
```

---

## 🔄 Webhook Flow Diagram

```
Midtrans → Webhook URL → Signature Check → Update Database → Response 200
    ↓
If Failed → Retry (up to 5 times) → Mark as Failed
    ↓
Success → Activate Subscription → Send Notification
```

---

## 📝 Best Practices

### 1. Idempotency
Pastikan webhook bisa dijalankan multiple times tanpa side effect:
```php
// Cek apakah sudah diproses sebelumnya
if ($transaction->transaction_status === 'settlement') {
    return response()->json(['message' => 'Already processed'], 200);
}
```

### 2. Async Processing
Untuk webhook yang kompleks, gunakan queue:
```php
// Dispatch job untuk processing
ProcessPaymentWebhook::dispatch($webhookData);
return response()->json(['message' => 'Queued for processing'], 200);
```

### 3. Monitoring & Alerting
Setup monitoring untuk webhook failures:
```php
// Alert jika webhook gagal
if ($webhookFailed) {
    Mail::to('admin@domain.com')->send(new WebhookFailedAlert($orderId));
}
```

---

## ✅ Checklist Testing

- [ ] Webhook URL accessible dari internet
- [ ] Signature verification working
- [ ] Transaction status update correctly
- [ ] Subscription activation working
- [ ] Error handling proper
- [ ] Logging comprehensive
- [ ] Response codes correct
- [ ] Security measures implemented

---

**🎯 Webhook siap untuk production!**

*Pastikan semua test case passed sebelum go-live.*