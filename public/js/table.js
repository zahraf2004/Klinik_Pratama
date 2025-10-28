document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('.tenaga-table');
    const tableBody = table.querySelector('tbody');
    const sortNama = document.querySelector('.sort-nama');
    
    // Perbaikan: Gunakan selector yang tepat untuk search input
    const searchInput = document.querySelector('.card-header-form input[type="text"]');
    
    // Deklarasi filterProfesi cukup satu kali
    const filterProfesi = document.querySelector('#filter-profesi');
    let asc = true;

    // ==== SORT NAMA ====
    if(sortNama) {
        sortNama.addEventListener("click", function () {
            const icon = this.querySelector('i');
            if(icon) {
                icon.className = asc ? 'fas fa-sort-up ms-1' : 'fas fa-sort-down ms-1';
            }
            
            const rows = Array.from(tableBody.querySelectorAll("tr"));
            const sorted = rows.sort((a, b) => {
                const nameA = a.cells[2].textContent.trim().toLowerCase();
                const nameB = b.cells[2].textContent.trim().toLowerCase();
                return asc ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
            });
            asc = !asc;
            renderTable(sorted);
        });
    }

    // ==== SEARCH ====
    if(searchInput) {
        searchInput.addEventListener('input', function () {
            applyFilters();
        });
    }

    // ==== FILTER PROFESI ====
    if(filterProfesi) {
        filterProfesi.addEventListener('change', function () {
            applyFilters();
        });
        
        // Mencegah penutupan dropdown saat mengklik select
        filterProfesi.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Mencegah penutupan dropdown untuk semua elemen di dalamnya
    document.querySelectorAll('.dropdown-menu, .dropdown-menu *').forEach(element => {
        element.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });

    // ==== FUNCTION RENDER TABLE ====
    function renderTable(rows) {
        tableBody.innerHTML = "";
        rows.forEach((row, index) => {
            row.cells[0].textContent = index + 1; // Update nomor urut
            tableBody.appendChild(row);
        });
    }

    // Fungsi applyFilters yang diperbaiki
    function applyFilters() {
        const searchVal = searchInput ? searchInput.value.toLowerCase() : "";
        const profesiVal = filterProfesi ? filterProfesi.value.toLowerCase() : "";
        const rows = Array.from(tableBody.querySelectorAll("tr"));

        rows.forEach(row => {
            const nama = row.cells[2].textContent.toLowerCase();
            // Kolom profesi adalah kolom ke-7 (index 7)
            const profesiElement = row.cells[7].querySelector('.badge');
            const profesi = profesiElement ? 
                profesiElement.textContent.toLowerCase() : 
                row.cells[7].textContent.toLowerCase();

            const matchSearch = searchVal === "" || nama.includes(searchVal);
            const matchProfesi = profesiVal === "" || profesi.includes(profesiVal);

            row.style.display = matchSearch && matchProfesi ? "" : "none";
        });

        // Update nomor urut
        let visibleRows = rows.filter(r => r.style.display !== 'none');
        visibleRows.forEach((row, index) => {
            row.cells[0].textContent = index + 1;
        });
    }
});