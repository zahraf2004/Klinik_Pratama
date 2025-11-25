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
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari dokter berdasarkan nama, STR, atau SIP...">
                    <button><i class="fas fa-search"></i> Cari</button>
                </form>

                <div class="konsul-section-card">
                    <h2 class="konsul-sectiontitle">
                        <i class="fas fa-user-md"></i> Dokter Tersedia
                    </h2>

                    <div class="konsul-doctors-grid">

                        @foreach($nakes as $nakesItem)
                        <div class="konsul-doctor-card">

                            <div class="konsul-doctor-header">
                                <div class="konsul-doctor-avatar-sm">
                                    @if($nakesItem->foto_url)
                                        <img src="{{ $nakesItem->foto_url }}" alt="{{ $nakesItem->nama }}">
                                    @else
                                        <img src="{{ asset('assets/img/avatar/avatar-1.png') }}" alt="Default Avatar">
                                    @endif
                                </div>

                                <div class="konsul-doctor-info-sm">
                                    <h3 class="konsul-doctor-name">{{ $nakesItem->nama }}</h3>
                                    <p class="konsul-doctor-specialty">
                                        @if($nakesItem->role === 'dokter_umum')
                                            Dokter Umum
                                        @elseif($nakesItem->role === 'admin')
                                            Admin Kesehatan
                                        @else
                                            {{ ucfirst($nakesItem->role) }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="konsul-doctor-info-card">
                                <!-- Kredensial -->
                                @if($nakesItem->str || $nakesItem->sip)
                                <div class="konsul-info-item">
                                    <div class="konsul-info-icon"><i class="fas fa-certificate"></i></div>
                                    <div class="konsul-info-text">
                                        <h4>Kredensial</h4>
                                        <p>
                                            @if($nakesItem->str)
                                                STR: {{ $nakesItem->str }}
                                            @endif
                                            @if($nakesItem->str && $nakesItem->sip)
                                                <br>
                                            @endif
                                            @if($nakesItem->sip)
                                                SIP: {{ $nakesItem->sip }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endif

                                <!-- Pengalaman -->
                                @if($nakesItem->tahun_mulai)
                                <div class="konsul-info-item">
                                    <div class="konsul-info-icon"><i class="fas fa-briefcase"></i></div>
                                    <div class="konsul-info-text">
                                        <h4>Pengalaman</h4>
                                        <p>{{ $nakesItem->pengalaman }}</p>
                                    </div>
                                </div>
                                @endif

                                <!-- Jadwal Praktik -->
                                @if($nakesItem->jadwal_shift && count($nakesItem->jadwal_shift) > 0)
                                <div class="konsul-info-item">
                                    <div class="konsul-info-icon"><i class="fas fa-calendar-check"></i></div>
                                    <div class="konsul-info-text">
                                        <h4>Jadwal Praktik</h4>
                                        <p>
                                            @php
                                                $hariList = [];
                                                foreach($nakesItem->jadwal_shift as $jadwal) {
                                                    // Cek apakah format baru (hari) atau lama (tanggal_mulai)
                                                    if (isset($jadwal['hari'])) {
                                                        $hariList[] = $jadwal['hari'];
                                                    }
                                                }
                                            @endphp
                                            @if(count($hariList) > 0)
                                                {{ implode(', ', $hariList) }}
                                            @else
                                                <span class="text-muted">Jadwal belum diatur</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endif

                                <!-- Role/Spesialisasi -->
                                <div class="konsul-info-item">
                                    <div class="konsul-info-icon"><i class="fas fa-user-md"></i></div>
                                    <div class="konsul-info-text">
                                        <h4>Spesialisasi</h4>
                                        <p>
                                            @if($nakesItem->role === 'dokter_umum')
                                                Dokter Umum
                                            @elseif($nakesItem->role === 'admin')
                                                Admin Kesehatan
                                            @else
                                                {{ ucfirst($nakesItem->role) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="konsul-action-buttons">
                                    <a href="/chatify" class="konsul-btnD konsul-btn-primary">
                                        <i class="fas fa-comments"></i> Chat Sekarang
                                    </a>
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
