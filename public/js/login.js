/**
 * Login Page JavaScript
 * Klinik Pratama Dokter Yanti
 */

// Toggle password visibility (pakai FontAwesome)
function togglePassword(fieldId = 'password', iconId = 'toggleIcon') {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);

    if (field.type === "password") {
        field.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        field.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            let isValid = true;
            
            // Simple email validation
            if (!emailField.value || !emailField.value.includes('@')) {
                emailField.classList.add('is-invalid');
                const feedback = emailField.nextElementSibling || document.createElement('span');
                feedback.className = 'invalid-feedback';
                feedback.innerHTML = '<strong>Please enter a valid email address.</strong>';
                if (!emailField.nextElementSibling) {
                    emailField.parentNode.appendChild(feedback);
                }
                isValid = false;
            } else {
                emailField.classList.remove('is-invalid');
                if (emailField.nextElementSibling && emailField.nextElementSibling.className === 'invalid-feedback') {
                    emailField.nextElementSibling.remove();
                }
            }
            
            // Password validation (minimum 6 characters)
            if (!passwordField.value || passwordField.value.length < 6) {
                passwordField.classList.add('is-invalid');
                const feedback = passwordField.nextElementSibling.nextElementSibling || document.createElement('span');
                feedback.className = 'invalid-feedback';
                feedback.innerHTML = '<strong>Password must be at least 6 characters.</strong>';
                if (!passwordField.nextElementSibling.nextElementSibling) {
                    passwordField.parentNode.appendChild(feedback);
                }
                isValid = false;
            } else {
                passwordField.classList.remove('is-invalid');
                if (passwordField.nextElementSibling.nextElementSibling && passwordField.nextElementSibling.nextElementSibling.className === 'invalid-feedback') {
                    passwordField.nextElementSibling.nextElementSibling.remove();
                }
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    }
});

// Add animation effects
document.addEventListener('DOMContentLoaded', function() {
    const formElements = document.querySelectorAll('.form-control, .btn-login');
    
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 100 * (index + 1));
    });
});

// Social login buttons hover effect
document.addEventListener('DOMContentLoaded', function() {
    const socialButtons = document.querySelectorAll('.social-icon');
    
    socialButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.1)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
