# Chatify Token Blocking System

## Problem Solved
Setelah 3 session selesai (token habis), pasien masih bisa mengirim pesan dan memulai chat baru.

## Solution Implemented

### 1. Message Blocking Logic
**Frontend Interceptor** di `sendMessage()`:
- Check permission sebelum kirim pesan
- Block message jika token habis
- Show upgrade popup dengan redirect ke subscription

### 2. Permission Check Endpoint
**New Route**: `POST /chatify/checkChatPermission`
**Controller**: `CustomChatifyController@checkChatPermission`

**Logic**:
- Dokter: Selalu bisa chat
- Pasien: Check `canChat()` method
- Return: `can_chat`, `remaining_tokens`, `has_active_subscription`

### 3. Input Field Blocking
**Function**: `blockMessageInput()`
- Disable input field dan send button
- Change placeholder ke "Upgrade ke premium untuk melanjutkan chat"
- Show warning banner dengan link upgrade
- Visual indicator (CSS class)

### 4. Auto Token Check
**Trigger**: Saat pilih contact (pasien)
**Function**: `checkTokenStatus()`
- Auto-check token status
- Block input jika token habis
- Prevent typing sebelum user coba kirim pesan

## User Flow

### Normal Flow (Token Available)
1. **Pasien pilih dokter** → Check token status
2. **Token > 0** → Input enabled, bisa chat normal
3. **Kirim pesan** → Check permission → Allow message
4. **Dokter end session** → Token berkurang

### Blocked Flow (Token Habis)
1. **Pasien pilih dokter** → Check token status  
2. **Token = 0** → Input disabled + warning banner
3. **Coba kirim pesan** → Popup upgrade modal
4. **Klik "Upgrade Sekarang"** → Redirect ke `/subscription`

## Technical Implementation

### Files Modified

#### 1. `app/Http/Controllers/CustomChatifyController.php`
```php
public function checkChatPermission(Request $request)
{
    $currentUser = Auth::user();
    
    // Dokter selalu bisa chat
    if ($currentUser->role === 'dokter') {
        return response()->json(['can_chat' => true]);
    }
    
    // Untuk pasien, cek token dan subscription
    $canChat = $currentUser->canChat();
    $remainingTokens = $currentUser->getRemainingSessionTokens();
    
    return response()->json([
        'can_chat' => $canChat,
        'remaining_tokens' => $remainingTokens,
        'message' => $canChat ? 'Can send messages' : 'No remaining tokens'
    ]);
}
```

#### 2. `app/Models/User.php`
```php
public function canSendMessage(): bool
{
    return $this->canChat();
}
```

#### 3. `routes/web.php`
```php
Route::post('/chatify/checkChatPermission', [CustomChatifyController::class, 'checkChatPermission']);
```

#### 4. `public/js/chatify/custom-chatify.js`
- **sendMessage interceptor**: Check permission before send
- **blockMessageInput()**: Disable input dan show warning
- **checkTokenStatus()**: Auto-check saat pilih contact
- **updateMessageCounter()**: Update counter display
- **Integration**: Uses existing `showPaymentModal()` function

#### 5. `resources/views/vendor/Chatify/layouts/footerLinks.blade.php`
- **Payment Modal JS**: Include payment-modal.js for patients
- **Modal Integration**: Ensure payment modal available in Chatify

#### 6. `resources/views/vendor/Chatify/pages/app.blade.php`
- **Payment Modal**: Already includes payment modal for patients
- **Existing System**: Reuses established payment flow

## UI/UX Features

### 1. Upgrade Modal (Existing System)
```javascript
// Uses existing payment modal from the system
showPaymentModal();
```

**Modal Features:**
- **Title**: "Upgrade ke Premium" 
- **Content**: "Chat Gratis Anda Sudah Habis!"
- **Plans**: Bulanan (Rp 50.000) & Tahunan (Rp 500.000)
- **Payment**: Integrated dengan Midtrans
- **Responsive**: Mobile-friendly design

