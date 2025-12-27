<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
<script >
    // Gloabl Chatify variables from PHP to JS
    window.chatify = {
        name: "{{ config('chatify.name') }}",
        sounds: {!! json_encode(config('chatify.sounds')) !!},
        allowedImages: {!! json_encode(config('chatify.attachments.allowed_images')) !!},
        allowedFiles: {!! json_encode(config('chatify.attachments.allowed_files')) !!},
        maxUploadSize: {{ Chatify::getMaxUploadSize() }},
        pusher: {!! json_encode(config('chatify.pusher')) !!},
        pusherAuthEndpoint: '{{route("pusher.auth")}}'
    };
    window.chatify.allAllowedExtensions = chatify.allowedImages.concat(chatify.allowedFiles);
</script>
<script src="{{ asset('js/chatify/utils.js') }}"></script>
<script src="{{ asset('js/chatify/code.js') }}"></script>
<script src="{{ asset('js/chatify/custom-chatify.js') }}"></script>

{{-- Payment Modal JS for Premium Features --}}
@if(Auth::user()->role === 'pasien')
    <script src="{{ asset('js/payment-modal.js') }}"></script>
    <script>
        // Debug payment modal availability
        console.log('Payment modal script loaded');
        console.log('showPaymentModal function:', typeof showPaymentModal);
        console.log('paymentModal element:', document.getElementById('paymentModal'));
        console.log('Bootstrap available:', typeof bootstrap);
        
        // Test function to manually trigger modal
        window.testPaymentModal = function() {
            console.log('Testing payment modal...');
            if (typeof showPaymentModal === 'function') {
                showPaymentModal();
            } else {
                console.log('showPaymentModal not available, trying direct Bootstrap');
                const modalEl = document.getElementById('paymentModal');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                } else {
                    console.log('Modal element or Bootstrap not available');
                }
            }
        };
    </script>
@endif
<script>
// Auto-refresh contact list every 10 seconds
// This ensures new messages appear in the list even if Pusher fails
setInterval(function() {
    // Only refresh if we're on the main chatify page (not in a conversation)
    const currentUrl = window.location.pathname;
    
    // Refresh contact list
    if (typeof getContacts === 'function') {
        getContacts();
    }
}, 10000); // 10 seconds

// Force refresh contact list when page becomes visible
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && typeof getContacts === 'function') {
        getContacts();
    }
});
</script>
