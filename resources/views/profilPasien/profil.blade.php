@extends('layouts.app3')
@section('content')
    <div class="pasien-container">
        <div class="pasien-main-content">
            <div class="pasien-content-area">
                <div class="main-column">
                    <div class="pasien-card">
                        <div class="pasien-card-header">
                            <div class="pasien-card-title">Profil Pasien</div>
                            <a href="javascript:void(0)" class="pasien-card-action" id="btnEditProfil">Edit Profil</a>
                        </div>
                        <div class="pasien-profile-info">
                            <div class="pasien-profile-avatar">
                                @if($profilPasien->foto)
                                    <img src="{{ asset('storage/'.$profilPasien->foto) }}" alt="Foto Profil">
                                @else
                                    {{ substr($user->name, 0, 2) }}
                                @endif
                            </div>

                            <div class="pasien-profile-details">
                                <div class="pasien-profile-name">{{ $user->name }}</div>
                                <div class="pasien-profile-id">ID Pasien: P-{{ $user->id }}</div>

                                <div class="pasien-info-grid">
                                    <div class="pasien-info-item">
                                        <div class="pasien-info-label">Tanggal Lahir</div>
                                        <div class="pasien-info-value">
                                            {{ $profilPasien->tanggal_lahir ? $profilPasien->tanggal_lahir->format('d F Y') : '-' }}
                                        </div>
                                    </div>

                                    <div class="pasien-info-item">
                                        <div class="pasien-info-label">Jenis Kelamin</div>
                                        <div class="pasien-info-value">{{ $profilPasien->jenis_kelamin ?? '-' }}</div>
                                    </div>

                                    <div class="pasien-info-item">
                                        <div class="pasien-info-label">No. Telepon</div>
                                        <div class="pasien-info-value">{{ $profilPasien->no_hp ?? '-' }}</div>
                                    </div>

                                    <div class="pasien-info-item">
                                        <div class="pasien-info-label">Email</div>
                                        <div class="pasien-info-value">{{ $user->email }}</div>
                                    </div>

                                    <div class="pasien-info-item">
                                        <div class="pasien-info-label">Alamat</div>
                                        <div class="pasien-info-value">{{ $profilPasien->alamat ?? '-' }}</div>
                                    </div>

                                    <div class="pasien-info-item">
                                        <div class="pasien-info-label">Golongan Darah</div>
                                        <div class="pasien-info-value">{{ $profilPasien->golongan_darah ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pasien-card">
                        <div class="pasien-card-header">
                            <div class="pasien-card-title">Janji Temu Mendatang</div>
                            <a href="#" class="pasien-card-action">Lihat Semua</a>
                        </div>

                        <div class="pasien-appointment-list">
                            @forelse ($appointments as $appt)
                                <div class="pasien-appointment-item">
                                    <div class="pasien-appointment-details">
                                        <!-- Nama pasien (ambil dari kolom nama_pasien, fallback ke user jika kosong) -->
                                        <div class="pasien-appointment-doctor">
                                            {{ $appt->nama_pasien ?? $user->name }}
                                        </div>

                                        <!-- Tanggal (pakai field tanggal_indo yang sudah diset di controller) + jam -->
                                        <div class="pasien-appointment-date">
                                            {{ $appt->tanggal_indo }} - {{ \Carbon\Carbon::parse($appt->jam)->format('H:i') }} WIB
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="pasien-appointment-status {{ $appt->status == 'dikonfirmasi' ? 'pasien-status-confirmed' : 'pasien-status-pending' }}">
                                        {{ ucfirst($appt->status) }}
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 px-4 py-2">Belum ada janji temu.</p>
                            @endforelse
                        </div>
                    </div>

                </div>

                <div class="side-column">
                    <div class="pasien-card">
                        <div class="pasien-card-header">
                            <div class="pasien-card-title">Statistik Kesehatan</div>
                        </div>

                        <div class="pasien-profile-stats">
                            <div class="pasien-stat-item">
                                <div class="pasien-stat-value">{{ $profilPasien->berat_badan ?? '-' }}</div>
                                <div class="pasien-stat-label">KG</div>
                            </div>

                            <div class="pasien-stat-item">
                                <div class="pasien-stat-value">{{ $profilPasien->tinggi_badan ?? '-' }}</div>
                                <div class="pasien-stat-label">CM</div>
                            </div>

                            <div class="pasien-stat-item">
                                <div class="pasien-stat-value">{{ $profilPasien->bmi ?? '-' }}</div>
                                <div class="pasien-stat-label">BMI</div>
                            </div>
                        </div>
                    </div>

                    <div class="pasien-card">
                        <div class="pasien-card-header">
                            <div class="pasien-card-title">Aksi Cepat</div>
                        </div>

                        <div class="pasien-quick-actions">
                            <a href="/Janji-Berobat" class="pasien-action-btn">
                                <i class="fas fa-calendar-plus pasien-action-icon"></i>
                                <div class="pasien-action-label">Buat Janji</div>
                            </a>

                            <a href="/Janji-Berobat" class="pasien-action-btn">
                                <i class="fa-solid fa-headset pasien-action-icon"></i>
                                <div class="pasien-action-label">Chat Online</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Popup -->
    @include('profilPasien._modalprofil')    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @auth
    <script>
        // Define global variables untuk digunakan di file external
        window.profilUpdateUrl = "{{ route('pasien.profil.update') }}";
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/profil_pasien.js') }}"></script>
    @endauth
@endsection
