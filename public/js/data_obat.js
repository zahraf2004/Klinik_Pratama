$(document).ready(function () {
    // Open add modal
    $("#btnTambah").click(function () {
        $("#formObat")[0].reset();
        $("#idObat").val("");
        $("#kodePreview").hide();
        $("#modalObat").modal("show");
    });

    // Submit form AJAX (store / update)
    $("#formObat").submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let id = $("#idObat").val();
        let url = id ? "/obat/" + id : "/obat";
        let method = "POST";
        if (id) formData.append("_method", "PUT");

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $("#modalObat").modal("hide");
                loadData(); // refresh table
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: id
                        ? "Data berhasil diperbarui"
                        : "Data berhasil disimpan",
                    timer: 1600,
                    showConfirmButton: false,
                });
            },
            error: function (xhr) {
                console.error(xhr);
                let msg = "Terjadi kesalahan";
                if (xhr.responseJSON && xhr.responseJSON.message)
                    msg = xhr.responseJSON.message;
                Swal.fire({ icon: "error", title: "Gagal!", text: msg });
            },
        });
    });

    // Load data (tangani response array atau paginate)
    function loadData(filterKategori = "") {
        let url = "/obat";
        if (filterKategori)
            url += "?kategori=" + encodeURIComponent(filterKategori);

        $.get(url, function (res) {
            // jika server mengembalikan paginate object -> ambil res.data
            if (!Array.isArray(res)) {
                if (res.data && Array.isArray(res.data)) {
                    res = res.data;
                } else {
                    console.error("Unexpected response from /obat:", res);
                    $(".obat-table tbody").html(
                        `<tr><td colspan="10" class="text-center">Tidak ada data</td></tr>`
                    );
                    return;
                }
            }

            let rows = "";
            if (res.length === 0) {
                rows = `<tr><td colspan="10" class="text-center">Tidak ada data</td></tr>`;
            } else {
                res.forEach((item, i) => {
                    // normalisasi nama field
                    const nama = item.nama_obat || "";
                    const foto = item.foto || item.foto_path || null;
                    const kategori = item.kategori || "";
                    const bentuk = item.bentuk || "";
                    const klasifikasi = item.klasifikasi || "";
                    const deskripsi =
                        item.deskripsi || item.deskripsi_obat || "";
                    const dosis = item.dosis || "";

                    // badge warna menurut kategori
                    let badgeClass = "badge-secondary";
                    const key = kategori.toLowerCase();
                    if (key === "obat bebas") badgeClass = "badge-success";
                    else if (key === "obat bebas terbatas")
                        badgeClass = "badge-primary";
                    else if (key === "obat herbal")
                        badgeClass = "badge-teal"; // custom css
                    else if (key === "jamu") badgeClass = "badge-warning";
                    else if (key === "fitofarmaka") badgeClass = "badge-info";
                    else if (key === "obat keras") badgeClass = "badge-danger";
                    else if (key === "narkotika") badgeClass = "badge-dark";

                    // === Logo kode (gambar berdasarkan kategori) ===
                    let kodeImg = "";
                    switch ((kategori || "").toLowerCase()) {
                        case "obat bebas":
                            kodeImg = `<img src="/img/bebas.png" width="30">`;
                            break;
                        case "obat bebas terbatas":
                            kodeImg = `<img src="/img/bebas_terbatas.png" width="30">`;
                            break;
                        case "obat herbal":
                            kodeImg = `<img src="/img/herbal.png" width="30">`;
                            break;
                        case "jamu":
                            kodeImg = `<img src="/img/Jamu.png" width="30">`;
                            break;
                        case "fitofarmaka":
                            kodeImg = `<img src="/img/Fitofarmaka.png" width="30">`;
                            break;
                        case "obat keras":
                            kodeImg = `<img src="/img/Keras.png" width="30">`;
                            break;
                        case "narkotika":
                            kodeImg = `<img src="/img/Narkotika.png" width="30">`;
                            break;
                        default:
                            kodeImg = `<img src="/img/default.png" width="30">`;
                    }

                    // foto
                    let fotoHtml = item.foto
                        ? `<img src="/storage/${item.foto}" class="rounded-circle foto-obat" width="40" height="40">`
                        : `<div class="avatar-sm"><span class="avatar-title rounded-circle">${item.nama_obat.charAt(
                              0
                          )}</span></div>`;

                    // ✅ Tambahin kolom dosis setelah bentuk
                    rows += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${fotoHtml}</td>
                        <td class="nama-col">${nama}</td>
                        <td><div class="badge ${badgeClass}">${kategori}</div></td>
                        <td>${bentuk}</td>                        
                        <td>${kodeImg}</td>
                        <td>${klasifikasi}</td>
                        <td class="desc-col">${deskripsi}</td>
                        <td class="dosis-col" title="${escapeHtml(
                            dosis
                        )}">${escapeHtml(dosis)}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-primary btnEdit" data-id="${
                                    item.id
                                }">
                                    <i class="far fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btnDelete" data-id="${
                                    item.id
                                }">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
                });
            }
            $(".obat-table tbody").html(rows);
        }).fail(function (xhr) {
            console.error("GET /obat failed:", xhr);
            $(".obat-table tbody").html(
                `<tr><td colspan="10" class="text-center">Gagal mengambil data</td></tr>`
            );
        });
    }

    // initial load
    loadData();

    // Edit: ambil per-id (lebih aman)
    $(document).on("click", ".btnEdit", function () {
        let id = $(this).data("id");

        $.get("/obat/" + id, function (data) {
            $("#idObat").val(data.id);
            $("input[name=nama_obat]").val(data.nama_obat);
            $("select[name=kategori]").val(data.kategori);
            $("select[name=bentuk]").val(data.bentuk);
            $("input[name=klasifikasi]").val(data.klasifikasi);
            $("#deskripsi").val(data.deskripsi);
            $("input[name=dosis]").val(data.dosis);
            $("#modalObat").modal("show");
        });
    });

    // Delete
    $(document).on("click", ".btnDelete", function () {
        let id = $(this).data("id");
        Swal.fire({
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            title: "Konfirmasi",
            text: "Yakin hapus data ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/obat/" + id,
                    type: "POST",
                    data: {
                        _method: "DELETE",
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function () {
                        loadData();
                        Swal.fire({
                            icon: "success",
                            title: "Dihapus",
                            timer: 1400,
                            showConfirmButton: false,
                        });
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        Swal.fire({ icon: "error", title: "Gagal" });
                    },
                });
            }
        });
    });

    // filter select (sesuaikan id di blade)
    $("#filter-profesi, #filter-obat").on("change", function () {
        const val = $(this).val() || "";
        loadData(val);
    });

    // simple search (client-side)
    $(".card-header-form input[type=text]").on("keyup", function () {
        applyFilterAndSearch();
    });

    // utility escape
    function escapeHtml(text) {
        if (!text) return "";
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }
});
