<?php
/**
 * Posts Grid Template with Filter Support
 */
?>

<div class="posts-header">
    <h2>Recent Insights</h2>
    
    <!-- Optional search bar -->
    <div class="search-bar" style="margin: 20px 0;">
        <input type="text" id="insights-search" placeholder="Search insights..." style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 300px;">
    </div>
</div>

<?php
// Query for insights (Custom Post Type)
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// Base args
$args = array(
    'post_type' => 'insight',
    'post_status' => 'publish',
    'posts_per_page' => 9,
    'paged' => $paged,
    'orderby' => 'date',
    'order' => 'DESC'
);

// Handle category filter from URL
$selected_category = '';
if (isset($_GET['category']) && !empty($_GET['category']) && $_GET['category'] !== 'all') {
    $selected_category = sanitize_text_field($_GET['category']);
    
    // Si tienes taxonomía personalizada, usa esto:
    /*
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'insight_category',
            'field' => 'slug',
            'terms' => $selected_category
        )
    );
    */
    
    // Si usas meta fields para categorizar, usa esto:
    $args['meta_query'] = array(
        array(
            'key' => 'insight_type',
            'value' => $selected_category,
            'compare' => '='
        )
    );
}

$insights_query = new WP_Query($args);

// DEBUG - Remover en producción
if (WP_DEBUG) {
    echo '<!-- DEBUG: Found ' . $insights_query->found_posts . ' insights';
    if ($selected_category) {
        echo ' | Filter: ' . $selected_category;
    }
    echo ' -->';
}
?>

<div class="posts-grid">
    <?php 
    if ($insights_query->have_posts()) : ?>
        <?php while ($insights_query->have_posts()) : $insights_query->the_post(); ?>
            <?php get_template_part('inc/insights/templates/partials/post-card'); ?>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="no-posts">
            <?php if ($selected_category) : ?>
                <h3>No <?php echo ucfirst($selected_category); ?> insights found</h3>
                <p>Try selecting a different category or <a href="<?php echo remove_query_arg('category'); ?>">view all insights</a>.</p>
            <?php else : ?>
                <h3>No insights available yet</h3>
                <p>Check back soon for new insights!</p>
            <?php endif; ?>
            
            <?php if (WP_DEBUG) : ?>
                <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; font-size: 12px;">
                    <strong>Debug Info:</strong><br>
                    Post Type: insight<br>
                    Filter: <?php echo $selected_category ?: 'none'; ?><br>
                    Total Published Insights: <?php echo wp_count_posts('insight')->publish; ?><br>
                    Query Args: <?php echo json_encode($args); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($insights_query->max_num_pages > 1) : ?>
<div class="load-more">
    <button class="load-more-btn" data-page="<?php echo $paged; ?>">Load More Insights</button>
</div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

<!-- JavaScript Variables for AJAX -->
<script>
var doittrading_ajax = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('insights_nonce'); ?>'
};
</script>