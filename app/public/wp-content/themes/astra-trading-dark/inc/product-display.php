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
                <div class="stat-value"><?php the_field('win_rate', $product_id); ?>%</div>
                <div class="stat-label">Win Rate</div>
                <div class="stat-context">Verified trades<br>Since Jan 2024</div>
            </div>
            <div class="stat-box-enhanced">
                <div class="stat-value negative">-<?php the_field('max_drawdown', $product_id); ?>%</div>
                <div class="stat-label">Max Drawdown</div>
                <div class="stat-context">Conservative<br>Risk management</div>
            </div>
            <div class="stat-box-enhanced">
                <div class="stat-value"><?php the_field('profit_factor', $product_id); ?></div>
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
add_action('woocommerce_single_product_summary', 'doittrading_roi_calculator', 21);
function doittrading_roi_calculator() {
    global $product;
    $product_id = get_the_ID();
    
    if (!doittrading_is_ea()) return;
    
    $monthly_gain = get_field('monthly_gain', $product_id);
    $price = $product->get_price();
    
    if (!$monthly_gain || !$price) return;
    
    // Cálculos ROI
    $capital = 1000;
    $monthly_return = ($monthly_gain / 100) * $capital;
    $payback_months = ceil($price / $monthly_return);
    $yearly_profit = $monthly_return * 12;
    $roi_percentage = (($yearly_profit / $price) * 100);
    ?>
    <div class="roi-calculator-section">
        <h3>💰 ROI Calculator: See Your Real Returns</h3>
        <div class="roi-grid">
            <div class="roi-item">
                <div class="roi-value"><?php echo doittrading_format_price($price); ?></div>
                <div class="roi-label">EA Cost</div>
            </div>
            <div class="roi-item">
                <div class="roi-value"><?php echo doittrading_format_price($monthly_return); ?></div>
                <div class="roi-label">Monthly Return<br><small>(<?php echo $monthly_gain; ?>% on $1,000)</small></div>
            </div>
            <div class="roi-item">
                <div class="roi-value"><?php echo $payback_months; ?></div>
                <div class="roi-label">Months to<br>Break Even</div>
            </div>
            <div class="roi-item">
                <div class="roi-value"><?php echo number_format($roi_percentage); ?>%</div>
                <div class="roi-label">Year 1 ROI<br><small>(<?php echo doittrading_format_price($yearly_profit); ?> profit)</small></div>
            </div>
        </div>
        <p class="roi-disclaimer">*Based on historical performance. Past results don't guarantee future returns.</p>
    </div>
    <?php
}

/**
 * Product FAQs
 */
add_action('woocommerce_after_single_product_summary', 'doittrading_product_faqs', 25);
function doittrading_product_faqs() {
    $product_id = get_the_ID();
    
    if (!doittrading_is_ea() || !get_field('faq_question_1', $product_id)) return;
    ?>
    <div class="product-faqs">
        <h2>Frequently Asked Questions</h2>
        
        <?php for($i = 1; $i <= 3; $i++): 
            $question = get_field('faq_question_' . $i, $product_id);
            $answer = get_field('faq_answer_' . $i, $product_id);
            if ($question && $answer): ?>
                <div class="faq-item">
                    <h3><?php echo esc_html($question); ?></h3>
                    <p><?php echo wp_kses_post($answer); ?></p>
                </div>
            <?php endif;
        endfor; ?>
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