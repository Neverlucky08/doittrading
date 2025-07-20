<?php
/**
 * DoItTrading WooCommerce Modifications
 * Personalización de WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Remove unnecessary WooCommerce elements
 */
add_action('init', 'doittrading_clean_woocommerce');

function doittrading_clean_woocommerce() {
    // Remover meta innecesaria
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    
    // Remover rating si no hay reviews
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

     /* 3. Precio <p class="price">…</p> */
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

    // Quitar el formulario <form class="cart"> original
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
}

/**
 * Reemplazar Add to Cart con botones MQL5
 */
add_action('woocommerce_single_product_summary', 'doittrading_mql5_buy_buttons', 30);

function doittrading_mql5_buy_buttons() {
    $product_id = get_the_ID();
    
    // Skip si no es EA
    if (!doittrading_is_ea($product_id)) {
        // Mostrar botón normal para otros productos
        woocommerce_template_single_add_to_cart();
        return;
    }
    
    $mt4_link = get_field('mql5_purchase_link_mt4', $product_id);
    $mt5_link = get_field('mql5_purchase_link_mt5', $product_id);
    
    if ($mt4_link || $mt5_link): ?>
        <div class="doittrading-buy-section">
            <?php if ($mt4_link && $mt5_link): ?>
                <h3>Choose your platform:</h3>
            <?php endif; ?>
            
            <div class="mql5-buttons">
                <?php if ($mt4_link): ?>
                    <a href="<?php echo esc_url($mt4_link); ?>" 
                       target="_blank" 
                       class="mql5-buy-btn mt4-btn"
                       onclick="doittrading_track_click('mql5_mt4', <?php echo $product_id; ?>)">
                        🛒 Buy for MT4 on MQL5
                    </a>
                <?php endif; ?>
                
                <?php if ($mt5_link): ?>
                    <a href="<?php echo esc_url($mt5_link); ?>" 
                       target="_blank" 
                       class="mql5-buy-btn mt5-btn"
                       onclick="doittrading_track_click('mql5_mt5', <?php echo $product_id; ?>)">
                        🛒 Buy for MT5 on MQL5
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="purchase-benefits">
                <p>
                    ✓ Secure payment via MQL5 Market<br>
                    ✓ Instant download after purchase<br>
                    ✓ Free lifetime updates<br>
                    ✓ 1 activation per license
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="doittrading-contact-purchase">
            <button type="button" class="single_add_to_cart_button button" onclick="doittrading_show_contact()">
                Contact for Purchase Details
            </button>
            <p class="purchase-note">This EA is available through private channels. Click to get purchase information.</p>
        </div>
    <?php endif;
}

/**
 * Custom product tabs
 */
add_filter('woocommerce_product_tabs', 'doittrading_custom_product_tabs', 98);
function doittrading_custom_product_tabs($tabs) {
    $product_id = get_the_ID();
    
    // Solo para EAs
    if (!doittrading_is_ea($product_id)) {
        return $tabs;
    }
    
    // Reviews tab personalizado
    if (get_field('review_1_name', $product_id)) {
        $total = get_field('mql5_total_reviews', $product_id) ?: 0;
        $tabs['reviews'] = array(
            'title' => sprintf('Reviews (%d)', $total),
            'priority' => 30,
            'callback' => 'doittrading_mql5_reviews_content'
        );
    } else {
        unset($tabs['reviews']);
    }
    
    // Settings tab
    if (get_field('minimum_deposit', $product_id)) {
        $tabs['settings'] = array(
            'title' => 'Requirements',
            'priority' => 20,
            'callback' => 'doittrading_requirements_tab'
        );
    }
    
    return $tabs;
}

/**
 * Requirements tab content
 */
function doittrading_requirements_tab() {
    $product_id = get_the_ID();
    $min_deposit = get_field('minimum_deposit', $product_id);
    $platforms = get_field('supported_platforms', $product_id);
    ?>
    <div class="requirements-content">
        <h2>Requirements & Setup</h2>
        
        <div class="requirements-grid">
            <div class="requirement-item">
                <strong>Minimum Deposit:</strong>
                <span>$<?php echo esc_html($min_deposit ?: '100'); ?></span>
            </div>
            
            <div class="requirement-item">
                <strong>Supported Platforms:</strong>
                <span><?php echo $platforms ? implode(', ', array_map('strtoupper', $platforms)) : 'MT4, MT5'; ?></span>
            </div>
            
            <div class="requirement-item">
                <strong>Recommended Leverage:</strong>
                <span>1:30 or higher</span>
            </div>
            
            <div class="requirement-item">
                <strong>VPS Required:</strong>
                <span>Recommended for 24/5 operation</span>
            </div>
        </div>
        
        <div class="setup-note">
            <p><strong>⚡ Quick Setup:</strong> Pre-configured settings included. Just attach to chart and start trading!</p>
        </div>
    </div>
    <?php
}

/**
 * Custom shop loop modifications
 */
add_action('woocommerce_shop_loop_item_title', 'doittrading_show_ea_badge', 5);
function doittrading_show_ea_badge() {
    if (doittrading_is_ea()) {
        $win_rate = get_field('win_rate', get_the_ID());
        if ($win_rate) {
            echo '<span class="ea-badge">' . $win_rate . '% Win Rate</span>';
        }
    }
}