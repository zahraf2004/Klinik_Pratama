@extends('layouts.app4')

@section('content')
<section class="hero-services">
  <div class="hero-overlay">
    <div class="hero-text">
      <h1>Janji Berobat</h1>
      <p>Atur janji temu dengan dokter dengan mudah, cepat, dan tanpa antri lama.</p>
    </div>
  </div>
</section>

<div class="appointment-wrapper">
  <div class="appointment-info">
    <h2>Tentang Layanan Janji Berobat</h2>
    <p>Kami membantu Anda mendapatkan waktu konsultasi yang sesuai dengan dokter terbaik kami.</p>

    <div class="appointment-features">
      <div class="feature-item">
        <div class="icon icon-blue"><i class="fa-solid fa-clock"></i></div>
        <div><h4>Hemat Waktu</h4><p>Kurangi waktu tunggu dengan jadwal yang pasti.</p></div>
      </div>
      <div class="feature-item">
        <div class="icon icon-green"><i class="fa-solid fa-user-doctor"></i></div>
        <div><h4>Dokter Terbaik</h4><p>Konsultasi dengan tenaga medis berpengalaman.</p></div>
      </div>
      <div class="feature-item">
        <div class="icon icon-yellow"><i class="fa-solid fa-mobile-screen-button"></i></div>
        <div><h4>Mudah Digunakan</h4><p>Proses pembuatan janji cepat dan praktis.</p></div>
      </div>
    </div>
  </div>

  <div class="appointment-status-side">
    <h2>Status Janji Anda</h2>

    <div class="appointment-status empty">
      <div class="status-icon">📅</div>
      <h3>Belum Ada Janji</h3>
      <p>Yuk, buat janji berobat pertama Anda sekarang!</p>

      @auth
        <a href="javascript:void(0)" class="btn btn-primary" id="btnTambah">+ Buat Janji</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-primary">+ Buat Janji</a>
      @endauth
    </div>

    <div class="appointment-list" style="display: none;"></div>
  </div>
</div>

@include('layanan._modalFormAppoint')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@auth
  <script src="{{ asset('js/data_janji.js') }}"></script>
@endauth
@endsection
