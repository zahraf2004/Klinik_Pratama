@extends('layouts.app3')
@section('content')
<!-- Hero -->
  <section class="hero_about">
    <h1>Klinik Pratama Dokter Yanti</h1>
    <p>Melayani dengan sepenuh hati, memberikan layanan kesehatan terbaik untuk keluarga Anda.</p>
  </section>

  <div class="container_about">
    <section class="about-layout">
      <!-- KIRI: TENTANG -->
      <div class="about-left card_about">
        <h2 class="title_about">Tentang Klinik</h2>
        <img 
          src="/img/foto_klinik_.jpg" 
          alt="Klinik Pratama Dokter Yanti"
          class="about-image"
        >
        <p class="about-text_about">
          Klinik Pratama Dokter Yanti merupakan klinik umum rawat jalan yang didirikan 
          oleh dr. Yanti Supriyatna pada tanggal 16 Agustus 2019. Klinik ini berlokasi di 
          Jalan Sersan Darpin RT. 06 No. 96 Kelurahan Ekajaya Kecamatan Paal Merah Kota Jambi.
        </p>
        <p class="about-text_about">
          Klinik Pratama Dokter Yanti bertujuan memberikan pelayanan kesehatan bermutu bagi
          masyarakat umum, peserta BPJS Kesehatan, serta asuransi kesehatan lainnya.
          Klinik beroperasi setiap hari pukul 08.00 – 21.00.
        </p>
      </div>

      <!-- KANAN: VISI & MISI -->
      <div class="about-right">        
        <!-- VISI -->
        <div class="card_about">
          <h2 class="title_about">Visi & Misi</h2>
          <h3>Visi</h3>
          <p>
            Menjadi klinik pilihan utama masyarakat dengan pelayanan kesehatan yang profesional,
            ramah, dan berorientasi pada pasien.
          </p>
        </div>

        <!-- MISI -->
        <div class="card_about">
          <h3>Misi</h3>
          <ul class="misi-list">
            <li>Memberikan layanan kesehatan prima dengan pemanfaatan teknologi medis</li>
            <li>Mengutamakan kepercayaan dan kepuasan pasien</li>
            <li>Bekerja secara profesional, inovatif, dan berdedikasi</li>
            <li>Memberikan pelayanan kesehatan yang berkualitas</li>
            <li>Menyediakan layanan kesehatan yang terjangkau</li>
          </ul>
        </div>

      </div>
    </section>


    <!-- Motto -->
    <section>
      <h2 class="title_about">Motto</h2>
      <div class="motto_about">
        "Murah, Nyaman, Sehat"
      </div>
    </section>

    <!-- Nilai Klinik -->
    <section>
      <h2 class="title_about">Tata Nilai (SEHAT)</h2>
      <div class="values_about">
        <div class="value-item_about">
          <i class="fa-solid fa-hands-praying" style="color: #FFD43B;"></i>
          <h4>S = SANTUN</h4>
          <p>Sopan dan Ramah dalam tutur kata dan perilaku</p>
        </div>
        <div class="value-item_about">
          <i class="fa-solid fa-heart" style="color: #ef157e;"></i>
          <h4>E = EMPATI</h4>
          <p>Kami melayani pasien dengan sepenuh hati.</p>
        </div>
        <div class="value-item_about">
          <i class="fa-solid fa-dumbbell" style="color: #1c6efd;"></i>
          <h4>H = HANDAL</h4>
          <p>Bekerja secara Profesional dan Berkompeten</p>
        </div>
        <div class="value-item_about">
          <i class="fa-solid fa-scale-balanced" style="color: #068e1d;"></i>
          <h4>A = ADIL</h4>
          <p>Pelayanan diberikan tanpa membeda-bedakan dan merata</p>
        </div>        
        <div class="value-item_about">
          <i class="fas fa-star" style="color: #FFD43B;"></i>
          <h4>T = TELADAN</h4>
          <p>Staff klinik menjadi panutan gerakan hidup sehat masyarakat</p>
        </div>
      </div>
    </section>

  </div>

    
@endsection