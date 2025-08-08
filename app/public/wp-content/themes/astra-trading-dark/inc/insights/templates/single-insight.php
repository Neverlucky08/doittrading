<?php
/**
 * Partial: Single Insight Sidebar
 * 
 * @package DoItTrading
 */

// Get passed args
$related_ea = $args['related_ea'] ?? null;
$insight_type = $args['insight_type'] ?? 'education';

// Get table of contents
$toc_items = doittrading_generate_toc(get_the_content());
?>

<!-- Table of Contents -->
<?php if (!empty($toc_items)): ?>
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

<!-- Featured EA (if mentioned in article) -->
<?php if ($related_ea): ?>
<div class="sidebar-widget">
    <h3 class="widget-header">⚡ Featured in This Article</h3>
    <?php
    // Get EA data
    $ea_id = is_object($related_ea) ? $related_ea->ID : $related_ea;
    $ea_title = get_the_title($ea_id);
    $win_rate = get_field('win_rate', $ea_id);
    $monthly_gain = get_field('monthly_gain', $ea_id);
    $max_drawdown = get_field('max_drawdown', $ea_id);
    $ea_url = get_permalink($ea_id);
    ?>
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
        <li><a href="/forex-trading-bots/">→ View All EAs</a></li>
        <li><a href="/forex-trading-bots/#live-results">→ Live Results</a></li>
        <?php if ($insight_type === 'setup' || $insight_type === 'education'): ?>
            <li><a href="/insights/?category=setup">→ More Setup Guides</a></li>
        <?php endif; ?>
        <li><a href="/contact/">→ Contact Support</a></li>
    </ul>
</div>

<?php
/**
 * Generate Table of Contents from content
 */
function doittrading_generate_toc($content) {
    $toc = array();
    
    // Match h2 and h3 tags
    preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h\1>/i', $content, $matches);
    
    if (!empty($matches[0])) {
        foreach ($matches[0] as $index => $heading) {
            $level = 'h' . $matches[1][$index];
            $text = strip_tags($matches[2][$index]);
            $id = sanitize_title($text);
            
            $toc[] = array(
                'level' => $level,
                'text' => $text,
                'id' => $id
            );
        }
    }
    
    return $toc;
}
?>