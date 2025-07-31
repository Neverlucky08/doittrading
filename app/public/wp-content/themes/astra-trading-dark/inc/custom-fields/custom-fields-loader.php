<?php
/**
 * Custom Fields Loader
 * Initializes all custom field modules for the DoItTrading theme
 * 
 * This replaces the ACF (Advanced Custom Fields) plugin dependency
 * with native WordPress custom fields implementation.
 */

if (!defined('ABSPATH')) {
    exit;
}

class DoItTrading_Custom_Fields_Loader {
    
    public function __construct() {
        add_action('init', array($this, 'load_custom_fields'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }
    
    /**
     * Load all custom field modules
     */
    public function load_custom_fields() {
        // Load migration utility if ACF is present
        $this->load_migration_utility();
        
        // Load common/universal custom fields
        $this->load_common_fields();
        
        // Load product-specific fields based on product categories
        $this->load_product_specific_fields();
        
        // Load universal functionality
        $this->load_universal_modules();
    }
    
    /**
     * Load common custom fields that apply to all products
     */
    private function load_common_fields() {
        $common_path = get_stylesheet_directory() . '/inc/custom-fields/common/';
        
        if (file_exists($common_path . 'product-fields-common.php')) {
            require_once $common_path . 'product-fields-common.php';
        }
    }
    
    /**
     * Load product-specific custom fields
     */
    private function load_product_specific_fields() {
        $fields_path = get_stylesheet_directory() . '/inc/custom-fields/';
        
        // Load EAS (Expert Advisors) specific fields
        if (file_exists($fields_path . 'eas/product-fields-eas.php')) {
            require_once $fields_path . 'eas/product-fields-eas.php';
        }
        
        // Load Indicators specific fields
        if (file_exists($fields_path . 'indicators/product-fields-indicators.php')) {
            require_once $fields_path . 'indicators/product-fields-indicators.php';
        }
    }
    
    /**
     * Load migration utility if ACF is present
     */
    private function load_migration_utility() {
        $migration_file = get_stylesheet_directory() . '/inc/custom-fields/acf-migration-utility.php';
        
        if (file_exists($migration_file)) {
            require_once $migration_file;
        }
        
        // Load debug helper in development
        if (WP_DEBUG) {
            $debug_file = get_stylesheet_directory() . '/inc/custom-fields/debug-helper.php';
            if (file_exists($debug_file)) {
                require_once $debug_file;
            }
        }
    }
    
    /**
     * Load universal modules that work across all product types
     */
    private function load_universal_modules() {
        $common_path = get_stylesheet_directory() . '/inc/custom-fields/common/';
        
        // Load reviews functionality
        if (file_exists($common_path . 'product-reviews.php')) {
            require_once $common_path . 'product-reviews.php';
        }
    }
    
    /**
     * Enqueue admin styles for custom fields
     */
    public function enqueue_admin_styles($hook) {
        global $post_type;
        
        if ($hook == 'post.php' || $hook == 'post-new.php') {
            if ($post_type == 'product') {
                wp_enqueue_style(
                    'doittrading-custom-fields-admin',
                    get_stylesheet_directory_uri() . '/assets/css/admin-custom-fields.css',
                    array(),
                    '1.0.0'
                );
                
                wp_enqueue_script('jquery');
            }
        }
    }
    
    /**
     * Get field value with fallback
     * 
     * @param int $post_id
     * @param string $field_name
     * @param mixed $default
     * @return mixed
     */
    public static function get_field($post_id, $field_name, $default = '') {
        $value = get_post_meta($post_id, $field_name, true);
        return !empty($value) ? $value : $default;
    }
    
    /**
     * Check if product is in specific category
     * 
     * @param int $post_id
     * @param string $category_slug
     * @return bool
     */
    public static function is_product_in_category($post_id, $category_slug) {
        $terms = wp_get_post_terms($post_id, 'product_cat', array('fields' => 'slugs'));
        return in_array($category_slug, $terms);
    }
    
    /**
     * Get product category type for conditional field loading
     * 
     * @param int $post_id
     * @return string
     */
    public static function get_product_type($post_id) {
        $terms = wp_get_post_terms($post_id, 'product_cat', array('fields' => 'slugs'));
        
        if (in_array('expert-advisors', $terms)) {
            return 'eas';
        } elseif (in_array('indicators', $terms) || in_array('trading-indicators', $terms)) {
            return 'indicators';
        }
        
        return 'general';
    }
    
    /**
     * Compatibility function for ACF migration
     * This function helps transition from ACF to native custom fields
     * 
     * @param string $field_name
     * @param int $post_id
     * @return mixed
     */
    public static function get_acf_compatible_field($field_name, $post_id = null) {
        if (!$post_id) {
            global $post;
            $post_id = $post ? $post->ID : 0;
        }
        
        return self::get_field($post_id, $field_name);
    }
}

// Initialize the custom fields loader
new DoItTrading_Custom_Fields_Loader();

/**
 * Helper functions for template usage
 */

/**
 * Get field value (template helper)
 * 
 * @param string $field_name
 * @param int $post_id
 * @param mixed $default
 * @return mixed
 */
function doittrading_get_field($field_name, $post_id = null, $default = '') {
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }
    
    return DoItTrading_Custom_Fields_Loader::get_field($post_id, $field_name, $default);
}

/**
 * Check if product is Expert Advisor
 * 
 * @param int $post_id
 * @return bool
 */
function doittrading_is_expert_advisor($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }
    
