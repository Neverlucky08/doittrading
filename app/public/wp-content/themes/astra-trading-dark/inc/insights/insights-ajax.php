<?php

// inc/insights/insights-ajax.php

// Load More Posts
add_action('wp_ajax_load_more_insights', 'doittrading_load_more_insights');
add_action('wp_ajax_nopriv_load_more_insights', 'doittrading_load_more_insights');

function doittrading_load_more_insights() {
    check_ajax_referer('insights_nonce', 'nonce');
    
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    
    $args = array(
        'post_type' => 'post', // O 'insight' si usas CPT
        'category_name' => 'trading-insights',
        'posts_per_page' => 9,
        'paged' => $page
    );
    
    if ($category) {
        $args['tag'] = $category; // O taxonomy query si usas CPT
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('inc/insights/templates/partials/post-card');
        endwhile;
    endif;
    $html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html,
        'max_pages' => $query->max_num_pages
    ));
}

?>