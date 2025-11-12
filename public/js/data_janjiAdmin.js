$(document).ready(function() {

  // === Klik tombol Edit ===
  $(document).on("click", ".btnEditJanji", function(e) {
    e.preventDefault();
    let id = $(this).data("id");

    // Ambil data appointment dari server
    $.get(`/admin/appointments/${id}`, function(data) {
      $("#idJanji").val(data.id);
      $("#nama").val(data.nama);
      $("#no_hp").val(data.no_hp);
      $("#tanggal_lahir").val(data.tanggal_lahir);
      $("#alamat").val(data.alamat);
      $("#tanggal").val(data.tanggal);
      $("#jam").val(data.jam);
      $("#keluhan").val(data.keluhan);
      $("#status").val(data.status);
      $("#admin_notes").val(data.admin_notes);

      // Tampilkan catatan kalau status dibatalkan
      if (data.status === "Dibatalkan") {
        $("#catatan-group").show();
      } else {
        $("#catatan-group").hide();
      }

      $("#modalJanji").modal("show");
    });
  });

  // === Ganti status: tampilkan catatan kalau Dibatalkan ===
  $("#status").on("change", function() {
    if ($(this).val() === "Dibatalkan") {
      $("#catatan-group").slideDown();
    } else {
      $("#catatan-group").slideUp();
    }
  });

  // === Simpan perubahan status ===
  $("#formJanji").on("submit", function(e) {
    e.preventDefault();
    let id = $("#idJanji").val();
    let formData = $(this).serialize();

    $.ajax({
      url: `/admin/appointments/update/${id}`,
      method: "POST",
      data: formData,
      success: function(res) {
        if (res.success) {
          Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: res.message,
            timer: 1500,
            showConfirmButton: false
          });
          $("#modalJanji").modal("hide");
          setTimeout(() => location.reload(), 1000);
        }
      },
      error: function() {
        Swal.fire({
          icon: "error",
          title: "Gagal!",
          text: "Terjadi kesalahan saat memperbarui data."
        });
      }
    });
  });

});
