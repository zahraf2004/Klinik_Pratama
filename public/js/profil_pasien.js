$(document).ready(function () {
    // Open modal when Edit Profil clicked
    $("#btnEditProfil").click(function () {
        $("#modalProfil").modal("show");
    });

    // Preview foto sebelum upload
    $("#fotoInput").change(function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const avatarPreview = $("#avatarPreview");
                const avatarText = $("#avatarText");

                if (avatarPreview.length) {
                    avatarPreview.attr("src", e.target.result);
                } else if (avatarText.length) {
                    avatarText.hide();
                    $(".avatar-preview").append(
                        '<img src="' +
                            e.target.result +
                            '" id="avatarPreview" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">'
                    );
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Submit form AJAX
    $("#formProfil").submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('pasien.profil.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $("#modalProfil").modal("hide");

                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: response.message || "Profil berhasil diperbarui",
                    timer: 1600,
                    showConfirmButton: false,
                }).then(() => {
                    // Reload halaman untuk menampilkan data terbaru
                    location.reload();
                });
            },
            error: function (xhr) {
                console.error(xhr);
                let msg = "Terjadi kesalahan saat menyimpan data";

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Tampilkan error validasi
                    let errorMessages = [];
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorMessages.push(value[0]);
                    });
                    msg = errorMessages.join("<br>");
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    html: msg,
                });
            },
        });
    });

    // Tutup modal ketika klik tombol close
    $(".close, .btn-light").click(function () {
        $("#modalProfil").modal("hide");
    });

    // Handle escape key untuk tutup modal
    $(document).keydown(function (e) {
        if (e.keyCode === 27) {
            $("#modalProfil").modal("hide");
        }
    });
});
