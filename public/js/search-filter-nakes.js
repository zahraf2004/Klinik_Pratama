// JavaScript untuk search dan filter data tenaga kesehatan - hanya logic tanpa mengubah tampilan
$(document).ready(function() {
    // Search functionality
    $('#search-nakes').on('keyup', function() {
        performSearch();
    });
    
    $('#btn-search-nakes').on('click', function() {
        performSearch();
    });
    
    // Filter functionality
    $('#filter-role').on('change', function() {
        performSearch();
    });
    
    // Clear search when input is empty
    $('#search-nakes').on('input', function() {
        if ($(this).val() === '') {
            performSearch();
        }
    });
    
    function performSearch() {
        let searchTerm = $('#search-nakes').val().toLowerCase().trim();
        let filterRole = $('#filter-role').val();
        let visibleCount = 0;
        
        $('.tenaga-table tbody tr').each(function() {
            let row = $(this);
            let namaNakes = row.find('td:nth-child(3)').text().toLowerCase().trim();
            let role = row.find('td:nth-child(8)').text().trim();
            
            // Check search match (bisa search di nama atau email)
            let email = row.find('td:nth-child(4)').text().toLowerCase().trim();
            let matchSearch = searchTerm === '' || 
                            namaNakes.includes(searchTerm) || 
                            email.includes(searchTerm);
            
            // Check filter match
            let matchFilter = filterRole === '' || role === filterRole;
            
            if (matchSearch && matchFilter) {
                row.show();
                visibleCount++;
            } else {
                row.hide();
            }
        });
        
        // Update row numbers
        updateRowNumbers();
        
        // Show no results message if needed
        showNoResultsMessage(visibleCount);
    }
    
    function updateRowNumbers() {
        let visibleRows = $('.tenaga-table tbody tr:visible');
        visibleRows.each(function(index) {
            $(this).find('td:first-child').text(index + 1);
        });
    }
    
    function showNoResultsMessage(count) {
        let tbody = $('.tenaga-table tbody');
        let noResultsRow = tbody.find('.no-results-row');
        
        if (count === 0) {
            if (noResultsRow.length === 0) {
                let colCount = $('.tenaga-table thead tr th').length;
                tbody.append(`
                    <tr class="no-results-row">
                        <td colspan="${colCount}" class="text-center py-4 text-muted">
                            Tidak ada data tenaga kesehatan yang ditemukan
                        </td>
                    </tr>
                `);
            }
        } else {
            noResultsRow.remove();
        }
    }
});