@extends('layouts.app4')

@section('content')
<section class="hero-services">
  <div class="hero-overlay">
    <div class="hero-text">
      <h1>Selamat Datang di Layanan Janji Berobat</h1>
      <p>Dengan membuat janji berobat, Anda dapat memilih waktu yang tepat dan mengurangi waktu tunggu.</p>
    </div>
  </div>
</section>

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
@endsection