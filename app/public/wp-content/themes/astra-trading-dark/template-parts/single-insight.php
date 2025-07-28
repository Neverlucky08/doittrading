<?php
/**
 * Template for displaying single insight posts
 * 
 * Save this file as: single-insight.php in your theme root
 * 
 * @package DoItTrading
 */

get_header(); ?>

<main id="primary" class="site-main single-insight-page">
    
    <?php
    // Include the main single insight template
    get_template_part('inc/insights/templates/single-insight');
    ?>
    
</main>

<?php get_footer(); ?>