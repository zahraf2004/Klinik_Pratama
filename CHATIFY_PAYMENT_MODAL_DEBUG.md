# Chatify Payment Modal Debug Guide

## Problem
Payment modal tidak muncul saat token habis di Chatify.

## Debug Steps Added

### 1. Enhanced Logging
Added comprehensive console logging untuk track:
- `sendMessage` function interception
- Form submit event handling
- Permission check AJAX calls
- `showPaymentModal` function availability
- `paymentModal` element existence  
- `Bootstrap` library availability
- Retry attempts dan error messages

### 2. Multiple Interceptor Approaches
Implemented 3 different interception methods:

#### Method A: sendMessage Function Override
```javascript
window.sendMessage = function() {
    // Check permission before calling original
}
```

#### Method B: Form Submit Event
```javascript
$(document).on('submit', '#message-form', function(e) {
    // Intercept form submission
});
```

#### Method C: Send Button Click
```javascript
$(document).on('click', '.send-button', function(e) {
    // Intercept button clicks
});
```

### 2. Multiple Fallback Methods
Implemented 5-level fallback system:

#### Method 1: Existing Function
```javascript
if (typeof showPaymentModal === 'function') {
    showPaymentModal();
}
```

#### Method 2: Direct Bootstrap 5
```javascript
const modal = new bootstrap.Modal(modalEl);
modal.show();
```

#### Method 3: jQuery Modal (Bootstrap 4)
```javascript
$('#paymentModal').modal('show');
```

#### Method 4: Retry Mechanism
- Max 3 retries dengan delay 500ms
- Handle timing issues dengan script loading

#### Method 5: SweetAlert2 Fallback
- Backup jika semua method gagal
- Redirect ke `/subscription` page

### 3. Test Function
Added `testPaymentModal()` function untuk manual testing:
```javascript
// Run in browser console
testPaymentModal();
```

## How to Debug

### 1. Check Browser Console
Buka Developer Tools → Console, lalu coba kirim pesan saat token habis:

**Expected Logs:**
```
Attempting to show payment modal, retry: 0
showPaymentModal function available: function
paymentModal element exists: true
Bootstrap available: object
Using showPaymentModal() function
```

**Problem Indicators:**
```
showPaymentModal function available: undefined
paymentModal element exists: false
Bootstrap available: undefined
```

### 2. Manual Test
Run di console browser:
```javascript
// Test 1: Check elements
console.log('Modal element:', document.getElementById('paymentModal'));
console.log('Bootstrap:', typeof bootstrap);
console.log('showPaymentModal:', typeof showPaymentModal);

// Test 2: Manual trigger
testPaymentModal();

// Test 3: Direct Bootstrap
const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
modal.show();
```

### 3. Check Network Tab
Pastikan files ter-load:
- ✅ `/js/payment-modal.js` (200 OK)
- ✅ `/js/app.js` (200 OK) 
- ✅ Bootstrap CSS/JS dari app.js

## Common Issues & Solutions

### Issue 1: showPaymentModal undefined
**Symptoms**: `showPaymentModal function available: undefined`
**Causes**:
- `payment-modal.js` tidak ter-load
- Script loading order salah
- JavaScript error di payment-modal.js

**Solutions**:
```bash
# Check file exists
ls public/js/payment-modal.js

# Check for JS errors
# Open browser console, look for red errors

# Clear cache
php artisan cache:clear
```

### Issue 2: paymentModal element not found
**Symptoms**: `paymentModal element exists: false`
**Causes**:
- Modal tidak di-include di Chatify page
- User bukan pasien (modal hanya untuk pasien)
- Template rendering error

**Solutions**:
```php
// Check in app.blade.php
@if(Auth::user()->role === 'pasien')
    @include('components.payment-modal')
@endif

// Verify user role
Auth::user()->role === 'pasien'
```

### Issue 3: Bootstrap undefined
**Symptoms**: `Bootstrap available: undefined`
**Causes**:
- Bootstrap tidak ter-load dari app.js
- Version conflict (Bootstrap 4 vs 5)
- app.js tidak ter-load

**Solutions**:
```bash
# Rebuild assets
npm run dev
# or
npm run build

# Check app.js includes Bootstrap
cat public/js/app.js | grep -i bootstrap
```

### Issue 4: Modal shows but no content
**Symptoms**: Modal muncul tapi kosong atau error
**Causes**:
- Payment modal component error
- Missing Midtrans configuration
- Database connection issue

**Solutions**:
```php
// Check component exists
ls resources/views/components/payment-modal.blade.php

// Check Midtrans config
php artisan config:show midtrans

// Check logs
tail -f storage/logs/laravel.log
```

### Issue 5: Script loading timing
**Symptoms**: Works sometimes, fails other times
**Causes**:
- Race condition dengan script loading
- DOM not ready saat function dipanggil
- Network latency issues

**Solutions**:
- ✅ Retry mechanism (already implemented)
- ✅ Multiple fallback methods
- ✅ Delay before retry

## Verification Checklist

### Frontend Checklist
- [ ] `payment-modal.js` ter-load tanpa error
- [ ] `showPaymentModal` function tersedia
- [ ] `paymentModal` element exists di DOM
- [ ] Bootstrap library ter-load
- [ ] No JavaScript errors di console

### Backend Checklist  
- [ ] User role = 'pasien'
- [ ] Payment modal component exists
- [ ] Midtrans configuration valid
- [ ] No server errors di logs

### Network Checklist
- [ ] All JS files load (200 OK)
- [ ] No CORS errors
- [ ] No 404 errors untuk assets
- [ ] Bootstrap CSS/JS ter-load

## Quick Fixes

### Fix 1: Force Include Bootstrap
```html
<!-- Add to headLinks.blade.php if needed -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
```

### Fix 2: Manual Modal Trigger
```javascript
// Add to custom-chatify.js as backup
function forceShowPaymentModal() {
    const modalHtml = `
        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Upgrade ke Premium</h5>
                    </div>
                    <div class="modal-body">
                        <p>Token chat habis! <a href="/subscription">Upgrade sekarang</a></p>
                    </div>
                </div>
            </div>
        </div>
    `;
    $('body').append(modalHtml);
}
```

### Fix 3: Direct Redirect
```javascript
// Simplest fallback
if (allMethodsFail) {
    window.location.href = '/subscription';
}
```

## Expected Behavior After Fix

### Success Flow
1. **Token habis** → `showPaymentModalWithRetry()` called
2. **Method 1 success** → `showPaymentModal()` works
3. **Modal appears** → "Upgrade ke Premium" dengan pricing
4. **User selects plan** → Midtrans payment flow
5. **Payment success** → Unlimited chat enabled

### Fallback Flow  
1. **Token habis** → `showPaymentModalWithRetry()` called
2. **Method 1-3 fail** → Retry 3x dengan delay
3. **All methods fail** → SweetAlert2 popup
4. **User clicks "Upgrade"** → Redirect ke `/subscription`
5. **Manual subscription** → Return to chat

## Monitoring

### Success Metrics
- Modal appears within 1 second
- No console errors
- Payment flow completes
- User can chat after payment

### Error Metrics  
- Fallback to SweetAlert2 rate
- Console error frequency
- Payment abandonment rate
- User complaints about modal