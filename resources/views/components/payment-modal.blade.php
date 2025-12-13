<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="paymentModalLabel">
                    <i class="fas fa-crown me-2"></i>Upgrade ke Premium
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Header Info -->
                <div class="bg-light p-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-comments fa-3x text-primary mb-2"></i>
                        <h4 class="text-dark">Chat Gratis Anda Sudah Habis!</h4>
                        <p class="text-muted mb-0">Upgrade ke premium untuk melanjutkan konsultasi dengan dokter</p>
                    </div>
                </div>

                <!-- Subscription Plans -->
                <div class="container-fluid p-4">
                    <div class="row g-3">
                        <!-- Monthly Plan -->
                        <div class="col-md-6">
                            <div class="card subscription-card h-100" data-plan="monthly" data-price="50000">
                                <div class="card-body text-center position-relative">
                                    <div class="plan-badge">Populer</div>
                                    <i class="fas fa-calendar-alt fa-2x text-primary mb-3"></i>
                                    <h5 class="card-title">Bulanan</h5>
                                    <div class="price-section mb-3">
                                        <span class="price-currency">Rp</span>
                                        <span class="price-amount">50.000</span>
                                        <span class="price-period">/bulan</span>
                                    </div>
                                    <ul class="list-unstyled text-start">
                                        <li><i class="fas fa-check text-success me-2"></i>Chat unlimited dengan dokter</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Konsultasi 24/7</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Riwayat chat tersimpan</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Prioritas respon dokter</li>
                                    </ul>
                                    <button class="btn btn-outline-primary w-100 select-plan-btn">
                                        Pilih Paket Ini
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Yearly Plan -->
                        <div class="col-md-6">
                            <div class="card subscription-card h-100" data-plan="yearly" data-price="500000">
                                <div class="card-body text-center position-relative">
                                    <div class="plan-badge bg-success">Hemat 17%</div>
                                    <i class="fas fa-calendar fa-2x text-success mb-3"></i>
                                    <h5 class="card-title">Tahunan</h5>
                                    <div class="price-section mb-3">
                                        <span class="price-currency">Rp</span>
                                        <span class="price-amount">500.000</span>
                                        <span class="price-period">/tahun</span>
                                        <div class="price-save">Hemat Rp 100.000</div>
                                    </div>
                                    <ul class="list-unstyled text-start">
                                        <li><i class="fas fa-check text-success me-2"></i>Semua fitur bulanan</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Konsultasi video call</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Reminder obat gratis</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Laporan kesehatan</li>
                                    </ul>
                                    <button class="btn btn-success w-100 select-plan-btn">
                                        Pilih Paket Ini
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form (Hidden initially) -->
                    <div id="payment-form-section" class="mt-4" style="display: none;">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-credit-card me-2"></i>Detail Pembayaran
                                </h6>
                            </div>
                            <div class="card-body">
                                <form id="subscription-payment-form">
                                    @csrf
                                    <input type="hidden" id="selected-plan" name="plan">
                                    <input type="hidden" id="selected-amount" name="amount">
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="selected-plan-info p-3 bg-light rounded mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1" id="plan-name-display">Paket Bulanan</h6>
                                                        <small class="text-muted" id="plan-description">Chat unlimited selama 1 bulan</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="h5 mb-0 text-primary" id="plan-price-display">Rp 50.000</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="payment-summary p-3 bg-primary text-white rounded">
                                                <div class="text-center">
                                                    <small>Total Pembayaran</small>
                                                    <div class="h4 mb-0" id="total-amount">Rp 50.000</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="button" class="btn btn-secondary me-2" onclick="backToPlans()">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-lg" id="pay-subscription-btn">
                                            <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <div class="w-100 text-center">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Pembayaran aman dengan enkripsi SSL
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap.js -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<style>
.subscription-card {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    cursor: pointer;
}

.subscription-card:hover {
    border-color: #007bff;
    box-shadow: 0 8px 25px rgba(0,123,255,0.15);
    transform: translateY(-2px);
}

.subscription-card.selected {
    border-color: #007bff;
    box-shadow: 0 8px 25px rgba(0,123,255,0.2);
    background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
}

.plan-badge {
    position: absolute;
    top: -10px;
    right: 15px;
    background: #ff6b6b;
    color: white;
    padding: 5px 15px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
}

.plan-badge.bg-success {
    background: #28a745 !important;
}

.price-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin: 0 -15px;
}

.price-currency {
    font-size: 18px;
    vertical-align: top;
}

.price-amount {
    font-size: 32px;
    font-weight: bold;
}

