/**
 * DoItTrading Indicator Product JavaScript
 * Handles interactive functionality for indicator product pages
 * 
 * @package DoItTrading
 */

(function($) {
    'use strict';
    
    // Sticky offset tracking
    let stickyOffset = 0;
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        
        // Visual Showcase Tabs
        if ($('.showcase-tabs').length > 0) {
            initShowcaseTabs();
        }
        
        // Product Details Tabs (Desktop)
        if ($('.details-tabs-nav').length > 0) {
            initDetailsTabs();
            initStickyTabs(); // Add sticky functionality
        }
        
        // Product Details Accordion (Mobile)
        if ($('.details-accordion').length > 0) {
            initDetailsAccordion();
        }
        
    });
    
    /**
     * Initialize Showcase Tabs
     */
    function initShowcaseTabs() {
        $('.showcase-tab').on('click', function() {
            const $tab = $(this);
            const tabId = $tab.data('tab');
            
            // Update active tab
            $('.showcase-tab').removeClass('active');
            $tab.addClass('active');
            
            // Update active content
            $('.showcase-item').removeClass('active');
            $('#' + tabId).addClass('active');
            
            // Smooth transition
            $('#' + tabId).hide().fadeIn(300);
        });
    }
    
    /**
     * Initialize Details Tabs (Desktop)
     */
    function initDetailsTabs() {
        $('.tab-btn').on('click', function() {
            const $btn = $(this);
            const tabId = $btn.data('tab');
            
            // Update active button
            $('.tab-btn').removeClass('active');
            $btn.addClass('active');
            
            // Update active content
            $('.tab-content').removeClass('active');
            $('#' + tabId).addClass('active');
            
            // Smooth transition
            $('#' + tabId).hide().fadeIn(300);
        });
    }
    
    /**
     * Initialize Details Accordion (Mobile)
     */
    function initDetailsAccordion() {
        $('.accordion-header').on('click', function() {
            const $header = $(this);
            const $item = $header.closest('.accordion-item');
            const $content = $item.find('.accordion-content');
            const $icon = $header.find('.accordion-icon');
            
            // Toggle current item
            if ($item.hasClass('active')) {
                // Close current item
                $item.removeClass('active');
                $content.slideUp(300);
                $icon.text('+');
            } else {
                // Close all other items
                $('.accordion-item').removeClass('active');
                $('.accordion-content').slideUp(300);
                $('.accordion-icon').text('+');
                
                // Open current item
                $item.addClass('active');
                $content.slideDown(300);
                $icon.text('−');
            }
        });
    }
    
    /**
     * Smooth scroll to section
     */
    function scrollToSection(target) {
        const $target = $(target);
        if ($target.length) {
            $('html, body').animate({
                scrollTop: $target.offset().top - 100
            }, 800);
        }
    }
    
    /**
     * Sticky tabs functionality
     */
    function initStickyTabs() {
        const $tabsNav = $('.details-tabs-nav');
        if (!$tabsNav.length) return;
        
        // Calculate initial offset
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
     * Throttle utility function
     */
    function throttle(func, delay) {
        let isThrottled = false;
        let savedArgs = null;
        let savedThis = null;
        
        return function(...args) {
            if (isThrottled) {
                savedArgs = args;
                savedThis = this;
                return;
            }
            
            func.apply(this, args);
            isThrottled = true;
            
            setTimeout(() => {
                isThrottled = false;
                if (savedArgs) {
                    throttle(func, delay).apply(savedThis, savedArgs);
                    savedArgs = null;
                    savedThis = null;
                }
            }, delay);
        };
    }
    
    // Handle window resize
    $(window).on('resize', debounce(function() {
        if ($(window).width() > 768) {
            // Recalculate sticky offset
            const $tabsNav = $('.details-tabs-nav');
            if ($tabsNav.length && !$tabsNav.hasClass('sticky')) {
                stickyOffset = $tabsNav.offset().top;
            }
        }
    }, 250));
    
    /**
     * Debounce utility function
     */
    function debounce(func, delay) {
        let timeoutId;
        return function(...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    }
    
})(jQuery);