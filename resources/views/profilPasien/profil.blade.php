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

                    {{-- Keterangan Pembayaran Sederhana --}}
                    <div class="pasien-card">
                        <div class="pasien-card-header">
                            <div class="pasien-card-title">Status Chat & Pembayaran</div>
                        </div>

                        <div class="payment-status-simple">
                            @php
                                $recentPayments = Auth::user()->transactions()
                                    ->where('transaction_status', 'settlement')
                                    ->where('description', 'like', '%Berlangganan%')
                                    ->latest()
                                    ->take(3)
                                    ->get();
                                
                                $hasActiveSubscription = Auth::user()->hasActiveSubscription();
                                $remainingTokens = Auth::user()->getRemainingSessionTokens();
                            @endphp

                            {{-- Status Chat --}}
                            <div class="chat-status-info">
                                @if($hasActiveSubscription)
                                    <div class="status-item premium">
                                        <i class="fas fa-crown"></i>
                                        <span><strong>Premium Active</strong> - Chat Unlimited</span>
                                    </div>
                                @else
                                    <div class="status-item free">
                                        <i class="fas fa-comments"></i>
                                        <span>Session Token: <strong>{{ $remainingTokens }}/3</strong> tersisa</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Detail Pembayaran Lengkap --}}
                            @if($recentPayments->count() > 0)
                                <div class="payment-details">
                                    <h6>Detail Pembayaran:</h6>
                                    @foreach($recentPayments as $payment)
                                        <div class="payment-detail-item">
                                            <div class="payment-header">
                                                <div class="payment-desc">{{ $payment->description }}</div>
                                                <div class="payment-status">
                                                    @if($payment->transaction_status === 'settlement')
                                                        <span class="badge-success">Berhasil</span>
                                                    @elseif($payment->transaction_status === 'pending')
                                                        <span class="badge-pending">Pending</span>
                                                    @else
                                                        <span class="badge-failed">{{ ucfirst($payment->transaction_status) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="payment-info-grid">
                                                <div class="info-item">
                                                    <span class="label">Order ID:</span>
                                                    <span class="value">{{ $payment->order_id }}</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="label">Jumlah:</span>
                                                    <span class="value amount">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="label">Tanggal:</span>
                                                    <span class="value">{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                @if($payment->payment_type)
                                                <div class="info-item">
                                                    <span class="label">Metode:</span>
                                                    <span class="value">{{ ucfirst($payment->payment_type) }}</span>
                                                </div>
                                                @endif
                                                @if($payment->transaction_id)
                                                <div class="info-item">
                                                    <span class="label">Transaction ID:</span>
                                                    <span class="value">{{ $payment->transaction_id }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Hidden Fix Link untuk Development --}}
                            @if(app()->environment('local') && $recentPayments->count() > 0)
                                @php $latestPayment = $recentPayments->first(); @endphp
                                @if($latestPayment->transaction_status === 'pending')
                                    <div style="margin-top: 10px; text-align: center;">
                                        <a href="/update-transaction-status/{{ $latestPayment->order_id }}" 
                                           style="font-size: 0.7rem; color: #6c757d; text-decoration: none;"
                                           title="Development: Update transaction status">
                                            [Dev: Update Status]
                                        </a>
                                    </div>
                                @endif
                            @endif
                            
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

                            <a href="/chatify" class="pasien-action-btn">
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
