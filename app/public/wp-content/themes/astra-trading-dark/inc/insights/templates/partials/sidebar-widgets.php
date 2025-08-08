<?php
/**
 * Insights Sidebar Widgets
 */
?>

<!-- Live Performance Widget -->
<div class="widget">
    <h3 class="widget-header">📊 Live Performance</h3>
    <div class="performance-list">
        <div class="performance-item">
            <span>DoIt GBP Master</span>
            <span class="performance-value">+287%</span>
        </div>
        <div class="performance-item">
            <span>Gold Guardian</span>
            <span class="performance-value">+456%</span>
        </div>
        <div class="performance-item">
            <span>Index Vanguard</span>
            <span class="performance-value">+94%</span>
        </div>
    </div>
    <a href="/forex-trading-bots/" class="view-all-link">View All Results →</a>
</div>

<!-- Trending Widget -->
<div class="widget">
    <h3 class="widget-header">🔥 Trending Now</h3>
    <div class="trending-list">
        <?php
        // Get trending posts
        $trending_query = new WP_Query(array(
            'post_type' => 'post',
            'category_name' => 'trading-insights',
            'posts_per_page' => 5,
            'orderby' => 'comment_count', // O 'meta_value_num' si tienes views
            'order' => 'DESC'
        ));
        
        if ($trending_query->have_posts()) :
            $count = 1;
            while ($trending_query->have_posts()) : $trending_query->the_post(); ?>
                <div class="trending-item">
                    <?php echo $count; ?>. <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </div>
            <?php 
            $count++;
            endwhile;
        else : ?>
            <p>No trending posts yet.</p>
        <?php endif;
        wp_reset_postdata();
        ?>
    </div>
</div>

<!-- Newsletter Widget -->
<div class="widget">
    <h3 class="widget-header">📧 Weekly Insights</h3>
    <p style="margin-bottom: 1rem; color: var(--doit-text-muted);">Get performance reports in your inbox</p>
    <form class="newsletter-form" action="#" method="post">
        <input type="email" class="newsletter-input" placeholder="Your email" required>
        <button type="submit" class="newsletter-btn">Subscribe</button>
    </form>
</div>

<!-- Help Widget -->
<div class="widget">
    <h3 class="widget-header">💬 Need Help?</h3>
    <div class="help-links">
        <a href="/doit-chat/">• EA Setup Support</a>
        <a href="/forex-trading-bots/">• Choose the Right EA</a>
        <a href="/doit-chat/">• Contact Diego Directly</a>
        <a href="#">• Join Telegram Group</a>
    </div>
</div>