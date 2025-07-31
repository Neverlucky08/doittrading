<?php
/**
 * Debug Helper for Custom Fields
 * Temporary file to help troubleshoot custom fields loading
 */

if (!defined('ABSPATH')) {
    exit;
}

// Add debug information to admin footer
add_action('admin_footer', function() {
    global $post;
    
    if (!$post || $post->post_type !== 'product') {
        return;
    }
    
    echo '<div id="doittrading-debug" style="position: fixed; bottom: 10px; right: 10px; background: #fff; border: 1px solid #ccc; padding: 10px; font-size: 12px; z-index: 9999; max-width: 300px;">';
    echo '<strong>DoItTrading Debug Info:</strong><br>';
    echo 'Product ID: ' . $post->ID . '<br>';
    echo 'Post Type: ' . $post->post_type . '<br>';
    
    // Check if classes exist
    echo 'Common Fields Class: ' . (class_exists('DoItTrading_Common_Product_Fields') ? 'Loaded' : 'NOT LOADED') . '<br>';
    echo 'EAS Fields Class: ' . (class_exists('DoItTrading_EAS_Product_Fields') ? 'Loaded' : 'NOT LOADED') . '<br>';
    echo 'Reviews Class: ' . (class_exists('DoItTrading_Product_Reviews') ? 'Loaded' : 'NOT LOADED') . '<br>';
    
    // Check product categories
    $terms = wp_get_post_terms($post->ID, 'product_cat', array('fields' => 'slugs'));
    echo 'Product Categories: ' . implode(', ', $terms) . '<br>';
    
    // Check if meta boxes are registered
    global $wp_meta_boxes;
    $product_meta_boxes = isset($wp_meta_boxes['product']['normal']) ? array_keys($wp_meta_boxes['product']['normal']) : array();
    echo 'Registered Meta Boxes: ' . implode(', ', $product_meta_boxes) . '<br>';
    
    echo '<button onclick="this.parentElement.style.display=\'none\'">Close</button>';
    echo '</div>';
});

// Log when classes are instantiated
add_action('init', function() {
    error_log('DoItTrading Custom Fields: init action fired');
    
    if (class_exists('DoItTrading_Common_Product_Fields')) {
        error_log('DoItTrading Custom Fields: Common fields class exists');
    } else {
        error_log('DoItTrading Custom Fields: Common fields class NOT found');
    }
});

// Debug meta box registration
add_action('add_meta_boxes', function() {
    error_log('DoItTrading Custom Fields: add_meta_boxes action fired');
}, 1);