<?php
/**
 * Trading Insights Archive Template
 */
get_header(); ?>

<div class="insights-page">
    
    <?php 
    // Hero Section con live data
    get_template_part('inc/insights/templates/partials/hero-section'); 
    ?>
    
    <?php 
    // Featured Posts (3 sticky posts)
    get_template_part('inc/insights/templates/partials/featured-posts'); 
    ?>
    
    <?php 
    // Filter Bar
    get_template_part('inc/insights/templates/partials/filter-bar'); 
    ?>
    
    <div class="container">
        <div class="main-content">
            
            <!-- Posts Section -->
            <div class="posts-section">
                <div class="posts-header">
                    <h2>Recent Insights</h2>
                </div>
                
                <?php
                // Query for insights
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'insight',
                    'posts_per_page' => 9,
                    'paged' => $paged,
                    'meta_query' => array(
                        array(
                            'key' => 'is_featured',
                            'value' => '1',
                            'compare' => '!='
                        )
                    )
                );
                
                // Filter by category if set
                if (isset($_GET['category'])) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'insight_category',
                            'field' => 'slug',
                            'terms' => sanitize_text_field($_GET['category'])
                        )
                    );
                }
                
                $insights_query = new WP_Query($args);
                ?>
                
                <div class="posts-grid">
                    <?php 
                    if ($insights_query->have_posts()) :
                        while ($insights_query->have_posts()) : $insights_query->the_post();
                            get_template_part('inc/insights/templates/partials/post-card');
                        endwhile;
                    endif;
                    ?>
                </div>
                
                <?php if ($insights_query->max_num_pages > 1) : ?>
                <div class="load-more">
                    <button class="load-more-btn" data-page="1">Load More Insights</button>
                </div>
                <?php endif; ?>
                
                <?php wp_reset_postdata(); ?>
            </div>
            
            <!-- Sidebar -->
            <aside class="sidebar">
                <?php get_template_part('inc/insights/templates/partials/sidebar-widgets'); ?>
            </aside>
            
        </div>
    </div>
    
</div>

<?php get_footer(); ?>