// Admin Scripts
document.addEventListener('DOMContentLoaded', function() {
    // Initialize admin tables with sorting and filtering
    const adminTables = document.querySelectorAll('[data-admin-table]');
    adminTables.forEach(table => {
        // Add row hover effects
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f0f0f0';
            });
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    });

    // Form validation
    const adminForms = document.querySelectorAll('[data-admin-form]');
    adminForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    });

    // Delete confirmation
    const deleteButtons = document.querySelectorAll('[data-delete-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.dataset.deleteConfirm || 'Apakah Anda yakin ingin menghapus?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Dynamic form field management
    const categorySelects = document.querySelectorAll('select[name="category_id"]');
    categorySelects.forEach(select => {
        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                console.log('Category selected:', selectedOption.text);
            }
        });
    });

    // Real-time form validation
    const formInputs = document.querySelectorAll('[data-admin-form] input, [data-admin-form] textarea, [data-admin-form] select');
    formInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });

        input.addEventListener('change', function() {
            validateField(this);
        });
    });

    // Total copies and available copies sync
    const totalCopiesInput = document.querySelector('input[name="total_copies"]');
    if (totalCopiesInput && !document.querySelector('input[name="available_copies"]')) {
        // For create form, available = total
        totalCopiesInput.addEventListener('change', function() {
            const createForm = document.querySelector('[data-admin-form="create"]');
            if (createForm) {
                console.log('Total copies set to:', this.value);
            }
        });
    }

    // Image preview for book covers (if upload exists)
    const coverInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    coverInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.querySelector('[data-cover-preview]');
                    if (preview) {
                        preview.src = event.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Fallback delete confirmation for forms that contain a red delete button
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const delBtn = form.querySelector('button.btn-danger, input[type="submit"].btn-danger');
        if (delBtn) {
            form.addEventListener('submit', function(e) {
                // If button triggered the submit, confirm
                const message = delBtn.dataset.deleteConfirm || 'Yakin ingin menghapus item ini?';
                if (!confirm(message)) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        }
    });

    // Stats animation
    const statBoxes = document.querySelectorAll('.stat-box');
    statBoxes.forEach((box, index) => {
        box.style.opacity = '0';
        box.style.transform = 'translateY(20px)';
        setTimeout(() => {
            box.style.transition = 'all 0.5s ease';
            box.style.opacity = '1';
            box.style.transform = 'translateY(0)';
            
            // Animate numbers
            const valueEl = box.querySelector('.stat-value');
            if (valueEl && valueEl.textContent.match(/^\d+$/)) {
                animateCounter(valueEl);
            }
        }, index * 100);
    });
});

// Validate individual field
function validateField(field) {
    let isValid = true;
    const value = field.value.trim();
    const type = field.type;
    const name = field.name;

    // Required validation
    if (field.required && !value) {
        isValid = false;
    }

    // Email validation
    if (type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        isValid = emailRegex.test(value);
    }

    // Number validation
    if (type === 'number' && value) {
        isValid = !isNaN(value);
    }

    // Update field styling
    if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
    }

    return isValid;
}

// Animate counter
function animateCounter(element, duration = 1000) {
    const final = parseInt(element.textContent);
    const start = 0;
    const increment = final / (duration / 16);
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if (current >= final) {
            element.textContent = final;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}

// Export admin utilities
window.AdminUtils = {
    validateField,
    animateCounter,
    deleteConfirm: function(message) {
        return confirm(message);
    }
};
