@php
    // Pastikan variable $reviews tersedia, jika tidak buat collection kosong
    $reviews = $reviews ?? collect();
@endphp

<!-- Section Review Pengguna -->
<section class="review-section">
    <div class="review-container">
        <h2>Berikan Review Anda</h2>
        <p class="review-subtitle">Bagikan pengalaman Anda menggunakan layanan kami untuk membantu pengguna lain</p>
        
        <div class="review-form-wrapper">
            @if(Auth::check())
            <form id="reviewForm" class="review-form">
                @csrf
                
                <!-- Rating Bintang -->
                <div class="rating-group">
                    <label class="rating-label">Rating Layanan</label>
                    <div class="star-rating">
                        <input type="radio" name="rating" value="5" id="star5">
                        <label for="star5" class="star">★</label>
                        <input type="radio" name="rating" value="4" id="star4">
                        <label for="star4" class="star">★</label>
                        <input type="radio" name="rating" value="3" id="star3">
                        <label for="star3" class="star">★</label>
                        <input type="radio" name="rating" value="2" id="star2">
                        <label for="star2" class="star">★</label>
                        <input type="radio" name="rating" value="1" id="star1">
                        <label for="star1" class="star">★</label>
                    </div>
                    <span class="rating-text">Pilih rating dari 1-5 bintang</span>
                </div>

                <!-- Nama Pengguna (Hidden/Auto) -->
                <input type="hidden" name="reviewer_name" value="{{ Auth::check() ? Auth::user()->name : 'Pengguna Anonim' }}">
                
                @if(Auth::check())
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-details">
                        <strong>{{ Auth::user()->name }}</strong>
                        <span>Memberikan review</span>
                    </div>
                </div>
                @endif

                <!-- Isi Review -->
                <div class="form-group">
                    <label for="review_content">Bagikan Pengalaman Anda</label>
                    <textarea id="review_content" name="review_content" rows="6" 
                              placeholder="Ceritakan pengalaman Anda menggunakan layanan klinik kami. Review Anda akan membantu pasien lain..." required></textarea>
                    <small class="char-count">0/300 karakter</small>
                </div>
                
                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Review
                    </button>                    
                </div>
            </form>
            @else
            <div class="login-prompt">
                <div class="login-icon">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h3>Login untuk Memberikan Review</h3>
                <p>Silakan login terlebih dahulu untuk memberikan review dan berbagi pengalaman Anda dengan pengguna lain.</p>
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="fas fa-user"></i>
                    Login Sekarang
                </a>
            </div>
            @endif
        </div>
        
    </div>
</section>

<style>
/* CSS khusus untuk section review - menggunakan class spesifik untuk menghindari konflik */
.review-section {
    max-width: 1200px;
    margin: 80px auto 60px auto;
    padding: 0 20px;
    font-family: "Nunito", sans-serif;
}

.review-container h2 {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    text-align: center;
    margin-bottom: 10px;
}

.review-subtitle {
    text-align: center;
    color: #666;
    font-size: 16px;
    margin-bottom: 40px;
}

.review-form-wrapper {
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 50px;
}

.review-form .form-group {
    margin-bottom: 25px;
}

.review-form label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
}

.review-form input[type="text"],
.review-form select,
.review-form textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: border-color 0.3s ease;
}

.review-form input[type="text"]:focus,
.review-form select:focus,
.review-form textarea:focus {
    outline: none;
    border-color: #0069ab;
    box-shadow: 0 0 0 3px rgba(0, 105, 171, 0.1);
}

.review-form textarea {
    resize: vertical;
    min-height: 120px;
}

.char-count {
    display: block;
    text-align: right;
    color: #666;
    font-size: 12px;
    margin-top: 5px;
}

/* Rating Bintang */
.rating-group {
    margin-bottom: 25px;
}

.rating-label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    margin-bottom: 5px;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating .star {
    font-size: 30px;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s ease;
    margin-right: 5px;
}

