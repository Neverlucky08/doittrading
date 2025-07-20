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

/**
 * Bonus Offer Section
 */
add_action('woocommerce_single_product_summary', 'doittrading_bonus_offer', 22);
function doittrading_bonus_offer() {
    if (!doittrading_is_ea()) return;
    
    // Obtener producto de forma segura
    global $product;
    if (empty($product)) {
        $product = wc_get_product(get_the_ID());
    }
    
    // Verificar que tenemos un producto válido
    if (!$product || !is_a($product, 'WC_Product')) return;
    
    // Obtener precio - manejar productos externos
    $price = 0;
    
    // Primero intentar precio normal
    if (method_exists($product, 'get_price') && $product->get_price()) {
        $price = $product->get_price();
    } 
    // Si no hay precio, intentar regular price
    elseif (method_exists($product, 'get_regular_price') && $product->get_regular_price()) {
        $price = $product->get_regular_price();
    }
    // Para productos externos, buscar en meta
    elseif ($product->is_type('external')) {
        $price = get_post_meta($product->get_id(), '_regular_price', true);
        if (!$price) {
            $price = get_post_meta($product->get_id(), '_price', true);
        }
    }
    
    // Solo mostrar si el precio es mayor a $200
    if (empty($price) || floatval($price) < 200) return;
    ?>
    <div class="bonus-offer-section">
        <h3>🎁 Limited Time Bonus</h3>
        <div class="bonus-content">
            <p><strong>Purchase today and receive:</strong></p>
            <ul>
                <li>✓ Free VPS Setup Guide ($97 value)</li>
                <li>✓ Advanced Settings Pack ($149 value)</li>
                <li>✓ Priority Support Access</li>
            </ul>
            <p class="bonus-timer">Bonus expires with price increase!</p>
        </div>
    </div>
    <?php
}

/**
 * Guarantee Section
 */
add_action('woocommerce_after_single_product_summary', 'doittrading_guarantee_section', 5);
function doittrading_guarantee_section() {
    if (!doittrading_is_ea()) return;
    ?>
    <div class="guarantee-section">
        <div class="guarantee-content">
            <h3>🛡️ Our Trading Promise</h3>
            <p>We're so confident in our EAs that we offer:</p>
            <ul>
                <li>✓ <strong>30-Day Performance Check:</strong> If the EA doesn't match advertised stats, we'll help optimize it for your broker</li>
                <li>✓ <strong>Lifetime Updates:</strong> All improvements and optimizations included forever</li>
                <li>✓ <strong>Setup Support:</strong> We'll help you get it running perfectly</li>
            </ul>
            <p class="guarantee-note">Note: Due to digital nature, no refunds after download. But we WILL make sure it works for you!</p>
        </div>
    </div>
    <?php
}

/**
 * Add schema markup for products
 */
add_action('wp_head', 'doittrading_product_schema');
function doittrading_product_schema() {
    if (!is_product()) return;
    
    // Obtener producto de forma segura
    global $product;
    if (empty($product)) {
        $product = wc_get_product(get_the_ID());
    }
    
    // Verificar que tenemos un producto válido
    if (!$product || !is_a($product, 'WC_Product')) return;
    
    $product_id = get_the_ID();
    
    if (!doittrading_is_ea()) return;
    
    $avg_rating = get_field('mql5_average_rating', $product_id) ?: 4.9;
    $total_reviews = get_field('mql5_total_reviews', $product_id) ?: 10;
    
    // Obtener precio de forma segura
    $price = $product->get_price();
    if (empty($price)) return;
    ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "<?php echo esc_js(get_the_title()); ?>",
        "description": "<?php echo esc_js(wp_strip_all_tags($product->get_short_description())); ?>",
        "brand": {
            "@type": "Brand",
            "name": "DoItTrading"
        },
        "offers": {
            "@type": "Offer",
            "url": "<?php echo esc_js(get_permalink()); ?>",
            "priceCurrency": "USD",
            "price": "<?php echo esc_js($price); ?>",
            "availability": "https://schema.org/InStock",
            "seller": {
                "@type": "Organization",
                "name": "DoItTrading"
            }
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "<?php echo esc_js($avg_rating); ?>",
            "reviewCount": "<?php echo esc_js($total_reviews); ?>"
        }
    }
    </script>
    <?php
}