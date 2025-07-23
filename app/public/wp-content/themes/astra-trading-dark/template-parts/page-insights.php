<?php
/**
 * Template Name: Trading Insights
 * 
 * @package DoItTrading
 */

get_header(); ?>

<div class="insights-page">
    
    <?php 
    // Hero Section
    get_template_part('inc/insights/templates/partials/hero-section'); 
    ?>
    
    <?php 
    // Featured Posts
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
                <?php get_template_part('inc/insights/templates/partials/posts-grid'); ?>
            </div>
            
            <!-- Sidebar -->
            <aside class="sidebar">
                <?php get_template_part('inc/insights/templates/partials/sidebar-widgets'); ?>
            </aside>
            
        </div>
    </div>
    
</div>

<?php get_footer(); ?>