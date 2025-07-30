<?php
/**
 * DoItTrading Product Display Features
 * Hero sections, stats, y elementos de producto
 * 
 * @package DoItTrading
 */

/** @phpcs:disable */
/** @phpstan-ignore-next-line */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Hero Section V2 - Hybrid Smart Layout
 * Hooks into woocommerce_before_single_product_summary to control full layout
 */
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
add_action('woocommerce_before_single_product_summary', 'doittrading_hero_hybrid_smart', 20);

function doittrading_hero_hybrid_smart() {
    if (!doittrading_is_ea()) {
        // For non-EA products, show default images
        woocommerce_show_product_images();
        return;
    }
    
    global $product;
    $product_id = get_the_ID();
    $win_rate = get_field('win_rate', $product_id);
    
    // Get product image
    $image_id = $product->get_image_id();
    $image_url = wp_get_attachment_image_url($image_id, 'large');
    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    
    if (!$image_url) {
        $image_url = wc_placeholder_img_src('large');
        $image_alt = 'Product placeholder';
    }
    ?>
    <div class="doittrading-hero-hybrid">
        <!-- Left Column: Product Image with Live Badge -->
        <div class="hero-image-column">
            <div class="product-image-wrapper">
                <div class="live-badge-hero">🟢 LIVE</div>
                <img src="<?php echo esc_url($image_url); ?>" 
                     alt="<?php echo esc_attr($image_alt ?: get_the_title()); ?>" 
                     class="hero-product-image">
            </div>
        </div>
        
        <!-- Right Column: Hero Content -->
        <div class="hero-content-column">
            <!-- Hero V2 Content -->
            <div class="doittrading-hero-v2">
                <!-- Hook Emocional -->
                <div class="hero-hook">
                    <h1 class="hero-title">Finally, A Trading EA That Actually Works</h1>
                    <div class="hero-subtitle">
                        <?php echo esc_html($win_rate); ?>% Win Rate • MyFxBook Verified • No Martingale
                    </div>
                </div>
                
                <!-- Live Indicators -->
                <div class="hero-live-bar">
                    <div class="live-indicator">
                        <span class="live-dot"></span>
                        <strong>LIVE:</strong> <?php echo doittrading_get_active_traders(); ?> traders using this EA right now
                    </div>
                    <div class="recent-trade">
                        <?php 
                        $last_trade = doittrading_get_last_trade();
                        $icon = $last_trade['direction'] === 'profit' ? '✅' : '⚡';
                        ?>
                        <?php echo $icon; ?> <strong>Last trade:</strong> 
                        <?php echo $last_trade['direction'] === 'profit' ? '+' : '-'; ?><?php echo $last_trade['pips']; ?> pips 
                        (<?php echo $last_trade['time_ago']; ?>m ago)
                    </div>
                </div>
            </div>
            
            <!-- Price Section (from marketing-features.php) -->
            <?php doittrading_urgency_section(); ?>
        </div>
    </div>
    <?php
}

/**
 * Wrapper for full-width sections after hero
 */
add_action('woocommerce_single_product_summary', 'doittrading_full_width_wrapper_start', 10);
function doittrading_full_width_wrapper_start() {
    if (!doittrading_is_ea()) return;
    echo '<div class="doittrading-full-width-sections">';
}

add_action('woocommerce_single_product_summary', 'doittrading_full_width_wrapper_end', 25);
function doittrading_full_width_wrapper_end() {
    if (!doittrading_is_ea()) return;
    echo '</div>';
}

/**
 * Enhanced Stats Display
 */
