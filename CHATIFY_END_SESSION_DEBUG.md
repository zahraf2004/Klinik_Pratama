# Chatify End Session Debug Guide

## Problem
End session tidak berfungsi - muncul error "Active session not found"

## Root Cause Found
Log menunjukkan: `Active session not found {"patient_id":"6","doctor_id":"7"}`

**Issue**: Session tidak dibuat otomatis saat chat dimulai jika dokter yang memulai conversation.

## Solution Implemented

### Auto-Create Session on End
Enhanced `endSession` method to handle missing sessions:

1. **Check for active session** (original logic)
2. **If no session found**: Check if there are chat messages between doctor-patient
3. **If messages exist**: Auto-create session then immediately end it
4. **If no messages**: Return "No active conversation found"

This handles the case where:
- Doctor starts conversation with patient
- Messages are exchanged via Chatify
- But session was never created via `getOrCreateSession`
- Doctor tries to end session

### Enhanced Logic Flow
```php
// 1. Look for active session
$session = ChatSession::where(...)->first();

if ($session) {
    // Normal flow: end existing session
    $session->endSession();
} else {
    // New flow: check for messages
    $hasMessages = DB::table('ch_messages')->where(...)->exists();
    
    if ($hasMessages) {
        // Create session and immediately end it
        $session = ChatSession::create([...]);
        $session->endSession();
    } else {
        // No conversation exists
        return error('No active conversation found');
    }
}
```

## Debug Steps Added

### 1. JavaScript Debug Logging
Added console logging untuk check:
- `patientId` value
- `url` variable 
- `csrfToken` variable
- Full URL yang akan dipanggil

### 2. Variable Initialization
Added fallback untuk ensure `url` dan `csrfToken` tersedia:
```javascript
if (typeof url === 'undefined') {
    window.url = $('meta[name="url"]').attr('content');
}
if (typeof csrfToken === 'undefined') {
    window.csrfToken = $('meta[name="csrf-token"]').attr('content');
}
```

### 3. Controller Error Logging
Added comprehensive logging di `endSession` method:
- Request parameters
- Current user info
- Session lookup
- Success/error states
- Exception handling

## How to Debug

### 1. Check Browser Console
Buka Developer Tools → Console, lalu klik "Sesi Selesai":
```
endChatSession called with patientId: 123
url variable: http://localhost:8000/chatify
csrfToken variable: abc123...
Full URL will be: http://localhost:8000/chatify/endSession
```

### 2. Check Laravel Logs
Buka `storage/logs/laravel.log` untuk melihat:
```
[2024-12-16 10:00:00] local.INFO: EndSession called {"request":{"patient_id":"123","_token":"abc123"}}
[2024-12-16 10:00:00] local.INFO: Current user {"user_id":1,"role":"dokter"}
[2024-12-16 10:00:00] local.INFO: Looking for session {"patient_id":"123","doctor_id":"1"}
```

### 3. Common Issues & Solutions

#### Issue 1: URL undefined
**Symptom**: Console shows `url variable: undefined`
**Solution**: Meta tag tidak ter-load, pastikan headLinks.blade.php ter-include

#### Issue 2: CSRF Token undefined  
**Symptom**: Console shows `csrfToken variable: undefined`
**Solution**: CSRF meta tag tidak ada, check headLinks.blade.php

#### Issue 3: Patient ID null
**Symptom**: Console shows `patientId: null`
**Solution**: Tidak ada conversation yang aktif, pilih pasien dulu

#### Issue 4: Session not found
**Symptom**: Log shows "Active session not found"
**Solution**: 
- Check apakah ada session aktif di database
- Pastikan patient_id dan doctor_id match
- Verify `is_active = true`

#### Issue 5: Role permission
**Symptom**: Log shows "Non-doctor tried to end session"
**Solution**: User bukan dokter, check role di database

#### Issue 6: Server error
**Symptom**: Log shows exception trace
**Solution**: Check specific error message dan trace

## Database Checks

### Check Active Sessions
```sql
SELECT * FROM chat_sessions 
WHERE is_active = 1 
AND doctor_id = [DOCTOR_ID] 
AND patient_id = [PATIENT_ID];
```

### Check User Roles
```sql
SELECT id, name, role FROM users 
WHERE id IN ([DOCTOR_ID], [PATIENT_ID]);
```

### Check Completed Sessions
```sql
SELECT COUNT(*) as completed_sessions 
FROM chat_sessions 
WHERE patient_id = [PATIENT_ID] 
AND is_active = 0 
AND is_premium = 0;
```

## Network Debug

### Check AJAX Request
Di Network tab browser, pastikan:
- URL: `POST /chatify/endSession`
- Status: 200 OK (bukan 404, 500, etc)
- Response: JSON dengan `success: true/false`

### Common HTTP Errors
- **404**: Route tidak ditemukan
- **403**: Permission denied (bukan dokter)
- **422**: Validation error (missing patient_id)
- **500**: Server error (check logs)

## Quick Fix Checklist

1. ✅ Route `/chatify/endSession` exists in web.php
2. ✅ Controller method `endSession` exists
3. ✅ User role = 'dokter'
4. ✅ Active session exists in database
5. ✅ JavaScript variables defined
6. ✅ CSRF token valid
7. ✅ Network request successful

## Test Commands

### Artisan Commands
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Check routes
php artisan route:list | grep endSession

# Check logs
tail -f storage/logs/laravel.log
```

### Browser Console Test
```javascript
// Test AJAX manually
$.ajax({
    url: '/chatify/endSession',
    method: 'POST',
    data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        patient_id: 123
    },
    success: function(data) { console.log('Success:', data); },
    error: function(xhr) { console.log('Error:', xhr.responseText); }
});
```