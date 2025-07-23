<?php
/**
 * Featured Insights Section
 */

// Query for featured posts
$featured_args = array(
    'post_type' => 'post',
    'category_name' => 'trading-insights',
    'posts_per_page' => 3,
    'meta_key' => 'is_featured',
    'meta_value' => '1',
    'meta_compare' => '='
);

// Si no tienes posts featured, muestra los más recientes
$featured_query = new WP_Query($featured_args);

if (!$featured_query->have_posts()) {
    // Fallback: mostrar últimos 3 posts
    $featured_query = new WP_Query(array(
        'post_type' => 'insight',
        'category_name' => 'trading-insights',
        'posts_per_page' => 3
    ));
}
?>

<?php if ($featured_query->have_posts()) : ?>
<section class="featured-section">
    <div class="container">
        <h2 class="section-header">📌 Essential Guides</h2>
        <div class="featured-grid">
            <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
                <div class="featured-card">
                    <a href="<?php the_permalink(); ?>">
                        <div class="featured-image">
                            <span class="featured-badge">Essential</span>
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium'); ?>
                            <?php else : ?>
                                <div class="placeholder-icon">📚</div>
                            <?php endif; ?>
                        </div>
                        <div class="featured-content">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                            <span class="read-time">
                                📖 <?php echo get_field('reading_time') ?: 5; ?> min read
                            </span>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; wp_reset_postdata(); ?>