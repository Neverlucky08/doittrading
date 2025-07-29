<?php
/**
 * Template for displaying single insight posts
 * 
 * This file should be in your theme root directory because it is a general Template!!
 * 
 * @package DoItTrading
 */

get_header(); 

// Load required JavaScript
wp_enqueue_script('doittrading-single-insight', get_stylesheet_directory_uri() . '/assets/js/single-insight.js', array('jquery'), '1.0', true);

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
        <section class="insight-hero">
            <div class="container">
                
                <!-- Breadcrumbs -->
                <nav class="insight-breadcrumbs">
                    <a href="<?php echo home_url(); ?>">Home</a>
                    <span>→</span>
                    <a href="<?php echo home_url('/insights/'); ?>">Trading Insights</a>
                    <span>→</span>
                    <span><?php echo ucfirst($insight_type); ?></span>
                </nav>
                
                <!-- Post Header -->
                <div class="insight-header">
                    <!-- Category Badge -->
                    <?php
                    $badge_config = array(
                        'performance' => array('text' => '📈 PERFORMANCE REPORT', 'class' => 'performance'),
                        'education' => array('text' => '🎓 EDUCATION', 'class' => 'education'),
                        'setup' => array('text' => '⚙️ SETUP GUIDE', 'class' => 'setup'),
                        'analysis' => array('text' => '📊 MARKET ANALYSIS', 'class' => 'analysis'),
                        'strategy' => array('text' => '💡 TRADING STRATEGY', 'class' => 'strategy'),
                        'success' => array('text' => '🏆 SUCCESS STORY', 'class' => 'success')
                    );
                    $badge = $badge_config[$insight_type] ?? $badge_config['education'];
                    ?>
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
                                <?php 
                                $author_avatar = get_avatar(get_the_author_meta('ID'), 50);
                                if (strpos($author_avatar, 'gravatar.com') !== false): 
                                    echo doittrading_get_author_initials(get_the_author());
                                else: 
                                    echo $author_avatar;
                                endif; 
                                ?>
                            </div>
                            <div class="author-details">
                                <h4><?php the_author(); ?></h4>
                                <span><?php echo get_the_author_meta('description') ?: 'DoItTrading Team'; ?></span>
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
                    <?php if ($has_live_data): 
                        $live_data = doittrading_get_live_trading_data();
                    ?>
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
        
        <!-- Content Section -->
        <div class="insight-content-wrapper">
            <div class="container">
                <div class="content-grid">
                    
                    <!-- Main Article -->
                    <article class="article-content">
                        <?php
                        // Add IDs to headings for TOC
                        $content = get_the_content();
                        $content = doittrading_add_heading_ids($content);
                        $content = apply_filters('the_content', $content);
                        echo $content;
                        ?>
                    </article>
                    
                    <!-- Sidebar -->
                    <aside class="insight-sidebar">
                        <?php
                        // Table of Contents
                        $toc_items = doittrading_generate_toc_items(get_the_content());
                        if (!empty($toc_items)): ?>
                        <div class="sidebar-widget">
                            <h3 class="widget-header">📑 Table of Contents</h3>
                            <ul class="toc-list" id="toc-list">
                                <?php foreach ($toc_items as $item): ?>
                                    <li>
                                        <a href="#<?php echo esc_attr($item['id']); ?>" 
                                           class="toc-link <?php echo $item['level'] === 'h3' ? 'toc-sub' : ''; ?>">
                                            <?php echo esc_html($item['text']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Featured EA -->
                        <?php if ($related_ea): 
                            $ea_id = is_object($related_ea) ? $related_ea->ID : $related_ea;
                            $ea_title = get_the_title($ea_id);
                            $win_rate = get_field('win_rate', $ea_id);
                            $monthly_gain = get_field('monthly_gain', $ea_id);
                            $max_drawdown = get_field('max_drawdown', $ea_id);
                            $ea_url = get_permalink($ea_id);
                        ?>
                        <div class="sidebar-widget">
                            <h3 class="widget-header">⚡ Featured in This Article</h3>
                            <div class="ea-showcase">
                                <h4><?php echo esc_html($ea_title); ?></h4>
                                <div class="ea-stats">
                                    <div class="ea-stat">
                                        <span><?php echo esc_html($win_rate); ?>%</span>
                                        <small>Win Rate</small>
                                    </div>
                                    <div class="ea-stat">
                                        <span>+<?php echo esc_html($monthly_gain); ?>%</span>
                                        <small>Monthly</small>
                                    </div>
                                    <div class="ea-stat">
                                        <span>-<?php echo esc_html($max_drawdown); ?>%</span>
                                        <small>Max DD</small>
                                    </div>
                                </div>
                                <a href="<?php echo esc_url($ea_url); ?>" class="ea-cta">View Details</a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Newsletter -->
                        <div class="sidebar-widget">
                            <h3 class="widget-header">📧 Weekly Updates</h3>
                            <p style="margin-bottom: 1rem; color: var(--doit-text-muted); font-size: 0.9rem;">
                                Get performance reports & trading insights
                            </p>
                            <form class="newsletter-form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
                                <input type="email" 
                                       name="email" 
                                       class="newsletter-input" 
                                       placeholder="Your email" 
                                       required>
                                <input type="hidden" name="action" value="doittrading_subscribe">
                                <?php wp_nonce_field('newsletter_subscribe', 'newsletter_nonce'); ?>
                                <button type="submit" class="newsletter-btn">Subscribe</button>
                            </form>
                        </div>
                        
                        <!-- Share Buttons -->
                        <div class="sidebar-widget">
                            <h3 class="widget-header">📤 Share This Story</h3>
                            <div class="share-buttons">
                                <?php
                                $share_url = urlencode(get_permalink());
                                $share_title = urlencode(get_the_title());
                                ?>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" 
                                   class="share-btn share-twitter" 
                                   target="_blank" 
                                   rel="noopener">𝕏</a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" 
                                   class="share-btn share-facebook" 
                                   target="_blank" 
                                   rel="noopener">f</a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" 
                                   class="share-btn share-linkedin" 
                                   target="_blank" 
                                   rel="noopener">in</a>
                                <a href="https://t.me/share/url?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" 
                                   class="share-btn share-telegram" 
                                   target="_blank" 
                                   rel="noopener">✈</a>
                            </div>
                        </div>
                        
                        <!-- Quick Links -->
                        <div class="sidebar-widget">
                            <h3 class="widget-header">🔗 Quick Links</h3>
                            <ul class="quick-links">
                                <li><a href="/shop/">→ View All EAs</a></li>
                                <li><a href="/forex-trading-bots/#live-results">→ Live Results</a></li>
                                <?php if ($insight_type === 'setup' || $insight_type === 'education'): ?>
                                    <li><a href="/insights/?category=setup">→ More Setup Guides</a></li>
                                <?php endif; ?>
                                <li><a href="/contact/">→ Contact Support</a></li>
                            </ul>
                        </div>
                    </aside>
                    
                </div>
            </div>
        </div>
        
        <!-- Related Posts -->
        <section class="related-insights-section">
            <div class="container">
                <div class="section-header">
                    <h2>Related Insights</h2>
                </div>
                
                <div class="related-grid">
                    <?php
                    $related_query = doittrading_get_related_insights(get_the_ID(), $insight_type);
                    
                    if ($related_query->have_posts()) :
                        while ($related_query->have_posts()) : $related_query->the_post(); 
                            $post_type = doittrading_get_insight_type(get_the_ID());
                            $reading_time = get_field('reading_time') ?: doittrading_calculate_reading_time(get_the_content());
                            
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
                            
                        <?php endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </section>
        
        <!-- CTA Section -->
        <?php
        // Determine CTA content based on insight type
        $cta_content = array(
            'performance' => array(
                'title' => 'See Live Results for Yourself',
                'description' => 'Join hundreds of traders already profiting with our verified EAs',
                'button_text' => 'View All EAs',
                'button_url' => '/forex-trading-bots/'
            ),
            'education' => array(
                'title' => 'Ready to Apply What You\'ve Learned?',
                'description' => 'Start trading smarter with our professionally developed EAs',
                'button_text' => 'Explore Our EAs',
                'button_url' => '/forex-trading-bots/'
            ),
            'setup' => array(
                'title' => 'Need Help with Setup?',
                'description' => 'Get personal support from our team for a smooth start',
                'button_text' => 'Contact Support',
                'button_url' => '/contact/'
            ),
            'success' => array(
                'title' => 'Ready to Start Your Success Story?',
                'description' => 'Join hundreds of traders already profiting with DoItTrading EAs',
                'button_text' => 'Get Started Today',
                'button_url' => '/forex-trading-bots/'
            ),
            'analysis' => array(
                'title' => 'Trade These Insights Automatically',
                'description' => 'Let our EAs execute professional strategies while you sleep',
                'button_text' => 'View Trading Bots',
                'button_url' => '/forex-trading-bots/'
            ),
            'strategy' => array(
                'title' => 'Automate This Strategy',
                'description' => 'Our EAs implement proven strategies with discipline and precision',
                'button_text' => 'Explore EAs',
                'button_url' => '/forex-trading-bots/'
            )
        );
        
        $cta = $cta_content[$insight_type] ?? $cta_content['education'];
        
        // Override if specific EA is mentioned
        if ($related_ea) {
            $ea_id = is_object($related_ea) ? $related_ea->ID : $related_ea;
            $ea_title = get_the_title($ea_id);
            $ea_url = get_permalink($ea_id);
            
            $cta = array(
                'title' => 'Ready to Start Trading with ' . $ea_title . '?',
                'description' => 'Join hundreds of traders already profiting with this verified EA',
                'button_text' => 'Get Started with ' . str_replace('DoIt ', '', $ea_title),
                'button_url' => $ea_url
            );
        }
        ?>
        
        <section class="insight-cta-section">
            <div class="container">
                <h2><?php echo esc_html($cta['title']); ?></h2>
                <p><?php echo esc_html($cta['description']); ?></p>
                <a href="<?php echo esc_url($cta['button_url']); ?>" class="cta-button">
                    <?php echo esc_html($cta['button_text']); ?>
                </a>
            </div>
        </section>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?>
