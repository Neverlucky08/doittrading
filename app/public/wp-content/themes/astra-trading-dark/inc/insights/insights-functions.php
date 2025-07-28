<?php
// inc/insights/insights-functions.php

// Get live trading data
function doittrading_get_live_trading_data() {
    // Cache for 5 minutes
    $cached = get_transient('insights_live_data');
    if ($cached) return $cached;
    
    // In production, get from MyFxBook API
    $data = array(
        'gbp_master' => array(
            'pips' => rand(15, 45),
            'direction' => rand(0, 1) ? '+' : '-'
        ),
        'gold_guardian' => array(
            'pips' => rand(10, 35),
            'direction' => '+'
        ),
        'index_vanguard' => array(
            'pips' => rand(5, 20),
            'direction' => rand(0, 1) ? '+' : '-'
        )
    );
    
    set_transient('insights_live_data', $data, 5 * MINUTE_IN_SECONDS);
    return $data;
}

// Get featured insights
function doittrading_get_featured_insights() {
    return new WP_Query(array(
        'post_type' => 'insight',
        'posts_per_page' => 3,
        'meta_query' => array(
            array(
                'key' => 'is_featured',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    ));
}

// Get trending insights
function doittrading_get_trending_insights($limit = 5) {
    return new WP_Query(array(
        'post_type' => 'insight',
        'posts_per_page' => $limit,
        'meta_key' => 'post_views_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    ));
}

?>