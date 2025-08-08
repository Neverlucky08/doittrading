<?php
/**
 * DoItTrading Related Products Section
 * Shows related products on single product pages
 * 
 * @package DoItTrading
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Display related products section
 */
add_action('woocommerce_after_single_product_summary', 'doittrading_related_products_section', 25);

function doittrading_related_products_section() {
    // Only show on EA and Indicator product pages
    if (!is_product()) return;
    
    $product_id = get_the_ID();
    
    // Check if it's an EA or Indicator
    if (!doittrading_is_ea($product_id) && !doittrading_is_indicator($product_id)) {
        return;
    }
    
    // Get related products
    $related_products = doittrading_get_related_products($product_id, 3);
    
    if (empty($related_products)) {
        return;
    }
    
    $is_ea = doittrading_is_ea($product_id);
    ?>
    
    <div class="doittrading-related-products-section">
        <div class="related-products-container">
            
            <!-- Section Header -->
            <div class="related-products-header">
                <?php if ($is_ea): ?>
                    <h2>Diversify Your EA Portfolio</h2>
                    <p>Complement your trading strategy with these proven Expert Advisors</p>
                <?php else: ?>
                    <h2>Complete Your Trading Toolkit</h2>
                    <p>Enhance your analysis with these professional indicators</p>
                <?php endif; ?>
            </div>
            
            <!-- Products Grid -->
            <div class="related-products-grid">
                <?php foreach ($related_products as $product): ?>
                    <?php if ($product['type'] === 'ea'): ?>
                        <!-- EA Product Card -->
                        <div class="related-product-card related-ea-card">
                            <?php if ($product['image']): ?>
                                <div class="related-product-image">
                                    <a href="<?php echo esc_url($product['url']); ?>">
                                        <img src="<?php echo esc_url($product['image']); ?>" 
                                             alt="<?php echo esc_attr($product['name']); ?>">
                                    </a>
                                    <?php if ($product['badge']): ?>
                                        <span class="product-badge-small badge-<?php echo esc_attr($product['badge_color']); ?>">
                                            <?php echo esc_html($product['badge']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="related-product-content">
                                <h3 class="related-product-title">
                                    <a href="<?php echo esc_url($product['url']); ?>">
                                        <?php echo esc_html($product['name']); ?>
                                    </a>
                                </h3>
                                
                                <?php if ($product['subtitle']): ?>
                                    <p class="related-product-subtitle">
                                        <?php echo esc_html($product['subtitle']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <!-- EA Info (Rating & Active Traders) -->
                                <div class="related-product-info">
                                    <?php if ($product['rating']): ?>
                                        <div class="product-rating">
                                            <span class="stars">
                                                <?php 
                                                $rating = floatval($product['rating']);
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $rating) {
                                                        echo '★';
                                                    } elseif ($i - 0.5 <= $rating) {
                                                        echo '☆';
                                                    } else {
                                                        echo '☆';
                                                    }
                                                }
                                                ?>
                                            </span>
                                            <span class="rating-value"><?php echo esc_html($product['rating']); ?>/5</span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($product['active_traders']): ?>
                                        <div class="active-traders">
                                            <span class="traders-icon">👥</span>
                                            <span class="traders-count"><?php echo esc_html($product['active_traders']); ?> active traders</span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($product['win_rate']): ?>
                                        <div class="win-rate-badge">
                                            <span class="win-rate-icon">🎯</span>
                                            <span class="win-rate-text"><?php echo esc_html($product['win_rate']); ?>% Win Rate</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Price and CTA -->
                                <div class="related-product-footer">
                                    <div class="related-product-price">
                                        <?php if ($product['price'] && $product['regular_price'] && $product['price'] < $product['regular_price']): ?>
                                            <span class="price-current">$<?php echo esc_html($product['price']); ?></span>
                                            <span class="price-original">$<?php echo esc_html($product['regular_price']); ?></span>
                                        <?php elseif ($product['price']): ?>
                                            <span class="price-current">$<?php echo esc_html($product['price']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <a href="<?php echo esc_url($product['url']); ?>" class="related-product-cta">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <!-- Indicator Product Card -->
                        <div class="related-product-card related-indicator-card">
                            <?php if ($product['image']): ?>
                                <div class="related-product-image">
                                    <a href="<?php echo esc_url($product['url']); ?>">
                                        <img src="<?php echo esc_url($product['image']); ?>" 
                                             alt="<?php echo esc_attr($product['name']); ?>">
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="related-product-content">
                                <h3 class="related-product-title">
                                    <a href="<?php echo esc_url($product['url']); ?>">
                                        <?php echo esc_html($product['name']); ?>
                                    </a>
                                </h3>
                                
                                <?php if ($product['subtitle']): ?>
                                    <p class="related-product-subtitle">
                                        <?php echo esc_html($product['subtitle']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <!-- Indicator Info -->
                                <div class="related-product-info">
                                    <?php if ($product['rating']): ?>
                                        <div class="product-rating">
                                            <span class="stars">
                                                <?php 
                                                $rating = floatval($product['rating']);
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $rating) {
                                                        echo '★';
                                                    } elseif ($i - 0.5 <= $rating) {
                                                        echo '☆';
                                                    } else {
                                                        echo '☆';
                                                    }
                                                }
                                                ?>
                                            </span>
                                            <span class="rating-value"><?php echo esc_html($product['rating']); ?>/5</span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($product['downloads']): ?>
                                        <div class="indicator-downloads">
                                            <span class="downloads-icon">↓</span>
                                            <span class="downloads-count"><?php echo esc_html($product['downloads']); ?> downloads</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Price and CTA -->
                                <div class="related-product-footer">
                                    <div class="related-product-price">
                                        <?php if ($product['price'] == 0): ?>
                                            <span class="price-free">FREE</span>
                                        <?php elseif ($product['price']): ?>
                                            <span class="price-current">$<?php echo esc_html($product['price']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <a href="<?php echo esc_url($product['url']); ?>" class="related-product-cta">
                                        View Tool →
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <!-- Section Footer CTA -->
            <div class="related-products-footer">
                <?php if ($is_ea): ?>
                    <p class="footer-text">
                        <strong>Build a diversified EA portfolio</strong> to maximize profits and minimize risk
                    </p>
                    <a href="/forex-trading-bots/" class="footer-cta">
                        Browse All Expert Advisors →
                    </a>
                <?php else: ?>
                    <p class="footer-text">
                        <strong>Discover more professional indicators</strong> to enhance your trading analysis
                    </p>
                    <a href="/forex-indicators/" class="footer-cta">
                        Browse All Indicators →
                    </a>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
    <?php
}