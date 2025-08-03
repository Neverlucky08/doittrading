<?php
/**
 * DoItTrading Indicator Product Display
 * Handles all display functionality for trading indicator products
 * 
 * @package DoItTrading
 */

if (!defined('ABSPATH')) exit;

class DoItTrading_Indicator_Product_Display {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks only for indicator products
        add_action('wp', array($this, 'init_hooks'));
    }
    
    /**
     * Initialize hooks if on indicator product page
     */
    public function init_hooks() {
        if (is_product() && doittrading_is_indicator()) {
            // Remove default WooCommerce elements
            remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
            
            // Add custom indicator sections
            add_action('woocommerce_before_single_product_summary', array($this, 'hero_section'), 20);
            add_action('woocommerce_single_product_summary', array($this, 'benefits_section'), 12);
            add_action('woocommerce_single_product_summary', array($this, 'visual_showcase'), 13);
            add_action('woocommerce_single_product_summary', array($this, 'stats_card'), 14);
            add_action('woocommerce_single_product_summary', array($this, 'buy_section'), 15);
            add_action('woocommerce_after_single_product_summary', array($this, 'details_section'), 5);
            
            // Enqueue scripts
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        }
    }
    
    /**
     * Indicator Hero Section
     */
    public function hero_section() {
        global $product;
        $product_id = get_the_ID();
        
        // Get product image
        $image_id = $product->get_image_id();
        $image_url = wp_get_attachment_image_url($image_id, 'large');
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        
        if (!$image_url) {
            $image_url = wc_placeholder_img_src('large');
            $image_alt = 'Product placeholder';
        }
        
        // Get indicator specific fields
        $is_trending = get_post_meta($product_id, 'is_trending', true);
        ?>
        <div class="doittrading-indicator-hero">
            <div class="indicator-hero-grid">
                <!-- Left Column: Visual -->
                <div class="indicator-hero-visual">
                    <div class="hero-image-wrapper">
                        <?php if ($is_trending): ?>
                        <div class="trending-badge">🔥 TRENDING</div>
                        <?php endif; ?>
                        <img src="<?php echo esc_url($image_url); ?>" 
                             alt="<?php echo esc_attr($image_alt ?: get_the_title()); ?>" 
                             class="indicator-hero-image">
                    </div>
                </div>
                
                <!-- Right Column: Content -->
                <div class="indicator-hero-content">
                    <!-- Hero Container with Border and Background -->
                    <div class="indicator-hero-container">
                        <h1 class="indicator-hero-title"><?php the_title(); ?></h1>
                        <div class="indicator-hero-subtitle">
                            <?php echo wp_kses_post($product->get_short_description()); ?>
                        </div>
                        
                        <!-- Stats Bar -->
                        <div class="indicator-stats-bar">
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $this->get_product_download_count($product_id); ?></span>
                                <span class="stat-label">Downloads</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $this->get_product_rating($product_id); ?>/5</span>
                                <span class="stat-label">Rating</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $this->get_active_users_count($product_id); ?>+</span>
                                <span class="stat-label">Active Users</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">24/7</span>
                                <span class="stat-label">Support</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Price Section -->
                    <?php $this->price_section(); ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Price Section
     */
    private function price_section() {
        global $product;
        ?>
        <div class="indicator-price-section">
            <?php if ($product->is_on_sale()): 
                $savings = $product->get_regular_price() - $product->get_price();
            ?>
            <div class="price-savings">SAVE <?php echo wc_price($savings); ?> - Limited Time Offer</div>
            <?php endif; ?>
            
            <!-- Last Purchase Info -->
            <div class="last-purchase-info">
                <?php 
                // Use product ID to generate consistent pseudo-random time
                $product_id = get_the_ID();
                $last_purchase_time = (($product_id * 7) % 12) + 1; // Generates 1-12 based on product ID
                ?>
                🔥 Last purchase: <?php echo $last_purchase_time; ?> hours ago
            </div>
            
            <div class="price-display">
                <?php if ($product->is_on_sale()): ?>
                    <span class="price-original"><?php echo wc_price($product->get_regular_price()); ?></span>
                <?php endif; ?>
                <span class="price-current"><?php echo wc_price($product->get_price()); ?></span>
            </div>
            
            <!-- Compact Trust Badges -->
            <div class="trust-badges-compact">
                <span class="trust-badge-mini">
                    <span class="icon">🔒</span> Secure
                </span>
                <span class="trust-badge-mini">
                    <span class="icon">⚡</span> Instant
                </span>
                <span class="trust-badge-mini">
                    <span class="icon">🔄</span> Updates
                </span>
            </div>
        </div>
        
        <!-- Hero CTA Compact Section -->
        <div class="hero-cta-compact">
            <?php 
            $product_id = get_the_ID();
            $mt4_link = get_field('mql5_purchase_link_mt4', $product_id);
            $mt5_link = get_field('mql5_purchase_link_mt5', $product_id);
            ?>
            
            <?php if ($mt4_link || $mt5_link): ?>
                <div class="hero-buy-buttons">
                    <?php if ($mt4_link): ?>
                        <a href="<?php echo esc_url($mt4_link); ?>" 
                        target="_blank" 
                        class="hero-buy-btn mt4-compact"
                        onclick="doittrading_track_click('hero_mt4', <?php echo $product_id; ?>)">
                            🛒 Get MT4 Version
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($mt5_link): ?>
                        <a href="<?php echo esc_url($mt5_link); ?>" 
                        target="_blank" 
                        class="hero-buy-btn mt5-compact"
                        onclick="doittrading_track_click('hero_mt5', <?php echo $product_id; ?>)">
                            🛒 Get MT5 Version
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="cta-buttons">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                    <?php if (get_field('demo_url', get_the_ID())): ?>
                    <a href="<?php the_field('demo_url'); ?>" class="cta-secondary" target="_blank">View Demo</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Benefits Section
     */
    public function benefits_section() {
        $product_id = get_the_ID();
        $benefits = DoItTrading_Indicators_Product_Fields::get_benefits($product_id);
        
        if (empty($benefits)) return;
        ?>
        <div class="doittrading-indicator-benefits">
            <div class="section-header">
                <h2>Why Traders Love This Indicator</h2>
                <p>Transform your trading with features designed for real market conditions</p>
            </div>
            
            <div class="benefits-grid">
                <?php foreach($benefits as $benefit): ?>
                <div class="benefit-card">
                    <span class="benefit-icon"><?php echo $this->get_icon_emoji($benefit['icon']); ?></span>
                    <h3 class="benefit-title"><?php echo esc_html($benefit['title']); ?></h3>
                    <p class="benefit-description"><?php echo esc_html($benefit['description']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Visual Showcase
     */
    public function visual_showcase() {
        $product_id = get_the_ID();
        $visuals = DoItTrading_Indicators_Product_Fields::get_visuals($product_id);
        
        if (empty($visuals)) return;
        ?>
        <div class="doittrading-visual-showcase">
            <div class="section-header">
                <h2>See It In Action</h2>
                <p>Discover how our indicator transforms your trading experience</p>
            </div>
            
            <div class="showcase-tabs">
                <?php foreach($visuals as $index => $visual): ?>
                <button class="showcase-tab <?php echo $index === 0 ? 'active' : ''; ?>" 
                        data-tab="visual-<?php echo $index; ?>">
                    <?php echo esc_html($visual['title']); ?>
                </button>
                <?php endforeach; ?>
            </div>
            
            <div class="showcase-content">
                <?php foreach($visuals as $index => $visual): ?>
                <div class="showcase-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                     id="visual-<?php echo $index; ?>">
                    <?php if ($visual['image']): ?>
                    <img src="<?php echo esc_url($visual['image']); ?>" 
                         alt="<?php echo esc_attr($visual['title']); ?>" 
                         class="showcase-image">
                    <?php endif; ?>
                    <div class="showcase-details">
                        <h3 class="showcase-title"><?php echo esc_html($visual['title']); ?></h3>
                        <p class="showcase-description"><?php echo wp_kses_post(nl2br($visual['description'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Stats Card
     */
    public function stats_card() {
        $product_id = get_the_ID();
        $stats = DoItTrading_Indicators_Product_Fields::get_stats($product_id);
        
        if (empty($stats)) return;
        ?>
        <div class="doittrading-indicator-stats-card">
            <div class="tool-stats-card">
                <div class="stats-card-header">
                    <h3>Performance That Speaks For Itself</h3>
                    <p>Real statistics from our community of successful traders</p>
                </div>
                
                <div class="main-stats">
                    <?php foreach($stats as $stat): ?>
                    <div class="main-stat">
                        <span class="main-stat-icon"><?php echo $this->get_icon_emoji($stat['icon']); ?></span>
                        <span class="main-stat-value"><?php echo esc_html($stat['value']); ?></span>
                        <span class="main-stat-label"><?php echo esc_html($stat['label']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Details Section (Tabs/Accordion)
     */
    public function details_section() {
        $product_id = get_the_ID();
        ?>
        <div class="doittrading-indicator-details">
            
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
                        <?php echo $this->get_overview_content($product_id); ?>
                    </div>
                </div>
                
                <!-- How It Works Accordion -->
                <div class="accordion-item" data-section="how-it-works">
                    <button class="accordion-header">
                        <span>How It Works</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <?php echo $this->get_how_it_works_content($product_id); ?>
                    </div>
                </div>
                
                <!-- Requirements Accordion -->
                <div class="accordion-item" data-section="requirements">
                    <button class="accordion-header">
                        <span>Requirements</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <?php echo $this->get_requirements_content($product_id); ?>
                    </div>
                </div>
                
                <!-- FAQs Accordion -->
                <div class="accordion-item" data-section="faqs">
                    <button class="accordion-header">
                        <span>FAQs</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <?php echo $this->get_faqs_content($product_id); ?>
                    </div>
                </div>
                
            </div>
            
            <!-- Desktop Tab Contents -->
            <div class="tab-contents hide-mobile">
                
                <!-- Overview Tab -->
                <div class="tab-content active" id="overview">
                    <?php echo $this->get_overview_content($product_id); ?>
                </div>
                
                <!-- How It Works Tab -->
                <div class="tab-content" id="how-it-works">
                    <?php echo $this->get_how_it_works_content($product_id); ?>
                </div>
                
                <!-- Requirements Tab -->
                <div class="tab-content" id="requirements">
                    <?php echo $this->get_requirements_content($product_id); ?>
                </div>
                
                <!-- FAQs Tab -->
                <div class="tab-content" id="faqs">
                    <?php echo $this->get_faqs_content($product_id); ?>
                </div>
                
            </div>
            
        </div>
        <?php
    }
    
    /**
     * Get Overview Content
     */
    private function get_overview_content($product_id) {
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
            
            <!-- Trading Capabilities -->
            <div class="trading-capabilities">
                <h3>📊 Trading Capabilities</h3>
                <div class="capabilities-grid">
                    <div class="capability">
                        <span class="icon">📈</span>
                        <strong>Multi-Timeframe Analysis</strong>
                        <p>Works seamlessly across all timeframes from M1 to Monthly</p>
                    </div>
                    <div class="capability">
                        <span class="icon">🎯</span>
                        <strong>Precision Signals</strong>
                        <p>Advanced filtering eliminates false signals and noise</p>
                    </div>
                    <div class="capability">
                        <span class="icon">⚡</span>
                        <strong>Real-Time Alerts</strong>
                        <p>Instant notifications for high-probability setups</p>
                    </div>
                    <div class="capability">
                        <span class="icon">🛡️</span>
                        <strong>Risk Management</strong>
                        <p>Built-in stop loss and take profit recommendations</p>
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
    private function get_how_it_works_content($product_id) {
        ob_start();
        ?>
        <div class="how-it-works-content">
            
            <h2>🔧 How This Indicator Works</h2>
            <p>Our advanced algorithm analyzes multiple market factors to generate high-probability trading signals:</p>
            
            <div class="how-it-works-grid">
                
                <div class="how-step">
                    <div class="step-number">1</div>
                    <div class="step-icon">📊</div>
                    <h4>Market Analysis</h4>
                    <p>Continuously scans price action, volume, and market structure across multiple timeframes.</p>
                </div>
                
                <div class="how-step">
                    <div class="step-number">2</div>
                    <div class="step-icon">🔍</div>
                    <h4>Pattern Recognition</h4>
                    <p>Identifies high-probability chart patterns and trading setups as they form.</p>
                </div>
                
                <div class="how-step">
                    <div class="step-number">3</div>
                    <div class="step-icon">⚡</div>
                    <h4>Signal Generation</h4>
                    <p>Generates clear buy/sell signals with entry points, stop loss, and take profit levels.</p>
                </div>
                
                <div class="how-step">
                    <div class="step-number">4</div>
                    <div class="step-icon">📱</div>
                    <h4>Alert Delivery</h4>
                    <p>Sends instant alerts via platform notifications, ensuring you never miss a trade.</p>
                </div>
                
            </div>
            
            <!-- Signal Types -->
            <div class="signal-types">
                <h3>📋 Signal Types</h3>
                <ul>
                    <li>✓ Trend Continuation Signals</li>
                    <li>✓ Trend Reversal Signals</li>
                    <li>✓ Breakout Signals</li>
                    <li>✓ Support/Resistance Bounce</li>
                    <li>✓ Divergence Signals</li>
                </ul>
            </div>
            
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get Requirements Content
     */
    private function get_requirements_content($product_id) {
        $platforms = get_field('supported_platforms', $product_id);
        
        ob_start();
        ?>
        <div class="requirements-content">
            
            <h2>⚙️ Requirements & Installation</h2>
            <p>Everything you need to get started with our indicator:</p>
            
            <div class="requirements-grid">
                
                <div class="requirement-item">
                    <strong>Supported Platforms</strong>
                    <span><?php echo $platforms ? implode(' & ', array_map('strtoupper', $platforms)) : 'MT4 & MT5'; ?></span>
                </div>
                
                <div class="requirement-item">
                    <strong>Minimum Deposit</strong>
                    <span>No minimum required</span>
                </div>
                
                <div class="requirement-item">
                    <strong>Broker Type</strong>
                    <span>Works with all brokers</span>
                </div>
                
                <div class="requirement-item">
                    <strong>Currency Pairs</strong>
                    <span>All pairs supported</span>
                </div>
                
                <div class="requirement-item">
                    <strong>Timeframes</strong>
                    <span>M1 to Monthly</span>
                </div>
                
                <div class="requirement-item">
                    <strong>Experience Level</strong>
                    <span>Beginner to Advanced</span>
                </div>
                
            </div>
            
            <!-- Quick Setup Steps -->
            <div class="setup-steps">
                <h3>🚀 Quick Installation (Under 2 Minutes)</h3>
                <ol class="setup-list">
                    <li><strong>Download:</strong> Get your indicator file after purchase</li>
                    <li><strong>Install:</strong> Copy to MT4/MT5 Indicators folder</li>
                    <li><strong>Attach:</strong> Drag indicator to any chart</li>
                    <li><strong>Configure:</strong> Adjust settings if desired (optional)</li>
                    <li><strong>Trade:</strong> Start receiving profitable signals!</li>
                </ol>
            </div>
            
            <div class="setup-note">
                <p><strong>⚡ Important:</strong> Default settings are optimized for best performance. No configuration needed!</p>
            </div>
            
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get FAQs Content
     */
    private function get_faqs_content($product_id) {
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
                endfor; ?>
                
                <!-- General Indicator FAQs -->
                <div class="faq-item-enhanced">
                    <h4 class="faq-question">Is this suitable for beginners?</h4>
                    <p class="faq-answer">Absolutely! The indicator comes with clear visual signals and detailed documentation. No complex analysis required.</p>
                </div>
                
                <div class="faq-item-enhanced">
                    <h4 class="faq-question">Does it repaint?</h4>
                    <p class="faq-answer">No. Once a signal appears, it stays there. Our indicator never repaints historical signals.</p>
                </div>
                
                <div class="faq-item-enhanced">
                    <h4 class="faq-question">Can I use it on multiple charts?</h4>
                    <p class="faq-answer">Yes! One license allows unlimited use on all your charts and trading accounts.</p>
                </div>
                
                <div class="faq-item-enhanced">
                    <h4 class="faq-question">What about updates?</h4>
                    <p class="faq-answer">All updates are free for life. We continuously improve the indicator based on market conditions.</p>
                </div>
                
                <div class="faq-item-enhanced">
                    <h4 class="faq-question">How accurate are the signals?</h4>
                    <p class="faq-answer">Our indicator maintains high accuracy by filtering out low-probability setups. Success rate varies by market conditions.</p>
                </div>
                
                <div class="faq-item-enhanced">
                    <h4 class="faq-question">Is there support available?</h4>
                    <p class="faq-answer">Yes! We provide 24/7 support via email and live chat during business hours.</p>
                </div>
                
            </div>
            
            <!-- Support Section -->
            <div class="support-section">
                <h3>🆘 Need More Help?</h3>
                <p>Our support team is here to help you succeed:</p>
                <ul>
                    <li>📧 Email: support@doittrading.com</li>
                    <li>💬 Live chat during business hours</li>
                    <li>📚 Complete documentation included</li>
                    <li>🎥 Video tutorials available</li>
                </ul>
            </div>
            
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Helper function to get icon emoji
     */
    private function get_icon_emoji($icon_name) {
        $icons = array(
            'chart-line' => '📈',
            'clock' => '⏰',
            'shield-check' => '🛡️',
            'lightning' => '⚡',
            'target' => '🎯',
            'trophy' => '🏆',
            'rocket' => '🚀',
            'gem' => '💎',
            'star' => '⭐',
            'fire' => '🔥',
            'bullseye' => '🎯',
            'users' => '👥',
            'diamond' => '💎',
            'zap' => '⚡',
            'trending-up' => '📊',
            'activity' => '📈',
            'dollar-sign' => '💲'
        );
        
        return isset($icons[$icon_name]) ? $icons[$icon_name] : '📍';
    }
    
    /**
     * Buy Section - Final CTA
     */
    public function buy_section() {
        // Use common buy section without countdown for indicators
        doittrading_common_buy_section(array(
            'show_countdown' => false,
            'title_prefix' => 'Join',
            'product_type' => 'indicator'
        ));
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'doittrading-indicator-product',
            get_stylesheet_directory_uri() . '/assets/js/indicator-product.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
    
    /**
     * Helper functions for indicator stats
     */
    private function get_product_download_count($product_id) {
        // This would typically come from actual data
        // For now, return a dynamic number based on product ID
        return number_format(1000 + ($product_id % 500));
    }
    
    private function get_product_rating($product_id) {
        $product = wc_get_product($product_id);
        $rating = $product->get_average_rating();
        return $rating > 0 ? number_format($rating, 1) : '4.9';
    }
    
    private function get_active_users_count($product_id) {
        // This would typically come from actual data
        return number_format(500 + ($product_id % 200));
    }
}

// Initialize the class
new DoItTrading_Indicator_Product_Display();