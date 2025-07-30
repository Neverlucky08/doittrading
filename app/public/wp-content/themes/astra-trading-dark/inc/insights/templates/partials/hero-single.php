<?php
/**
 * Partial: Single Insight Hero Section
 * 
 * @package DoItTrading
 */

// Get passed args
$insight_type = $args['insight_type'] ?? 'education';
$reading_time = $args['reading_time'] ?? 5;
$post_views = $args['post_views'] ?? 0;
$has_live_data = $args['has_live_data'] ?? false;

// Get author data
$author_id = get_the_author_meta('ID');
$author_name = get_the_author();
$author_avatar = get_avatar($author_id, 50);
$author_bio = get_the_author_meta('description');

// Badge configuration
$badge_config = array(
    'performance' => array('text' => '📈 PERFORMANCE REPORT', 'class' => 'performance'),
    'education' => array('text' => '🎓 EDUCATION', 'class' => 'education'),
    'setup' => array('text' => '⚙️ SETUP GUIDE', 'class' => 'setup'),
    'analysis' => array('text' => '📊 MARKET ANALYSIS', 'class' => 'analysis'),
    'strategy' => array('text' => '💡 TRADING STRATEGY', 'class' => 'strategy'),
    'success' => array('text' => '🏆 SUCCESS STORY', 'class' => 'success')
);

$badge = $badge_config[$insight_type] ?? $badge_config['education'];

// Get live data if needed
$live_data = $has_live_data ? doittrading_get_live_trading_data() : null;
?>

<section class="insight-hero">
    <div class="container">
        
        <!-- Breadcrumbs -->
        <nav class="insight-breadcrumbs">
            <a href="<?php echo home_url(); ?>">Home</a>
            <span>→</span>
            <a href="<?php echo home_url('/insights/'); ?>">Trading Insights</a>
            <span>→</span>
            <span><?php echo esc_html($badge['text']); ?></span>
        </nav>
        
        <!-- Post Header -->
        <div class="insight-header">
            <!-- Category Badge -->
            <div class="insight-category-badge <?php echo esc_attr($badge['class']); ?>">
                <?php echo esc_html($badge['text']); ?>
            </div>
            
            <!-- Title -->
            <h1 class="insight-title"><?php the_title(); ?></h1>
            
            <!-- Meta Info -->
            <div class="insight-meta">
                <!-- Author Info -->
                <div class="author-info">
                    <div class="author-avatar">
                        <?php if (strpos($author_avatar, 'gravatar.com') !== false): ?>
                            <?php echo strtoupper(substr($author_name, 0, 2)); ?>
                        <?php else: ?>
                            <?php echo $author_avatar; ?>
                        <?php endif; ?>
                    </div>
                    <div class="author-details">
                        <h4><?php echo esc_html($author_name); ?></h4>
                        <span><?php echo esc_html($author_bio ?: 'DoItTrading Team'); ?></span>
                    </div>
                </div>
                
                <!-- Meta Stats -->
                <div class="meta-stats">
                    <span>📅 <?php echo get_the_date('F j, Y'); ?></span>
                    <span>📖 <?php echo esc_html($reading_time); ?> min read</span>
                    <span>👁 <?php echo number_format($post_views); ?> views</span>
                </div>
            </div>
            
            <!-- Live Indicator (if applicable) -->
            <?php if ($has_live_data && $live_data): ?>
            <div class="insight-live-indicator">
                <div class="live-dot"></div>
                <span>
                    <strong>LIVE UPDATE:</strong>
                    <?php 
                    $updates = array();
                    foreach ($live_data as $ea => $data) {
                        $ea_name = str_replace('_', ' ', ucwords($ea));
                        $updates[] = $ea_name . ': ' . $data['direction'] . $data['pips'] . ' pips';
                    }
                    echo implode(' | ', array_slice($updates, 0, 2));
                    ?>
                </span>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</section>