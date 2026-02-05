/* ========================================
   Landing Page JavaScript
   ======================================== */

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for navigation links
    setupSmoothScrolling();
    
    // Add scroll animations
    setupScrollAnimations();
    
    // Mobile menu toggle
    setupMobileMenu();
});

/**
 * Smooth scrolling for navigation links
 */
function setupSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * Scroll animations - fade in elements as they come into view
 */
function setupScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe all animated elements
    const animatedElements = document.querySelectorAll(
        '.stat-card, .book-card, .feature-card, .cta-section'
    );
    
    animatedElements.forEach(element => {
        element.style.animationPlayState = 'paused';
        observer.observe(element);
    });
}

/**
 * Mobile menu toggle
 */
function setupMobileMenu() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
            // Close menu when clicking a nav link on mobile
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        navbarToggler.click();
                    }
                });
            });
        });
    }
}

/**
 * Add active state to navbar links based on scroll position
 */
window.addEventListener('scroll', function() {
    const sections = document.querySelectorAll('section, .hero');
    let current = '';
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        
        if (scrollY >= sectionTop - sectionHeight / 3) {
            current = section.getAttribute('id');
        }
    });
    
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href').slice(1) === current) {
            link.classList.add('active');
        }
    });
});

/**
 * Add scroll to top button visibility
 */
window.addEventListener('scroll', function() {
    const scrollTopButton = document.getElementById('scrollTopBtn');
    
    if (scrollTopButton) {
        if (window.scrollY > 300) {
            scrollTopButton.style.display = 'block';
        } else {
            scrollTopButton.style.display = 'none';
        }
    }
});

/**
 * Scroll to top button click handler
 */
document.addEventListener('DOMContentLoaded', function() {
    const scrollTopButton = document.getElementById('scrollTopBtn');
    
    if (scrollTopButton) {
        scrollTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});

/**
 * Format book rating display
 */
function initializeBookCards() {
    const bookCards = document.querySelectorAll('.book-card');
    
    bookCards.forEach(card => {
        // Add click animation
        card.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.98)';
        });
        
        card.addEventListener('mouseup', function() {
            this.style.transform = '';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
}

// Initialize book cards when DOM is ready
document.addEventListener('DOMContentLoaded', initializeBookCards);

/**
 * Handle category filter clicks
 */
function setupCategoryFilters() {
    const categoryButtons = document.querySelectorAll('[data-category]');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category');
            
            // Scroll to featured section
            const featuredSection = document.querySelector('.featured-section');
            if (featuredSection) {
                featuredSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', setupCategoryFilters);
