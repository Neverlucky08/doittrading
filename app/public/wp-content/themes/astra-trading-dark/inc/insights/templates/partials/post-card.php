<?php
/**
 * Insight Post Card Template
 */

$insight_type = get_field('insight_type');
$has_live_data = get_field('has_live_data');
$reading_time = get_field('reading_time') ?: 5;
$views = get_post_meta(get_the_ID(), 'post_views_count', true) ?: rand(500, 3000);

// Badge settings
$badge_config = array(
    'performance' => array('text' => '📈 PERFORMANCE', 'class' => 'badge-update'),
    'education' => array('text' => '🎓 EDUCATION', 'class' => 'badge-guide'),
    'setup' => array('text' => '⚙️ SETUP', 'class' => 'badge-setup'),
    'analysis' => array('text' => '📊 ANALYSIS', 'class' => 'badge-update'),
    'strategy' => array('text' => '💡 STRATEGY', 'class' => 'badge-strategy'),
    'success' => array('text' => '🏆 SUCCESS', 'class' => 'badge-success')
);

$badge = $badge_config[$insight_type] ?? array('text' => '📄 ARTICLE', 'class' => '');
?>

<div class="post-card" data-category="<?php echo esc_attr($insight_type); ?>">
    <a href="<?php the_permalink(); ?>" class="post-card-link">
        
        <div class="post-thumbnail">
            <?php if ($has_live_data) : ?>
                <div class="post-badge badge-live">🟢 LIVE UPDATE</div>
            <?php else : ?>
                <div class="post-badge <?php echo esc_attr($badge['class']); ?>">
                    <?php echo esc_html($badge['text']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
            <?php else : ?>
                <div class="placeholder-icon">
                    <?php echo $insight_type === 'performance' ? '📈' : '📊'; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="post-content">
            <div class="post-meta">
                <?php 
                $category = get_the_terms(get_the_ID(), 'insight_category');
                if ($category && !is_wp_error($category)) {
                    echo esc_html($category[0]->name);
                }
                ?> • <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?>
            </div>
            
            <h3 class="post-title"><?php the_title(); ?></h3>
            
            <p class="post-excerpt">
                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
            </p>
            
            <div class="post-stats">
                <span>👁 <?php echo number_format($views); ?> views</span>
                <span>📖 <?php echo $reading_time; ?> min read</span>
            </div>
        </div>
        
    </a>
</div>