    return DoItTrading_Custom_Fields_Loader::is_product_in_category($post_id, 'expert-advisors');
}

/**
 * Check if product is Indicator
 * 
 * @param int $post_id
 * @return bool
 */
function doittrading_is_indicator($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }
    
    return DoItTrading_Custom_Fields_Loader::is_product_in_category($post_id, 'indicators') || 
           DoItTrading_Custom_Fields_Loader::is_product_in_category($post_id, 'trading-indicators');
}

/**
 * Get product reviews
 * 
 * @param int $post_id
 * @return array
 */
function doittrading_get_product_reviews($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }
    
    if (class_exists('DoItTrading_Product_Reviews')) {
        return DoItTrading_Product_Reviews::get_all_reviews($post_id);
    }
    
    return array();
}

/**
 * Render star rating HTML
 * 
 * @param int $rating
 * @param string $class
 * @return string
 */
function doittrading_render_stars($rating, $class = '') {
    if (class_exists('DoItTrading_Product_Reviews')) {
        return DoItTrading_Product_Reviews::render_stars_html($rating, $class);
    }
    
    return '';
}

/**
 * Get product benefits (for indicators)
 * 
 * @param int $post_id
 * @return array
 */
function doittrading_get_product_benefits($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }

    if (class_exists('DoItTrading_Indicators_Product_Fields')) {
        return DoItTrading_Indicators_Product_Fields::get_benefits($post_id);
    }
    
    return array();
}

/**
 * Get product visual showcase (for indicators)
 * 
 * @param int $post_id
 * @return array
 */
function doittrading_get_product_visuals($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }
    
    if (class_exists('DoItTrading_Indicators_Product_Fields')) {
        return DoItTrading_Indicators_Product_Fields::get_visuals($post_id);
    }
    
    return array();
}

/**
 * Get product key features (from common fields)
 * 
 * @param int $post_id
 * @return array
 */
function doittrading_get_key_features($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }
    
    $features = array();
    
    for ($i = 1; $i <= 4; $i++) {
        $feature = get_post_meta($post_id, 'key_feature_' . $i, true);
        if (!empty($feature)) {
            $features[] = $feature;
        }
    }
    
    return $features;
}

/**
 * Get product stats (for indicators tool-stats-card)
 * 
 * @param int $post_id
 * @return array
 */
function doittrading_get_product_stats($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }
    
    if (class_exists('DoItTrading_Indicators_Product_Fields')) {
        return DoItTrading_Indicators_Product_Fields::get_stats($post_id);
    }
    
    return array();
}

/**
 * ACF Compatibility Layer
 * These functions provide backward compatibility for templates still using ACF functions
 */

if (!function_exists('get_field')) {
    /**
     * ACF get_field compatibility function
     * 
     * @param string $field_name
     * @param int $post_id
     * @return mixed
     */
    function get_field($field_name, $post_id = null) {
        return doittrading_get_field($field_name, $post_id);
    }
}

if (!function_exists('the_field')) {
    /**
     * ACF the_field compatibility function
     * 
     * @param string $field_name
     * @param int $post_id
     */
    function the_field($field_name, $post_id = null) {
        echo doittrading_get_field($field_name, $post_id);
    }
}