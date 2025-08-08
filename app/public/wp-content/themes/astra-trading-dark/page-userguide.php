<?php
/**
 * Template Name: User Guide
 * Description: Template for product user guides with sidebar navigation
 * 
 * @package DoItTrading
 */

get_header();

// Get product information from custom fields or URL parameter
$product_id = get_field('related_product_id') ?: get_query_var('product_id');
$product = $product_id ? wc_get_product($product_id) : null;
?>

<div class="userguide-container">
    <div class="userguide-wrapper">
        
        <!-- Sidebar Navigation -->
        <aside class="userguide-sidebar">
            <?php if ($product): ?>
                <!-- Product Information -->
                <div class="sidebar-product-info">
                    <?php if ($product->get_image_id()): ?>
                        <div class="product-image">
                            <?php echo $product->get_image('thumbnail'); ?>
                        </div>
                    <?php endif; ?>
                    <h3 class="product-name"><?php echo esc_html($product->get_name()); ?></h3>
                    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="back-to-product">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M11 1L4 8l7 7" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                        Back to Product
                    </a>
                </div>
                <div class="sidebar-divider"></div>
            <?php endif; ?>
            
            <!-- Navigation Menu -->
            <nav class="userguide-nav">
                <h4 class="nav-title">Table of Contents</h4>
                <ul class="nav-menu" id="userguide-nav-menu">
                    <!-- Navigation items will be generated dynamically via JavaScript -->
                </ul>
            </nav>
            
            <!-- Newsletter Widget -->
            <div class="widget newsletter-widget">
                <h3 class="widget-header">📧 Weekly Insights</h3>
                <p class="widget-description">Get trading tips and EA performance reports in your inbox</p>
                <form class="newsletter-form" action="#" method="post">
                    <input type="email" class="newsletter-input" placeholder="Your email" required>
                    <button type="submit" class="newsletter-btn">Subscribe</button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="userguide-content">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('userguide-article'); ?>>
                    <header class="userguide-header">
                        <h1 class="userguide-title"><?php the_title(); ?></h1>
                        <?php if (get_field('guide_subtitle')): ?>
                            <p class="userguide-subtitle"><?php echo esc_html(get_field('guide_subtitle')); ?></p>
                        <?php endif; ?>
                    </header>
                    
                    <div class="userguide-body">
                        <?php the_content(); ?>
                        
                        <!-- Call to Action Section -->
                        <?php if ($product && $product->is_purchasable() && $product->is_in_stock()): ?>
                            <div class="userguide-cta">
                                <h3>Ready to Get Started?</h3>
                                <p>Purchase <?php echo esc_html($product->get_name()); ?> and start trading today!</p>
                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="btn-primary">
                                    Purchase Now - <?php echo $product->get_price_html(); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Support Section
                        <div class="userguide-support">
                            <h3>Need Help?</h3>
                            <p>Our support team is here to assist you with setup and configuration.</p>
                            <div class="support-buttons">
                                <a href="/support" class="btn-secondary">Contact Support</a>
                                <a href="/documentation" class="btn-secondary">View Documentation</a>
                            </div>
                        </div>
                        -->
                    </div>
                </article>
                <?php
            endwhile;
            ?>
        </main>
    </div>
</div>

<?php
get_footer();
?>