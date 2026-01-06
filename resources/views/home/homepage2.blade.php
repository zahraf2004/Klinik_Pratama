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
      <a href="/login" class="btn-hero">Ayo Masuk Sekarang!</a>
    </div>

    <!-- IMAGE -->
    <div class="hero-image">
      <img src="/img/doktor.png" alt="Dokter">
    </div>

  </div>
</section>

@include('home.layanan')
@include('home.about')
@include('home.doktor')
@include('dashboard.review')

    
@endsection