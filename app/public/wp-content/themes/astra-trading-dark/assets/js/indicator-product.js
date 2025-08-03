/**
 * DoItTrading Indicator Product JavaScript
 * Handles interactive functionality for indicator product pages
 * 
 * @package DoItTrading
 */

(function($) {
    'use strict';
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        
        // Visual Showcase Tabs
        if ($('.showcase-tabs').length > 0) {
            initShowcaseTabs();
        }
        
        // Product Details Tabs (Desktop)
        if ($('.details-tabs-nav').length > 0) {
            initDetailsTabs();
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
    
})(jQuery);