<?php
/**
 * Partial: Related Insights Section
 * 
 * @package DoItTrading
 */

// Get passed args
$current_id = $args['current_id'] ?? get_the_ID();
$insight_type = $args['insight_type'] ?? '';

// Get related posts
$related_query = doittrading_get_related_insights($current_id, $insight_type);

if ($related_query->have_posts()) : ?>

<section class="related-insights-section">
    <div class="container">
        <div class="section-header">
            <h2>Related Insights</h2>
        </div>
        
        <div class="related-grid">
            <?php while ($related_query->have_posts()) : $related_query->the_post(); 
                $post_type = doittrading_get_insight_type(get_the_ID());
                $reading_time = get_field('reading_time') ?: doittrading_calculate_reading_time(get_the_content());
                
                // Icon mapping
                $icons = array(
                    'performance' => '📈',
                    'education' => '📚',
                    'setup' => '⚙️',
                    'analysis' => '📊',
                    'strategy' => '💡',
                    'success' => '🏆'
                );
                $icon = $icons[$post_type] ?? '📄';
                ?>
                
                <a href="<?php the_permalink(); ?>" class="related-card">
                    <div class="related-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium'); ?>
                        <?php else : ?>
                            <span class="placeholder-icon"><?php echo $icon; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="related-content">
                        <h3><?php the_title(); ?></h3>
                        <div class="related-meta">
                            <?php echo ucfirst($post_type); ?> • <?php echo $reading_time; ?> min read
                        </div>
                    </div>
                </a>
                
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php endif;
wp_reset_postdata(); ?>