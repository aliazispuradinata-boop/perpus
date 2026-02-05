// Global Utility Functions and Common Scripts
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips and popovers globally
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    const popoverList = popoverTriggerList.map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));

    // Global flash message handler
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('alert-dismissible')) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        }
    });

    // Global form submission handler
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
            }
        });
    });
});

// Global utility functions
window.AppUtils = {
    /**
     * Show confirmation dialog
     */
    confirm: function(message = 'Apakah Anda yakin?') {
        return confirm(message);
    },

    /**
     * Format currency to IDR
     */
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(amount);
    },

    /**
     * Format date
     */
    formatDate: function(date, format = 'd M Y') {
        const d = new Date(date);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const day = d.getDate();
        const month = months[d.getMonth()];
        const year = d.getFullYear();

        return `${day} ${month} ${year}`;
    },

    /**
     * Show loading spinner
     */
    showLoading: function(element) {
        element.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
        element.disabled = true;
    },

    /**
     * Hide loading spinner
     */
    hideLoading: function(element, text) {
        element.innerHTML = text;
        element.disabled = false;
    },

    /**
     * Get CSRF token from meta tag
     */
    getCsrfToken: function() {
        return document.querySelector('meta[name="csrf-token"]').content;
    },

    /**
     * Make API request
     */
    apiRequest: function(url, method = 'GET', data = null) {
        const options = {
            method: method,
            headers: {
                'X-CSRF-TOKEN': this.getCsrfToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        };

        if (data && method !== 'GET') {
            options.body = JSON.stringify(data);
        }

        return fetch(url, options).then(response => response.json());
    },

    /**
     * Show toast notification
     */
    showToast: function(message, type = 'info', duration = 3000) {
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
        }, duration);
    }
};

// Polyfill for older browsers
if (!Array.prototype.slice.call) {
    Array.prototype.slice = function() {
        return Array.from(this);
    };
}
