/**
 * DoItTrading Single Insight JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    
    /**
     * Progress Bar
     */
    function updateProgressBar() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        
        const progressBar = document.getElementById('progressBar');
        if (progressBar) {
            progressBar.style.width = scrolled + '%';
        }
    }
    
    window.addEventListener('scroll', updateProgressBar);
    
    /**
     * Table of Contents Active State
     */
    function updateTOC() {
        const sections = document.querySelectorAll('h2[id], h3[id]');
        const tocLinks = document.querySelectorAll('.toc-link');
        
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.scrollY >= (sectionTop - 200)) {
                current = section.getAttribute('id');
            }
        });
        
        tocLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').slice(1) === current) {
                link.classList.add('active');
            }
        });
    }
    
    window.addEventListener('scroll', updateTOC);
    
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
                const offsetTop = targetSection.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    /**
     * Copy Code Functionality
     */
    window.copyCode = function(codeId) {
        const codeElement = document.getElementById(codeId);
        const button = document.querySelector(`[data-code-id="${codeId}"] .copy-button`);
        
        if (codeElement && button) {
            const code = codeElement.textContent;
            
            navigator.clipboard.writeText(code).then(() => {
                // Success feedback
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.style.background = '#4CAF50';
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.style.background = '';
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                button.textContent = 'Failed';
            });
        }
    };
    
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