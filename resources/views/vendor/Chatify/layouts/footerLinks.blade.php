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
<script src="{{ asset('js/chatify/custom-contacts.js') }}"></script>
<script src="{{ asset('js/chatify/custom-info-sidebar.js') }}"></script>
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