.star-rating .star:hover,
.star-rating .star:hover ~ .star,
.star-rating input[type="radio"]:checked ~ .star {
    color: #ffc107;
}

.rating-text {
    font-size: 12px;
    color: #666;
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    border-left: 4px solid #0069ab;
}

.user-avatar i {
    font-size: 40px;
    color: #0069ab;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-details strong {
    font-size: 16px;
    color: #333;
    margin-bottom: 2px;
}

.user-details span {
    font-size: 14px;
    color: #666;
}

/* Checkbox Custom */
.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 14px;
    color: #333;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #ddd;
    border-radius: 4px;
    margin-right: 10px;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background-color: #0069ab;
    border-color: #0069ab;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: "✓";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 12px;
}

/* Buttons */
.form-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

.btn-submit,
.btn-reset {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-submit {
    background: linear-gradient(135deg, #0069ab 0%, #004d7a 100%);
    color: white;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 105, 171, 0.3);
}

.btn-reset {
    background: #f8f9fa;
    color: #666;
    border: 2px solid #e9ecef;
}

.btn-reset:hover {
    background: #e9ecef;
    color: #333;
}

/* Existing Reviews */
.existing-reviews h3 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 25px;
    text-align: center;
}

.review-list {
    display: grid;
    gap: 20px;
}

.review-item {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #0069ab;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.reviewer-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.reviewer-name {
    font-size: 16px;
    color: #333;
}

.review-stars {
    display: flex;
    gap: 2px;
}

.review-stars .star {
    font-size: 16px;
    color: #ddd;
}

.review-stars .star.filled {
    color: #ffc107;
}

.review-date {
    font-size: 12px;
    color: #999;
}

.review-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.review-text {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
}

.review-category-tag {
    display: inline-block;
    background: #e3f2fd;
    color: #0069ab;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* Login Prompt */
.login-prompt {
    text-align: center;
    padding: 50px 30px;
    background: #f8f9fa;
    border-radius: 15px;
    border: 2px dashed #dee2e6;
}

.login-icon i {
    font-size: 60px;
    color: #0069ab;
    margin-bottom: 20px;
}

.login-prompt h3 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
}

.login-prompt p {
    color: #666;
    font-size: 16px;
    margin-bottom: 25px;
    line-height: 1.6;
}

.btn-login {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #0069ab 0%, #004d7a 100%);
    color: white;
    padding: 15px 30px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 105, 171, 0.3);
    text-decoration: none;
    color: white;
}

/* Custom Popup Styles */
.custom-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.custom-popup.show {
    opacity: 1;
    visibility: visible;
}

.popup-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.popup-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.8);
    background: white;
    border-radius: 20px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 90%;
    transition: transform 0.3s ease;
}

.custom-popup.show .popup-content {
    transform: translate(-50%, -50%) scale(1);
}

.popup-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    font-weight: bold;
    color: white;
}