### 2. Warning Banner
```html
<div id="tokenWarning">
    <i class="fas fa-lock"></i> 
    <strong>Upgrade ke Premium!</strong> 
    <a href="javascript:void(0)" onclick="showPaymentModal()">Upgrade ke premium</a> untuk chat unlimited.
</div>
```

### 3. Disabled Input
- Placeholder: "Upgrade ke premium untuk melanjutkan chat."
- Disabled send button dan attachment
- Visual CSS class untuk styling

## Security & Validation

### Backend Validation
- ✅ Role-based permission (dokter vs pasien)
- ✅ Token calculation dari database
- ✅ Subscription status check
- ✅ Proper error handling

### Frontend Validation  
- ✅ Permission check sebelum send message
- ✅ Auto-disable input saat token habis
- ✅ Visual feedback untuk user
- ✅ Graceful error handling

## Testing Scenarios

### Test 1: Normal User (Token Available)
1. Login sebagai pasien dengan token > 0
2. Pilih dokter → Input enabled
3. Kirim pesan → Berhasil terkirim
4. Dokter end session → Token berkurang

### Test 2: Blocked User (Token Habis)  
1. Login sebagai pasien dengan token = 0
2. Pilih dokter → Input disabled + warning
3. Coba kirim pesan → Popup upgrade
4. Klik "Upgrade" → Redirect ke subscription

### Test 3: Premium User
1. Login sebagai pasien premium
2. Pilih dokter → Input enabled (unlimited)
3. Kirim pesan → Selalu berhasil
4. No token limitation

### Test 4: Doctor User
1. Login sebagai dokter
2. Pilih pasien → Input selalu enabled
3. Kirim pesan → Selalu berhasil
4. No permission check

## Expected Behavior

### ✅ **After 3 Sessions Completed:**

#### **For Patients (0/3 tokens):**
- **Input field**: Disabled dengan placeholder warning
- **Send button**: Disabled
- **Warning banner**: Muncul dengan link upgrade
- **Try to send**: Popup upgrade modal
- **Auto-check**: Token status saat pilih contact

#### **For Doctors (patient has 0/3 tokens):**
- **End Session button**: Changes to gray "Token Habis"
- **Button disabled**: cursor: not-allowed, opacity: 0.7
- **Click disabled button**: Info popup "Token Pasien Habis"
- **Tooltip**: "Pasien sudah menggunakan semua token gratis (0/3)"
- **Try end session**: Better error message with context

### ✅ **Premium Users:**
- **No limitations**: Bisa chat unlimited
- **No blocking**: Input selalu enabled
- **No warnings**: Tidak ada banner atau popup

### ✅ **Doctors:**
- **Always enabled**: Tidak ada limitation
- **No permission check**: Langsung bisa kirim pesan
- **Professional UX**: Tidak terganggu sistem token

## Monitoring & Logs

### Success Logs
```
[INFO] Permission check: can_chat=false, remaining_tokens=0
[INFO] Message blocked for user_id=123, reason=no_tokens
[INFO] Upgrade popup shown to user_id=123
```

### Error Logs
```
[ERROR] Permission check failed for user_id=123
[WARNING] Token calculation error for patient_id=123
```

## Latest Improvements

### Smart End Session Button
- **Dynamic State**: Button changes based on patient token status
- **Visual Feedback**: Gray "Token Habis" when patient has 0 tokens
- **Prevent Errors**: Block action if no active session possible
- **Better UX**: Clear messaging for doctors about patient status

### Enhanced Error Handling
- **Contextual Messages**: Different error types for different scenarios
- **Token Status**: Show remaining tokens in error messages
- **User-Friendly**: Info popup instead of error for expected states

### Auto-Update System
- **Real-time Check**: Button state updates when selecting patients
- **Token Awareness**: Doctors see patient token status immediately
- **Prevent Confusion**: No more "session not found" errors

## Future Enhancements

1. **Real-time Updates**: WebSocket untuk update token real-time
2. **Grace Period**: Allow 1-2 pesan extra sebelum block
3. **Trial Extension**: Temporary token boost untuk new users
4. **Analytics**: Track conversion rate dari popup ke subscription
5. **Batch Operations**: End multiple sessions at once
6. **Session History**: Show completed sessions for each patient