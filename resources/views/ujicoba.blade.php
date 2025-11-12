<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Pasien - MedCare</title>
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #2ecc71;
            --secondary-dark: #27ae60;
            --dark: #2c3e50;
            --light: #ecf0f1;
            --danger: #e74c3c;
            --warning: #f39c12;
            --gray: #95a5a6;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --radius: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 15px 0;
            box-shadow: var(--shadow);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .logo i {
            font-size: 1.8rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--primary);
        }

        /* Main Content */
        .main-content {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        /* Sidebar */
        .sidebar {
            background-color: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 20px 0;
            height: fit-content;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary);
        }

        .sidebar-item.active {
            background-color: rgba(52, 152, 219, 0.15);
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }

        /* Content Area */
        .content-area {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
        }

        /* Cards */
        .card {
            background-color: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .card-action {
            color: var(--primary);
            font-size: 0.9rem;
            cursor: pointer;
            font-weight: 500;
        }

        /* Profile Section */
        .profile-info {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary);
            flex-shrink: 0;
        }

        .profile-details {
            flex: 1;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .profile-id {
            color: var(--gray);
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .profile-tags {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .tag {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            border-radius: var(--radius);
            background-color: rgba(52, 152, 219, 0.05);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--gray);
        }

        /* Medical Info */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--gray);
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 500;
        }

        /* Appointments */
        .appointment-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .appointment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: rgba(52, 152, 219, 0.05);
            border-radius: var(--radius);
        }

        .appointment-details {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .appointment-doctor {
            font-weight: 600;
        }

        .appointment-date {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .appointment-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-confirmed {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--secondary-dark);
        }

        .status-pending {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background-color: rgba(52, 152, 219, 0.1);
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background-color: rgba(52, 152, 219, 0.2);
            transform: translateY(-3px);
        }

        .action-icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .action-label {
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .content-area {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .profile-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-stats {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-heartbeat"></i>
                    <span>MedCare</span>
                </div>
                <div class="user-menu">
                    <div class="notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-profile">
                        <div class="avatar">AS</div>
                        <span>Ahmad Surya</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <ul class="sidebar-menu">
                    <li class="sidebar-item active">
                        <i class="fas fa-user"></i>
                        <span>Profil Saya</span>
                    </li>
                    <li class="sidebar-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Janji Temu</span>
                    </li>
                    <li class="sidebar-item">
                        <i class="fas fa-file-medical"></i>
                        <span>Rekam Medis</span>
                    </li>
                    <li class="sidebar-item">
                        <i class="fas fa-prescription"></i>
                        <span>Resep Obat</span>
                    </li>
                    <li class="sidebar-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Pembayaran</span>
                    </li>
                    <li class="sidebar-item">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </li>
                    <li class="sidebar-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Bantuan</span>
                    </li>
                </ul>
            </div>

            <div class="content-area">
                <div class="main-column">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Profil Pasien</div>
                            <div class="card-action">Edit Profil</div>
                        </div>
                        <div class="profile-info">
                            <div class="profile-avatar">AS</div>
                            <div class="profile-details">
                                <div class="profile-name">Ahmad Surya</div>
                                <div class="profile-id">ID Pasien: P-2023-04567</div>
                                <div class="profile-tags">
                                    <div class="tag">BPJS</div>
                                    <div class="tag">Asuransi Mandiri</div>
                                </div>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Tanggal Lahir</div>
                                        <div class="info-value">15 Agustus 1985</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Jenis Kelamin</div>
                                        <div class="info-value">Laki-laki</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">No. Telepon</div>
                                        <div class="info-value">+62 812-3456-7890</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Email</div>
                                        <div class="info-value">ahmad.surya@email.com</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Alamat</div>
                                        <div class="info-value">Jl. Merdeka No. 123, Jakarta</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Golongan Darah</div>
                                        <div class="info-value">O+</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Informasi Medis</div>
                            <div class="card-action">Lihat Detail</div>
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Alergi</div>
                                <div class="info-value">Tidak ada</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kondisi Kronis</div>
                                <div class="info-value">Hipertensi</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Riwayat Keluarga</div>
                                <div class="info-value">Diabetes, Jantung</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Obat Rutin</div>
                                <div class="info-value">Amlodipine 5mg</div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Janji Temu Mendatang</div>
                            <div class="card-action">Lihat Semua</div>
                        </div>
                        <div class="appointment-list">
                            <div class="appointment-item">
                                <div class="appointment-details">
                                    <div class="appointment-doctor">Dr. Rina Wijaya</div>
                                    <div class="appointment-date">Kamis, 15 Juni 2023 - 10:00 WIB</div>
                                </div>
                                <div class="appointment-status status-confirmed">Dikonfirmasi</div>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-details">
                                    <div class="appointment-doctor">Dr. Andi Pratama</div>
                                    <div class="appointment-date">Senin, 19 Juni 2023 - 14:30 WIB</div>
                                </div>
                                <div class="appointment-status status-pending">Menunggu</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="side-column">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Statistik Kesehatan</div>
                        </div>
                        <div class="profile-stats">
                            <div class="stat-item">
                                <div class="stat-value">72</div>
                                <div class="stat-label">KG</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">175</div>
                                <div class="stat-label">CM</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">24.5</div>
                                <div class="stat-label">BMI</div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Aksi Cepat</div>
                        </div>
                        <div class="quick-actions">
                            <div class="action-btn">
                                <i class="fas fa-calendar-plus action-icon"></i>
                                <div class="action-label">Buat Janji</div>
                            </div>
                            <div class="action-btn">
                                <i class="fas fa-file-prescription action-icon"></i>
                                <div class="action-label">Resep Saya</div>
                            </div>
                            <div class="action-btn">
                                <i class="fas fa-chart-line action-icon"></i>
                                <div class="action-label">Riwayat</div>
                            </div>
                            <div class="action-btn">
                                <i class="fas fa-notes-medical action-icon"></i>
                                <div class="action-label">Laporan</div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Dokter Favorit</div>
                        </div>
                        <div class="appointment-list">
                            <div class="appointment-item">
                                <div class="appointment-details">
                                    <div class="appointment-doctor">Dr. Rina Wijaya</div>
                                    <div class="appointment-date">Dokter Umum</div>
                                </div>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-details">
                                    <div class="appointment-doctor">Dr. Budi Santoso</div>
                                    <div class="appointment-date">Spesialis Jantung</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple JavaScript untuk interaksi
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar menu interaction
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            sidebarItems.forEach(item => {
                item.addEventListener('click', function() {
                    sidebarItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Notification icon interaction
            const notificationIcon = document.querySelector('.notification-icon');
            notificationIcon.addEventListener('click', function() {
                alert('Anda memiliki 3 notifikasi baru');
            });

            // Quick action buttons
            const actionButtons = document.querySelectorAll('.action-btn');
            actionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const label = this.querySelector('.action-label').textContent;
                    alert(`Membuka: ${label}`);
                });
            });
        });
    </script>
</body>
</html>