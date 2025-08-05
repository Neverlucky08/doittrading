<?php
/**
 * DoItTrading Custom Footer
 * 
 * @package DoItTrading
 * @version 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Remove default Astra footer
 */
add_action('init', 'doittrading_remove_astra_footer');
function doittrading_remove_astra_footer() {
    remove_action('astra_footer_content', 'astra_footer_small_footer_template', 5);
    remove_action('astra_footer', 'astra_footer_markup');
}

/**
 * Add custom footer
 */
add_action('astra_footer', 'doittrading_custom_footer_markup');
function doittrading_custom_footer_markup() {
    ?>
    <footer class="doittrading-footer" role="contentinfo">
        <div class="ast-container">
            <div class="footer-container">
                <!-- Column 1: Copyright -->
                <div class="footer-column footer-copyright">
                    <div class="footer-logo">
                        <h3 class="footer-brand">DoIt Trading</h3>
                    </div>
                    <p>&copy; <?php echo date('Y'); ?> DoItTrading. All rights reserved.</p>
                    <p class="footer-tagline">
                        <span>Professional Expert Advisors for serious traders. </span>
                        <span>Verified results, transparent performance.</span>
                    </p>
                    <div class="footer-badges">
                        <span class="badge">✓ MQL5 Verified</span>
                        <span class="badge">✓ Live Testing</span>
                    </div>
                </div>
                
                <!-- Column 2: Top Performing Products -->
                <div class="footer-column footer-products">
                    <h3 class="footer-title">Top Performing Products</h3>
                    <?php
                    $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => 5,
                        'meta_key' => '_price',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                        'meta_query' => array(
                            array(
                                'key' => '_stock_status',
                                'value' => 'instock',
                                'compare' => '='
                            )
                        )
                    );
                    
                    $products = new WP_Query($args);
                    
                    if ($products->have_posts()) : ?>
                        <ul class="footer-products-list">
                            <?php while ($products->have_posts()) : $products->the_post(); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php endif;
                    wp_reset_postdata();
                    ?>
                </div>
                
                <!-- Column 3: Legal Pages -->
                <div class="footer-column footer-legal">
                    <h3 class="footer-title">Legal Information</h3>
                    <ul class="footer-legal-list">
                        <li><a href="<?php echo get_page_link(get_page_by_path('terms-of-use-agb')); ?>">Terms of Use</a></li>
                        <li><a href="<?php echo get_page_link(get_page_by_path('refund-returns-policy')); ?>">Refund & Returns Policy</a></li>
                        <li><a href="<?php echo get_page_link(get_page_by_path('legal-notice-impressum')); ?>">Legal Notice (Impressum)</a></li>
                        <li><a href="<?php echo get_page_link(get_page_by_path('privacy-statement')); ?>">Privacy Statement</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Risk Warning -->
            <div class="footer-risk-warning">
                <p>Between 74-89 % of retail investor accounts lose money when trading CFDs. You should consider whether you understand how CFDs work and whether you can afford to take the high risk of losing your money.</p>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Get top performing products based on custom criteria
 */
function doittrading_get_top_products($limit = 5) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'meta_key' => 'total_sales',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '='
            )
        )
    );
    
    return new WP_Query($args);
}