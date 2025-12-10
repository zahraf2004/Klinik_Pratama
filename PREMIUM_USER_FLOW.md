# 👑 Premium User Flow - Chat Unlimited System

## 📋 Overview

Dokumentasi lengkap untuk flow user premium dalam sistem chat dengan pembayaran berlangganan. Sistem ini membedakan antara **Free Users** (3 chat gratis) dan **Premium Users** (chat unlimited).

---

## 🎯 User Types & Privileges

### 🆓 Free Users
- ✅ **3 pesan gratis** per dokter
- ✅ **Chat counter** menunjukkan sisa pesan
- ✅ **Popup pembayaran** saat limit tercapai
- ❌ **Input ter-block** setelah 3 pesan

### 👑 Premium Users
- ✅ **Chat unlimited** dengan semua dokter
- ✅ **Premium badge** di interface
- ✅ **No message counter** (tidak ada limit)
- ✅ **Priority support** (future feature)

---

## 🔄 Premium User Flow

### 1. Subscription Activation
```
Payment Success → Webhook → Activate Subscription → Update Chat Sessions → Premium Status Active
```

### 2. Chat Session Handling
```
Premium User → Open Chat → Load Session → Check Subscription → Enable Unlimited Chat
```

### 3. Message Sending
```
Premium User → Type Message → Send → No Counter Check → Message Sent Successfully
```

### 4. Subscription Expiry
```
Subscription Expires → Cron Job → Deactivate Subscription → Revert to Free User
```

---

## 🛠️ Technical Implementation

### 1. Database Structure

**Subscription Status Check:**
```php
// User Model
public function hasActiveSubscription(): bool
{
    return Subscription::userHasActiveSubscription($this->id);
}

public function activeSubscription()
{
    return $this->subscriptions()->active()->first();
}
```

**Chat Session Premium Flag:**
```php
// ChatSession Model
'is_premium' => boolean // True jika user premium saat session dibuat
```

### 2. Backend Logic

**CustomChatifyController - getOrCreateSession:**
```php
// Cek subscription status
$hasActiveSubscription = $patient->hasActiveSubscription();

// Update session premium status
$session = ChatSession::create([
    'patient_id' => $patientId,
    'doctor_id' => $doctorId,
    'is_premium' => $hasActiveSubscription, // Set berdasarkan subscription
    'message_count' => 0,
    'is_active' => true,
]);
```

**Message Count Logic:**
```php
// Hanya increment untuk free users
if (!$session->is_premium) {
    $session->incrementMessageCount();
}
```

### 3. Frontend Logic

**JavaScript Premium Detection:**
```javascript
// Load session dengan premium check
if (data.session.is_premium) {
    showPremiumStatus(data.subscription);
    enableUnlimitedChat();
} else {
    updateMessageCounter(data.session);
    if (data.session.has_reached_limit) {
        blockMessageInput();
    }
}
```

**Premium Status Display:**
```javascript
function showPremiumStatus(subscription) {
    $('#premiumStatus').html(`
        <i class="fas fa-crown"></i> Status Premium Aktif
        - ${subscription.plan_name} (${subscription.days_remaining} hari tersisa)
    `);
}
```

---

## 🎨 UI/UX Differences

### Free User Interface:
```
┌─────────────────────────────────┐
│ 💬 Chat dengan Dr. Ahmad        │
├─────────────────────────────────┤
│ ⚠️  Sisa pesan gratis: 2/3      │
├─────────────────────────────────┤
│ [Chat Messages]                 │
├─────────────────────────────────┤
│ [Input: "Ketik pesan..."]  [📤] │
└─────────────────────────────────┘
```

### Premium User Interface:
```
┌─────────────────────────────────┐
│ 💬 Chat dengan Dr. Ahmad        │
├─────────────────────────────────┤
│ 👑 Status Premium Aktif - Bulanan│
│    (25 hari tersisa)            │
├─────────────────────────────────┤
│ [Chat Messages]                 │
├─────────────────────────────────┤
│ [Input: "Ketik pesan..."]  [📤] │
└─────────────────────────────────┘
```

