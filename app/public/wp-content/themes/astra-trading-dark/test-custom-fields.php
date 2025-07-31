<?php
/**
 * Temporary Test File for Custom Fields
 * Add this to the end of functions.php temporarily to test loading
 */

// Test if custom fields are loading
add_action('admin_notices', function() {
    if (current_user_can('manage_options')) {
        $status = array();
        
        // Check if files exist
        $files = array(
            'Loader' => get_stylesheet_directory() . '/inc/custom-fields/custom-fields-loader.php',
            'Common' => get_stylesheet_directory() . '/inc/custom-fields/common/product-fields-common.php',
            'EAS' => get_stylesheet_directory() . '/inc/custom-fields/eas/product-fields-eas.php',
            'Reviews' => get_stylesheet_directory() . '/inc/custom-fields/common/product-reviews.php',
        );
        
        foreach ($files as $name => $file) {
            $status[] = $name . ': ' . (file_exists($file) ? 'EXISTS' : 'MISSING');
        }
        
        // Check if classes are loaded
        $classes = array(
            'DoItTrading_Custom_Fields_Loader',
            'DoItTrading_Common_Product_Fields', 
            'DoItTrading_EAS_Product_Fields',
            'DoItTrading_Product_Reviews'
        );
        
        foreach ($classes as $class) {
            $status[] = $class . ': ' . (class_exists($class) ? 'LOADED' : 'NOT FOUND');
        }
        
        echo '<div class="notice notice-info"><p><strong>Custom Fields Status:</strong><br>' . implode('<br>', $status) . '</p></div>';
    }
});