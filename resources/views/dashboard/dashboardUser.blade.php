@extends('layouts.app3')
@section('content')
<section class="hero-wrapper">
  <div class="hero-box">
    
    <!-- TEXT -->
    <div class="hero-text">
      <h1 class="hero-title">
        <span class="title-big">LANGKAH MUDAH</span><br>
        <span class="title-small">MENUJU KESEHATAN</span>
      </h1>
      <p>Cek kesehatan & konsultasi online dengan mudah</p>
      <a href="/Janji-Berobat" class="btn-hero">Buat Janji Berobat Sekarang</a>
    </div>

    <!-- IMAGE -->
    <div class="hero-image">
      <img src="/img/doktor.png" alt="Dokter">
    </div>

  </div>
</section>
@include('dashboard.layanan')
@include('dashboard.obat')
@include('dashboard.review')
@include('dashboard.inputReview')

    
@endsection