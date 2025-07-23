<?php
/**
 * Featured Insights Section
 */
$featured_query = doittrading_get_featured_insights();
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
                            <span class="featured-badge">
                                <?php echo get_field('is_featured') ? 'Essential' : 'Featured'; ?>
                            </span>
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
                                <?php if (get_field('has_live_data')) : ?>
                                    • 🔥 Live Data
                                <?php endif; ?>
                            </span>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; wp_reset_postdata(); ?>