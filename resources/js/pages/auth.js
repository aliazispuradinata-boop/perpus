// Auth Scripts
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const authForms = document.querySelectorAll('form[method="POST"]');
    authForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    });

    // Password visibility toggle
    const passwordToggles = document.querySelectorAll('[data-toggle-password]');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const passwordInput = document.querySelector(this.dataset.togglePassword);
            if (passwordInput) {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle icon
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            }
        });
    });

    // Real-time form validation
    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateAuthField(this);
        });

        input.addEventListener('input', function() {
            // Remove error state when user starts typing
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Email validation on input
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateEmail(this);
        });
    });

    // Password strength indicator
    const passwordInputs = document.querySelectorAll('input[name="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            showPasswordStrength(this);
        });
    });

    // Confirm password validation
    const confirmInputs = document.querySelectorAll('input[name="password_confirmation"]');
    confirmInputs.forEach(input => {
        input.addEventListener('change', function() {
            validatePasswordMatch();
        });
    });

    // Auto-focus first input
    const firstInput = document.querySelector('form input:first-of-type');
    if (firstInput && !firstInput.value) {
        firstInput.focus();
    }

    // Demo credentials copy to clipboard
    const demoCredentials = document.querySelectorAll('[data-demo-credential]');
    demoCredentials.forEach(credential => {
        credential.addEventListener('click', function(e) {
            if (e.target.tagName !== 'A') {
                const text = this.textContent.trim();
                navigator.clipboard.writeText(text).then(() => {
                    showToast('Copied to clipboard!', 'success');
                }).catch(err => {
                    console.error('Failed to copy:', err);
                });
            }
        });
        credential.style.cursor = 'pointer';
    });
});

// Validate auth field
function validateAuthField(field) {
    const value = field.value.trim();
    const type = field.type;
    const name = field.name;
    let isValid = true;

    // Required validation
    if (field.required && !value) {
        isValid = false;
        showFieldError(field, 'Field ini harus diisi');
    } else {
        // Email validation
        if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
            if (!isValid) {
                showFieldError(field, 'Email tidak valid');
            } else {
                clearFieldError(field);
            }
        }
        // Password validation
        else if (type === 'password' && value) {
            if (name === 'password' && value.length < 8) {
                isValid = false;
                showFieldError(field, 'Password minimal 8 karakter');
            } else {
                clearFieldError(field);
            }
        }
        // Name validation
        else if (name === 'name' && value) {
            if (value.length < 3) {
                isValid = false;
                showFieldError(field, 'Nama minimal 3 karakter');
            } else {
                clearFieldError(field);
            }
        }
        // Phone validation
        else if (name === 'phone' && value) {
            const phoneRegex = /^(\+62|0)[0-9]{9,12}$/;
            if (!phoneRegex.test(value)) {
                isValid = false;
                showFieldError(field, 'Nomor telepon tidak valid');
            } else {
                clearFieldError(field);
            }
        } else {
            clearFieldError(field);
        }
    }

    return isValid;
}

// Validate email
function validateEmail(field) {
    const value = field.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (value && !emailRegex.test(value)) {
        showFieldError(field, 'Email tidak valid');
        return false;
    } else {
        clearFieldError(field);
        return true;
    }
}

// Show password strength
function showPasswordStrength(field) {
    const value = field.value;
    const strengthIndicator = field.parentElement.querySelector('[data-strength-indicator]');
    
    if (!strengthIndicator) return;

    let strength = 0;
    if (value.length >= 8) strength++;
    if (/[a-z]/.test(value) && /[A-Z]/.test(value)) strength++;
    if (/[0-9]/.test(value)) strength++;
    if (/[^a-zA-Z0-9]/.test(value)) strength++;

    const labels = ['Lemah', 'Cukup', 'Baik', 'Sangat Baik'];
    const colors = ['danger', 'warning', 'info', 'success'];

    if (value) {
        strengthIndicator.innerHTML = `
            <div class="progress" style="height: 5px;">
                <div class="progress-bar bg-${colors[strength - 1]}" style="width: ${(strength / 4) * 100}%"></div>
            </div>
            <small class="text-${colors[strength - 1]}">${labels[strength - 1] || 'Lemah'}</small>
        `;
    } else {
        strengthIndicator.innerHTML = '';
    }
}

// Validate password match
function validatePasswordMatch() {
    const password = document.querySelector('input[name="password"]');
    const confirm = document.querySelector('input[name="password_confirmation"]');

    if (password && confirm) {
        if (password.value !== confirm.value) {
            showFieldError(confirm, 'Password tidak cocok');
            return false;
        } else {
            clearFieldError(confirm);
            return true;
        }
    }
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    let errorDiv = field.parentElement.querySelector('.invalid-feedback');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback d-block';
        field.parentElement.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
}

// Clear field error
function clearFieldError(field) {
    field.classList.remove('is-invalid');
    const errorDiv = field.parentElement.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Show toast notification
function showToast(message, type = 'info') {
    const alertClass = `alert alert-${type}`;
    const alertHtml = `
        <div class="${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    const alert = document.body.querySelector('.alert:last-of-type');
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    }, 3000);
}

// Export auth utilities
window.AuthUtils = {
    validateField: validateAuthField,
    validateEmail,
    validatePasswordMatch,
    showPasswordStrength,
    showToast
};
