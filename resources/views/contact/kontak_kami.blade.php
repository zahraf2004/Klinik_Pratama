@extends('layouts.app3')
@section('content')
<!-- Hero Section -->
    <section class="hero_about">
        <h1>Hubungi Kami</h1>
        <p>Tim Klinik Pratama siap membantu Anda. Kami senang mendengar dari Anda</p>
    </section>

    <div class="container2" style="margin: 50px 50px;">
        <div class="contact-main">
            <div class="contact-info">
                <div class="section-card">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> Informasi Kontak</h2>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Alamat Kami</h3>
                            <p>Jl. Sersan Darphin No.96, Eka Jaya, </p>
                            <p>Kec. Palmerah, Kota Jambi,</p>
                            <p>Jambi Kode Pos 36135</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Telepon</h3>
                            <p><a href="tel:+622112345678">(021) 1234-5678</a></p>
                            <p><a href="tel:+628112345678">0811-2345-678</a> (Emergency)</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email</h3>
                            <p><a href="mailto:info@klinikPratamaDrYanti.com">info@klinikPratamaDrYanti.com</a> (Janji Temu)</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Jam Operasional</h3>
                            <table class="hours-table">
                                <tr>
                                    <td>Setiap hari &nbsp</td>
                                    <td>08:00 - 21:00</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="contactdetails">
                            <h3>Media Sosial</h3>
                            <div class="social-icons" style="display: flex; gap: 15px; margin-top: 10px;">
                                <a href="#" style="width: 45px; height: 45px; background-color: #f5f9ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #1a73e8; font-size: 20px; transition: all 0.3s;">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" style="width: 45px; height: 45px; background-color: #f5f9ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #1a73e8; font-size: 20px; transition: all 0.3s;">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" style="width: 45px; height: 45px; background-color: #f5f9ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #1a73e8; font-size: 20px; transition: all 0.3s;">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="contact-map">
                <div class="map-container">
                    <div class="map-overlay">
                        <div class="map-overlay-content">
                            <h3>Klinik Pratama Dokter Yanti</h3>
                            <p><i class="fas fa-map-marker-alt"></i> Jl. Sersan Darphin No.96, Eka Jaya, Kec. Palmerah, Kota Jambi, Jambi 36135</p>
                        </div>
                        <a href="#" class="directions-btn">
                            <i class="fas fa-route"></i> Petunjuk Arah
                        </a>
                    </div>
                    
                    <div class="map-placeholder">
                        <i class="fas fa-map"></i>
                        <!-- nanti ingeti brooooo ini diatur diganti dengan iframe Google Maps -->
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection