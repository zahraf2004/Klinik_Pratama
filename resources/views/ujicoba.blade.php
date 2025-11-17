<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Janji Temu - Nakes</title>
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: var(--primary);
            color: white;
            padding: 20px 0;
            box-shadow: var(--box-shadow);
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo h1 {
            font-size: 1.5rem;
            margin-left: 10px;
        }

        .logo-icon {
            font-size: 1.8rem;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }

        .nav-icon {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .greeting h2 {
            font-size: 1.8rem;
            color: var(--dark);
        }

        .date {
            color: var(--gray);
            font-size: 1rem;
        }

        .stats {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 15px;
            box-shadow: var(--box-shadow);
            flex: 1;
            display: flex;
            align-items: center;
        }

        .stat-icon {
            font-size: 2rem;
            margin-right: 15px;
            color: var(--primary);
        }

        .stat-info h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .content-area {
            display: flex;
            gap: 20px;
        }

        /* Filter Section */
        .filter-section {
            width: 250px;
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            height: fit-content;
        }

        .filter-section h3 {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--gray-light);
        }

        .filter-group {
            margin-bottom: 20px;
        }

        .filter-group h4 {
            margin-bottom: 10px;
            font-size: 1rem;
            color: var(--gray);
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .filter-option input {
            margin-right: 8px;
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .calendar th, .calendar td {
            text-align: center;
            padding: 8px 5px;
            font-size: 0.8rem;
        }

        .calendar th {
            color: var(--gray);
            font-weight: normal;
        }

        .calendar .today {
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
        }

        /* Appointment List */
        .appointment-list {
            flex: 1;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .appointment-header h3 {
            font-size: 1.4rem;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: var(--gray-light);
            border-radius: 20px;
            padding: 8px 15px;
            width: 250px;
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            flex: 1;
            margin-left: 8px;
        }

        .appointment-cards {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-height: 600px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .appointment-card {
            border-radius: var(--border-radius);
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-left: 4px solid;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            position: relative;
        }

        .appointment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .appointment-card.confirmed {
            border-left-color: var(--secondary);
        }

        .appointment-card.pending {
            border-left-color: var(--warning);
        }

        .appointment-card.urgent {
            border-left-color: var(--danger);
        }

        .appointment-card.completed {
            border-left-color: var(--gray);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .patient-info {
            display: flex;
            align-items: center;
        }

        .patient-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 12px;
        }

        .patient-details h4 {
            font-size: 1.1rem;
            margin-bottom: 3px;
        }

        .patient-details p {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .appointment-time {
            text-align: right;
        }

        .appointment-time .time {
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--dark);
        }

        .appointment-time .date {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .card-body {
            margin-bottom: 15px;
        }

        .complaint {
            color: var(--dark);
            margin-bottom: 8px;
        }

        .payment-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .payment-status.paid {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--secondary);
        }

        .payment-status.unpaid {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning);
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .online-indicator {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            color: var(--gray);
        }

        .online-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--secondary);
            margin-right: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .btn i {
            margin-right: 5px;
        }

        .btn-detail {
            background-color: var(--gray-light);
            color: var(--dark);
        }

        .btn-chat {
            background-color: var(--primary);
            color: white;
        }

        .btn-start {
            background-color: var(--secondary);
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            margin-left: 5px;
        }

        /* Detail Panel */
        .detail-panel {
            width: 300px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
            height: fit-content;
        }

        .detail-panel h3 {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--gray-light);
        }

        .patient-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--gray-light);
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .profile-name {
            font-size: 1.3rem;
            margin-bottom: 5px;
        }

        .profile-age {
            color: var(--gray);
            margin-bottom: 10px;
        }

        .profile-contact {
            display: flex;
            gap: 10px;
        }

        .contact-btn {
            padding: 6px 12px;
            border-radius: 4px;
            background-color: var(--gray-light);
            color: var(--dark);
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .contact-btn i {
            margin-right: 5px;
        }

        .medical-info {
            margin-bottom: 20px;
        }

        .info-section {
            margin-bottom: 15px;
        }

        .info-section h4 {
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .info-list {
            list-style: none;
        }

        .info-list li {
            padding: 5px 0;
            border-bottom: 1px solid var(--gray-light);
        }

        .chat-history {
            margin-bottom: 20px;
        }

        .chat-messages {
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 10px;
        }

        .chat-message {
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .message-in {
            background-color: var(--gray-light);
            margin-right: 20px;
        }

        .message-out {
            background-color: rgba(52, 152, 219, 0.2);
            margin-left: 20px;
        }

        .chat-input {
            display: flex;
            margin-top: 10px;
        }

        .chat-input input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid var(--gray-light);
            border-radius: 20px 0 0 20px;
            outline: none;
        }

        .chat-input button {
            padding: 8px 15px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0 20px 20px 0;
            cursor: pointer;
        }

        .action-buttons-large {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-large {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }

        .btn-call {
            background-color: var(--secondary);
            color: white;
        }

        .btn-cancel {
            background-color: var(--danger);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .content-area {
                flex-direction: column;
            }
            
            .filter-section, .detail-panel {
                width: 100%;
            }
            
            .detail-panel {
                order: -1;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .stats {
                flex-direction: column;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon"><i class="fas fa-stethoscope"></i></div>
                <h1>MediCare</h1>
            </div>
            <ul class="nav-menu">
                <li class="nav-item active">
                    <i class="fas fa-calendar-alt nav-icon"></i>
                    <span>Janji Temu</span>
                </li>
                <li class="nav-item">
                    <i class="fas fa-comments nav-icon"></i>
                    <span>Pesan</span>
                    <span class="badge">3</span>
                </li>
                <li class="nav-item">
                    <i class="fas fa-users nav-icon"></i>
                    <span>Pasien</span>
                </li>
                <li class="nav-item">
                    <i class="fas fa-file-medical nav-icon"></i>
                    <span>Rekam Medis</span>
                </li>
                <li class="nav-item">
                    <i class="fas fa-cog nav-icon"></i>
                    <span>Pengaturan</span>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="greeting">
                    <h2>Selamat Pagi, dr. Andi!</h2>
                    <p class="date">Senin, 15 Juli 2023</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <h3>8</h3>
                        <p>Janji Hari Ini</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <h3>2</h3>
                        <p>Menunggu Konfirmasi</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
                    <div class="stat-info">
                        <h3>1</h3>
                        <p>Urgent</p>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Filter Section -->
                <div class="filter-section">
                    <h3>Filter</h3>
                    <div class="filter-group">
                        <h4>Status</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" checked> Semua
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" checked> Dikonfirmasi
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" checked> Menunggu
                            </label>
                            <label class="filter-option">
                                <input type="checkbox"> Urgent
                            </label>
                            <label class="filter-option">
                                <input type="checkbox"> Selesai
                            </label>
                        </div>
                    </div>
                    <div class="filter-group">
                        <h4>Tanggal</h4>
                        <table class="calendar">
                            <thead>
                                <tr>
                                    <th>M</th>
                                    <th>S</th>
                                    <th>S</th>
                                    <th>R</th>
                                    <th>K</th>
                                    <th>J</th>
                                    <th>S</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>10</td>
                                    <td>11</td>
                                    <td>12</td>
                                    <td>13</td>
                                    <td>14</td>
                                    <td class="today">15</td>
                                    <td>16</td>
                                </tr>
                                <tr>
                                    <td>17</td>
                                    <td>18</td>
                                    <td>19</td>
                                    <td>20</td>
                                    <td>21</td>
                                    <td>22</td>
                                    <td>23</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Appointment List -->
                <div class="appointment-list">
                    <div class="appointment-header">
                        <h3>Janji Temu Hari Ini</h3>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Cari pasien...">
                        </div>
                    </div>
                    <div class="appointment-cards">
                        <!-- Appointment Card 1 - Urgent -->
                        <div class="appointment-card urgent" onclick="selectAppointment(1)">
                            <div class="card-header">
                                <div class="patient-info">
                                    <div class="patient-avatar">BS</div>
                                    <div class="patient-details">
                                        <h4>Budi Santoso <i class="fas fa-circle online-dot"></i></h4>
                                        <p>45 Tahun • Laki-laki</p>
                                    </div>
                                </div>
                                <div class="appointment-time">
                                    <div class="time">10:00 - 10:30</div>
                                    <div class="date">Hari ini</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="complaint">Konsultasi Hasil Lab & Nyeri Dada</p>
                                <span class="payment-status unpaid">Belum Bayar</span>
                            </div>
                            <div class="card-footer">
                                <div class="online-indicator">
                                    <div class="online-dot"></div>
                                    <span>Online</span>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn btn-detail"><i class="fas fa-info-circle"></i> Detail</button>
                                    <button class="btn btn-chat"><i class="fas fa-comment"></i> Chat <span class="badge">2</span></button>
                                    <button class="btn btn-start"><i class="fas fa-play"></i> Mulai</button>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Card 2 - Confirmed -->
                        <div class="appointment-card confirmed" onclick="selectAppointment(2)">
                            <div class="card-header">
                                <div class="patient-info">
                                    <div class="patient-avatar">SD</div>
                                    <div class="patient-details">
                                        <h4>Sari Dewi <i class="fas fa-circle online-dot"></i></h4>
                                        <p>32 Tahun • Perempuan</p>
                                    </div>
                                </div>
                                <div class="appointment-time">
                                    <div class="time">11:00 - 11:30</div>
                                    <div class="date">Hari ini</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="complaint">Kontrol tekanan darah rutin</p>
                                <span class="payment-status paid">Lunas</span>
                            </div>
                            <div class="card-footer">
                                <div class="online-indicator">
                                    <div class="online-dot"></div>
                                    <span>Online</span>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn btn-detail"><i class="fas fa-info-circle"></i> Detail</button>
                                    <button class="btn btn-chat"><i class="fas fa-comment"></i> Chat</button>
                                    <button class="btn btn-start"><i class="fas fa-play"></i> Mulai</button>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Card 3 - Pending -->
                        <div class="appointment-card pending" onclick="selectAppointment(3)">
                            <div class="card-header">
                                <div class="patient-info">
                                    <div class="patient-avatar">AM</div>
                                    <div class="patient-details">
                                        <h4>Ahmad Maulana</h4>
                                        <p>28 Tahun • Laki-laki</p>
                                    </div>
                                </div>
                                <div class="appointment-time">
                                    <div class="time">13:15 - 13:45</div>
                                    <div class="date">Hari ini</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="complaint">Periksa mata & keluhan sakit kepala</p>
                                <span class="payment-status unpaid">Belum Bayar</span>
                            </div>
                            <div class="card-footer">
                                <div class="online-indicator">
                                    <div class="online-dot" style="background-color: #ccc;"></div>
                                    <span>Offline</span>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn btn-detail"><i class="fas fa-info-circle"></i> Detail</button>
                                    <button class="btn btn-chat"><i class="fas fa-comment"></i> Chat</button>
                                    <button class="btn btn-start"><i class="fas fa-play"></i> Konfirmasi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Panel -->
                <div class="detail-panel">
                    <h3>Detail Pasien</h3>
                    <div class="patient-profile">
                        <div class="profile-avatar">BS</div>
                        <div class="profile-name">Budi Santoso</div>
                        <div class="profile-age">45 Tahun • Laki-laki</div>
                        <div class="profile-contact">
                            <div class="contact-btn"><i class="fas fa-phone"></i></div>
                            <div class="contact-btn"><i class="fas fa-video"></i></div>
                        </div>
                    </div>

                    <div class="medical-info">
                        <div class="info-section">
                            <h4>Riwayat Medis</h4>
                            <ul class="info-list">
                                <li>Hipertensi (2018 - sekarang)</li>
                                <li>Diabetes Tipe 2 (2020 - sekarang)</li>
                                <li>Alergi: -</li>
                            </ul>
                        </div>

                        <div class="info-section">
                            <h4>Janji Temu Ini</h4>
                            <ul class="info-list">
                                <li>Konsultasi Hasil Lab</li>
                                <li>Keluhan: Nyeri dada</li>
                                <li>Durasi: 30 menit</li>
                            </ul>
                        </div>
                    </div>

                    <div class="chat-history">
                        <h4>Percakapan Terakhir</h4>
                        <div class="chat-messages">
                            <div class="chat-message message-in">
                                Selamat pagi dok, saya sudah sampai
                            </div>
                            <div class="chat-message message-out">
                                Baik, silakan tunggu sebentar
                            </div>
                            <div class="chat-message message-in">
                                Saya bawa hasil lab seperti yang disarankan
                            </div>
                        </div>
                        <div class="chat-input">
                            <input type="text" placeholder="Ketik pesan...">
                            <button><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>

                    <div class="action-buttons-large">
                        <button class="btn-large btn-call"><i class="fas fa-phone"></i> Hubungi via Telepon</button>
                        <button class="btn-large btn-cancel"><i class="fas fa-times"></i> Batalkan Janji</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk memilih janji temu (akan memperbarui panel detail)
        function selectAppointment(id) {
            // Dalam implementasi nyata, ini akan mengambil data dari database/API
            // Di sini kita hanya menampilkan alert sebagai contoh
            alert('Janji temu dengan ID ' + id + ' dipilih. Panel detail akan diperbarui.');
            
            // Dalam implementasi nyata, kode berikut akan memperbarui panel detail
            // dengan data yang sesuai dengan janji temu yang dipilih
        }

        // Event listener untuk tombol chat di card janji temu
        document.querySelectorAll('.btn-chat').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Mencegah event bubbling ke card
                alert('Membuka chat dengan pasien...');
                // Dalam implementasi nyata, ini akan membuka/memperbarui panel chat
            });
        });

        // Event listener untuk tombol detail
        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Mencegah event bubbling ke card
                alert('Menampilkan detail lengkap pasien...');
                // Dalam implementasi nyata, ini akan memperbarui panel detail
            });
        });

        // Event listener untuk tombol mulai/konfirmasi
        document.querySelectorAll('.btn-start').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Mencegah event bubbling ke card
                alert('Memulai janji temu...');
                // Dalam implementasi nyata, ini akan mengubah status janji temu
            });
        });
    </script>
</body>
</html>