// Konsultasi Search Enhancement
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    
    if (!searchForm || !searchInput) return;
    
    // Auto-focus search input jika ada query
    if (searchInput.value) {
        searchInput.focus();
        searchInput.select();
    }
    
    // Highlight search term in results
    const searchTerm = searchInput.value.trim();
    if (searchTerm) {
        highlightSearchTerm(searchTerm);
    }
    
    // Clear button functionality
    const clearBtn = document.querySelector('.konsul-btn-clear');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchInput.value = '';
            searchForm.submit();
        });
    }
    
    // Prevent empty search
    searchForm.addEventListener('submit', function(e) {
        const value = searchInput.value.trim();
        if (!value) {
            e.preventDefault();
            // If empty, redirect to show all
            window.location.href = searchForm.action;
        }
    });
    
    // Add loading state on submit
    searchForm.addEventListener('submit', function() {
        const submitBtn = searchForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
            submitBtn.disabled = true;
        }
    });
});

// Function to highlight search term
function highlightSearchTerm(term) {
    const doctorCards = document.querySelectorAll('.konsul-doctor-card');
    
    doctorCards.forEach(card => {
        const nameElement = card.querySelector('.konsul-doctor-name');
        const strElement = card.querySelector('.konsul-info-text p');
        
        if (nameElement) {
            highlightText(nameElement, term);
        }
        
        if (strElement) {
            highlightText(strElement, term);
        }
    });
}

// Function to highlight text
function highlightText(element, term) {
    const text = element.textContent;
    const regex = new RegExp(`(${escapeRegex(term)})`, 'gi');
    
    if (regex.test(text)) {
        const highlightedText = text.replace(regex, '<mark style="background-color: #fff59d; padding: 2px 4px; border-radius: 3px;">$1</mark>');
        element.innerHTML = highlightedText;
    }
}

// Escape special regex characters
function escapeRegex(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// Add keyboard shortcut (Ctrl+K or Cmd+K) to focus search
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
});
