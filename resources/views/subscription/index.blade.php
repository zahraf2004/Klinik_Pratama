@extends('layouts.app')

@section('title', 'Subscription Status')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Status Berlangganan</h4>
                </div>
                <div class="card-body">
                    @if($activeSubscription)
                        <!-- Active Subscription -->
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subscription-icon me-3">
                                        <i class="fas fa-crown fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Berlangganan {{ ucfirst($activeSubscription->plan_name) }}</h5>
                                        <p class="text-muted mb-0">Status: <span class="badge bg-success">Aktif</span></p>
                                    </div>
                                </div>
                                
                                <div class="subscription-details">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p><strong>Mulai:</strong> {{ $activeSubscription->starts_at->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p><strong>Berakhir:</strong> {{ $activeSubscription->expires_at->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p><strong>Sisa Hari:</strong> {{ $activeSubscription->daysRemaining() }} hari</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p><strong>Harga:</strong> Rp {{ number_format($activeSubscription->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="subscription-benefits">
                                    <h6>Benefit Aktif:</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Chat Unlimited</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Konsultasi 24/7</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Prioritas Respon</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @php
                            $totalDays = $activeSubscription->starts_at->diffInDays($activeSubscription->expires_at);
                            $usedDays = $activeSubscription->starts_at->diffInDays(now());
                            $progress = $totalDays > 0 ? ($usedDays / $totalDays) * 100 : 0;
                        @endphp
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Periode Berlangganan</span>
                                <span>{{ number_format($progress, 1) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 text-center">
                            <a href="{{ route('subscription.plans') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-sync-alt me-2"></i>Perpanjang
                            </a>
                            <form action="{{ route('subscription.cancel') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" 
                                        onclick="return confirm('Yakin ingin membatalkan subscription?')">
                                    <i class="fas fa-times me-2"></i>Batalkan
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- No Active Subscription -->
                        <div class="text-center py-4">
                            <div class="mb-4">
                                <i class="fas fa-user-clock fa-4x text-muted mb-3"></i>
                                <h5>Anda Menggunakan Akun Gratis</h5>
                                <p class="text-muted">Sisa chat gratis: <strong>{{ $remainingFreeChats }}</strong> dari 3 chat</p>
                            </div>

                            @if($remainingFreeChats > 0)
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Anda masih bisa chat <strong>{{ $remainingFreeChats }} kali</strong> dengan dokter secara gratis.
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Chat gratis Anda sudah habis. Berlangganan untuk chat unlimited!
                                </div>
                            @endif

                            <div class="mt-4">
                                <a href="{{ route('subscription.plans') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-crown me-2"></i>Berlangganan Sekarang
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-comments fa-2x text-primary mb-3"></i>
                            <h6>Mulai Chat</h6>
                            <p class="text-muted small">Konsultasi dengan dokter</p>
                            <a href="{{ route('konsultasi.index') }}" class="btn btn-primary btn-sm">
                                Chat Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-history fa-2x text-info mb-3"></i>
                            <h6>Riwayat Subscription</h6>
                            <p class="text-muted small">Lihat riwayat berlangganan</p>
                            <a href="{{ route('subscription.history') }}" class="btn btn-info btn-sm">
                                Lihat Riwayat
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-credit-card fa-2x text-success mb-3"></i>
                            <h6>Riwayat Pembayaran</h6>
                            <p class="text-muted small">Lihat semua transaksi</p>
                            <a href="{{ route('payment.history') }}" class="btn btn-success btn-sm">
                                Lihat Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border: none;
}

.subscription-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 193, 7, 0.1);
    border-radius: 50%;
}

.subscription-benefits ul li {
    padding: 2px 0;
}

.progress {
    height: 8px;
    border-radius: 10px;
}

.alert {
    border: none;
    border-radius: 10px;
}

.btn {
    border-radius: 25px;
}
</style>
@endsection