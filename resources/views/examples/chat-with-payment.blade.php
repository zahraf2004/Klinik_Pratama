@extends('layouts.app')

@section('title', 'Konsultasi Chat')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            {{-- Payment Banner (Show when user has limited chats remaining) --}}
            @if(Auth::user()->role === 'pasien' && !Auth::user()->hasActiveSubscription())
                @php
                    $remainingChats = Auth::user()->getRemainingFreeChats();
                @endphp
                
                @if($remainingChats > 0)
                    <x-payment-trigger 
                        type="banner" 
                        text="Anda memiliki {{ $remainingChats }} chat gratis tersisa. Upgrade untuk chat unlimited!"
                        class="mb-4" />
                @else
                    <x-payment-trigger 
                        type="banner" 
                        text="Chat gratis Anda sudah habis! Upgrade ke premium untuk melanjutkan konsultasi."
                        class="mb-4" />
                @endif
            @endif

            {{-- Chat Interface --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-comments me-2"></i>Konsultasi dengan Dokter
                    </h5>
                    
                    {{-- Premium Badge or Upgrade Button --}}
                    @if(Auth::user()->hasActiveSubscription())
                        <span class="badge bg-success">
                            <i class="fas fa-crown me-1"></i>Premium Member
                        </span>
                    @else
                        <x-payment-trigger 
                            type="button" 
                            size="sm" 
                            variant="outline-primary"
                            text="Upgrade Premium" />
                    @endif
                </div>
                <div class="card-body">
                    {{-- Chat messages area --}}
                    <div id="chat-messages" class="chat-messages mb-3" style="height: 400px; overflow-y: auto;">
                        {{-- Chat messages will be loaded here --}}
                    </div>

                    {{-- Message input --}}
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               id="message-input"
                               placeholder="Ketik pesan Anda..."
                               @if(!Auth::user()->canSendMessage()) disabled @endif>
                        <button class="btn btn-primary" 
                                type="button" 
                                id="send-message-btn"
                                onclick="sendMessage()"
                                @if(!Auth::user()->canSendMessage()) disabled @endif>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>

                    {{-- Chat limit warning --}}
                    @if(!Auth::user()->canSendMessage())
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Anda sudah mencapai batas chat gratis. 
                            <a href="javascript:void(0)" onclick="showPaymentModal()" class="alert-link">
                                Upgrade ke premium
                            </a> untuk melanjutkan chat.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Floating Upgrade Button (for non-premium users) --}}
@if(!Auth::user()->hasActiveSubscription())
    <x-payment-trigger type="floating" />
@endif

<script>
function sendMessage() {
    // Check if user can send message
    if (!{{ Auth::user()->canSendMessage() ? 'true' : 'false' }}) {
        showPaymentModal();
        return;
    }

    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();
    
    if (!message) return;

    // Check chat limit before sending
    checkChatLimit({{ $doctorId ?? 'null' }}).then(canSend => {
        if (canSend) {
            // Send message logic here
            console.log('Sending message:', message);
            messageInput.value = '';
        }
    });
}

// Auto-check when user tries to type
document.getElementById('message-input').addEventListener('focus', function() {
    if (!{{ Auth::user()->canSendMessage() ? 'true' : 'false' }}) {
        this.blur();
        showSubscriptionRequired({{ Auth::user()->getRemainingFreeChats() }});
    }
});
</script>

<style>
.chat-messages {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
}

.input-group .form-control:disabled {
    background-color: #f8f9fa;
    opacity: 0.6;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endsection