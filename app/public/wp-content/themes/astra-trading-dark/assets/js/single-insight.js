/**
 * DoItTrading Single Insight JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Throttle function to improve performance
    function throttle(func, wait) {
        let waiting = false;
        return function() {
            if (waiting) {
                return;
            }
            waiting = true;
            setTimeout(() => {
                func.apply(this, arguments);
                waiting = false;
            }, wait);
        };
    }

    /**
     * Progress Bar
     */
    const progressBar = document.getElementById('progressBar');
    
    function updateProgressBar() {
        if (!progressBar) return;
        
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        
        progressBar.style.width = scrolled + '%';
    }
    
    /**
     * Table of Contents Active State
     */
    function updateTOC() {
        const sections = document.querySelectorAll('.article-content h2[id], .article-content h3[id]');
        const tocLinks = document.querySelectorAll('.toc-link');
        
        if (!sections.length || !tocLinks.length) return;
        
        const scrollPosition = window.scrollY + 100; // Account for fixed header
        let current = '';
        
        // Find which section we're currently viewing
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionBottom = sectionTop + section.offsetHeight;
            
            // Check if we're within this section
            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                current = section.getAttribute('id');
            }
            // If we've scrolled past all sections, highlight the last one
            else if (scrollPosition >= sectionTop) {
                current = section.getAttribute('id');
            }
        });
        
        // Update active states
        tocLinks.forEach(link => {
            link.classList.remove('active');
            const href = link.getAttribute('href');
            if (href && href.slice(1) === current) {
                link.classList.add('active');
            }
        });
    }
    
    // Throttled scroll handlers
    const throttledProgressBar = throttle(updateProgressBar, 16); // ~60fps
    const throttledTOC = throttle(updateTOC, 100);
    
    window.addEventListener('scroll', throttledProgressBar);
    window.addEventListener('scroll', throttledTOC);
    
    // Initial calls
    updateProgressBar();
    updateTOC();
    
    /**
     * Smooth Scroll for TOC Links
     */
    const tocLinks = document.querySelectorAll('.toc-link');
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').slice(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                const headerOffset = 80; // Account for fixed header
                const elementPosition = targetSection.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Update URL without jumping
                history.pushState(null, null, '#' + targetId);
            }
        });
    });
    
    /**
     * Newsletter Form
     */
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const button = this.querySelector('.newsletter-btn');
            const originalText = button.textContent;
            
            button.textContent = 'Subscribing...';
            button.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = 'Subscribed!';
                    button.style.background = '#4CAF50';
                    this.reset();
                } else {
                    button.textContent = 'Error';
                    button.style.background = '#FF4757';
                }
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.style.background = '';
                    button.disabled = false;
                }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
                button.textContent = 'Error';
                button.disabled = false;
            });
        });
    }
    
    /**
     * Image Lightbox
     */
    const contentImages = document.querySelectorAll('.article-content img');
    contentImages.forEach(img => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', function() {
            // Create lightbox
            const lightbox = document.createElement('div');
            lightbox.className = 'image-lightbox';
            lightbox.innerHTML = `
                <div class="lightbox-content">
                    <span class="close-lightbox">&times;</span>
                    <img src="${this.src}" alt="${this.alt}">
                </div>
            `;
            
            document.body.appendChild(lightbox);
            
            // Close functionality
            lightbox.addEventListener('click', function(e) {
                if (e.target === lightbox || e.target.className === 'close-lightbox') {
                    lightbox.remove();
                }
            });
        });
    });
    
    /**
     * Reading Time Update
     */
    function updateReadingProgress() {
        const article = document.querySelector('.article-content');
        if (!article) return;
        
        const articleTop = article.offsetTop;
        const articleHeight = article.offsetHeight;
        const windowHeight = window.innerHeight;
        const scrolled = window.scrollY;
        
        const start = articleTop - windowHeight;
        const end = articleTop + articleHeight - windowHeight;
        const progress = Math.max(0, Math.min(100, ((scrolled - start) / (end - start)) * 100));
        
        // Update any reading progress indicators
        const progressIndicators = document.querySelectorAll('.reading-progress');
        progressIndicators.forEach(indicator => {
            indicator.style.width = progress + '%';
        });
    }
    
    window.addEventListener('scroll', updateReadingProgress);
    
    /**
     * Sticky Sidebar
     */
    function handleStickySidebar() {
        const sidebar = document.querySelector('.insight-sidebar');
        const content = document.querySelector('.article-content');
        
        if (!sidebar || !content || window.innerWidth < 992) return;
        
        const sidebarTop = sidebar.offsetTop;
        const contentBottom = content.offsetTop + content.offsetHeight;
        const sidebarHeight = sidebar.offsetHeight;
        
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            
            if (scrolled > sidebarTop - 20) {
                if (scrolled + sidebarHeight + 20 < contentBottom) {
                    sidebar.style.position = 'fixed';
                    sidebar.style.top = '20px';
                } else {
                    sidebar.style.position = 'absolute';
                    sidebar.style.top = (contentBottom - sidebarHeight - sidebarTop) + 'px';
                }
            } else {
                sidebar.style.position = 'sticky';
                sidebar.style.top = '20px';
            }
        });
    }
    
    handleStickySidebar();
    
    /**
     * Analytics Tracking
     */
    function trackReadComplete() {
        let hasTracked = false;
        
        window.addEventListener('scroll', () => {
            if (hasTracked) return;
            
            const scrolled = window.scrollY + window.innerHeight;
            const docHeight = document.documentElement.scrollHeight;
            
            if (scrolled >= docHeight - 100) {
                hasTracked = true;
                
                // Track with Google Analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'read_complete', {
                        'event_category': 'Insights',
                        'event_label': document.title
                    });
                }
                
                // Track with Facebook Pixel
                if (typeof fbq !== 'undefined') {
                    fbq('track', 'ViewContent', {
                        content_name: document.title,
                        content_category: 'insight_complete'
                    });
                }
            }
        });
    }
    
    trackReadComplete();
});

// CSS for lightbox
const lightboxStyles = `
<style>
.image-lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
}

.lightbox-content img {
    max-width: 100%;
    max-height: 90vh;
    object-fit: contain;
}

.close-lightbox {
    position: absolute;
    top: -40px;
    right: 0;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    transition: color 0.3s;
}

.close-lightbox:hover {
    color: var(--doit-green);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
`;

document.head.insertAdjacentHTML('beforeend', lightboxStyles);