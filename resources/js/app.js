import './bootstrap';

// ========================================
// RetroLib - JavaScript Utilities
// ========================================

/**
 * Initialize tooltips
 */
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

/**
 * Wishlist toggle
 */
function toggleWishlist(bookId) {
    const button = document.querySelector(`[data-wishlist="${bookId}"]`);
    const isWishlisted = button.classList.contains('active');
    const url = isWishlisted ? `/books/${bookId}/wishlist` : `/books/${bookId}/wishlist`;
    const method = isWishlisted ? 'DELETE' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
        .then(response => response.json())
        .then(data => {
            if (isWishlisted) {
                button.classList.remove('active');
                button.innerHTML = '<i class="far fa-heart"></i> Tambah ke Wishlist';
            } else {
                button.classList.add('active');
                button.innerHTML = '<i class="fas fa-heart"></i> Hapus dari Wishlist';
            }
            showAlert(data.message, 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan, silakan coba lagi', 'danger');
        });
}

/**
 * Show alert message
 */
function showAlert(message, type = 'info') {
    const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    const alertContainer = document.querySelector('main .container');
    const newAlert = document.createElement('div');
    newAlert.innerHTML = alertHTML;
    alertContainer.insertBefore(newAlert.firstElementChild, alertContainer.firstChild);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

/**
 * Confirm dialog
 */
function confirmAction(message = 'Apakah Anda yakin?') {
    return confirm(message);
}

/**
 * Format number to currency
 */
function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(value);
}

/**
 * Format date
 */
function formatDate(date) {
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
}

/**
 * Copy to clipboard
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('Berhasil disalin ke clipboard', 'success');
    }).catch(err => {
        console.error('Failed to copy:', err);
        showAlert('Gagal menyalin ke clipboard', 'danger');
    });
}

/**
 * Smooth scroll
 */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

/**
 * Mobile menu active state
 */
document.addEventListener('DOMContentLoaded', function () {
    const currentPage = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });
});

// Export functions for global use
window.toggleWishlist = toggleWishlist;
window.showAlert = showAlert;
window.confirmAction = confirmAction;
window.formatCurrency = formatCurrency;
window.formatDate = formatDate;
window.copyToClipboard = copyToClipboard;

