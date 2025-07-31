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
    
    $remaining = function_exists('doittrading_get_remaining_stock') ? doittrading_get_remaining_stock() : 10;
    $countdown_target = function_exists('doittrading_get_countdown_target') ? doittrading_get_countdown_target() : date('Y-m-d H:i:s', strtotime('+2 days'));
    ?>
    <div class="hero-price-section">
        <div class="countdown-timer" data-target="<?php echo esc_attr($countdown_target); ?>">
            ⏰ Price increases in <span class="countdown-display">calculating...</span>
        </div>
        
        <?php if ($product->is_on_sale()): ?>
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
    
    $recent_buyer = function_exists('doittrading_get_recent_buyer') 
        ? doittrading_get_recent_buyer() 
        : array('name' => 'Alex K.', 'country' => 'USA');
    $minutes_ago = rand(3, 15);
    ?>
    <div class="social-proof-box">
        <div class="recent-purchase">
            🔥 <strong><?php echo esc_html($recent_buyer['name']); ?></strong> from <?php echo esc_html($recent_buyer['country']); ?> just purchased (<?php echo $minutes_ago; ?> minutes ago)
        </div>
    </div>
    <?php
}
