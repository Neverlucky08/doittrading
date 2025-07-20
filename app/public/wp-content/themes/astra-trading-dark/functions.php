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
add_action('wp_enqueue_scripts', 'doittrading_enqueue_assets', 20);
function doittrading_enqueue_assets() {
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
}

/**
 * Include modular functionality
 * IMPORTANTE: El orden importa - core-functions.php debe ir primero
 */

// 1. Core functions y helpers (DEBE IR PRIMERO)
require_once get_stylesheet_directory() . '/inc/core-functions.php';

// 2. WooCommerce modifications
require_once get_stylesheet_directory() . '/inc/woocommerce-mods.php';

// 3. Product display features
require_once get_stylesheet_directory() . '/inc/product-display.php';

// 4. Social proof elements
require_once get_stylesheet_directory() . '/inc/social-proof.php';

// 5. Marketing features (DEBE IR AL FINAL porque usa funciones de core)
require_once get_stylesheet_directory() . '/inc/marketing-features.php';

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

