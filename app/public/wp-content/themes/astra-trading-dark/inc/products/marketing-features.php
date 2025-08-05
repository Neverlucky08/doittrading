<?php
/**
 * DoItTrading Marketing Features
 * Urgency, scarcity y elementos de conversión
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Price & Urgency Section
 * Now called from hero layout, not as separate action
 */
// Remove from default position as it's now part of hero
// add_action('woocommerce_single_product_summary', 'doittrading_urgency_section', 9);
function doittrading_urgency_section() {
    global $product;
    
    // Verificar que las funciones existan
    if (!function_exists('doittrading_is_ea') || !doittrading_is_ea()) return;
    
    $product_id = $product->get_id();
    $launching_promo = get_post_meta($product_id, 'launching_promo', true);
    $remaining = function_exists('doittrading_get_remaining_stock') ? doittrading_get_remaining_stock() : 10;
    $countdown_target = function_exists('doittrading_get_countdown_target') ? doittrading_get_countdown_target() : date('Y-m-d H:i:s', strtotime('+2 days'));
    ?>
    <div class="hero-price-section">
        <?php if ($launching_promo): ?>
        <div class="countdown-timer" data-target="<?php echo esc_attr($countdown_target); ?>">
            ⏰ Price increases in <span class="countdown-display">calculating...</span>
        </div>
        <?php else: ?>
            <?php if ($product->is_on_sale()): 
                $savings = $product->get_regular_price() - $product->get_price();
            ?>
            <div class="price-savings">SAVE <?php echo wc_price($savings); ?> - Limited Time Offer</div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if ($product->is_on_sale() && $launching_promo): ?>
            <div class="price-warning">🔥 LAUNCH PRICE ENDING SOON</div>
        <?php endif; ?>
        <div class="hero-price">
                $<?php echo $product->get_sale_price() ?: $product->get_regular_price(); ?>
                <?php if ($product->get_sale_price()): ?>
                    <span class="original-price">$<?php echo $product->get_regular_price(); ?></span>
                <?php endif; ?>
            </div>
        <div class="stock-warning">
            ⚠️ Only <strong><?php echo $remaining; ?> licenses</strong> left at this price
        </div>
    </div>
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
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Social Proof Box
 */
add_action('woocommerce_single_product_summary', 'doittrading_social_proof_box', 11);
function doittrading_social_proof_box() {
    if (!function_exists('doittrading_is_ea') || !doittrading_is_ea()) return;
    
    global $product;
    $product_id = $product ? $product->get_id() : get_the_ID();
    
    // Obtener comprador específico del producto
    $recent_buyer = function_exists('doittrading_get_recent_buyer') 
        ? doittrading_get_recent_buyer($product_id) 
        : array('name' => 'Alex K.', 'country' => 'USA', 'timestamp' => time());
    
    // Obtener tiempo dinámico basado en timestamp
    $minutes_ago = function_exists('doittrading_get_purchase_time') 
        ? doittrading_get_purchase_time($recent_buyer) 
        : rand(3, 15);
    ?>
    <div class="social-proof-box">
        <div class="recent-purchase">
            🔥 <strong><?php echo esc_html($recent_buyer['name']); ?></strong> from <?php echo esc_html($recent_buyer['country']); ?> just purchased (<?php echo $minutes_ago; ?> minutes ago)
        </div>
    </div>
    <?php
}

/**
 * Common Buy Section - Used by both EAs and Indicators
 * 
 * @param array $args Arguments to customize the buy section
 *                    - show_countdown (bool): Whether to show countdown timer
 *                    - title_prefix (string): Prefix for the title (default: 'Join')
 *                    - product_type (string): 'ea' or 'indicator'
 */
function doittrading_common_buy_section($args = array()) {
    // Default arguments
    $defaults = array(
        'show_countdown' => true,
        'title_prefix' => 'Join',
        'product_type' => 'ea'
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $product_id = get_the_ID();
    $mt4_link = get_field('mql5_purchase_link_mt4', $product_id);
    $mt5_link = get_field('mql5_purchase_link_mt5', $product_id);
    
    // Only show if we have MQL5 links
    if (!$mt4_link && !$mt5_link) {
        return;
    }
    
    // Get total reviews/users count from field
    $count_field = 'total_active_users';
    $count = get_field($count_field, $product_id) ?: 10;
    
    // Title text based on product type
    $title_suffix = $args['product_type'] === 'indicator' ? 
        '+ Verified Traders Using This Indicator' : 
        '+ Verified Traders Making Consistent Profits';
    
    ?>
    <div class="doittrading-buy-section">
        <h2><?php echo esc_html($args['title_prefix']); ?> <?php echo esc_html($count); ?><?php echo esc_html($title_suffix); ?></h2>
        
        <div class="mql5-buttons">
            <?php if ($mt4_link): ?>
                <a href="<?php echo esc_url($mt4_link); ?>" 
                   target="_blank" 
                   class="mql5-buy-btn mt4-btn"
                   onclick="doittrading_track_click('mql5_mt4', <?php echo $product_id; ?>)">
                    🛒 Get MT4 Version
                </a>
            <?php endif; ?>
            
            <?php if ($mt5_link): ?>
                <a href="<?php echo esc_url($mt5_link); ?>" 
                   target="_blank" 
                   class="mql5-buy-btn mt5-btn"
                   onclick="doittrading_track_click('mql5_mt5', <?php echo $product_id; ?>)">
                    🛒 Get MT5 Version
                </a>
            <?php endif; ?>
        </div>
        
        <div class="purchase-benefits">
            <p>
                ✓ Secure payment via MQL5 Market<br>
                ✓ Instant download after purchase<br>
                ✓ Free lifetime updates included<br>
                ✓ 24/7 setup support available
            </p>
        </div>
        
        <?php if ($args['show_countdown']): 
            $launching_promo = get_post_meta($product_id, 'launching_promo', true);
            if ($launching_promo):
                $countdown_target = function_exists('doittrading_get_countdown_target') ? 
                    doittrading_get_countdown_target() : 
                    date('Y-m-d H:i:s', strtotime('+2 days'));
        ?>
            <div class="price-reminder-box">
                <strong>⏰ Remember:</strong> Price increases to $999 in <strong class="countdown-inline" data-target="<?php echo esc_attr($countdown_target); ?>">calculating...</strong>
            </div>
        <?php 
            endif;
        endif; ?>
    </div>
    <?php
}
