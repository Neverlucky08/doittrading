/**
 * User Guide Template JavaScript
 * Handles navigation, sticky sidebar, and smooth scrolling
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initUserGuide();
    });

    function initUserGuide() {
        const sidebar = document.querySelector('.userguide-sidebar');
        const navMenu = document.getElementById('userguide-nav-menu');
        const content = document.querySelector('.userguide-body');
        
        if (!sidebar || !navMenu || !content) {
            return;
        }

        // Generate navigation from content headings
        generateNavigation(content, navMenu);
        
        // Initialize sticky sidebar
        initStickySidebar(sidebar);
        
        // Initialize smooth scrolling
        initSmoothScrolling();
        
        // Initialize active section highlighting
        initActiveSection();
        
        // Initialize mobile navigation
        initMobileNavigation();
    }

    /**
     * Generate navigation menu from content headings
     */
    function generateNavigation(content, navMenu) {
        const headings = content.querySelectorAll('h2, h3');
        
        if (headings.length === 0) {
            navMenu.innerHTML = '<li><span style="color: var(--doit-text-muted);">No sections found</span></li>';
            return;
        }
        
        let navHTML = '';
        let sectionIndex = 0;
        
        headings.forEach(function(heading) {
            sectionIndex++;
            const headingId = heading.id || 'section-' + sectionIndex;
            
            // Add ID to heading if it doesn't have one
            if (!heading.id) {
                heading.id = headingId;
            }
            
            const headingText = heading.textContent.trim();
            const isSubheading = heading.tagName.toLowerCase() === 'h3';
            const className = isSubheading ? 'nav-sub-item' : 'nav-main-item';
            
            navHTML += `
                <li class="${className}">
                    <a href="#${headingId}" data-section="${headingId}">
                        ${headingText}
                    </a>
                </li>
            `;
        });
        
        navMenu.innerHTML = navHTML;
    }

    /**
     * Initialize sticky sidebar behavior
     */
    function initStickySidebar(sidebar) {
        // Add sticky class to sidebar
        sidebar.classList.add('sticky');
        
        // Adjust sticky position based on admin bar
        const adminBar = document.getElementById('wpadminbar');
        if (adminBar) {
            const adminBarHeight = adminBar.offsetHeight;
            sidebar.style.top = (100 + adminBarHeight) + 'px';
        }
    }

    /**
     * Initialize smooth scrolling for navigation links
     */
    function initSmoothScrolling() {
        document.querySelectorAll('.userguide-nav a[href^="#"]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    const offset = 100; // Account for fixed header
                    const targetPosition = targetElement.offsetTop - offset;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile navigation if open
                    const mobileNav = document.querySelector('.userguide-nav.mobile-visible');
                    if (mobileNav) {
                        mobileNav.classList.remove('mobile-visible');
                    }
                }
            });
        });
    }

    /**
     * Initialize active section highlighting
     */
    function initActiveSection() {
        const navLinks = document.querySelectorAll('.userguide-nav a[data-section]');
        const sections = [];
        
        // Collect all sections
        navLinks.forEach(function(link) {
            const sectionId = link.getAttribute('data-section');
            const section = document.getElementById(sectionId);
            if (section) {
                sections.push({
                    id: sectionId,
                    element: section,
                    link: link
                });
            }
        });
        
        if (sections.length === 0) {
            return;
        }
        
        // Throttle function for scroll performance
        let ticking = false;
        function updateActiveSection() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    highlightActiveSection(sections);
                    ticking = false;
                });
                ticking = true;
            }
        }
        
        // Listen for scroll events
        window.addEventListener('scroll', updateActiveSection);
        
        // Initial highlight
        highlightActiveSection(sections);
    }

    /**
     * Highlight the currently visible section
     */
    function highlightActiveSection(sections) {
        const scrollPosition = window.scrollY;
        const windowHeight = window.innerHeight;
        const offset = 150; // Offset for determining active section
        
        let activeSection = null;
        
        // Find the current section
        sections.forEach(function(section) {
            const sectionTop = section.element.offsetTop - offset;
            const sectionBottom = sectionTop + section.element.offsetHeight;
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                activeSection = section;
            }
        });
        
        // If no section is active, check if we're at the top
        if (!activeSection && scrollPosition < 200) {
            activeSection = sections[0];
        }
        
        // Update active classes
        sections.forEach(function(section) {
            if (section === activeSection) {
                section.link.classList.add('active');
            } else {
                section.link.classList.remove('active');
            }
        });
    }

    /**
     * Initialize mobile navigation toggle
     */
    function initMobileNavigation() {
        // Create mobile toggle button
        const toggleButton = document.createElement('button');
        toggleButton.className = 'mobile-nav-toggle';
        toggleButton.innerHTML = `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        `;
        toggleButton.setAttribute('aria-label', 'Toggle navigation menu');
        
        // Add toggle button to page
        document.body.appendChild(toggleButton);
        
        // Handle toggle click
        toggleButton.addEventListener('click', function() {
            const nav = document.querySelector('.userguide-nav');
            if (nav) {
                nav.classList.toggle('mobile-visible');
                
                // Update button icon
                if (nav.classList.contains('mobile-visible')) {
                    toggleButton.innerHTML = `
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    `;
                } else {
                    toggleButton.innerHTML = `
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    `;
                }
            }
        });
        
        // Close mobile nav when clicking outside
        document.addEventListener('click', function(e) {
            const nav = document.querySelector('.userguide-nav.mobile-visible');
            if (nav && !nav.contains(e.target) && !toggleButton.contains(e.target)) {
                nav.classList.remove('mobile-visible');
                toggleButton.innerHTML = `
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                `;
            }
        });
    }

})();