$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function () {
    // === Tombol Tambah Janji ===
    $(document).on("click", "#btnTambah", function () {
        $("#formJanji")[0].reset();
        $("#idJanji").val("");
        $("#modalJanji").modal("show");
    });

    // === Simpan atau Update ===
    $("#formJanji").submit(function (e) {
        e.preventDefault();

        let id = $("#idJanji").val();
        let formData = new FormData(this);

        // kalau edit, tambahkan _method=PUT
        if (id) {
            formData.append("_method", "PUT");
        }

        $.ajax({
            url: id ? `/janji-berobat/${id}` : `/janji-berobat`,
            type: "POST", // tetap POST untuk dua-duanya (karena Laravel paham dari _method)
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                $("#modalJanji").modal("hide");
                loadJanji();
                Swal.fire({
                    icon: "success",
                    title: id
                        ? "Janji berhasil diupdate!"
                        : "Janji berhasil ditambahkan!",
                    timer: 1500,
                    showConfirmButton: false,
                });
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    text: "Terjadi kesalahan saat menyimpan data.",
                });
            },
        });
    });

    // === Load data janji ===
    function loadJanji() {
        $.get("/janji-berobat", function (res) {
            let container = $(".appointment-list");
            let emptyState = $(".appointment-status");

            if (!res || res.length === 0) {
                container.hide();
                emptyState.show();
                return;
            }

            container.show();
            emptyState.hide();

            let html = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Daftar Janji Anda</h3>
                    <button id="btnTambah" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Buat Janji Baru
                    </button>
                </div>
            `;

            res.forEach((item) => {
                // Warna & ikon status
                let statusMap = {
                    Menunggu: {
                        text: "Menunggu",
                        color: "#ff9800",
                        icon: "fa-hourglass-half",
                    },
                    Disetujui: {
                        text: "Disetujui",
                        color: "#4caf50",
                        icon: "fa-check-circle",
                    },
                    Selesai: {
                        text: "Selesai",
                        color: "#2196f3",
                        icon: "fa-clipboard-check",
                    },
                    Dibatalkan: {
                        text: "Dibatalkan",
                        color: "#f44336",
                        icon: "fa-times-circle",
                    },
                };
                let status = statusMap[item.status] || statusMap["Menunggu"];

                html += `
                    <div class="appointment-item border rounded p-3 mb-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="appointment-date text-muted">
                                <i class="far fa-calendar-alt"></i> ${
                                    item.tanggal
                                } - ${item.jam.slice(0, 5)}
                            </span>
                            <span class="badge-status" 
                                style="background:${
                                    status.color
                                }; color:#fff; padding:5px 10px; border-radius:20px; font-size:12px;">
                                <i class="fas ${status.icon}"></i> ${
                    status.text
                }
                            </span>
                        </div>

                        <p><strong>${item.nama}</strong> - ${item.no_hp}</p>
                        <p><i class="fa-regular fa-calendar"></i> Tanggal Lahir ${
                            item.tanggal_lahir
                        }</p>
                        <p><i class="fas fa-map-marker-alt"></i> Alamat ${
                            item.alamat
                        }</p>
                        <p><i class="fas fa-notes-medical"></i> ${
                            item.keluhan
                        }</p>
                        ${
                            item.admin_notes
                                ? `<p><em>Catatan Admin: ${item.admin_notes}</em></p>`
                                : ""
                        }

                        <div class="d-flex gap-2 mt-2">
                            ${
                                item.status === "Menunggu"
                                    ? `
                                <button class="btn btn-sm btn-outline-primary btnEdit" data-id="${item.id}">
                                    <i class="far fa-edit"></i> Edit
                                </button>
                            `
                                    : ""
                            }
                            <button class="btn btn-sm btn-outline-danger btnDelete" data-id="${
                                item.id
                            }">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>`;
            });

            container.html(html);
        }).fail(() => {
            Swal.fire({ icon: "error", title: "Gagal memuat data janji" });
        });
    }

    // === Edit Janji ===
    $(document).on("click", ".btnEdit", function () {
        let id = $(this).data("id");
        $.get(`/janji-berobat/${id}`, function (data) {
            $("#idJanji").val(data.id);
            $("input[name=nama]").val(data.nama);
            $("input[name=no_hp]").val(data.no_hp);
            $("input[name=tanggal_lahir]").val(data.tanggal_lahir);
            $("textarea[name=alamat]").val(data.alamat);
            $("input[name=tanggal]").val(data.tanggal);
            $("input[name=jam]").val(data.jam);
            $("textarea[name=keluhan]").val(data.keluhan);
            $("#modalJanji").modal("show");
        });
    });

    // === Hapus Janji ===
    $(document).on("click", ".btnDelete", function () {
        let id = $(this).data("id");

        Swal.fire({
            title: "Yakin mau hapus?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/janji-berobat/${id}`,
                    type: "POST", // kirim via POST
                    data: {
                        _method: "DELETE", // Laravel bakal ngerti ini DELETE
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function () {
                        loadJanji();
                        Swal.fire({
                            icon: "success",
                            title: "Dihapus!",
                            timer: 1200,
                            showConfirmButton: false,
                        });
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Gagal menghapus data!",
                        });
                    },
                });
            }
        });
    });

    // === Load data saat awal ===
    loadJanji();
});
