/**
 * Payment Modal Helper Functions
 */

class PaymentModal {
    constructor() {
        this.modal = null;
        this.selectedPlan = null;
        this.init();
    }

    init() {
        // Initialize modal when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            this.modal = new bootstrap.Modal(document.getElementById('paymentModal'), {
                backdrop: 'static',
                keyboard: false
            });
            this.bindEvents();
        });
    }

    bindEvents() {
        // Plan selection events
        document.querySelectorAll('.subscription-card').forEach(card => {
            card.addEventListener('click', (e) => this.selectPlan(e));
        });

        // Form submission
        const form = document.getElementById('subscription-payment-form');
        if (form) {
            form.addEventListener('submit', (e) => this.processPayment(e));
        }
    }

    selectPlan(event) {
        const card = event.currentTarget;
        const plan = card.dataset.plan;
        const price = card.dataset.price;

        // Remove selection from other cards
        document.querySelectorAll('.subscription-card').forEach(c => {
            c.classList.remove('selected');
            const btn = c.querySelector('.select-plan-btn');
            const cardPlan = c.dataset.plan;
            btn.innerHTML = 'Pilih Paket Ini';
            btn.classList.remove('btn-primary');
            btn.classList.add(cardPlan === 'yearly' ? 'btn-success' : 'btn-outline-primary');
        });

        // Select current card
        card.classList.add('selected');
        const selectBtn = card.querySelector('.select-plan-btn');
        selectBtn.innerHTML = '<i class="fas fa-check me-2"></i>Terpilih';
        selectBtn.classList.remove('btn-outline-primary', 'btn-success');
        selectBtn.classList.add('btn-primary');

        this.selectedPlan = { plan, price };
    }

    showPaymentForm() {
        if (!this.selectedPlan) {
            this.showNotification('Silakan pilih paket terlebih dahulu', 'warning');
            return;
        }

        const { plan, price } = this.selectedPlan;

        // Update form data
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

        // Show payment form with animation
        const formSection = document.getElementById('payment-form-section');
        formSection.style.display = 'block';
        formSection.scrollIntoView({ behavior: 'smooth' });
        
        // Add animation class
        setTimeout(() => {
            formSection.classList.add('fadeInUp');
        }, 100);
    }

    async processPayment(event) {
        event.preventDefault();

        const payButton = document.getElementById('pay-subscription-btn');
        const originalText = payButton.innerHTML;

        try {
            // Show loading state
            this.setLoadingState(payButton, true);

            const formData = new FormData(event.target);
            formData.append('description', 'Subscription ' + document.getElementById('plan-name-display').textContent);

            const response = await fetch('/payment/process', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.snap_token) {
                // Close modal
                this.hide();

                // Open Midtrans payment popup
                snap.pay(data.snap_token, {
                    onSuccess: (result) => {
                        this.handlePaymentSuccess(data.order_id, result);
                    },
                    onPending: (result) => {
                        this.handlePaymentPending(result);
                    },
                    onError: (result) => {
                        this.handlePaymentError(data.order_id, result);
                    },
                    onClose: () => {
                        this.handlePaymentClose();
                    }
                });
            } else {
                throw new Error(data.message || 'Gagal memproses pembayaran');
            }
        } catch (error) {
            console.error('Payment Error:', error);
            this.showNotification('Terjadi kesalahan: ' + error.message, 'error');
        } finally {
            this.setLoadingState(payButton, false, originalText);
        }
    }

    handlePaymentSuccess(orderId, result) {
        this.showNotification('Pembayaran berhasil! Redirecting...', 'success');
        setTimeout(() => {
            window.location.href = `/payment/success/${orderId}`;
        }, 1500);
    }

    handlePaymentPending(result) {
        this.showNotification('Pembayaran pending. Silakan selesaikan pembayaran Anda.', 'info');
        setTimeout(() => {
            window.location.href = '/payment/history';
        }, 2000);
    }

    handlePaymentError(orderId, result) {
        this.showNotification('Pembayaran gagal. Silakan coba lagi.', 'error');
        setTimeout(() => {
            window.location.href = `/payment/failed/${orderId}`;
        }, 2000);
    }

    handlePaymentClose() {
        // Reopen modal if user closes payment popup
        setTimeout(() => {
            this.show();
            this.showNotification('Pembayaran dibatalkan. Silakan coba lagi.', 'warning');
        }, 500);
    }

    setLoadingState(button, isLoading, originalText = '') {
        if (isLoading) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        } else {
            button.disabled = false;
            button.innerHTML = originalText || button.innerHTML;
        }
    }

    show() {
        if (this.modal) {
            this.modal.show();
        }
    }

    hide() {
        if (this.modal) {
            this.modal.hide();
        }
    }

    backToPlans() {
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

        this.selectedPlan = null;
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${this.getBootstrapAlertClass(type)} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        
        notification.innerHTML = `
            <i class="fas ${this.getNotificationIcon(type)} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    getBootstrapAlertClass(type) {
        const classes = {
            'success': 'success',
            'error': 'danger',
            'warning': 'warning',
            'info': 'info'
        };
        return classes[type] || 'info';
    }

    getNotificationIcon(type) {
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        return icons[type] || 'fa-info-circle';
    }
}

// Global functions for easy access
window.showPaymentModal = function() {
    if (window.paymentModalInstance) {
        window.paymentModalInstance.show();
    }
};

window.hidePaymentModal = function() {
    if (window.paymentModalInstance) {
        window.paymentModalInstance.hide();
    }
};

window.backToPlans = function() {
    if (window.paymentModalInstance) {
        window.paymentModalInstance.backToPlans();
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.paymentModalInstance = new PaymentModal();
});

// Function to check if user has reached chat limit
window.checkChatLimit = async function(doctorId) {
    try {
        const response = await fetch('/api/check-chat-limit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ doctor_id: doctorId })
        });

        const data = await response.json();
        
        if (data.limit_reached) {
            // Show payment modal
            showPaymentModal();
            return false;
        }
        
        return true;
    } catch (error) {
        console.error('Error checking chat limit:', error);
        return true; // Allow chat on error
    }
};

// Function to show subscription required modal
window.showSubscriptionRequired = function(remainingChats = 0) {
    const modal = document.getElementById('paymentModal');
    const modalTitle = modal.querySelector('.modal-title');
    const headerInfo = modal.querySelector('.bg-light p');
    
    if (remainingChats > 0) {
        modalTitle.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Chat Terbatas';
        headerInfo.innerHTML = `Anda memiliki <strong>${remainingChats} chat gratis</strong> tersisa. Upgrade ke premium untuk chat unlimited!`;
    } else {
        modalTitle.innerHTML = '<i class="fas fa-crown me-2"></i>Upgrade ke Premium';
        headerInfo.innerHTML = 'Chat gratis Anda sudah habis! Upgrade ke premium untuk melanjutkan konsultasi dengan dokter.';
    }
    
    showPaymentModal();
};