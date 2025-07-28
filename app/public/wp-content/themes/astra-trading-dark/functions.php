<?php
/**
 * DoItTrading Theme Functions
 * 
 * @package DoItTrading
 * @version 2.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Theme Setup
 */
add_action('after_setup_theme', 'doittrading_theme_setup');
function doittrading_theme_setup() {
    // Soporte para WooCommerce
    add_theme_support('woocommerce');
    
    // Soporte para imágenes destacadas
    add_theme_support('post-thumbnails');
    
    // Título dinámico
    add_theme_support('title-tag');
}

/**
 * Enqueue Styles & Scripts
 */
add_action('wp_enqueue_scripts', 'doittrading_enqueue_styles', 20);
function doittrading_enqueue_styles() {
    // Parent theme style
    wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css');
    
    // Child theme style
    wp_enqueue_style('doittrading-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'), '2.0');
    
    // Main CSS (con todos los @imports)
    wp_enqueue_style('doittrading-main', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), '2.0');
    
    // JavaScript para features dinámicos
    if (is_product()) {
        wp_enqueue_script('doittrading-product', get_stylesheet_directory_uri() . '/assets/js/product-features.js', array('jquery'), '2.0', true);
        
        wp_enqueue_script('doittrading-product-details', get_stylesheet_directory_uri() . '/assets/js/product-details.js', array('jquery'), '2.0', true);
        
        // Pasar datos PHP a JavaScript
        wp_localize_script('doittrading-product', 'doittrading_vars', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'product_id' => get_the_ID(),
            'nonce' => wp_create_nonce('doittrading_nonce')
        ));
    }

    if (is_front_page()) {
        wp_enqueue_style('doittrading-homepage', get_stylesheet_directory_uri() . '/assets/css/homepage.css', array('doittrading-main'), '1.0');
        wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true);
    }

    if (is_page('indicators')) {
            wp_enqueue_style('indicators-style', get_stylesheet_directory_uri() . '/assets/css/indicators.css');
    }
}

/**
 * Include modular functionality
 * IMPORTANTE: El orden importa - core-functions.php debe ir primero
 */

// 1. Core functions y helpers (DEBE IR PRIMERO)
require_once get_stylesheet_directory() . '/inc/core-functions.php';

// 2. WooCommerce modifications
require_once get_stylesheet_directory() . '/inc/products/woocommerce-mods.php';

// 3. Product display features
require_once get_stylesheet_directory() . '/inc/products/product-display.php';

// 4. Social proof elements
require_once get_stylesheet_directory() . '/inc/products/social-proof.php';

// 5. Marketing features (DEBE IR AL FINAL porque usa funciones de core)
require_once get_stylesheet_directory() . '/inc/products/marketing-features.php';

// 6. Homepage sections 
require_once get_stylesheet_directory() . '/inc/homepage/homepage-sections.php';

// 7. Forex Bots Page
require_once get_stylesheet_directory() . '/inc/pages/forex-bots-page.php';

// 8. Indicators page sections
require_once get_stylesheet_directory() . '/inc/pages/indicators-page.php';

// 9. Trading insights
require_once get_stylesheet_directory() . '/inc/insights/insights-cpt.php';
require_once get_stylesheet_directory() . '/inc/insights/insights-functions.php';

// Registrar CPT y taxonomías
add_action('init', 'doittrading_register_insights_cpt');
add_action('init', 'doittrading_register_insights_taxonomies');

// 10. Helpers for Product date linking
require_once get_stylesheet_directory() . '/inc/helpers/data-helpers.php';

/**
 * Debug helper (solo en desarrollo)
 */
if (WP_DEBUG) {
    add_action('wp_footer', function() {
        echo '<!-- DoItTrading Theme v2.0 - Debug Mode -->';
        echo '<!-- Active Template: ' . (is_product() ? 'Product Single' : 'Other') . ' -->';
    });
}

/**
 * Theme activation check
 */
add_action('admin_notices', 'doittrading_check_dependencies');
function doittrading_check_dependencies() {
    // Verificar que Astra esté activo
    $theme = wp_get_theme();
    if ($theme->get('Template') !== 'astra') {
        echo '<div class="notice notice-error"><p>DoItTrading Theme requires Astra theme to be installed.</p></div>';
    }
    
    // Verificar WooCommerce
    if (!class_exists('WooCommerce')) {
        echo '<div class="notice notice-warning"><p>DoItTrading Theme works best with WooCommerce activated.</p></div>';
    }
    
    // Verificar ACF
    if (!class_exists('ACF')) {
        echo '<div class="notice notice-warning"><p>DoItTrading Theme requires Advanced Custom Fields for full functionality.</p></div>';
    }
}

function doittrading_page_body_class($classes) {
    if (is_front_page()) {
        $classes[] = 'page-home-style';
    }
    if (is_page('indicators')) {
        $classes[] = 'page-indicators-style';
    }
    return $classes;
}
add_filter('body_class', 'doittrading_page_body_class');

function doittrading_enqueue_insights_assets() {
    if (is_page('insights') || is_singular('insight')) {
        // CSS
        wp_enqueue_style(
            'doittrading-insights', 
            get_stylesheet_directory_uri() . '/assets/css/insights.css', 
            array('doittrading-main'), 
            '1.0'
        );
        
        // JavaScript
        wp_enqueue_script(
            'doittrading-insights', 
            get_stylesheet_directory_uri() . '/assets/js/insights.js', 
            array('jquery'), 
            '1.0', 
            true
        );
        
        // Localize para AJAX
        wp_localize_script('doittrading-insights', 'doittrading_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('insights_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'doittrading_enqueue_insights_assets');

/**
 * Add this to your functions.php file in the doittrading_enqueue_scripts function
 */

// Update the existing function to include single insight assets
function doittrading_enqueue_single_insight_assets() {
    echo('IN---------------------------------------------------------------');
    $post_type = get_post_type();
    echo 'Estoy en un post type: ' . $post_type . '<br>';
    $isPost0 = is_singular();
    $isPost = is_singular('insight');
    $isPost2 = is_post_type_archive('insight');
    echo '$isPost0: ' . $isPost0 . ' /$isPost: ' . $isPost . ' /$isPost2: ' . $isPost2;
    if (is_singular('insight')) {
        echo('IN2---------------------------------------------------------------');

        // CSS for single insight
        wp_enqueue_style(
            'doittrading-single-insight', 
            get_stylesheet_directory_uri() . '/assets/css/single-insight.css', 
            array('doittrading-main'), 
            '1.0'
        );
        
        // JavaScript for single insight
        wp_enqueue_script(
            'doittrading-single-insight', 
            get_stylesheet_directory_uri() . '/assets/js/single-insight.js', 
            array('jquery'), 
            '1.0', 
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('doittrading-single-insight', 'doittrading_single', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'post_id' => get_the_ID(),
            'nonce' => wp_create_nonce('single_insight_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'doittrading_enqueue_single_insight_assets', 25);
