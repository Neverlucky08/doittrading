/**
 * DoItTrading Product Details - Tabs & Accordion
 * Maneja tanto tabs desktop como accordion mobile
 */

let stickyOffset = 0;

(function($) {
    'use strict';
    
    let isDesktop = window.innerWidth > 768;
    let stickyOffset = 0;
    
    /**
     * Initialize product details functionality
     */
    function initProductDetails() {
        if (!$('.doittrading-product-details').length) return;
        
        initTabSwitching();
        initAccordion();
        initStickyTabs();
        initUrlHash();
        
        // Handle responsive switching
        $(window).on('resize', debounce(handleResize, 300));
    }
    
    /**
     * Desktop Tab Switching
     */
    function initTabSwitching() {
        $('.tab-btn').on('click', function(e) {
            e.preventDefault();
            
            const $btn = $(this);
            const tabId = $btn.data('tab');
            
            if ($btn.hasClass('active')) return;
            
            // Update buttons
            $('.tab-btn').removeClass('active');
            $btn.addClass('active');
            
            // Switch content with animation
            $('.tab-content.active').fadeOut(200, function() {
                $(this).removeClass('active');
                $(`#${tabId}`).fadeIn(300).addClass('active');
            });
            
            // Update URL hash
            updateUrlHash(tabId);
            
            // Analytics tracking
            trackTabSwitch(tabId);
        });
    }
    
    /**
     * Mobile Accordion
     */
    function initAccordion() {
        $('.accordion-header').on('click', function(e) {
            e.preventDefault();
            
            const $header = $(this);
            const $item = $header.closest('.accordion-item');
            const $content = $item.find('.accordion-content');
            const isOpen = $item.hasClass('active');
            
            if (isOpen) {
                // Close this item
                $item.removeClass('active');
                $content.slideUp(300);
                $header.find('.accordion-icon').text('+');
            } else {
                // Close all other items
                $('.accordion-item.active').each(function() {
                    $(this).removeClass('active');
                    $(this).find('.accordion-content').slideUp(300);
                    $(this).find('.accordion-icon').text('+');
                });
                
                // Open this item
                $item.addClass('active');
                $content.slideDown(300);
                $header.find('.accordion-icon').text('−');
                
                // Scroll to header with offset
                $('html, body').animate({
                    scrollTop: $header.offset().top - 100
                }, 300);
            }
            
            // Analytics tracking
            trackAccordionToggle($item.data('section'));
        });
    }
    
    /**
     * Sticky tabs functionality
     */
    function initStickyTabs() {
        const $tabsNav = $('.details-tabs-nav');
        if (!$tabsNav.length) return;
        
        // Calcular offset inicial
        stickyOffset = $tabsNav.offset().top;
        
        // Store original width
        const originalWidth = $tabsNav.outerWidth();
        
        $(window).on('scroll', throttle(function() {
            handleStickyTabs(originalWidth);
        }, 16));
    }
    
    function handleStickyTabs(originalWidth) {
        const $tabsNav = $('.details-tabs-nav');
        const $container = $('.doittrading-product-details');
        const scrollTop = $(window).scrollTop();
        
        if (scrollTop >= stickyOffset - 20) {
            if (!$tabsNav.hasClass('sticky')) {
                // Get container position and width
                const containerOffset = $container.offset();
                const containerWidth = $container.outerWidth();
                
                $tabsNav.addClass('sticky');
                
                // Set width and position to match original container
                $tabsNav.css({
                    'width': containerWidth + 'px',
                    'left': '50%',
                    'transform': 'translateX(-50%)',
                    'margin-left': '0'
                });
                
                // Add spacer to prevent jump
                $tabsNav.before('<div class="sticky-spacer" style="height: ' + $tabsNav.outerHeight() + 'px;"></div>');
            }
        } else {
            if ($tabsNav.hasClass('sticky')) {
                $tabsNav.removeClass('sticky');
                
                // Reset styles
                $tabsNav.css({
                    'width': '',
                    'left': '',
                    'transform': '',
                    'margin-left': ''
                });
                
                $('.sticky-spacer').remove();
            }
        }
    }
    
    /**
     * URL Hash management
     */
    function initUrlHash() {
        // Check for hash on load
        // const hash = window.location.hash.substring(1);
        // if (hash && $('.tab-btn[data-tab="' + hash + '"]').length) {
        //     $('.tab-btn[data-tab="' + hash + '"]').trigger('click');
        // }
        
        // Handle browser back/forward
        $(window).on('hashchange', function() {
            const newHash = window.location.hash.substring(1);
            if (newHash && $('.tab-btn[data-tab="' + newHash + '"]').length) {
                $('.tab-btn[data-tab="' + newHash + '"]').trigger('click');
            }
        });
    }
    
    function updateUrlHash(tabId) {
        if (history.replaceState) {
            history.replaceState(null, null, '#' + tabId);
        } else {
            window.location.hash = tabId;
        }
    }
    
    /**
     * Handle responsive changes
     */
    function handleResize() {
        const newIsDesktop = window.innerWidth > 768;
        
        if (newIsDesktop !== isDesktop) {
            isDesktop = newIsDesktop;
            
            // Remove sticky class and reset
            $('.details-tabs-nav').removeClass('sticky').css({
                'width': '',
                'left': '',
                'transform': '',
                'margin-left': ''
            });
            $('.sticky-spacer').remove();
            
            if (isDesktop) {
                // Switch to desktop mode
                $('.accordion-item').removeClass('active');
                $('.accordion-content').hide();
                $('.accordion-icon').text('+');
                
                // Show first tab content
                $('.tab-content').removeClass('active').hide();
                $('.tab-content:first').addClass('active').show();
                $('.tab-btn:first').addClass('active');
            } else {
                // Switch to mobile mode
                $('.tab-btn').removeClass('active');
                $('.tab-content').removeClass('active').hide();
                
                // Open first accordion item
                $('.accordion-item:first').addClass('active');
                $('.accordion-item:first .accordion-content').show();
                $('.accordion-item:first .accordion-icon').text('−');
            }
            
            // Recalculate sticky offset
            setTimeout(function() {
                const $tabsNav = $('.details-tabs-nav');
                if ($tabsNav.length && isDesktop) {
                    stickyOffset = $tabsNav.offset().top;
                }
            }, 100);
        }
    }
    
    /**
     * Analytics tracking
     */
    function trackTabSwitch(tabId) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'tab_switch', {
                'event_category': 'Product Details',
                'event_label': tabId,
                'value': 1
            });
        }
    }
    
    function trackAccordionToggle(section) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'accordion_toggle', {
                'event_category': 'Product Details Mobile',
                'event_label': section,
                'value': 1
            });
        }
    }
    
    /**
     * Utility functions
     */
    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
    
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
    
    /**
     * Initialize on DOM ready
     */
    $(document).ready(function() {
        initProductDetails();
        
        // Debug
        if (typeof doittrading_vars !== 'undefined' && console.log) {
            console.log('DoItTrading Product Details JS loaded');
        }
    });
    
})(jQuery);