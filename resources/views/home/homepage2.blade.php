@extends('layouts.app3')
@section('content')
<div class="hero">
  <div class="hero-content">
    <h1>Mitra <span class="gradient-text">terpercaya dalam</span> layanan kesehatan</h1>
    <p>
      Meningkatkan Kesehatan Anda di Setiap Langkah. 
      Nikmati perawatan medis personal dari jarak jauh. 
      Terhubung dengan dokter bersertifikat, dan jadwalkan janji temu dengan mudah. 
      Siap untuk menjaga kesehatan Anda?
    </p>
    <a href="/login" class="btn">Ayo Masuk Sekarang!</a>
  </div>
</div>
@include('home.layanan')
@include('home.about')
@include('home.doktor')

    
@endsection