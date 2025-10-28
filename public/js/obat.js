document.addEventListener("DOMContentLoaded", function(){
    const kodeSelect = document.getElementById("kode");
    const kodePreview = document.getElementById("kodePreview");

    function updateKodePreview() {
        let opt = kodeSelect.options[kodeSelect.selectedIndex];
        let img = opt.getAttribute("data-img");
        if (img) {
            kodePreview.src = img;
            kodePreview.style.display = "inline-block";
        } else {
            kodePreview.style.display = "none";
        }
    }

    kodeSelect.addEventListener("change", updateKodePreview);
    updateKodePreview(); // biar langsung update kalau ada value default
});

function applyFilterAndSearch() {
    let filter = $("#filter-obat").val().toLowerCase();
    let search = $(".card-header-form input[type=text]").val().toLowerCase();

    $(".obat-table tbody tr").filter(function () {
        let nama = $(this).find(".nama-col").text().toLowerCase();
        let kategori = $(this).find("td:nth-child(4)").text().toLowerCase();
        let deskripsi = $(this).find("td:nth-child(8)").text().toLowerCase();

        let matchSearch = nama.includes(search) || deskripsi.includes(search);
        let matchFilter = filter === "" || kategori.includes(filter);

        $(this).toggle(matchSearch && matchFilter);
    });
}
