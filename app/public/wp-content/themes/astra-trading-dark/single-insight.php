<?php
/**
 * Template for displaying single insight posts
 * 
 * This file should be in your theme root directory because it is a general Template!!
 * 
 * @package DoItTrading
 */

get_header(); 

// Debug: Check if we're in the right template
if (WP_DEBUG) {
    echo '<!-- DoItTrading: Using single-insight.php template -->';
}

// Track post views
if (function_exists('doittrading_track_post_views')) {
    doittrading_track_post_views(get_the_ID());
}

// Get insight data
$insight_type = doittrading_get_insight_type(get_the_ID());
$reading_time = get_field('reading_time') ?: doittrading_calculate_reading_time(get_the_content());
$post_views = get_post_meta(get_the_ID(), 'post_views_count', true) ?: 0;
$has_live_data = get_field('has_live_data') ?: false;

// Detect mentioned EA in content
$related_ea = doittrading_detect_mentioned_ea(get_the_content());
?>

<!-- Progress Bar -->
<div class="insight-progress-bar" id="progressBar"></div>

<main id="primary" class="site-main single-insight-page">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- Hero Section -->
        <?php 
        get_template_part('inc/insights/templates/partials/hero-single', null, array(
            'insight_type' => $insight_type,
            'reading_time' => $reading_time,
            'post_views' => $post_views,
            'has_live_data' => $has_live_data
        )); 
        ?>
        
        <!-- Content Section -->
        <div class="insight-content-wrapper">
            <div class="container">
                <div class="content-grid">
                    
                    <!-- Main Article -->
                    <article class="article-content">
                        <?php get_template_part('inc/insights/templates/partials/content-single'); ?>
                    </article>
                    
                    <!-- Sidebar -->
                    <aside class="insight-sidebar">
                        <?php 
                        get_template_part('inc/insights/templates/partials/sidebar-single', null, array(
                            'related_ea' => $related_ea,
                            'insight_type' => $insight_type
                        )); 
                        ?>
                    </aside>
                    
                </div>
            </div>
        </div>
        
        <!-- Related Posts -->
        <?php 
        get_template_part('inc/insights/templates/partials/related-posts', null, array(
            'current_id' => get_the_ID(),
            'insight_type' => $insight_type
        )); 
        ?>
        
        <!-- CTA Section -->
        <?php 
        get_template_part('inc/insights/templates/partials/cta-single', null, array(
            'related_ea' => $related_ea,
            'insight_type' => $insight_type
        )); 
        ?>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?>