.price-period {
    font-size: 16px;
    opacity: 0.8;
}

.price-save {
    font-size: 12px;
    background: rgba(255,255,255,0.2);
    padding: 2px 8px;
    border-radius: 10px;
    margin-top: 5px;
    display: inline-block;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.modal-content {
    border: none;
    border-radius: 15px;
    overflow: hidden;
}

.selected-plan-info {
    border-left: 4px solid #007bff;
}

.payment-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

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

.modal.show .modal-dialog {
    animation: fadeInUp 0.3s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Plan selection
    document.querySelectorAll('.subscription-card').forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            document.querySelectorAll('.subscription-card').forEach(c => c.classList.remove('selected'));
            
            // Add selected class to clicked card
            this.classList.add('selected');
            
            // Update select button
            const selectBtn = this.querySelector('.select-plan-btn');
            selectBtn.innerHTML = '<i class="fas fa-check me-2"></i>Terpilih';
            selectBtn.classList.remove('btn-outline-primary', 'btn-success');
            selectBtn.classList.add('btn-primary');
            
            // Reset other buttons
            document.querySelectorAll('.select-plan-btn').forEach(btn => {
                if (btn !== selectBtn) {
                    const card = btn.closest('.subscription-card');
                    const plan = card.dataset.plan;
                    btn.innerHTML = 'Pilih Paket Ini';
                    btn.classList.remove('btn-primary');
                    btn.classList.add(plan === 'yearly' ? 'btn-success' : 'btn-outline-primary');
                }
            });
        });
        
        // Select plan button
        card.querySelector('.select-plan-btn').addEventListener('click', function(e) {
            e.stopPropagation();
            
            const plan = card.dataset.plan;
            const price = card.dataset.price;
            
            // Update form
            document.getElementById('selected-plan').value = plan;
            document.getElementById('selected-amount').value = price;
            
            // Update display
            const planNames = {
                'monthly': 'Paket Bulanan',
                'yearly': 'Paket Tahunan'
            };
            
            const planDescriptions = {
                'monthly': 'Chat unlimited selama 1 bulan',
                'yearly': 'Chat unlimited selama 1 tahun + fitur premium'
            };
            
            document.getElementById('plan-name-display').textContent = planNames[plan];
            document.getElementById('plan-description').textContent = planDescriptions[plan];
            document.getElementById('plan-price-display').textContent = 'Rp ' + parseInt(price).toLocaleString('id-ID');
            document.getElementById('total-amount').textContent = 'Rp ' + parseInt(price).toLocaleString('id-ID');
            
            // Show payment form
            document.getElementById('payment-form-section').style.display = 'block';
            document.getElementById('payment-form-section').scrollIntoView({ behavior: 'smooth' });
        });
    });
    
    // Payment form submission
    document.getElementById('subscription-payment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const payButton = document.getElementById('pay-subscription-btn');
        const originalText = payButton.innerHTML;
        
        // Disable button and show loading
        payButton.disabled = true;
        payButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        
        const formData = new FormData(this);
        formData.append('description', 'Berlangganan Chat Dokter - ' + document.getElementById('plan-name-display').textContent);
        
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
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                modal.hide();
                
                // Open Midtrans payment popup
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: 'Subscription Anda telah aktif. Chat unlimited sudah bisa digunakan!',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#28a745',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Reload halaman profil untuk update status
                                window.location.reload();
                            }
                        });
                    },
                    onPending: function(result) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Pembayaran Pending',
                            text: 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#17a2b8'
                        }).then(() => {
                            window.location.href = '{{ route("payment.history") }}';
                        });
                    },
                    onError: function(result) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
                            confirmButtonText: 'Coba Lagi',
                            confirmButtonColor: '#e74c3c'
                        }).then(() => {
                            // Reopen modal for retry
                            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
                            modal.show();
                        });
                    },
                    onClose: function() {
                        // Reopen modal if user closes payment popup
                        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
                        modal.show();
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
});

function backToPlans() {
    document.getElementById('payment-form-section').style.display = 'none';
    
    // Reset selections
    document.querySelectorAll('.subscription-card').forEach(card => {
        card.classList.remove('selected');
        const btn = card.querySelector('.select-plan-btn');
        const plan = card.dataset.plan;
        btn.innerHTML = 'Pilih Paket Ini';
        btn.classList.remove('btn-primary');
        btn.classList.add(plan === 'yearly' ? 'btn-success' : 'btn-outline-primary');
    });
}

// Function to show modal (can be called from anywhere)
function showPaymentModal() {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}
</script>