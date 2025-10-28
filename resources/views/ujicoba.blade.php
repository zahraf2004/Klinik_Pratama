<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Janji Berobat - Klinik Sehat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, #1e88e5, #0d47a1);
            color: white;
            padding: 30px 0;
            text-align: center;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        header h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        
        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .page {
            display: none;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }
        
        .active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .intro-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .intro-icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: #1e88e5;
        }
        
        h2 {
            color: #1e88e5;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e3f2fd;
        }
        
        p {
            margin-bottom: 20px;
            font-size: 1.1rem;
            color: #555;
        }
        
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 30px 0;
        }
        
        .feature {
            flex: 1;
            min-width: 200px;
            text-align: center;
            padding: 20px;
            background: #f8fbff;
            border-radius: 8px;
            border: 1px solid #e3f2fd;
        }
        
        .feature-icon {
            font-size: 40px;
            margin-bottom: 15px;
            color: #1e88e5;
        }
        
        .feature h3 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .btn {
            display: inline-block;
            background: #1e88e5;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            margin-top: 10px;
        }
        
        .btn:hover {
            background: #1565c0;
        }
        
        .btn-secondary {
            background: #f5f5f5;
            color: #333;
            margin-right: 10px;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: #1e88e5;
            outline: none;
            box-shadow: 0 0 0 2px rgba(30, 136, 229, 0.2);
        }
        
        .appointment-status {
            text-align: center;
            padding: 30px;
            background: #f8fbff;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .status-icon {
            font-size: 60px;
            margin-bottom: 20px;
            color: #ff9800;
        }
        
        .appointment-list {
            margin-top: 30px;
        }
        
        .appointment-item {
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #fafafa;
        }
        
        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .appointment-date {
            font-weight: bold;
            color: #1e88e5;
        }
        
        .appointment-doctor {
            font-weight: 600;
        }
        
        footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            color: #666;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .features {
                flex-direction: column;
            }
            
            .feature {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Janji Berobat - Klinik Sehat</h1>
            <p>Layanan kesehatan terpercaya untuk Anda dan keluarga</p>
        </div>
    </header>
    
    <div class="container">
        <!-- Halaman Pengenalan -->
        <div id="intro-page" class="page active">
            <div class="intro-content">
                <div class="intro-icon">🏥</div>
                <h2>Selamat Datang di Layanan Janji Berobat</h2>
                <p>Kami menyediakan layanan kesehatan terbaik dengan dokter berpengalaman. Dengan membuat janji berobat, Anda dapat memilih waktu yang tepat dan mengurangi waktu tunggu.</p>
                
                <div class="features">
                    <div class="feature">
                        <div class="feature-icon">⏰</div>
                        <h3>Hemat Waktu</h3>
                        <p>Kurangi waktu tunggu dengan janji terjadwal</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">👨‍⚕️</div>
                        <h3>Dokter Terbaik</h3>
                        <p>Konsultasi dengan dokter berpengalaman</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">📱</div>
                        <h3>Mudah Digunakan</h3>
                        <p>Proses pembuatan janji yang sederhana</p>
                    </div>
                </div>
                
                <button id="start-btn" class="btn">Mulai Buat Janji Berobat</button>
            </div>
        </div>
        
        <!-- Halaman Status Janji -->
        <div id="status-page" class="page">
            <h2>Status Janji Berobat Anda</h2>
            
            <div class="appointment-status">
                <div class="status-icon">📅</div>
                <h3>Anda belum pernah membuat janji berobat</h3>
                <p>Yuk, buat janji berobat pertama Anda sekarang dan dapatkan pelayanan terbaik dari kami.</p>
                <button id="create-appointment-btn" class="btn">Buat Janji Sekarang</button>
            </div>
            
            <!-- Jika sudah ada janji, tampilkan di sini -->
            <div class="appointment-list" style="display: none;">
                <h3>Janji Berobat Aktif</h3>
                <!-- Contoh janji berobat -->
                <div class="appointment-item">
                    <div class="appointment-header">
                        <span class="appointment-date">15 November 2023 - 10:00</span>
                        <span class="appointment-status-badge" style="background: #4caf50; color: white; padding: 5px 10px; border-radius: 20px;">Terkonfirmasi</span>
                    </div>
                    <p class="appointment-doctor">dr. Ahmad Wijaya - Spesialis Penyakit Dalam</p>
                    <p>Keluhan: Pusing dan demam selama 3 hari</p>
                </div>
            </div>
        </div>
        
        <!-- Halaman Form Janji Berobat -->
        <div id="form-page" class="page">
            <h2>Form Janji Berobat</h2>
            <form id="appointmentForm">
                <div class="form-group">
                    <label for="fullName">Nama Lengkap</label>
                    <input type="text" id="fullName" name="fullName" required placeholder="Masukkan nama lengkap">
                </div>
                
                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" required placeholder="Contoh: 081234567890">
                </div>
                
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" placeholder="nama@contoh.com">
                </div>
                
                <div class="form-group">
                    <label for="doctor">Pilih Dokter</label>
                    <select id="doctor" name="doctor" required>
                        <option value="">-- Pilih Dokter --</option>
                        <option value="dr. Ahmad Wijaya">dr. Ahmad Wijaya (Spesialis Penyakit Dalam)</option>
                        <option value="dr. Sari Indah">dr. Sari Indah (Spesialis Anak)</option>
                        <option value="dr. Budi Santoso">dr. Budi Santoso (Spesialis Kulit)</option>
                        <option value="dr. Maya Sari">dr. Maya Sari (Spesialis Kandungan)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date">Tanggal Berobat</label>
                    <input type="date" id="date" name="date" required>
                </div>
                
                <div class="form-group">
                    <label for="time">Waktu Berobat</label>
                    <select id="time" name="time" required>
                        <option value="">-- Pilih Waktu --</option>
                        <option value="08:00">08:00</option>
                        <option value="09:00">09:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="complaint">Keluhan</label>
                    <textarea id="complaint" name="complaint" rows="4" placeholder="Jelaskan keluhan Anda"></textarea>
                </div>
                
                <button type="submit" class="btn">Buat Janji Berobat</button>
                <button type="button" id="back-to-status" class="btn btn-secondary">Kembali</button>
            </form>
        </div>
        
        <!-- Halaman Konfirmasi -->
        <div id="confirmation-page" class="page">
            <div class="intro-content">
                <div class="intro-icon" style="color: #4caf50;">✅</div>
                <h2>Janji Berobat Berhasil Dibuat!</h2>
                <p>Terima kasih telah membuat janji berobat di Klinik Sehat. Detail janji berobat Anda:</p>
                
                <div class="appointment-item" style="max-width: 500px; margin: 30px auto;">
                    <div class="appointment-header">
                        <span class="appointment-date" id="confirm-date">15 November 2023 - 10:00</span>
                        <span style="background: #4caf50; color: white; padding: 5px 10px; border-radius: 20px;">Menunggu Konfirmasi</span>
                    </div>
                    <p class="appointment-doctor" id="confirm-doctor">dr. Ahmad Wijaya - Spesialis Penyakit Dalam</p>
                    <p id="confirm-complaint">Keluhan: Pusing dan demam selama 3 hari</p>
                </div>
                
                <p>Kami akan mengirimkan konfirmasi melalui WhatsApp dalam waktu 1x24 jam. Silakan datang 15 menit sebelum waktu janji.</p>
                
                <button id="new-appointment-btn" class="btn">Buat Janji Baru</button>
                <button id="back-to-status2" class="btn btn-secondary">Lihat Status Janji</button>
            </div>
        </div>
    </div>
    
    <footer>
        <div class="container">
            <p>&copy; 2023 Klinik Sehat. Semua hak dilindungi.</p>
        </div>
    </footer>
    
    <script>
        // Set minimum date to today
        document.getElementById('date').min = new Date().toISOString().split('T')[0];
        
        // Navigation between pages
        document.getElementById('start-btn').addEventListener('click', function() {
            // Dalam implementasi nyata, di sini akan ada pengecekan login
            // Untuk simulasi, kita asumsikan user sudah login
            showPage('status-page');
        });
        
        document.getElementById('create-appointment-btn').addEventListener('click', function() {
            showPage('form-page');
        });
        
        document.getElementById('back-to-status').addEventListener('click', function() {
            showPage('status-page');
        });
        
        document.getElementById('back-to-status2').addEventListener('click', function() {
            showPage('status-page');
        });
        
        document.getElementById('new-appointment-btn').addEventListener('click', function() {
            showPage('form-page');
            document.getElementById('appointmentForm').reset();
        });
        
        // Form submission handling
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const fullName = document.getElementById('fullName').value;
            const doctor = document.getElementById('doctor').value;
            const date = document.getElementById('date').value;
            const time = document.getElementById('time').value;
            const complaint = document.getElementById('complaint').value;
            
            // Format date
            const formattedDate = new Date(date).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            // Update confirmation page
            document.getElementById('confirm-date').textContent = `${formattedDate} - ${time}`;
            document.getElementById('confirm-doctor').textContent = doctor;
            document.getElementById('confirm-complaint').textContent = `Keluhan: ${complaint}`;
            
            // Show confirmation page
            showPage('confirmation-page');
        });
        
        // Function to show specific page and hide others
        function showPage(pageId) {
            // Hide all pages
            const pages = document.querySelectorAll('.page');
            pages.forEach(page => {
                page.classList.remove('active');
            });
            
            // Show the requested page
            document.getElementById(pageId).classList.add('active');
        }
    </script>
</body>
</html>