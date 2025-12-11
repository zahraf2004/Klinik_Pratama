// JavaScript untuk search dan filter data obat - hanya logic tanpa mengubah tampilan
$(document).ready(function() {
    // Search functionality
    $('#search-obat').on('keyup', function() {
        performSearch();
    });
    
    $('#btn-search-obat').on('click', function() {
        performSearch();
    });
    
    // Filter functionality
    $('#filter-kategori').on('change', function() {
        performSearch();
    });
    
    // Clear search when input is empty
    $('#search-obat').on('input', function() {
        if ($(this).val() === '') {
            performSearch();
        }
    });
    
    function performSearch() {
        let searchTerm = $('#search-obat').val().toLowerCase().trim();
        let filterKategori = $('#filter-kategori').val();
        let visibleCount = 0;
        
        $('.obat-table tbody tr').each(function() {
            let row = $(this);
            let namaObat = row.find('td:nth-child(3)').text().toLowerCase().trim();
            let kategori = row.find('td:nth-child(4)').text().trim();
            
            // Check search match
            let matchSearch = searchTerm === '' || namaObat.includes(searchTerm);
            
            // Check filter match
            let matchFilter = filterKategori === '' || kategori === filterKategori;
            
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
        let visibleRows = $('.obat-table tbody tr:visible');
        visibleRows.each(function(index) {
            $(this).find('td:first-child').text(index + 1);
        });
    }
    
    function showNoResultsMessage(count) {
        let tbody = $('.obat-table tbody');
        let noResultsRow = tbody.find('.no-results-row');
        
        if (count === 0) {
            if (noResultsRow.length === 0) {
                let colCount = $('.obat-table thead tr th').length;
                tbody.append(`
                    <tr class="no-results-row">
                        <td colspan="${colCount}" class="text-center py-4 text-muted">
                            Tidak ada data obat yang ditemukan
                        </td>
                    </tr>
                `);
            }
        } else {
            noResultsRow.remove();
        }
    }
});