/**
 * Custom Info Sidebar Behavior
 * - Sembunyikan info sidebar saat pertama load
 * - Buka info sidebar saat klik avatar/nama (seperti WhatsApp)
 */

// Wait for code.js to load first
setTimeout(function() {
    // Sembunyikan info sidebar saat pertama load
    $('.messenger-infoView').hide();
    
    // Override default behavior - remove old event handlers
    $(".messenger-infoView nav a, .show-infoSide").off('click');
    
    // Handle klik pada avatar dan nama user di header
    $(document).on('click', '.header-avatar.show-infoSide, .user-name.show-infoSide', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Toggle info sidebar
        $('.messenger-infoView').toggle();
    });
    
    // Handle klik tombol info (icon i) - tetap berfungsi
    $(document).on('click', '.show-infoSide:not(.header-avatar):not(.user-name)', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Toggle info sidebar
        $('.messenger-infoView').toggle();
    });
    
    // Handle klik tombol close di info sidebar
    $(document).on('click', '.messenger-infoView nav a', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.messenger-infoView').hide();
    });
    
    console.log('Custom info sidebar behavior loaded');
}, 500); // Wait 500ms for code.js to initialize
