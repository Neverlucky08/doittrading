// ROI Calculator Interactive JavaScript
(function() {
    'use strict';
    
    let calculatorInstance = null;
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initROICalculator);
    } else {
        initROICalculator();
    }
    
    function initROICalculator() {
        // Check if roiConfig is available (passed from PHP)
        if (typeof roiConfig === 'undefined') {
            console.warn('ROI Calculator: roiConfig not found');
            return;
        }
        
        // DOM Elements
        const capitalSlider = document.getElementById('capitalSlider');
        const capitalDisplay = document.getElementById('capitalDisplay');
        
        // Result elements
        const eaCostEl = document.getElementById('eaCost');
        const monthlyReturnEl = document.getElementById('monthlyReturn');
        const monthlyDescEl = document.getElementById('monthlyDesc');
        const paybackTimeEl = document.getElementById('paybackTime');
        const yearlyROIEl = document.getElementById('yearlyROI');
        const yearlyProfitEl = document.getElementById('yearlyProfit');
        
        // Check if all elements exist
        if (!capitalSlider || !capitalDisplay) {
            console.warn('ROI Calculator: Required DOM elements not found');
            return;
        }
        
        // Configuration from PHP
        const config = {
            price: parseFloat(roiConfig.price) || 297,
            monthlyGainPercent: parseFloat(roiConfig.monthlyGain) || 7.5,
            currency: roiConfig.currency || '$'
        };
        
        /**
         * Format price with currency symbol
         */
        function formatPrice(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }
        
        /**
         * Format percentage
         */
        function formatPercentage(percent) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(percent) + '%';
        }
        
        /**
         * Add highlight animation to element
         */
        function addHighlightAnimation(element) {
            if (!element) return;
            
            element.classList.add('highlight-animation');
            setTimeout(() => {
                element.classList.remove('highlight-animation');
            }, 1000);
        }
        
        /**
         * Calculate and update all ROI values
         */
        function calculateROI() {
            const capital = parseInt(capitalSlider.value);
            
            // Basic calculations
            const monthlyReturn = (config.monthlyGainPercent / 100) * capital;
            const paybackMonths = config.price / monthlyReturn;
            const yearlyProfit = monthlyReturn * 12;
            const yearlyROI = ((yearlyProfit / config.price) * 100);
            
            // Update capital display
            capitalDisplay.textContent = formatPrice(capital);
            
            // Update results with error checking
            if (eaCostEl) {
                eaCostEl.textContent = formatPrice(config.price);
            }
            
            if (monthlyReturnEl) {
                monthlyReturnEl.textContent = formatPrice(monthlyReturn);
                addHighlightAnimation(monthlyReturnEl);
            }
            
            if (monthlyDescEl) {
                monthlyDescEl.textContent = `${config.monthlyGainPercent}% monthly gain on your capital`;
            }
            
            if (paybackTimeEl) {
                paybackTimeEl.textContent = paybackMonths.toFixed(1);
            }
            
            if (yearlyROIEl) {
                yearlyROIEl.textContent = formatPercentage(yearlyROI);
                addHighlightAnimation(yearlyROIEl);
            }
            
            if (yearlyProfitEl) {
                yearlyProfitEl.textContent = `${formatPrice(yearlyProfit)} total profit in first year`;
            }
        }
        
        /**
         * Set capital to specific amount
         */
        function setCapital(amount) {
            capitalSlider.value = amount;
            calculateROI();
            addHighlightAnimation(capitalDisplay);
        }
        
        // Create calculator instance
        calculatorInstance = {
            setCapital: setCapital,
            calculateROI: calculateROI
        };
        
        // Event listeners
        capitalSlider.addEventListener('input', calculateROI);
        
        // Add interactive behaviors to result cards
        document.querySelectorAll('.result-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                const value = card.querySelector('.result-value');
                if (value) {
                    value.style.transform = 'scale(1.05)';
                }
            });
            
            card.addEventListener('mouseleave', () => {
                const value = card.querySelector('.result-value');
                if (value) {
                    value.style.transform = 'scale(1)';
                }
            });
        });
        
        // Add click handlers to preset buttons
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = parseInt(this.textContent.replace(/[$,]/g, ''));
                setCapital(amount);
            });
        });
        
        // Initialize calculator
        calculateROI();
        
        console.log('ROI Calculator initialized successfully');
    }
    
    // Global setCapital function
    window.setCapital = function(amount) {
        if (calculatorInstance && calculatorInstance.setCapital) {
            calculatorInstance.setCapital(amount);
        } else {
            console.warn('ROI Calculator not yet initialized');
        }
    };
})();