@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Pembayaran</h4>
                </div>
                <div class="card-body">
                    <form id="payment-form">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Pembayaran</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       min="1000" step="1000" required>
                            </div>
                            <div class="form-text">Minimum pembayaran Rp 1.000</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control" id="description" name="description" 
                                   placeholder="Contoh: Konsultasi Dokter, Pembelian Obat" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="pay-button">
                                <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Methods Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Metode Pembayaran yang Tersedia</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="payment-method">
                                <i class="fab fa-cc-visa fa-2x text-primary"></i>
                                <p class="mt-2 mb-0">Visa</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="payment-method">
                                <i class="fab fa-cc-mastercard fa-2x text-warning"></i>
                                <p class="mt-2 mb-0">Mastercard</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="payment-method">
                                <i class="fas fa-university fa-2x text-success"></i>
                                <p class="mt-2 mb-0">Bank Transfer</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="payment-method">
                                <i class="fas fa-mobile-alt fa-2x text-info"></i>
                                <p class="mt-2 mb-0">E-Wallet</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap.js -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const payButton = document.getElementById('pay-button');
    const originalText = payButton.innerHTML;
    
    // Disable button and show loading
    payButton.disabled = true;
    payButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    
    const formData = new FormData(this);
    
    fetch('{{ route("payment.process") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.snap_token) {
            // Open Midtrans payment popup
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    window.location.href = '{{ url("payment/success") }}/' + data.order_id;
                },
                onPending: function(result) {
                    alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
                    window.location.href = '{{ route("payment.history") }}';
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    window.location.href = '{{ url("payment/failed") }}/' + data.order_id;
                },
                onClose: function() {
                    alert('Anda menutup popup pembayaran sebelum menyelesaikan pembayaran');
                }
            });
        } else {
            alert('Gagal memproses pembayaran. Silakan coba lagi.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    })
    .finally(() => {
        // Re-enable button
        payButton.disabled = false;
        payButton.innerHTML = originalText;
    });
});
</script>

<style>
.payment-method {
    padding: 15px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.1);
}

.card {
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border: none;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
}
</style>
@endsection