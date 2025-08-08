<?php
/**
 * Template Name: Empty Page
 * 
 * @package DoItTrading
 */

get_header(); 
?>

<div class="container">
    <div class="empty-page-content">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
        ?>
    </div>
</div>

<?php get_footer(); ?>