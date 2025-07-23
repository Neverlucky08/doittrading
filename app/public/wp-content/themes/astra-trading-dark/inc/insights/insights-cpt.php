<?php

function doittrading_register_insights_cpt() {
    register_post_type('insight', array(
        'labels' => array(
            'name' => 'Trading Insights',
            'singular_name' => 'Insight',
            'add_new' => 'Add New Insight',
            'add_new_item' => 'Add New Insight',
            'edit_item' => 'Edit Insight',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'insights'),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-chart-line',
        'show_in_rest' => true, // Gutenberg support
    ));
}

// Taxonomía para categorías
function doittrading_register_insights_taxonomies() {
    register_taxonomy('insight_category', 'insight', array(
        'labels' => array(
            'name' => 'Insight Categories',
            'singular_name' => 'Category',
        ),
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'insight-category'),
    ));
}

?>