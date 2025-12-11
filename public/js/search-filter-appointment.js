// JavaScript untuk search dan filter data janji berobat - hanya logic tanpa mengubah tampilan
$(document).ready(function() {
    // Search functionality
    $('#search-pasien').on('keyup', function() {
        performSearch();
    });
    
    $('#btn-search-pasien').on('click', function() {
        performSearch();
    });
    
    // Filter functionality
    $('#filter-status').on('change', function() {
        performSearch();
    });
    
    // Clear search when input is empty
    $('#search-pasien').on('input', function() {
        if ($(this).val() === '') {
            performSearch();
        }
    });
    
    function performSearch() {
        let searchTerm = $('#search-pasien').val().toLowerCase().trim();
        let filterStatus = $('#filter-status').val();
        let visibleCount = 0;
        
        $('.tenaga-table tbody tr').each(function() {
            let row = $(this);
            let namaPasien = row.find('td:nth-child(2)').text().toLowerCase().trim();
            let statusBadge = row.find('td:nth-child(9) .badge').text().trim();
            
            // Check search match (bisa search di nama atau nomor HP)
            let noHP = row.find('td:nth-child(4)').text().toLowerCase().trim();
            let matchSearch = searchTerm === '' || 
                            namaPasien.includes(searchTerm) || 
                            noHP.includes(searchTerm);
            
            // Check filter match
            let matchFilter = filterStatus === '' || statusBadge === filterStatus;
            
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
                            Tidak ada data janji berobat yang ditemukan
                        </td>
                    </tr>
                `);
            }
        } else {
            noResultsRow.remove();
        }
    }
});