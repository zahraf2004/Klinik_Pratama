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
                <form method="GET" action="{{ route('konsultasi.index') }}" class="konsul-search-container" id="searchForm">
                    <input type="text" name="search" id="searchInput" value="{{ $search }}" placeholder="Cari dokter berdasarkan nama, STR, SIP, atau email..." autocomplete="off">
                    <button type="submit"><i class="fas fa-search"></i> Cari</button>
                    @if($search)
                        <a href="{{ route('konsultasi.index') }}" class="konsul-btn-clear" title="Clear search">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>

                <!-- Search Results Info -->
                @if($search)
                    <div class="konsul-search-info">
                        <p>
                            <i class="fas fa-info-circle"></i> 
                            Menampilkan <strong>{{ $totalResults }}</strong> hasil untuk "<strong>{{ $search }}</strong>"
                            @if($totalResults == 0)
                                <span style="color: #e74c3c;">- Tidak ada dokter yang ditemukan</span>
                            @endif
                        </p>
                    </div>
                @endif

                <div class="konsul-section-card">
                    <h2 class="konsul-sectiontitle">
                        <i class="fas fa-user-md"></i> Dokter Tersedia
                        @if(!$search)
                            <span style="font-size: 14px; font-weight: normal; color: #666;">({{ $totalResults }} dokter)</span>
                        @endif
                    </h2>

                    @if($nakes->count() > 0)
                    <div class="konsul-doctors-grid">

                        @foreach($nakes as $nakesItem)
                        <div class="konsul-doctor-card">

                            <div class="konsul-doctor-header">
                                <div class="konsul-doctor-avatar-sm" style="position: relative;">
                                    @if($nakesItem->foto_url)
                                        <img src="{{ $nakesItem->foto_url }}" alt="{{ $nakesItem->nama }}">
                                    @else
                                        <img src="{{ asset('assets/img/avatar/avatar-1.png') }}" alt="Default Avatar">
                                    @endif
                                    @if($nakesItem->user_id)
                                        <span class="online-indicator" style="position: absolute; bottom: 5px; right: 5px; width: 12px; height: 12px; background: #4CAF50; border: 2px solid white; border-radius: 50%;" title="Tersedia untuk chat"></span>
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

                                <!-- Jadwal Praktik dengan Jam -->
                                @if($nakesItem->jadwal_shift && count($nakesItem->jadwal_shift) > 0)
                                <div class="konsul-info-item">
                                    <div class="konsul-info-icon"><i class="fas fa-calendar-check"></i></div>
                                    <div class="konsul-info-text">
                                        <h4>Jadwal Praktik</h4>
                                        <div style="line-height: 1.8;">
                                            @php
                                                $jadwalList = [];
                                                foreach($nakesItem->jadwal_shift as $jadwal) {
                                                    if (isset($jadwal['hari'])) {
                                                        $hari = $jadwal['hari'];
                                                        $jamMulai = $jadwal['jam_mulai'] ?? '';
                                                        $jamSelesai = $jadwal['jam_selesai'] ?? '';
                                                        
                                                        if ($jamMulai && $jamSelesai) {
                                                            $jadwalList[] = "$hari ($jamMulai - $jamSelesai)";
                                                        } else {
                                                            $jadwalList[] = $hari;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @if(count($jadwalList) > 0)
                                                @foreach($jadwalList as $jadwal)
                                                    <div style="margin-bottom: 4px;">• {{ $jadwal }}</div>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Jadwal belum diatur</span>
                                            @endif
                                        </div>
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
                                    @if($nakesItem->user_id)
                                        <a href="{{ route('chatify.user', ['id' => $nakesItem->user_id]) }}" class="konsul-btnD konsul-btn-primary">
                                            <i class="fas fa-comments"></i> Chat Sekarang
                                        </a>
                                    @else
                                        <button class="konsul-btnD konsul-btn-primary" disabled title="Dokter belum terhubung ke sistem chat">
                                            <i class="fas fa-comments"></i> Chat Tidak Tersedia
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>

                    @if($nakes->hasPages())
                    <div class="konsul-pagination">
                        {{ $nakes->links() }}
                    </div>
                    @endif

                    @else
                    <!-- No Results -->
                    <div class="konsul-no-results">
                        <div class="konsul-no-results-icon">
                            <i class="fas fa-user-md-slash fa-3x"></i>
                        </div>
                        <h3>Tidak Ada Dokter Ditemukan</h3>
                        <p>Maaf, tidak ada dokter yang sesuai dengan pencarian "<strong>{{ $search }}</strong>"</p>
                        <a href="{{ route('konsultasi.index') }}" class="konsul-btnD konsul-btn-primary">
                            <i class="fas fa-arrow-left"></i> Lihat Semua Dokter
                        </a>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
