@extends('layouts.app2')
@section('content')
<main>
    <!--? Slider Area Start-->
    <div class="slider-area">
        <div class="slider-active dot-style">
            <!-- Slider Single -->
            <div class="single-slider d-flex align-items-center slider-height">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-7 col-lg-8 col-md-10 ">
                            <div class="hero-wrapper">
                                <!-- Video icon -->
                                <div class="video-icon">
                                    <a class="popup-video btn-icon" href="https://www.youtube.com/watch?v=up68UAfH0d0" data-animation="bounceIn" data-delay=".4s">
                                        <i class="fas fa-play"></i>
                                    </a>
                                </div>
                                <div class="hero__caption">
                                    <h1 data-animation="fadeInUp" data-delay=".3s">Kesehatan adalah kekayaan </h1>
                                    <p data-animation="fadeInUp" data-delay=".6s">Jagalah agar tetap sehat<br> bersama Klinik P  ratama Dokter Yanti</p>
                                    <a href="services.html" class="btn" data-animation="fadeInLeft" data-delay=".3s">Ayo Masuk!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
             
        </div>
    </div>
    <!-- Slider Area End -->

    @include('home.section2')
    @include('home.layanan')
    @include('home.testimoni')
    @include('home.video')
    @include('home.blogobat')            
    <!--? About Law Start-->
    <section class="about-low-area mt-30">
        <div class="container">
            <div class="about-cap-wrapper">
                <div class="row">
                    <div class="col-xl-5  col-lg-6 col-md-10 offset-xl-1">
                        <div class="about-caption mb-50">
                            <!-- Section Tittle -->
                            <div class="section-tittle mb-35">
                                <h2>100% satisfaction guaranteed.</h2>
                            </div>
                            <p>Almost before we knew it, we had left the ground</p>
                            <a href="about.html" class="border-btn">Make an Appointment</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <!-- about-img -->
                        <div class="about-img">
                            <div class="about-font-img">
                                <img src="assets2/img/gallery/about2.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Law End-->
</main>
@endsection