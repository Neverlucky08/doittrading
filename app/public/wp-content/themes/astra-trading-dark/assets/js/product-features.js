/**
 * DoItTrading Product Features
 * JavaScript para elementos dinámicos
 */

(function($) {
    'use strict';
    
    /**
     * Countdown Timer
     */
    function initCountdown() {
        const $timer = $('.countdown-timer');
        if (!$timer.length) return;
        
        const target = new Date($timer.data('target')).getTime();
        
        const updateTimer = () => {
            const now = new Date().getTime();
            const difference = target - now;
            
            if (difference > 0) {
                const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((difference % (1000 * 60)) / 1000);
                
                $('.countdown-display').text(
                    `${days}d ${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
                );
            } else {
                $('.countdown-display').text('EXPIRED - Refresh page');
            }
        };
        
        setInterval(updateTimer, 1000);
        updateTimer();
    }
    
    /**
     * Inline Countdown (for CTA section)
     */
    function initInlineCountdown() {
        const $timer = $('.countdown-inline');
        if (!$timer.length) return;
        
        const target = new Date($timer.data('target')).getTime();
        
        const updateTimer = () => {
            const now = new Date().getTime();
            const difference = target - now;
            
            if (difference > 0) {
                const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                
                $timer.text(`${days} days ${hours}:${minutes.toString().padStart(2, '0')}:00`);
            } else {
                $timer.text('NOW!');
            }
        };
        
        setInterval(updateTimer, 60000); // Update every minute
        updateTimer();
    }
    
    /**
     * Live dot animation
     */
    function initLiveDot() {
        $('.live-dot').each(function() {
            const $dot = $(this);
            setInterval(() => {
                $dot.fadeOut(300).fadeIn(300);
            }, 2000);
        });
    }
    
    /**
     * Recent purchase notification
     */
    function showPurchaseNotification() {
        // Solo mostrar si hay stock warning
        if (!$('.stock-warning').length) return;
        
        // Obtener datos del DOM si están disponibles
        const $recentPurchase = $('.recent-purchase');
        let buyerData = null;
        
        if ($recentPurchase.length) {
            // Extraer datos del HTML existente
            const text = $recentPurchase.text();
            const matches = text.match(/🔥\s*([^from]+)\s*from\s*([^just]+)\s*just purchased\s*\((\d+)\s*minutes? ago\)/);
            
            if (matches) {
                buyerData = {
                    name: matches[1].trim(),
                    location: matches[2].trim(),
                    minutes: parseInt(matches[3])
                };
            }
        }
        
        // Si no hay datos, usar valores por defecto
        if (!buyerData) {
            const buyers = [
                { name: 'Alex K.', location: 'London' },
                { name: 'Maria S.', location: 'Madrid' },
                { name: 'Zhang W.', location: 'Singapore' },
                { name: 'Ahmed M.', location: 'Dubai' }
            ];
            
            const buyer = buyers[Math.floor(Math.random() * buyers.length)];
            buyerData = {
                name: buyer.name,
                location: buyer.location,
                minutes: Math.floor(Math.random() * 10) + 3
            };
        }
        
        setTimeout(() => {
            const notification = $(`
                <div class="purchase-notification">
                    <span class="close">&times;</span>
                    <p>🔥 <strong>${buyerData.name}</strong> from ${buyerData.location} just purchased (${buyerData.minutes} minutes ago)</p>
                </div>
            `);
            
            $('body').append(notification);
            notification.fadeIn(300);
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
            
            // Close button
            notification.find('.close').on('click', function() {
                notification.fadeOut(300, function() {
                    $(this).remove();
                });
            });
            
        }, 15000); // Show after 15 seconds
    }
    
    /**
     * Track MQL5 clicks
     */
    window.doittrading_track_click = function(type, productId) {
        // Send to analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'click', {
                'event_category': 'MQL5',
                'event_label': type,
                'value': productId
            });
        }
        
        // Update stock if showing
        const $stock = $('.stock-warning strong');
        if ($stock.length) {
            const current = parseInt($stock.text());
            if (current > 5) {
                $stock.text(current - 1);
            }
        }
    };
    
    /**
     * Show contact modal
     */
    window.doittrading_show_contact = function() {
        alert('Please email support@doittrading.com for purchase information.');
        // In production, this would open a nice modal
    };
    
    /**
     * Smooth scroll to sections
     */
    function initSmoothScroll() {
        $('a[href*="#"]:not([href="#"])').on('click', function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 800);
                    return false;
                }
            }
        });
    }
    
    /**
     * Initialize all features
     */
    $(document).ready(function() {
        initCountdown();
        initInlineCountdown();
        initLiveDot();
        showPurchaseNotification();
        initSmoothScroll();
        
        // Debug mode indicator
        if (doittrading_vars && console.log) {
            console.log('DoItTrading Product JS loaded', {
                product_id: doittrading_vars.product_id
            });
        }
    });
    
})(jQuery);