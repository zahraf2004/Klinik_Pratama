@extends('layouts.app')

@section('title', 'Subscription Aktif!')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white text-center">
                    <i class="fas fa-crown fa-3x mb-3"></i>
                    <h3 class="mb-0">Selamat! Anda Sekarang Premium Member</h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <h4 class="text-success">Subscription Berhasil Diaktifkan!</h4>
                        <p class="lead">Chat unlimited dengan dokter sudah bisa digunakan sekarang.</p>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Anda akan diarahkan ke halaman chat dalam <span id="countdown">5</span> detik...
                        <div class="progress mt-2">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 id="progress-bar" style="width: 100%"></div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="fas fa-receipt me-2"></i>Detail Transaksi</h6>
                                    <p class="mb-1"><strong>Order ID:</strong> {{ $transaction->order_id }}</p>
                                    <p class="mb-1"><strong>Jumlah:</strong> Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</p>
                                    <p class="mb-0"><strong>Status:</strong> <span class="badge bg-success">Berhasil</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="fas fa-crown me-2"></i>Benefit Premium</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-check text-success me-2"></i>Chat Unlimited</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Konsultasi 24/7</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Prioritas Respon</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center">
                        <a href="/chatify" class="btn btn-success btn-lg me-2" id="chat-btn">
                            <i class="fas fa-comments me-2"></i>Mulai Chat Sekarang
                        </a>
                        <a href="{{ route('subscription.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-cog me-2"></i>Kelola Subscription
                        </a>
                    </div>

                    <!-- Skip Auto-redirect -->
                    <div class="mt-3">
                        <button class="btn btn-link btn-sm" onclick="cancelRedirect()">
                            <i class="fas fa-times me-1"></i>Batalkan redirect otomatis
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let countdownTimer;
let redirectTimer;
let countdown = 5;

function startCountdown() {
    const countdownElement = document.getElementById('countdown');
    const progressBar = document.getElementById('progress-bar');
    
    countdownTimer = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        
        // Update progress bar
        const progress = (countdown / 5) * 100;
        progressBar.style.width = progress + '%';
        
        if (countdown <= 0) {
            clearInterval(countdownTimer);
            window.location.href = '/chatify';
        }
    }, 1000);
}

function cancelRedirect() {
    clearInterval(countdownTimer);
    document.querySelector('.alert-info').innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        Redirect otomatis dibatalkan. Anda bisa memilih tindakan selanjutnya.
    `;
    document.querySelector('.alert-info').className = 'alert alert-secondary';
}

// Start countdown when page loads
document.addEventListener('DOMContentLoaded', function() {
    startCountdown();
    
    // Add click handler to chat button
    document.getElementById('chat-btn').addEventListener('click', function() {
        clearInterval(countdownTimer);
    });
});

// Show success animation
document.addEventListener('DOMContentLoaded', function() {
    // Add some celebration animation
    const card = document.querySelector('.card');
    card.style.animation = 'fadeInUp 0.6s ease-out';
    
    // Add confetti effect (optional)
    setTimeout(() => {
        // You can add confetti library here if needed
        console.log('🎉 Subscription activated successfully!');
    }, 500);
});
</script>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    box-shadow: 0 0 30px rgba(40, 167, 69, 0.2);
    border: none;
}

.progress {
    height: 6px;
}

.btn-link {
    text-decoration: none;
    font-size: 0.875rem;
}

.btn-link:hover {
    text-decoration: underline;
}
</style>
@endsection