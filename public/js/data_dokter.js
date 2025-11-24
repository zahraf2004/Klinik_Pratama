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

        // Collect jadwal shift data
        let jadwalShift = [];
        $('.jadwal-item').each(function() {
            let tanggalMulai = $(this).find('.jadwal-tanggal-mulai').val();
            let tanggalSelesai = $(this).find('.jadwal-tanggal-selesai').val();
            let jamMulai = $(this).find('input[type="time"]').eq(0).val();
            let jamSelesai = $(this).find('input[type="time"]').eq(1).val();
            
            if (tanggalMulai && tanggalSelesai && jamMulai && jamSelesai) {
                jadwalShift.push({
                    tanggal_mulai: tanggalMulai,
                    tanggal_selesai: tanggalSelesai,
                    jam_mulai: jamMulai,
                    jam_selesai: jamSelesai
                });
            }
        });
        
        formData.append('jadwal_shift', JSON.stringify(jadwalShift));

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                $("#modalTenaga").modal("hide");
                loadData();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil disimpan',
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                });
            },
        });
    });

    // Load data
    function loadData() {
        $.get("/tenaga-kesehatan", function (res) {
            let rows = "";
            res.forEach((item, i) => {
                // Badge color untuk role
                let roleBadge = '';
                if (item.role === 'superadmin') {
                    roleBadge = '<div class="badge badge-danger">Super Admin</div>';
                } else if (item.role === 'admin') {
                    roleBadge = '<div class="badge badge-warning">Admin</div>';
                } else {
                    roleBadge = '<div class="badge badge-success">Dokter Umum</div>';
                }

                rows += `
                <tr>
                    <td>${i + 1}</td>
                    <td>
                        ${
                            item.foto_path
                                ? `<img src="/storage/${item.foto_path}" width="50" class="rounded-circle" height="50">`
                                : '<div class="avatar bg-secondary text-white" style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;border-radius:50%;">' + (item.nama ? item.nama.charAt(0).toUpperCase() : '?') + '</div>'
                        }
                    </td>
                    <td>${item.nama || '-'}</td>
                    <td>${item.email || '-'}</td>
                    <td>${item.hp || '-'}</td>
                    <td>${item.str || '-'}</td>
                    <td>${item.sip || '-'}</td>
                    <td>${roleBadge}</td>
                    <td>${item.tahun_mulai || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-primary btnEdit" data-id="${item.id}" title="Edit">
                            <i class="far fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-info btnDetail" data-id="${item.id}" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btnDelete" data-id="${item.id}" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
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
            $("#nama").val(data.nama);
            $("#email").val(data.email);
            $("#hp").val(data.hp);
            $("#str").val(data.str);
            $("#sip").val(data.sip);
            $("#tahun_mulai").val(data.tahun_mulai);
            $("#role").val(data.role);

            // Load jadwal shift
            $('#jadwal-container').empty();
            if (data.jadwal_shift && data.jadwal_shift.length > 0) {
                data.jadwal_shift.forEach((jadwal, index) => {
                    let jadwalHtml = `
                        <div class="jadwal-item border p-3 mb-2 rounded">
                            <div class="row">
                                <div class="col-6">
                                    <label class="small mb-1">Tanggal Mulai</label>
                                    <input type="date" name="jadwal[${index}][tanggal_mulai]" class="form-control form-control-sm jadwal-tanggal-mulai mb-2" value="${jadwal.tanggal_mulai}">
                                    <label class="small mb-1">Jam Mulai</label>
                                    <input type="time" name="jadwal[${index}][jam_mulai]" class="form-control form-control-sm" value="${jadwal.jam_mulai}">
                                </div>
                                <div class="col-6">
                                    <label class="small mb-1">Tanggal Selesai</label>
                                    <input type="date" name="jadwal[${index}][tanggal_selesai]" class="form-control form-control-sm jadwal-tanggal-selesai mb-2" value="${jadwal.tanggal_selesai}">
                                    <label class="small mb-1">Jam Selesai</label>
                                    <input type="time" name="jadwal[${index}][jam_selesai]" class="form-control form-control-sm" value="${jadwal.jam_selesai}">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-jadwal btn-block" ${index === 0 && data.jadwal_shift.length === 1 ? 'style="display:none;"' : ''}>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#jadwal-container').append(jadwalHtml);
                });
            } else {
                // Default 1 jadwal kosong
                $('#jadwal-container').html(`
                    <div class="jadwal-item border p-3 mb-2 rounded">
                        <div class="row">
                            <div class="col-6">
                                <label class="small mb-1">Tanggal Mulai</label>
                                <input type="date" name="jadwal[0][tanggal_mulai]" class="form-control form-control-sm jadwal-tanggal-mulai mb-2">
                                <label class="small mb-1">Jam Mulai</label>
                                <input type="time" name="jadwal[0][jam_mulai]" class="form-control form-control-sm">
                            </div>
                            <div class="col-6">
                                <label class="small mb-1">Tanggal Selesai</label>
                                <input type="date" name="jadwal[0][tanggal_selesai]" class="form-control form-control-sm jadwal-tanggal-selesai mb-2">
                                <label class="small mb-1">Jam Selesai</label>
                                <input type="time" name="jadwal[0][jam_selesai]" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-jadwal btn-block" style="display:none;">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }

            $("#modalTenaga").modal("show");
        });
    });

    // Detail (Modal untuk melihat detail lengkap)
    $(document).on("click", ".btnDetail", function () {
        let id = $(this).data("id");

        $.get("/tenaga-kesehatan/" + id, function (data) {
            // Format role badge
            let roleBadgeClass = '';
            let roleText = '';
            if (data.role === 'superadmin') {
                roleBadgeClass = 'superadmin';
                roleText = 'Super Admin';
            } else if (data.role === 'admin') {
                roleBadgeClass = 'admin';
                roleText = 'Admin';
            } else {
                roleBadgeClass = 'dokter_umum';
                roleText = 'Dokter Umum';
            }

            // Hitung pengalaman otomatis
            let pengalamanHtml = '';
            if (data.tahun_mulai) {
                const tahunSekarang = new Date().getFullYear();
                const lamaKerja = tahunSekarang - data.tahun_mulai;
                
                if (lamaKerja < 0) {
                    pengalamanHtml = `
                        <div class="pengalaman-box">
                            <div class="pengalaman-number">-</div>
                            <div class="pengalaman-label">Belum Mulai</div>
                        </div>
                    `;
                } else if (lamaKerja == 0) {
                    pengalamanHtml = `
                        <div class="pengalaman-box">
                            <div class="pengalaman-number">&lt;1</div>
                            <div class="pengalaman-label">Tahun Pengalaman</div>
                        </div>
                    `;
                } else {
                    pengalamanHtml = `
                        <div class="pengalaman-box">
                            <div class="pengalaman-number">${lamaKerja}</div>
                            <div class="pengalaman-label">Tahun Pengalaman</div>
                        </div>
                    `;
                }
            } else {
                pengalamanHtml = '<p class="detail-value empty">Tahun mulai belum diisi</p>';
            }

            // Format jadwal shift
            let jadwalHtml = '';
            if (data.jadwal_shift && data.jadwal_shift.length > 0) {
                jadwalHtml = '<ul class="jadwal-list">';
                data.jadwal_shift.forEach(jadwal => {
                    jadwalHtml += `
                        <li class="jadwal-item-detail">
                            <div class="jadwal-periode">
                                <i class="fas fa-calendar-alt"></i>
                                ${jadwal.tanggal_mulai} s/d ${jadwal.tanggal_selesai}
                            </div>
                            <div class="jadwal-waktu">
                                <i class="fas fa-clock"></i>
                                ${jadwal.jam_mulai} - ${jadwal.jam_selesai}
                            </div>
                        </li>
                    `;
                });
                jadwalHtml += '</ul>';
            } else {
                jadwalHtml = '<p class="detail-value empty">Belum ada jadwal shift</p>';
            }

            Swal.fire({
                html: `
                    <div class="detail-nakes-header">
                        <h2>${data.nama || 'Nama Tidak Tersedia'}</h2>
                        <div class="subtitle">
                            <span class="badge-role ${roleBadgeClass}">${roleText}</span>
                        </div>
                    </div>
                    <div class="detail-nakes-body">
                        <!-- Informasi Kontak -->
                        <div class="detail-section">
                            <div class="detail-section-title">
                                <i class="fas fa-user-circle"></i>
                                Kontak
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </div>
                                <div class="detail-value ${!data.email ? 'empty' : ''}">
                                    ${data.email || 'Tidak tersedia'}
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-phone"></i>
                                    HP
                                </div>
                                <div class="detail-value ${!data.hp ? 'empty' : ''}">
                                    ${data.hp || 'Tidak tersedia'}
                                </div>
                            </div>
                        </div>

                        <!-- Kredensial -->
                        <div class="detail-section">
                            <div class="detail-section-title">
                                <i class="fas fa-certificate"></i>
                                Kredensial
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-id-card"></i>
                                    STR
                                </div>
                                <div class="detail-value ${!data.str ? 'empty' : ''}">
                                    ${data.str || 'Tidak tersedia'}
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-file-medical"></i>
                                    SIP
                                </div>
                                <div class="detail-value ${!data.sip ? 'empty' : ''}">
                                    ${data.sip || 'Tidak tersedia'}
                                </div>
                            </div>
                        </div>

                        <!-- Pengalaman -->
                        <div class="detail-section">
                            <div class="detail-section-title">
                                <i class="fas fa-briefcase"></i>
                                Pengalaman
                            </div>
                            ${pengalamanHtml}
                        </div>

                        <!-- Jadwal Shift -->
                        <div class="detail-section">
                            <div class="detail-section-title">
                                <i class="fas fa-calendar-week"></i>
                                Jadwal Shift
                            </div>
                            ${jadwalHtml}
                        </div>
                    </div>
                `,
                width: '550px',
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: '<i class="fas fa-times"></i> Tutup',
                confirmButtonColor: '#6777ef',
                customClass: {
                    popup: 'detail-nakes-modal',
                    confirmButton: 'btn btn-primary'
                },
                showClass: {
                    popup: 'animate__animated animate__zoomIn animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut animate__faster'
                }
            });
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
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/tenaga-kesehatan/" + id,
                    type: "POST",
                    data: { 
                        _method: "DELETE", 
                        _token: $('meta[name="csrf-token"]').attr("content") 
                    },
                    success: function () {
                        loadData();
                        Swal.fire({ 
                            icon: "success", 
                            title: "Berhasil Dihapus", 
                            timer: 1400, 
                            showConfirmButton: false 
                        });
                    },
                    error: function (xhr) { 
                        console.error(xhr); 
                        Swal.fire({
                            icon:'error',
                            title:'Gagal',
                            text: 'Terjadi kesalahan saat menghapus data'
                        }); 
                    }
                });
            }
        });
    });
});


    // ===== JADWAL SHIFT MANAGEMENT =====
    let jadwalIndex = 1;

    // Tambah jadwal shift baru
    $(document).on('click', '#btn-add-jadwal', function() {
        const newJadwal = `
          <div class="jadwal-item border p-3 mb-2 rounded">
            <div class="row">
              <div class="col-6">
                <label class="small mb-1">Tanggal Mulai</label>
                <input type="date" name="jadwal[${jadwalIndex}][tanggal_mulai]" class="form-control form-control-sm jadwal-tanggal-mulai mb-2">
                <label class="small mb-1">Jam Mulai</label>
                <input type="time" name="jadwal[${jadwalIndex}][jam_mulai]" class="form-control form-control-sm">
              </div>
              <div class="col-6">
                <label class="small mb-1">Tanggal Selesai</label>
                <input type="date" name="jadwal[${jadwalIndex}][tanggal_selesai]" class="form-control form-control-sm jadwal-tanggal-selesai mb-2">
                <label class="small mb-1">Jam Selesai</label>
                <input type="time" name="jadwal[${jadwalIndex}][jam_selesai]" class="form-control form-control-sm">
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-12">
                <button type="button" class="btn btn-danger btn-sm btn-remove-jadwal btn-block">
                  <i class="fas fa-trash"></i> Hapus
                </button>
              </div>
            </div>
          </div>
        `;
        
        $('#jadwal-container').append(newJadwal);
        jadwalIndex++;
        
        // Show remove button on all items if more than 1
        if ($('.jadwal-item').length > 1) {
            $('.btn-remove-jadwal').show();
        }
    });

    // Hapus jadwal shift
    $(document).on('click', '.btn-remove-jadwal', function() {
        $(this).closest('.jadwal-item').remove();
        
        // Hide remove button if only 1 item left
        if ($('.jadwal-item').length === 1) {
            $('.btn-remove-jadwal').hide();
        }
    });

    // Reset form saat modal ditutup
    $('#modalTenaga').on('hidden.bs.modal', function() {
        $('#formTenaga')[0].reset();
        $('#idTenaga').val('');
        
        // Reset jadwal ke 1 item
        $('.jadwal-item').not(':first').remove();
        $('.btn-remove-jadwal').hide();
        jadwalIndex = 1;
    });
