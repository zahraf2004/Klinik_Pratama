{{-- Payment Trigger Button Component --}}
@props(['type' => 'button', 'size' => 'md', 'variant' => 'primary', 'text' => 'Upgrade Premium'])

@php
    $sizeClasses = [
        'sm' => 'btn-sm',
        'md' => '',
        'lg' => 'btn-lg'
    ];
    
    $variantClasses = [
        'primary' => 'btn-primary',
        'success' => 'btn-success',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        'outline-primary' => 'btn-outline-primary'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? '';
    $variantClass = $variantClasses[$variant] ?? 'btn-primary';
@endphp

@if($type === 'button')
    <button type="button" 
            class="btn {{ $variantClass }} {{ $sizeClass }} payment-trigger-btn" 
            onclick="showPaymentModal()"
            {{ $attributes }}>
        <i class="fas fa-crown me-2"></i>{{ $text }}
    </button>
@elseif($type === 'link')
    <a href="javascript:void(0)" 
       class="btn {{ $variantClass }} {{ $sizeClass }} payment-trigger-btn" 
       onclick="showPaymentModal()"
       {{ $attributes }}>
        <i class="fas fa-crown me-2"></i>{{ $text }}
    </a>
@elseif($type === 'card')
    <div class="card border-warning payment-upgrade-card" {{ $attributes }}>
        <div class="card-body text-center">
            <i class="fas fa-crown fa-3x text-warning mb-3"></i>
            <h5 class="card-title">Upgrade ke Premium</h5>
            <p class="card-text text-muted">{{ $text }}</p>
            <button type="button" class="btn btn-warning" onclick="showPaymentModal()">
                <i class="fas fa-rocket me-2"></i>Upgrade Sekarang
            </button>
        </div>
    </div>
@elseif($type === 'banner')
    <div class="alert alert-warning alert-dismissible fade show payment-banner" role="alert" {{ $attributes }}>
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div class="flex-grow-1">
                <h6 class="alert-heading mb-1">Chat Gratis Terbatas!</h6>
                <p class="mb-2">{{ $text }}</p>
            </div>
            <button type="button" class="btn btn-warning btn-sm" onclick="showPaymentModal()">
                <i class="fas fa-crown me-1"></i>Upgrade
            </button>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@elseif($type === 'floating')
    <div class="floating-upgrade-btn" {{ $attributes }}>
        <button type="button" 
                class="btn btn-warning btn-lg rounded-circle shadow-lg" 
                onclick="showPaymentModal()"
                title="Upgrade ke Premium">
            <i class="fas fa-crown"></i>
        </button>
    </div>
@endif

@if($type === 'floating')
<style>
.floating-upgrade-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1050;
}

.floating-upgrade-btn .btn {
    width: 60px;
    height: 60px;
    animation: pulse-crown 2s infinite;
}

@keyframes pulse-crown {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
    }
}

.floating-upgrade-btn .btn:hover {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}
</style>
@endif

@if($type === 'card')
<style>
.payment-upgrade-card {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 2px solid #ffc107 !important;
    transition: all 0.3s ease;
}

.payment-upgrade-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.3);
}
</style>
@endif

@if($type === 'banner')
<style>
.payment-banner {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 2px solid #ffc107;
    border-radius: 10px;
}
</style>
@endif