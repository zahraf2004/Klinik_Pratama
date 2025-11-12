$(document).ready(function () {
    // Buka modal tambah
    $("#btnTambah").click(function () {
        $("#formTenaga")[0].reset();
        $("#idTenaga").val("");
        $("#modalTenaga").modal("show");
    });

    // Submit form AJAX
    $("#formTenaga").submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let id = $("#idTenaga").val();
        let url = id ? "/tenaga-kesehatan/" + id : "/tenaga-kesehatan";
        let method = "POST";

        if (id) formData.append("_method", "PUT");

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                $("#modalTenaga").modal("hide");
                loadData();
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseJSON.message);
            },
        });
    });

    // Load data
    function loadData() {
        $.get("/tenaga-kesehatan", function (res) {
            let rows = "";
            res.forEach((item, i) => {
                rows += `
                <tr>
                    <td>${i + 1}</td>
                    <td>
                        ${
                            item.foto_path
                                ? `<img src="/storage/${item.foto_path}" width="50" class="rounded-circle" height="50">`
                                : ""
                        }
                    </td>
                    <td>${item.nama}</td>
                    <td>${item.tanggal_lahir}</td>
                    <td>${item.email}</td>
                    <td>${item.hp}</td>
                    <td>${item.alumnus}</td>
                    <td><div class="badge badge-info">${item.profesi}</div></td>
                    <td>
                        <button class="btn btn-sm btn-primary btnEdit" data-id="${
                            item.id
                        }"><i class="far fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger btnDelete" data-id="${
                            item.id
                        }"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
            });
            $(".tenaga-table tbody").html(rows);
        });
    }

    loadData();

    // Edit
    $(document).on("click", ".btnEdit", function () {
        let id = $(this).data("id");

        $.get("/tenaga-kesehatan/" + id, function (data) {
            $("#idTenaga").val(data.id);
            $("input[name=nama]").val(data.nama);
            $("input[name=tanggal_lahir]").val(data.tanggal_lahir);
            $("input[name=email]").val(data.email);
            $("input[name=hp]").val(data.hp);
            $("input[name=alumnus]").val(data.alumnus);
            $("select[name=profesi]").val(data.profesi);
            $("#modalTenaga").modal("show");
        });
    });

    // Delete
    $(document).on("click", ".btnDelete", function () {
        if (!confirm("Yakin hapus data ini?")) return;

        let id = $(this).data("id");
        $.ajax({
            url: "/tenaga-kesehatan/" + id,
            type: "POST",
            data: {
                _method: "DELETE",
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function () {
                loadData();
            },
        });
    });
});
