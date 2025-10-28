@extends('layouts.app4')

@section('content')
<!-- Hero Section -->
<section class="hero-services">
  <div class="hero-overlay">
    <div class="hero-text">
      <h1>Layanan Lainnya</h1>
      <p>Kami menyediakan berbagai layanan penunjang kesehatan untuk mendukung Anda</p>
    </div>
  </div>
</section>

<!-- Services Section -->
<section class="services-section">
  <div class="container">
    <div class="services-grid">
      <!-- Card Resep Obat -->
      <div class="keterangan-layanan-card">
        <div class="keterangan-layanan-image">
          <img src="img/obat.jpg" alt="Resep Obat">
        </div>
        <div class="keterangan-layanan-content">
          <h3>Resep Obat</h3>
          <p>
            Pasien dapat memperoleh resep obat dari dokter dan menebusnya
            langsung di klinik dengan obat yang terjamin kualitasnya.
          </p>
        </div>
      </div>

      <!-- Card Layanan Kesehatan -->
      <div class="keterangan-layanan-card">
        <div class="keterangan-layanan-image">
          <img src="img/layanan1.jpg" alt="Layanan Kesehatan">
        </div>
        <div class="keterangan-layanan-content">
          <h3>Layanan Kesehatan</h3>
          <p>
            Meliputi pemeriksaan kesehatan umum, pemeriksaan tekanan darah,
            konsultasi ringan, hingga tindakan medis dasar.
          </p>
        </div>
      </div>

      <!-- Card Laboratorium -->
      <div class="keterangan-layanan-card">
        <div class="keterangan-layanan-image">
          <img src="img/labor.jpg" alt="Laboratorium Kesehatan">
        </div>
        <div class="keterangan-layanan-content">
          <h3>Laboratorium Kesehatan</h3>
          <p>
            Menyediakan layanan pemeriksaan laboratorium dasar untuk
            mendukung diagnosis dan perawatan kesehatan pasien.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection