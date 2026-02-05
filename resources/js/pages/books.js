// Books Page Scripts
document.addEventListener('DOMContentLoaded', function() {
    // Initialize book cards with lazy loading
    const bookCovers = document.querySelectorAll('[data-book-cover]');
    bookCovers.forEach(cover => {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    observer.unobserve(img);
                }
            });
        });
        observer.observe(cover);
    });

    // Search and filter functionality
    const searchForm = document.querySelector('.search-filter-form');
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[name="search"]');
        const categorySelect = searchForm.querySelector('select[name="category"]');
        const sortSelect = searchForm.querySelector('select[name="sort"]');

        // Debounce search
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchForm.submit();
                }, 500);
            });
        }

        // Auto-submit on category change
        if (categorySelect) {
            categorySelect.addEventListener('change', () => {
                searchForm.submit();
            });
        }

        // Auto-submit on sort change
        if (sortSelect) {
            sortSelect.addEventListener('change', () => {
                searchForm.submit();
            });
        }
    }

    // Add to wishlist functionality
    const wishlistButtons = document.querySelectorAll('[data-wishlist-toggle]');
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const bookId = this.dataset.bookId;
            const isInWishlist = this.classList.contains('active');
            const url = isInWishlist 
                ? `/books/${bookId}/wishlist` 
                : `/books/${bookId}/wishlist`;
            const method = isInWishlist ? 'DELETE' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    this.classList.toggle('active');
                    const icon = this.querySelector('i');
                    if (isInWishlist) {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    } else {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Book card hover animation
    const bookCards = document.querySelectorAll('.book-card');
    bookCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = 'cardHover 0.3s ease-out';
            }, 10);
        });
    });

    // Rating display
    const ratings = document.querySelectorAll('[data-rating]');
    ratings.forEach(el => {
        const rating = parseFloat(el.dataset.rating);
        const stars = '★'.repeat(Math.floor(rating)) + '☆'.repeat(5 - Math.floor(rating));
        el.textContent = stars + ' ' + rating;
    });
});

// Export functions
window.BooksUtils = {
    toggleWishlist: function(bookId) {
        const button = document.querySelector(`[data-book-id="${bookId}"]`);
        if (button) button.click();
    }
};
