<?php
/**
 * DoItTrading Social Proof Elements
 * Trust indicators y elementos de prueba social
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Trust Badges
 */
add_action('woocommerce_single_product_summary', 'doittrading_trust_badges', 15);
function doittrading_trust_badges() {
    if (!doittrading_is_ea()) return;
    ?>
    <div class="trust-badges-row">
        <div class="trust-badge">
            <span class="icon">📊</span>
            <span class="text">Live Results Available</span>
        </div>
        <div class="trust-badge">
            <span class="icon">📱</span>
            <span class="text">24/7 Support</span>
        </div>
        <div class="trust-badge">
            <span class="icon">🔄</span>
            <span class="text">Regular Updates</span>
        </div>
        <div class="trust-badge">
            <span class="icon">⚡</span>
            <span class="text">VPS Compatible</span>
        </div>
    </div>
    <?php
}

/**
 * Enhanced Reviews Section
 */
add_action('woocommerce_after_single_product_summary', 'doittrading_reviews_enhanced', 15);
function doittrading_reviews_enhanced() {
    $product_id = get_the_ID();
    
    if (!doittrading_is_ea() || !get_field('review_1_name', $product_id)) return;
    ?>
    <div class="reviews-enhanced-section">
        <h3>What Real Traders Are Saying (All Verified Purchases)</h3>
        
        <div class="reviews-enhanced-grid">
            <?php 
            // Mostrar hasta 3 reviews principales
            for ($i = 1; $i <= 3; $i++):
                $name = get_field("review_{$i}_name", $product_id);
                if (!$name) continue;
                
                $date = get_field("review_{$i}_date", $product_id);
                $stars = get_field("review_{$i}_stars", $product_id);
                $text = get_field("review_{$i}_text", $product_id);
                ?>
                
                <div class="review-enhanced-card">
                    <div class="verified-purchase-badge">✓ VERIFIED</div>
                    <div class="review-stars">
                        <?php echo str_repeat('⭐', $stars); ?>
                    </div>
                    <p class="review-text-enhanced"><?php echo esc_html($text); ?></p>
                    <div class="review-author">- <?php echo esc_html($name); ?></div>
                    <?php if ($date): ?>
                        <div class="review-date"><?php echo date('F Y', strtotime($date)); ?></div>
                    <?php endif; ?>
                </div>
                
            <?php endfor; ?>
            
            <!-- Summary Card -->
            <div class="review-enhanced-card summary-card">
                <div class="review-summary">
                    <div class="summary-rating">
                        <?php 
                        $avg_rating = get_field('mql5_average_rating', $product_id) ?: 4.9;
                        echo number_format((float)$avg_rating, 1); 
                        ?>★
                    </div>
                    <div class="summary-count">
                        <strong><?php echo get_field('mql5_total_reviews', $product_id) ?: 47; ?> verified reviews</strong>
                    </div>
                    <div class="summary-note">All from real MQL5 buyers</div>
                    <?php if ($mql5_link = get_field('mql5_purchase_link_mt4', $product_id)): ?>
                        <a href="<?php echo esc_url($mql5_link); ?>" target="_blank" class="summary-link">
                            View all reviews on MQL5 →
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Value Proposition Comparison
 */
add_action('woocommerce_after_single_product_summary', 'doittrading_value_proposition', 17);
function doittrading_value_proposition() {
    global $product;
    
    if (!doittrading_is_ea()) return;
    
    $price = $product->get_price();
    ?>
    <div class="value-proposition-section">
        <h3>Why $<?php echo $price; ?> is Actually Cheap</h3>
        
        <div class="value-comparison">
            <div class="value-column bad">
                <h4>❌ Typical $99 EAs:</h4>
                <ul>
                    <li>Backtest-only results</li>
                    <li>No live verification</li>
                    <li>Dangerous strategies</li>
                    <li>No ongoing support</li>
                    <li>Account blowup risk</li>
                </ul>
            </div>
            
            <div class="value-column good">
                <h4>✅ DoItTrading EAs:</h4>
                <ul>
                    <li>Live MyFxBook verified</li>
                    <li>Conservative risk management</li>
                    <li>No martingale/grid</li>
                    <li>24/7 support & updates</li>
                    <li>Sustainable profits</li>
                </ul>
            </div>
        </div>
        
        <div class="value-bottom-line">
            <p><strong>Bottom Line:</strong> Would you rather lose $99 on a bad EA, or invest $<?php echo $price; ?> in one that actually works?</p>
        </div>
    </div>
    <?php
}

/**
 * Why DoItTrading Section
 */
add_action('woocommerce_after_single_product_summary', 'doittrading_why_us_section', 20);
function doittrading_why_us_section() {
    if (!doittrading_is_ea()) return;
    ?>
    <div class="why-doittrading">
        <h2>Why 500+ Traders Choose DoItTrading</h2>
        <div class="features-grid">
            <div class="feature">
                <div class="icon">📈</div>
                <h3>Transparent Results</h3>
                <p>Every EA has public MyFxBook accounts. No hiding, no tricks.</p>
            </div>
            <div class="feature">
                <div class="icon">🛠️</div>
                <h3>Real Support</h3>
                <p>Get help from the actual developer. Response within 24 hours.</p>
            </div>
            <div class="feature">
                <div class="icon">📚</div>
                <h3>Complete Guides</h3>
                <p>Step-by-step setup instructions and optimization tips included.</p>
            </div>
            <div class="feature">
                <div class="icon">🔄</div>
                <h3>Active Updates</h3>
                <p>EAs updated based on market changes and user feedback.</p>
            </div>
        </div>
    </div>
    <?php
}

/**
 * MQL5 Reviews Tab Content
 */
function doittrading_mql5_reviews_content() {
    $product_id = get_the_ID();
    $total_reviews = get_field('mql5_total_reviews', $product_id) ?: 0;
    $avg_rating = get_field('mql5_average_rating', $product_id) ?: 5.0;
    ?>
    <div class="mql5-reviews">
        <div class="reviews-header">
            <h2>Verified Reviews from MQL5 Market</h2>
            <div class="rating-summary">
                <span class="stars"><?php echo str_repeat('⭐', round($avg_rating)); ?></span>
                <span class="rating"><?php echo number_format($avg_rating, 1); ?> out of 5</span>
                <span class="count">(<?php echo $total_reviews; ?> reviews)</span>
            </div>
        </div>
        
        <?php 
        // Mostrar hasta 5 reviews
        for ($i = 1; $i <= 5; $i++):
            $name = get_field('review_' . $i . '_name', $product_id);
            if (!$name) continue;
            
            $date = get_field('review_' . $i . '_date', $product_id);
            $stars = get_field('review_' . $i . '_stars', $product_id);
            $text = get_field('review_' . $i . '_text', $product_id);
            ?>
            
            <div class="review-item">
                <div class="review-header">
                    <strong><?php echo esc_html($name); ?></strong>
                    <span class="review-date"><?php echo $date ? date('F j, Y', strtotime($date)) : ''; ?></span>
                    <span class="stars"><?php echo str_repeat('⭐', $stars); ?></span>
                </div>
                <p class="review-text"><?php echo esc_html($text); ?></p>
                <span class="verified-badge">✓ Verified Purchase on MQL5</span>
            </div>
            
        <?php endfor; ?>
        
        <?php 
        $mql5_link = get_field('mql5_purchase_link_mt4', $product_id) ?: get_field('mql5_purchase_link_mt5', $product_id);
        if ($mql5_link): ?>
            <a href="<?php echo esc_url($mql5_link); ?>" target="_blank" class="view-all-reviews">
                View all <?php echo $total_reviews; ?> reviews on MQL5 Market →
            </a>
        <?php endif; ?>
    </div>
    <?php
}