add_action('woocommerce_single_product_summary', 'doittrading_stats_enhanced', 11);
function doittrading_stats_enhanced() {
    $product_id = get_the_ID();
    
    if (!doittrading_is_ea() || !get_field('monthly_gain', $product_id)) return;
    ?>
    <div class="doittrading-stats-enhanced">
        <h3>Real Results, Real Money, Real Timeframes</h3>
        <div class="stats-grid-enhanced">
            <div class="stat-box-enhanced">
                <div class="stat-value positive">+<?php the_field('monthly_gain', $product_id); ?>%</div>
                <div class="stat-label">Monthly Gain</div>
                <div class="stat-context">Last 6 months avg<br>(Live account)</div>
            </div>
            <div class="stat-box-enhanced">
                <div class="stat-value positive"><?php the_field('win_rate', $product_id); ?>%</div>
                <div class="stat-label">Win Rate</div>
                <div class="stat-context">Verified trades<br>Since Jan 2024</div>
            </div>
            <div class="stat-box-enhanced">
                <div class="stat-value positive">-<?php the_field('max_drawdown', $product_id); ?>%</div>
                <div class="stat-label">Max Drawdown</div>
                <div class="stat-context">Conservative<br>Risk management</div>
            </div>
            <div class="stat-box-enhanced">
                <div class="stat-value positive"><?php the_field('profit_factor', $product_id); ?></div>
                <div class="stat-label">Profit Factor</div>
                <div class="stat-context">$<?php echo number_format((float)get_field('profit_factor', $product_id), 2); ?> earned<br>per $1 risked</div>
            </div>
        </div>
        
        <?php if (get_field('myfxbook_url', $product_id)): ?>
        <div class="myfxbook-link-enhanced">
            <a href="<?php the_field('myfxbook_url', $product_id); ?>" target="_blank" rel="noopener">
                📊 View Live MyFxBook Account (Updated Every Trade) →
            </a>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * ROI Calculator
 */
add_action('woocommerce_single_product_summary', 'doittrading_interactive_roi_calculator', 21);
function doittrading_interactive_roi_calculator() {
    global $product;
    $product_id = get_the_ID();
    
    if (!doittrading_is_ea()) return;
    
    $monthly_gain = get_field('monthly_gain', $product_id);
    $price = $product->get_price();
    
    if (!$monthly_gain || !$price) return;
    
    // Enqueue our scripts and styles with correct path
    wp_enqueue_script('roi-calculator-js', get_stylesheet_directory_uri() . '/assets/js/roi-calculator.js', array(), '1.0.1', true);

    // Pass PHP data to JavaScript
    wp_localize_script('roi-calculator-js', 'roiConfig', array(
        'price' => floatval($price),
        'monthlyGain' => floatval($monthly_gain),
        'currency' => get_woocommerce_currency_symbol(),
        'debug' => true // Para debugging
    ));
    ?>
    
    <div class="roi-calculator-interactive">
        <div class="roi-header">
            <h3 class="roi-title">💰 Calculate Your Real Returns</h3>
            <p class="roi-subtitle">See how much profit you could make with your investment capital</p>
        </div>

        <div class="controls-section">
            <div class="capital-control">
                <label class="capital-label">Your Investment Capital:</label>
                <div class="capital-display pulse" id="capitalDisplay">$5,000</div>
                <input type="range" class="capital-slider" id="capitalSlider" 
                       min="500" max="50000" step="500" value="5000">
                
                <div class="preset-buttons">
                    <button class="preset-btn" data-amount="1000">$1,000</button>
                    <button class="preset-btn" data-amount="5000">$5,000</button>
                    <button class="preset-btn" data-amount="10000">$10,000</button>
                    <button class="preset-btn" data-amount="25000">$25,000</button>
                    <button class="preset-btn" data-amount="50000">$50,000</button>
                </div>
            </div>
        </div>

        <div class="results-grid">
            <div class="result-card">
                <div class="result-icon">💰</div>
                <div class="result-value" id="eaCost"><?php echo doittrading_format_price($price); ?></div>
                <div class="result-label">EA Cost</div>
                <div class="result-description">One-time investment to access the EA</div>
            </div>

            <div class="result-card">
                <div class="result-icon">📈</div>
                <div class="result-value" id="monthlyReturn">$375</div>
                <div class="result-label">Monthly Return</div>
                <div class="result-description" id="monthlyDesc"><?php echo $monthly_gain; ?>% monthly gain on your capital</div>
            </div>

            <div class="result-card">
                <div class="result-icon">⏱️</div>
                <div class="result-value" id="paybackTime">0.8</div>
                <div class="result-label">Months to Break Even</div>
                <div class="result-description">Time to recover your EA investment</div>
            </div>

            <div class="result-card">
                <div class="result-icon">🚀</div>
                <div class="result-value" id="yearlyROI">1,414%</div>
                <div class="result-label">Year 1 ROI</div>
                <div class="result-description" id="yearlyProfit">$4,203 total profit in first year</div>
            </div>
        </div>

        <p class="roi-disclaimer">
            *Calculations based on historical EA performance of <?php echo $monthly_gain; ?>% monthly returns. 
            Past results don't guarantee future returns. Trading involves risk of loss.
        </p>
    </div>
    
    <?php
}

/**
 * Benefits Grid (antes del content)
 */
add_filter('the_content', 'doittrading_add_benefits_grid');
function doittrading_add_benefits_grid($content) {
    if (!is_product() || !doittrading_is_ea()) {
        return $content;
    }
    
    $benefits = '<div class="benefits-grid">
        <div class="benefit">
            <span class="icon">💰</span>
            <strong>Start Earning While You Sleep</strong>
            <p>EA trades 24/5 automatically</p>
        </div>
        <div class="benefit">
            <span class="icon">⚡</span>
            <strong>Live Proven Strategy</strong>
            <p>Real results you can verify</p>
        </div>
        <div class="benefit">
            <span class="icon">🛡️</span>
            <strong>Protected Capital</strong>
            <p>Smart risk management built-in</p>
        </div>
        <div class="benefit">
            <span class="icon">🎯</span>
            <strong>Setup in 5 Minutes</strong>
            <p>Pre-optimized settings included</p>
        </div>
    </div>';
    
    return $benefits . $content;
}



    /**
 * Product Details Section - Tabs/Accordion
 */
add_action('woocommerce_after_single_product_summary', 'doittrading_product_details_section', 5);
function doittrading_product_details_section() {
    if (!doittrading_is_ea()) return;
    
    $product_id = get_the_ID();
    ?>
    <div class="doittrading-product-details">
        
        <!-- Desktop Tabs Navigation -->
        <div class="details-tabs-nav hide-mobile">
            <button class="tab-btn active" data-tab="overview">Overview</button>
            <button class="tab-btn" data-tab="how-it-works">How It Works</button>
            <button class="tab-btn" data-tab="requirements">Requirements</button>
            <button class="tab-btn" data-tab="faqs">FAQs</button>
        </div>
        
        <!-- Mobile Accordion -->
        <div class="details-accordion show-mobile">
            
            <!-- Overview Accordion -->
            <div class="accordion-item active" data-section="overview">
                <button class="accordion-header">
                    <span>Overview</span>
                    <span class="accordion-icon">−</span>
                </button>
                <div class="accordion-content" style="display: block;">
                    <?php echo doittrading_get_overview_content($product_id); ?>
                </div>
            </div>
            
            <!-- How It Works Accordion -->
            <div class="accordion-item" data-section="how-it-works">
                <button class="accordion-header">
                    <span>How It Works</span>
                    <span class="accordion-icon">+</span>
                </button>
                <div class="accordion-content">
                    <?php echo doittrading_get_how_it_works_content($product_id); ?>
                </div>
            </div>
            
            <!-- Requirements Accordion -->
            <div class="accordion-item" data-section="requirements">
                <button class="accordion-header">
                    <span>Requirements</span>
                    <span class="accordion-icon">+</span>
                </button>
                <div class="accordion-content">
                    <?php echo doittrading_get_requirements_content($product_id); ?>
                </div>
            </div>
            
            <!-- FAQs Accordion -->
            <div class="accordion-item" data-section="faqs">
                <button class="accordion-header">
                    <span>FAQs</span>
                    <span class="accordion-icon">+</span>
                </button>
                <div class="accordion-content">
                    <?php echo doittrading_get_faqs_content($product_id); ?>
                </div>
            </div>
            
        </div>
        
        <!-- Desktop Tab Contents -->
        <div class="tab-contents hide-mobile">
            
            <!-- Overview Tab -->
            <div class="tab-content active" id="overview">
                <?php echo doittrading_get_overview_content($product_id); ?>
            </div>
            
            <!-- How It Works Tab -->
            <div class="tab-content" id="how-it-works">
                <?php echo doittrading_get_how_it_works_content($product_id); ?>
            </div>
            
            <!-- Requirements Tab -->
            <div class="tab-content" id="requirements">
                <?php echo doittrading_get_requirements_content($product_id); ?>
            </div>
            
            <!-- FAQs Tab -->
            <div class="tab-content" id="faqs">
                <?php echo doittrading_get_faqs_content($product_id); ?>
            </div>
            
        </div>
        
    </div>
    <?php
}

/**
 * Get Overview Content
 */
function doittrading_get_overview_content($product_id) {
    global $product;
    
    ob_start();
    ?>
    <div class="overview-content">
        
        <!-- Product Description -->
        <div class="product-description">
            <?php echo apply_filters('the_content', $product->get_description()); ?>
        </div>
        
        <!-- Key Features -->
        <?php if (get_field('key_features', $product_id)): ?>
        <div class="key-features-section">
            <h3>🔑 Key Features</h3>
            <div class="features-content">
                <?php 
                $features = get_field('key_features', $product_id) ?? '';
                $features_array = explode("\n", $features);
                echo '<ul>';
                foreach ($features_array as $feature) {
                    if (trim($feature)) {
                        echo '<li>✓ ' . esc_html(trim($feature)) . '</li>';
                    }
                }
                echo '</ul>';
                ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Benefits Grid -->
        <div class="benefits-overview">
            <h3>💰 Why This EA Changes Everything</h3>
            <div class="benefits-grid">
                <div class="benefit">
                    <span class="icon">💰</span>
                    <strong>Passive Income Stream</strong>
                    <p>EA trades automatically 24/5 while you sleep, work, or travel</p>
                </div>
                <div class="benefit">
                    <span class="icon">📊</span>
                    <strong>Verified Performance</strong>
                    <p>Live MyFxBook account shows real results, not backtest dreams</p>
                </div>
                <div class="benefit">
                    <span class="icon">🛡️</span>
                    <strong>Protected Capital</strong>
                    <p>Conservative risk management prevents account blowups</p>
                </div>
                <div class="benefit">
                    <span class="icon">⚡</span>
                    <strong>Instant Setup</strong>
                    <p>Pre-optimized settings mean you're trading in under 5 minutes</p>
                </div>
            </div>
        </div>
        
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get How It Works Content
 */
function doittrading_get_how_it_works_content($product_id) {
    $trading_style = get_field('trading_style', $product_id);
    $platforms = get_field('supported_platforms', $product_id);
    
    ob_start();
    ?>
    <div class="how-it-works-content">
        
        <h2>🔧 How This EA Actually Works</h2>
        <p>Understanding the strategy behind your automated profits:</p>
        
        <div class="how-it-works-grid">
            
            <div class="how-step">
                <div class="step-number">1</div>
                <div class="step-icon">📈</div>
                <h4>Market Analysis</h4>
                <p>EA continuously analyzes price patterns, support/resistance levels, and market momentum using proven technical indicators.</p>
            </div>
            
            <div class="how-step">
                <div class="step-number">2</div>
                <div class="step-icon">🎯</div>
                <h4>Smart Entry</h4>
                <p>Only enters trades when ALL conditions align: proper setup, favorable risk/reward, and optimal market timing.</p>
            </div>
            
            <div class="how-step">
                <div class="step-number">3</div>
                <div class="step-icon">🛡️</div>
                <h4>Risk Management</h4>
                <p>Every trade includes automatic stop-loss, take-profit, and trailing stop to protect your capital and maximize gains.</p>
            </div>
            
            <div class="how-step">
                <div class="step-number">4</div>
                <div class="step-icon">💰</div>
                <h4>Profit Capture</h4>
                <p>Smart exit strategy locks in profits at optimal levels while giving winning trades room to grow.</p>
            </div>
            
        </div>
        
        <!-- Trading Details -->
        <div class="trading-details">
            <h3>📋 Trading Specifications</h3>
            <div class="specs-grid">
                <div class="spec-item">
                    <strong>Trading Style:</strong>
                    <span><?php echo $trading_style ? ucfirst($trading_style) : 'Multiple Strategies'; ?></span>
                </div>
                <div class="spec-item">
                    <strong>Timeframes:</strong>
                    <span>Automatically optimized</span>
                </div>
                <div class="spec-item">
                    <strong>Platforms:</strong>
                    <span><?php echo $platforms ? implode(' & ', array_map('strtoupper', $platforms)) : 'MT4 & MT5'; ?></span>
                </div>
                <div class="spec-item">
                    <strong>Strategy Type:</strong>
                    <span>No Martingale, No Grid</span>
                </div>
            </div>
        </div>
        
        <!-- Safety Note -->
        <div class="safety-note">
            <h3>🔒 Built-In Safety Features</h3>
            <ul>
                <li>✓ Maximum daily loss limits</li>
                <li>✓ Automatic spread filtering</li>
                <li>✓ News event avoidance</li>
                <li>✓ Account protection mechanisms</li>
                <li>✓ Conservative position sizing</li>
            </ul>
        </div>
        
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get Requirements Content
 */
function doittrading_get_requirements_content($product_id) {
    $min_deposit = get_field('minimum_deposit', $product_id);
    $platforms = get_field('supported_platforms', $product_id);
    
    ob_start();
    ?>
    <div class="requirements-content">
        
        <h2>⚙️ Requirements & Setup</h2>
        <p>Everything you need to start trading successfully:</p>
        
        <div class="requirements-grid">
            
            <div class="requirement-item">
                <strong>Minimum Deposit</strong>
                <span>$<?php echo esc_html($min_deposit ?: '100'); ?></span>
            </div>
            
            <div class="requirement-item">
                <strong>Supported Platforms</strong>
                <span><?php echo $platforms ? implode(' & ', array_map('strtoupper', $platforms)) : 'MT4 & MT5'; ?></span>
            </div>
            
            <div class="requirement-item">
                <strong>Recommended Leverage</strong>
                <span>1:30 or higher</span>
            </div>
            
            <div class="requirement-item">
                <strong>VPS Recommended</strong>
                <span>For 24/7 operation</span>
            </div>
            
            <div class="requirement-item">
                <strong>Broker Type</strong>
                <span>Low spread preferred</span>
            </div>
            
            <div class="requirement-item">
                <strong>Experience Level</strong>
                <span>Beginner to Advanced</span>
            </div>
            
        </div>
        
        <!-- Quick Setup Steps -->
        <div class="setup-steps">
            <h3>🚀 Quick Setup (Under 5 Minutes)</h3>
            <ol class="setup-list">
                <li><strong>Download:</strong> Get your EA file after purchase</li>
                <li><strong>Install:</strong> Copy to MT4/MT5 Experts folder</li>
                <li><strong>Attach:</strong> Drag EA to your chart</li>
                <li><strong>Configure:</strong> Use included preset settings</li>
                <li><strong>Go Live:</strong> Enable auto-trading and relax!</li>
            </ol>
        </div>
        
        <div class="setup-note">
            <p><strong>⚡ Important:</strong> Pre-configured settings included. No complex optimization needed - just attach and trade!</p>
        </div>
        
        <!-- Broker Recommendations -->
        <div class="broker-section">
            <h3>🏦 Recommended Broker Features</h3>
            <ul>
                <li>✓ Low spreads (under 2 pips major pairs)</li>
                <li>✓ Reliable execution (no requotes)</li>
                <li>✓ Regulated by major authorities</li>
                <li>✓ ECN/STP account type preferred</li>
                <li>✓ VPS service available</li>
            </ul>
        </div>
        
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get FAQs Content
 */
function doittrading_get_faqs_content($product_id) {
    // Validar que tenemos un product_id válido
    if (!$product_id || !is_numeric($product_id)) {
        return '<p>Error: Invalid product ID</p>';
    }
    
    ob_start();
    ?>
    <div class="faqs-content">
        
        <h2>❓ Frequently Asked Questions</h2>
        
        <div class="faq-grid">
            
            <!-- Product-specific FAQs from ACF -->
            <?php 
            $has_custom_faqs = false;
            for($i = 1; $i <= 3; $i++): 
                $question = get_field('faq_question_' . $i, $product_id);
                $answer = get_field('faq_answer_' . $i, $product_id);
                
                if (!empty($question) && !empty($answer)): 
                    $has_custom_faqs = true;
                    ?>
                    <div class="faq-item-enhanced">
                        <h4 class="faq-question"><?php echo esc_html($question); ?></h4>
                        <p class="faq-answer"><?php echo wp_kses_post(nl2br($answer)); ?></p>
                    </div>
                    <?php 
                endif;
            endfor; 
            
            // Si no hay FAQs personalizadas, mostrar mensaje
            if (!$has_custom_faqs): ?>
                <div class="faq-item-enhanced">
                    <h4 class="faq-question">Custom FAQs will appear here</h4>
                    <p class="faq-answer">Product-specific questions and answers from the admin panel will be displayed in this section.</p>
                </div>
            <?php endif; ?>
            
            <!-- General Trading FAQs -->
            <div class="faq-item-enhanced">
                <h4 class="faq-question">Is this suitable for beginners?</h4>
                <p class="faq-answer">Absolutely! The EA comes with pre-optimized settings and detailed setup instructions. No trading experience required.</p>
            </div>
            
            <div class="faq-item-enhanced">
                <h4 class="faq-question">Do I need to monitor trades?</h4>
                <p class="faq-answer">No. The EA manages everything automatically - entries, exits, risk management. You can check progress whenever convenient.</p>
            </div>
            
            <div class="faq-item-enhanced">
                <h4 class="faq-question">What about updates?</h4>
                <p class="faq-answer">All updates are free for life. We continuously improve the EA based on market conditions and user feedback.</p>
            </div>
            
            <div class="faq-item-enhanced">
                <h4 class="faq-question">Can I use this on multiple accounts?</h4>
                <p class="faq-answer">Yes, one license allows use on unlimited personal accounts. Cannot be shared or resold.</p>
            </div>
            
            <div class="faq-item-enhanced">
                <h4 class="faq-question">Is there a money-back guarantee?</h4>
                <p class="faq-answer">Due to the digital nature, no refunds after download. However, we provide full support to ensure the EA works perfectly for you.</p>
            </div>
            
            <div class="faq-item-enhanced">
                <h4 class="faq-question">How quickly will I see results?</h4>
                <p class="faq-answer">The EA starts trading immediately once attached. First trades typically appear within hours, depending on market conditions.</p>
            </div>
            
        </div>
        
        <!-- Support Section -->
        <div class="support-section">
            <h3>🆘 Need More Help?</h3>
            <p>Our support team responds within 24 hours:</p>
            <ul>
                <li>📧 Email: support@doittrading.com</li>
                <li>💬 Live chat during business hours</li>
                <li>📚 Complete setup documentation included</li>
                <li>🎥 Video tutorials available</li>
            </ul>
        </div>
        
    </div>
    <?php
    $content = ob_get_clean();
    
    // Verificar que tenemos contenido válido
    if (empty($content)) {
        return '<div class="faqs-content"><h2>❓ FAQs</h2><p>Content loading...</p></div>';
    }
    
    return $content;
}

add_filter( 'woocommerce_product_tabs', 'woo_remove_all_product_tabs', 98 );
function woo_remove_all_product_tabs( $tabs ) {
    return array();
}