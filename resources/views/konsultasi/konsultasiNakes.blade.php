@extends('layouts/app3')
@section('content')
<div class="konsul-telemedicine">
    <div class="konsul-main-content">

        <!-- Kolom kiri -->
        <div class="konsul-left-column">
            <section class="konsul-section-card">
                <h2 class="konsul-sectiontitle">
                    <i class="fas fa-laptop-medical"></i> Telemedicine
                </h2>

                <div class="konsul-telemedicine-content">
                    <p>Telemedicine adalah layanan ...</p>

                    <div class="konsul-telemedicine-image">
                        <i class="fa-solid fa-comments" style="color:#4a83d3"></i>
                    </div>

                    <h3 class="konsul-benefit-title">Manfaat Telemedicine:</h3>

                    <ul class="konsul-benefits-list">
                        <li>Konsultasi dirumah tanpa antri</li>
                        <li>Menghemat waktu dan biaya transportasi</li>
                        <li>Akses ke dokter lebih mudah</li>
                        <li>Efisiensi bagi Penyedia Layanan Kesehatan</li>
                        <li>Pemantauan Kesehatan Berkelanjutan</li>
                    </ul>
                </div>
            </section>
        </div>

        <!-- Kolom kanan -->
        <div class="konsul-right-column">

            <div class="konsul-doctors-section">
                <form method="GET" action="{{ route('konsultasi.index') }}" class="konsul-search-container">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama atau profesi...">
                    <button><i class="fas fa-search"></i> Cari</button>
                </form>

                <div class="konsul-section-card">
                    <h2 class="konsul-sectiontitle">
                        <i class="fas fa-user-md"></i> Tenaga Kesehatan
                    </h2>

                    <div class="konsul-doctors-grid">

                        @foreach($nakes as $nakesItem)
                        <div class="konsul-doctor-card">

                            <div class="konsul-doctor-header">
                                <div class="konsul-doctor-avatar-sm">
                                    @if($nakesItem->foto_url)
                                        <img src="{{ $nakesItem->foto_url }}">
                                    @else
                                        {{ strtoupper(substr($nakesItem->nama, 0, 1)) }}
                                    @endif
                                </div>

                                <div class="konsul-doctor-info-sm">
                                    <h3 class="konsul-doctor-name">{{ $nakesItem->nama }}</h3>
                                    <p class="konsul-doctor-specialty">{{ $nakesItem->profesi }}</p>
                                </div>
                            </div>

                            <div class="konsul-doctor-info-card">
                                <div class="konsul-info-item">
                                    <div class="konsul-info-icon"><i class="fas fa-star"></i></div>
                                    <div class="konsul-info-text">
                                        <h4>Alumni</h4>
                                        <p>{{ $nakesItem->alumnus }}</p>
                                    </div>
                                </div>

                                <div class="konsul-info-item">
                                    <div class="konsul-info-icon"><i class="fas fa-clock"></i></div>
                                    <div class="konsul-info-text">
                                        <h4>Tanggal Lahir</h4>
                                        <p>{{ $nakesItem->tanggal_lahir }}</p>
                                    </div>
                                </div>

                                <div class="konsul-action-buttons">
                                    <a href="/chatify" class="konsul-btnD konsul-btn-primary">Chat Sekarang</a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>

                    <div class="konsul-pagination">
                        {{ $nakes->links() }}
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