### Limit Reached (Free User):
```
┌─────────────────────────────────┐
│ 💬 Chat dengan Dr. Ahmad        │
├─────────────────────────────────┤
│ 🔒 Limit pesan gratis tercapai! │
│    [👑 Upgrade Premium]         │
├─────────────────────────────────┤
│ [Chat Messages]                 │
├─────────────────────────────────┤
│ [Input: DISABLED] [📤 DISABLED] │
└─────────────────────────────────┘
```

---

## 📊 Subscription Management

### 1. Subscription Plans
```php
// Monthly Plan
'monthly' => [
    'price' => 50000,
    'duration' => 1, // months
    'features' => ['unlimited_chat', '24_7_support']
]

// Yearly Plan  
'yearly' => [
    'price' => 500000,
    'duration' => 12, // months
    'features' => ['unlimited_chat', '24_7_support', 'video_call']
]
```

### 2. Subscription Lifecycle
```php
// Activation (after payment success)
Subscription::create([
    'user_id' => $userId,
    'plan_name' => 'monthly',
    'status' => 'active',
    'starts_at' => now(),
    'expires_at' => now()->addMonths(1)
]);

// Update chat sessions to premium
ChatSession::where('patient_id', $userId)
    ->where('is_active', true)
    ->update(['is_premium' => true]);
```

### 3. Expiry Handling
```php
// Cron job untuk cek subscription expired
// Di app/Console/Kernel.php
$schedule->call(function () {
    Subscription::where('status', 'active')
        ->where('expires_at', '<=', now())
        ->update(['status' => 'expired']);
        
    // Revert premium sessions to free
    $expiredUsers = Subscription::where('status', 'expired')
        ->pluck('user_id');
        
    ChatSession::whereIn('patient_id', $expiredUsers)
        ->where('is_active', true)
        ->update(['is_premium' => false]);
})->daily();
```

---

## 🔄 State Transitions

### Free → Premium
```
1. User reaches 3 message limit
2. Payment modal appears
3. User selects plan & pays
4. Webhook activates subscription
5. Chat sessions updated to premium
6. UI switches to premium mode
7. Unlimited chat enabled
```

### Premium → Free (Expiry)
```
1. Subscription expires (cron job)
2. Subscription status → 'expired'
3. Chat sessions → is_premium = false
4. UI reverts to free mode
5. Message counter reappears
6. 3 message limit enforced
```

### Premium → Premium (Renewal)
```
1. User renews before expiry
2. New subscription created
3. Old subscription extended/replaced
4. Premium status maintained
5. No interruption in service
```

---

## 🧪 Testing Scenarios

### Scenario 1: Free User Upgrade
```bash
# 1. Login sebagai free user
# 2. Chat dengan dokter (kirim 3 pesan)
# 3. Coba kirim pesan ke-4 → popup muncul
# 4. Pilih paket & bayar
# 5. Verifikasi premium status aktif
# 6. Test unlimited chat
```

### Scenario 2: Premium User Experience
```bash
# 1. Login sebagai premium user
# 2. Buka chat dengan dokter
# 3. Verifikasi premium badge muncul
# 4. Kirim banyak pesan (>3)
# 5. Verifikasi tidak ada limit
# 6. Check subscription info
```

### Scenario 3: Subscription Expiry
```bash
# 1. Set subscription expires_at ke masa lalu
# 2. Jalankan cron job atau manual update
# 3. Login sebagai user
# 4. Verifikasi kembali ke free mode
# 5. Test 3 message limit
```

### Scenario 4: Multiple Doctors
```bash
# 1. Premium user chat dengan Dokter A
# 2. Kirim banyak pesan (unlimited)
# 3. Switch ke Dokter B
# 4. Verifikasi tetap unlimited
# 5. Check session premium status
```

