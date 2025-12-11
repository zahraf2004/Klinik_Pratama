// JavaScript untuk memperbaiki dropdown navbar admin
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi dropdown Bootstrap
    if (typeof bootstrap !== 'undefined') {
        // Bootstrap 5
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    } else if (typeof $ !== 'undefined' && $.fn.dropdown) {
        // Bootstrap 4 dengan jQuery
        $('.dropdown-toggle').dropdown();
    }
    
    // Manual dropdown handler sebagai fallback
    const dropdownToggles = document.querySelectorAll('.nav-link-user');
    
    dropdownToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.dropdown');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            // Tutup semua dropdown lain
            document.querySelectorAll('.dropdown.show').forEach(function(openDropdown) {
                if (openDropdown !== dropdown) {
                    openDropdown.classList.remove('show');
                    openDropdown.querySelector('.dropdown-menu').classList.remove('show');
                }
            });
            
            // Toggle dropdown saat ini
            dropdown.classList.toggle('show');
            menu.classList.toggle('show');
            
            // Update aria-expanded
            const isExpanded = dropdown.classList.contains('show');
            toggle.setAttribute('aria-expanded', isExpanded);
        });
    });
    
    // Tutup dropdown ketika klik di luar
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown.show').forEach(function(dropdown) {
                dropdown.classList.remove('show');
                dropdown.querySelector('.dropdown-menu').classList.remove('show');
                const toggle = dropdown.querySelector('.dropdown-toggle');
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });
    
    // Prevent dropdown close ketika klik di dalam menu
    document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.dropdown.show').forEach(function(dropdown) {
                dropdown.classList.remove('show');
                dropdown.querySelector('.dropdown-menu').classList.remove('show');
                const toggle = dropdown.querySelector('.dropdown-toggle');
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.focus();
                }
            });
        }
    });
});

// jQuery fallback jika tersedia
if (typeof $ !== 'undefined') {
    $(document).ready(function() {
        // Pastikan dropdown Bootstrap berfungsi
        if ($.fn.dropdown) {
            $('.dropdown-toggle').dropdown();
        }
        
        // Debug: Log ketika dropdown diklik
        $('.nav-link-user').on('click', function(e) {
            console.log('Dropdown clicked');
            
            // Jika Bootstrap dropdown tidak bekerja, gunakan manual toggle
            if (!$.fn.dropdown) {
                e.preventDefault();
                e.stopPropagation();
                
                var $dropdown = $(this).closest('.dropdown');
                var $menu = $dropdown.find('.dropdown-menu');
                
                // Toggle dropdown
                $('.dropdown.show').not($dropdown).removeClass('show');
                $('.dropdown-menu.show').not($menu).removeClass('show');
                
                $dropdown.toggleClass('show');
                $menu.toggleClass('show');
            }
        });
        
        // Tutup dropdown ketika klik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown.show').removeClass('show');
                $('.dropdown-menu.show').removeClass('show');
            }
        });
    });
}