.popup-icon.success {
    background: linear-gradient(135deg, #4CAF50, #45a049);
    animation: successPulse 0.6s ease-out;
}

.popup-icon.error {
    background: linear-gradient(135deg, #f44336, #d32f2f);
    animation: errorShake 0.6s ease-out;
}

@keyframes successPulse {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@keyframes errorShake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.popup-title {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.popup-message {
    font-size: 16px;
    color: #666;
    line-height: 1.5;
    margin-bottom: 25px;
}

.popup-btn {
    background: linear-gradient(135deg, #0069ab, #004d7a);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 100px;
}

.popup-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 105, 171, 0.3);
}

.popup-btn.success {
    background: linear-gradient(135deg, #4CAF50, #45a049);
}

.popup-btn.success:hover {
    box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
}

.popup-btn.error {
    background: linear-gradient(135deg, #f44336, #d32f2f);
}

.popup-btn.error:hover {
    box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .review-section {
        padding: 0 15px;
        margin: 50px auto 40px auto;
    }
    
    .review-form-wrapper {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-submit,
    .btn-reset {
        width: 100%;
        justify-content: center;
    }
    
    .review-header {
        flex-direction: column;
        gap: 10px;
    }
    
    /* Popup responsive */
    .popup-content {
        padding: 30px 20px;
        max-width: 350px;
    }
    
    .popup-icon {
        width: 60px;
        height: 60px;
        font-size: 30px;
        margin-bottom: 15px;
    }
    
    .popup-title {
        font-size: 20px;
        margin-bottom: 12px;
    }
    
    .popup-message {
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .popup-btn {
        padding: 10px 25px;
        font-size: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter untuk textarea
    const textarea = document.getElementById('review_content');
    const charCount = document.querySelector('.char-count');
    const maxLength = 300;
    
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = `${currentLength}/${maxLength} karakter`;
            
            if (currentLength > maxLength) {
                charCount.style.color = '#dc3545';
                this.style.borderColor = '#dc3545';
            } else {
                charCount.style.color = '#666';
                this.style.borderColor = '#e9ecef';
            }
        });
        
        textarea.setAttribute('maxlength', maxLength);
    }
    
    // Form submission
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi rating
            const rating = document.querySelector('input[name="rating"]:checked');
            if (!rating) {
                showErrorPopup('Silakan berikan rating terlebih dahulu');
                return;
            }
            
            // Validasi review content
            const reviewContent = document.getElementById('review_content').value.trim();
            if (!reviewContent) {
                showErrorPopup('Silakan tulis review Anda');
                return;
            }
            
            // Prepare form data
            const formData = new FormData(this);
            
            // Debug: Log form data
            console.log('Form data being sent:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            const submitBtn = this.querySelector('.btn-submit');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            submitBtn.disabled = true;
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                            document.querySelector('input[name="_token"]')?.value;
            
            if (!csrfToken) {
                showErrorPopup('CSRF token tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            // Send AJAX request
            fetch('/review/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success popup
                    showSuccessPopup(data.message);
                    this.reset();
                    
                    // Reset character counter
                    if (charCount) {
                        charCount.textContent = '0/300 karakter';
                        charCount.style.color = '#666';
                    }
                } else {
                    showErrorPopup(data.message || 'Terjadi kesalahan saat mengirim review');
                    if (data.errors) {
                        console.log('Validation errors:', data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorPopup('Terjadi kesalahan saat mengirim review. Silakan coba lagi.');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Custom popup functions
    function showSuccessPopup(message) {
        createPopup('success', 'Review Berhasil Dikirim!', message, '✓');
    }
    
    function showErrorPopup(message) {
        createPopup('error', 'Oops!', message, '✕');
    }
    
    function createPopup(type, title, message, icon) {
        // Remove existing popup
        const existingPopup = document.querySelector('.custom-popup');
        if (existingPopup) {
            existingPopup.remove();
        }
        
        // Create popup HTML
        const popup = document.createElement('div');
        popup.className = `custom-popup ${type}`;
        popup.innerHTML = `
            <div class="popup-overlay"></div>
            <div class="popup-content">
                <div class="popup-icon ${type}">
                    <span>${icon}</span>
                </div>
                <h3 class="popup-title">${title}</h3>
                <p class="popup-message">${message}</p>
                <button class="popup-btn ${type}" onclick="closePopup()">OK</button>
            </div>
        `;
        
        // Add to body
        document.body.appendChild(popup);
        
        // Show with animation
        setTimeout(() => {
            popup.classList.add('show');
        }, 10);
        
        // Auto close after 5 seconds
        setTimeout(() => {
            closePopup();
        }, 5000);
    }
    
    function closePopup() {
        const popup = document.querySelector('.custom-popup');
        if (popup) {
            popup.classList.remove('show');
            setTimeout(() => {
                popup.remove();
            }, 300);
        }
    }
});
</script>