---

## 🔍 Monitoring & Analytics

### Key Metrics untuk Premium Users:
```sql
-- Total premium users
SELECT COUNT(*) FROM subscriptions WHERE status = 'active';

-- Premium user engagement
SELECT 
    AVG(message_count) as avg_messages,
    COUNT(*) as total_sessions
FROM chat_sessions 
WHERE is_premium = true;

-- Conversion rate (free → premium)
SELECT 
    (SELECT COUNT(*) FROM subscriptions WHERE status = 'active') * 100.0 /
    (SELECT COUNT(*) FROM users WHERE role = 'pasien') as conversion_rate;

-- Revenue metrics
SELECT 
    plan_name,
    COUNT(*) as subscribers,
    SUM(price) as total_revenue
FROM subscriptions 
WHERE status = 'active'
GROUP BY plan_name;
```

### Premium User Behavior:
```sql
-- Messages per premium session
SELECT 
    doctor_id,
    AVG(message_count) as avg_messages,
    COUNT(*) as premium_sessions
FROM chat_sessions 
WHERE is_premium = true 
GROUP BY doctor_id;

-- Premium user retention
SELECT 
    DATEDIFF(expires_at, starts_at) as subscription_days,
    COUNT(*) as users
FROM subscriptions 
GROUP BY DATEDIFF(expires_at, starts_at);
```

---

## 🚨 Error Handling

### Common Issues & Solutions:

**1. Premium Status Not Updating**
```php
// Debug subscription status
$user = User::find($userId);
dd([
    'has_subscription' => $user->hasActiveSubscription(),
    'active_subscription' => $user->activeSubscription(),
    'chat_sessions' => $user->chatSessionsAsPatient()->where('is_active', true)->get()
]);
```

**2. Chat Session Not Premium**
```php
// Force update session to premium
ChatSession::where('patient_id', $userId)
    ->where('is_active', true)
    ->update(['is_premium' => true]);
```

**3. UI Not Updating**
```javascript
// Force reload chat session
if (typeof loadChatSession === 'function') {
    loadChatSession();
}

// Check premium status in console
console.log('Premium status:', data.session.is_premium);
```

---

## 🔮 Future Enhancements

### Premium Features Roadmap:
- [ ] **Video Call** - Video consultation untuk yearly subscribers
- [ ] **Priority Queue** - Faster doctor response untuk premium
- [ ] **Chat History Export** - Download riwayat chat
- [ ] **Multiple Device Sync** - Sync chat across devices
- [ ] **Advanced Analytics** - Health tracking dashboard
- [ ] **Family Plan** - Subscription untuk keluarga
- [ ] **Corporate Plan** - Bulk subscription untuk perusahaan

### Technical Improvements:
- [ ] **Real-time Premium Status** - WebSocket untuk update real-time
- [ ] **Auto-renewal** - Automatic subscription renewal
- [ ] **Promo Codes** - Discount system untuk premium
- [ ] **Referral Program** - Bonus untuk referral premium users

---

## ✅ Premium User Checklist

### Development:
- [ ] Subscription model & migration
- [ ] Premium status detection
- [ ] Chat session premium flag
- [ ] UI premium indicators
- [ ] Unlimited chat logic
- [ ] Subscription management pages

### Testing:
- [ ] Free to premium upgrade
- [ ] Premium user experience
- [ ] Subscription expiry handling
- [ ] Multiple doctor sessions
- [ ] Payment integration
- [ ] UI state transitions

### Production:
- [ ] Cron job untuk expiry check
- [ ] Monitoring dashboard
- [ ] Error logging
- [ ] Performance optimization
- [ ] Security audit
- [ ] User documentation

---

**🎉 Premium User System Ready!**

*Sistem premium memberikan pengalaman chat unlimited yang seamless untuk subscribers, dengan clear differentiation dari free users.*