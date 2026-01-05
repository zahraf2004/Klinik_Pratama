<!-- ===== CONTENT SECTION ===== -->
    <div class="row mt-4">

        <!-- === JADWAL HARI INI === -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center" style="font-weight: bold;">
                    <span>Jadwal Hari Ini</span>
                    <small class="text-muted">{{ Carbon\Carbon::today()->format('d M Y') }}</small>
                </div>
                <div class="card-body">
                    <ul class="schedule-list" id="jadwal-list">
                        @forelse($jadwalHariIni as $jadwal)
                        <li class="schedule-item" data-id="{{ $jadwal['id'] }}" style="cursor: pointer; padding: 10px; border-radius: 5px; margin-bottom: 10px; border: 1px solid #e9ecef;">
                            <div>
                                <strong>{{ $jadwal['jam_range'] }}</strong> <br>
                                Pasien: <span class="text-dark">{{ $jadwal['nama_pasien'] }}</span>
                            </div>
                            @if($jadwal['status'] == 'Menunggu')
                                <span class="badge bg-primary" style="color: #ffff; height: 30px; margin:15px;">Menunggu</span>
                            @elseif($jadwal['status'] == 'Disetujui')
                                <span class="badge bg-success" style="color: #ffff; height: 30px; margin:15px;">Disetujui</span>
                            @endif
                        </li>
                        @empty
                        <li>
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                                Tidak ada jadwal hari ini
                            </div>
                        </li>
                        @endforelse
                    </ul>

                </div>
            </div>
        </div>

        <!-- === RIWAYAT TERAKHIR === -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold" style="font-weight: bold;">Riwayat Janji Temu Terakhir</div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Pasien</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatJanjiTemu as $riwayat)
                            <tr class="riwayat-item" data-id="{{ $riwayat['id'] }}" style="cursor: pointer;">
                                <td>{{ $riwayat['nama_pasien'] }}</td>
                                <td>{{ $riwayat['tanggal'] }}</td>
                                <td>
                                    @if($riwayat['status'] == 'Disetujui')
                                        <span class="badge bg-success" style="color: #ffff;">Disetujui</span>
                                    @elseif($riwayat['status'] == 'Dibatalkan')
                                        <span class="badge bg-danger" style="color: #ffff;">Dibatalkan</span>
                                    @elseif($riwayat['status'] == 'Selesai')
                                        <span class="badge bg-success" style="color: #ffff;">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    <i class="fas fa-history fa-2x mb-2"></i><br>
                                    Belum ada riwayat janji temu
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Appointment -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Detail Janji Temu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="appointmentModalBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle click pada jadwal item
        document.querySelectorAll('.schedule-item').forEach(item => {
            item.addEventListener('click', function() {
                const appointmentId = this.dataset.id;
                showAppointmentDetail(appointmentId);
            });
            
            // Hover effect
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });

        // Handle click pada riwayat item
        document.querySelectorAll('.riwayat-item').forEach(item => {
            item.addEventListener('click', function() {
                const appointmentId = this.dataset.id;
                showAppointmentDetail(appointmentId);
            });
            
            // Hover effect
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });

        // Auto refresh setiap 5 menit
        setInterval(function() {
            refreshJadwalData();
        }, 300000); // 5 menit

        function refreshJadwalData() {
            // Refresh jadwal hari ini
            fetch('/api/dokter/jadwal-hari-ini')
                .then(response => response.json())
                .then(data => {
                    updateJadwalList(data);
                })
                .catch(error => {
                    console.error('Error refreshing jadwal:', error);
                });

            // Refresh riwayat
            fetch('/api/dokter/riwayat-janji-temu')
                .then(response => response.json())
                .then(data => {
                    updateRiwayatTable(data);
                })
                .catch(error => {
                    console.error('Error refreshing riwayat:', error);
                });
        }

        function updateJadwalList(jadwalData) {
            const jadwalList = document.getElementById('jadwal-list');
            
            if (jadwalData.length === 0) {
                jadwalList.innerHTML = `
                    <li>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                            Tidak ada jadwal hari ini
                        </div>
                    </li>
                `;
                return;
            }

            let html = '';
            jadwalData.forEach(jadwal => {
                const statusBadge = jadwal.status === 'Menunggu' 
                    ? '<span class="badge bg-primary" style="color: #ffff; height: 30px; margin:15px;">Menunggu</span>'
                    : '<span class="badge bg-success" style="color: #ffff; height: 30px; margin:15px;">Disetujui</span>';

                html += `
                    <li class="schedule-item" data-id="${jadwal.id}" style="cursor: pointer; padding: 10px; border-radius: 5px; margin-bottom: 10px; border: 1px solid #e9ecef;">
                        <div>
                            <strong>${jadwal.jam_range}</strong> <br>
                            Pasien: <span class="text-dark">${jadwal.nama_pasien}</span>
                        </div>
                        ${statusBadge}
                    </li>
                `;
            });

            jadwalList.innerHTML = html;
            
            // Re-attach event listeners
            document.querySelectorAll('.schedule-item').forEach(item => {
                item.addEventListener('click', function() {
                    const appointmentId = this.dataset.id;
                    showAppointmentDetail(appointmentId);
                });
            });
        }

        function updateRiwayatTable(riwayatData) {
            const tbody = document.querySelector('.table tbody');
            
            if (riwayatData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">
                            <i class="fas fa-history fa-2x mb-2"></i><br>
                            Belum ada riwayat janji temu
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            riwayatData.forEach(riwayat => {
                let statusBadge = '';
                switch(riwayat.status) {
                    case 'Disetujui':
                        statusBadge = '<span class="badge bg-success" style="color: #ffff;">Disetujui</span>';
                        break;
                    case 'Dibatalkan':
                        statusBadge = '<span class="badge bg-danger" style="color: #ffff;">Dibatalkan</span>';
                        break;
                    case 'Selesai':
                        statusBadge = '<span class="badge bg-success" style="color: #ffff;">Selesai</span>';
                        break;
                }

                html += `
                    <tr class="riwayat-item" data-id="${riwayat.id}" style="cursor: pointer;">
                        <td>${riwayat.nama_pasien}</td>
                        <td>${riwayat.tanggal}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            
            // Re-attach event listeners
            document.querySelectorAll('.riwayat-item').forEach(item => {
                item.addEventListener('click', function() {
                    const appointmentId = this.dataset.id;
                    showAppointmentDetail(appointmentId);
                });
            });
        }

        function showAppointmentDetail(appointmentId) {
            // Show loading
            document.getElementById('appointmentModalBody').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail...</p>
                </div>
            `;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
            modal.show();

            // Fetch appointment detail
            fetch(`/api/dokter/appointment/${appointmentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    const statusBadge = getStatusBadge(data.status);
                    
                    document.getElementById('appointmentModalBody').innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Informasi Pasien</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td><strong>Nama:</strong></td>
                                        <td>${data.nama}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. HP:</strong></td>
                                        <td>${data.no_hp}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Lahir:</strong></td>
                                        <td>${data.tanggal_lahir || '-'}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat:</strong></td>
                                        <td>${data.alamat}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Informasi Janji Temu</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td><strong>Tanggal:</strong></td>
                                        <td>${data.tanggal}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jam:</strong></td>
                                        <td>${data.jam}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>${statusBadge}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="fw-bold">Keluhan</h6>
                                <p class="border p-3 rounded bg-light">${data.keluhan}</p>
                            </div>
                        </div>
                        ${data.admin_notes ? `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="fw-bold">Catatan Admin</h6>
                                <p class="border p-3 rounded bg-warning bg-opacity-10">${data.admin_notes}</p>
                            </div>
                        </div>
                        ` : ''}
                    `;
                })
                .catch(error => {
                    document.getElementById('appointmentModalBody').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Error: ${error.message}
                        </div>
                    `;
                });
        }

        function getStatusBadge(status) {
            switch(status) {
                case 'Menunggu':
                    return '<span class="badge bg-primary">Menunggu</span>';
                case 'Disetujui':
                    return '<span class="badge bg-success">Disetujui</span>';
                case 'Selesai':
                    return '<span class="badge bg-success">Selesai</span>';
                case 'Dibatalkan':
                    return '<span class="badge bg-danger">Dibatalkan</span>';
                default:
                    return '<span class="badge bg-secondary">' + status + '</span>';
            }
        }
    });
    </script>