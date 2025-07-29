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
require_once get_stylesheet_directory() . '/inc/insights/insights-content-helpers.php';

// Registrar CPT y taxonomías
add_action('init', 'doittrading_register_insights_cpt');
add_action('init', 'doittrading_register_insights_taxonomies');

/**
 * Add theme support for insights
 */
add_theme_support('post-thumbnails', array('post', 'page', 'product', 'insight'));

// 10. Helpers for Product date linking
require_once get_stylesheet_directory() . '/inc/helpers/data-helpers.php';

// Load AJAX handlers
require_once get_stylesheet_directory() . '/inc/insights/insights-ajax.php';

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

    if (is_singular('insight')) {

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

// Enqueue scripts and styles for insights
add_action('wp_enqueue_scripts', 'doittrading_enqueue_insight_assets');
function doittrading_enqueue_insight_assets() {
    if (is_singular('insight') || is_singular('post')) {
        // Check if it's an insight-type post
        $post_id = get_the_ID();
        $categories = wp_get_post_categories($post_id, array('fields' => 'slugs'));
        
        if (get_post_type() === 'insight' || 
            in_array('trading-insights', $categories) || 
            has_tag(array('performance-report', 'setup-guide', 'success-story'))) {
            
            // Enqueue single insight JS
            wp_enqueue_script(
                'doittrading-single-insight',
                get_stylesheet_directory_uri() . '/assets/js/single-insight.js',
                array('jquery'),
                '1.0.0',
                true
            );
        }
    }
    
    // For insights archive page
    if (is_page('insights') || is_post_type_archive('insight')) {
        wp_enqueue_script(
            'doittrading-insights',
            get_stylesheet_directory_uri() . '/assets/js/insights.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('doittrading-insights', 'doittrading_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('insights_nonce')
        ));
    }
}

/**
 * Filter to enhance insight content
 */
add_filter('the_content', 'doittrading_maybe_enhance_insight_content', 20);
function doittrading_maybe_enhance_insight_content($content) {
    // Only on single insight pages
    if (!is_singular() || get_post_type() !== 'insight') {
        // Also check if it's a regular post with insight category
        if (get_post_type() === 'post') {
            $categories = wp_get_post_categories(get_the_ID(), array('fields' => 'slugs'));
            if (!in_array('trading-insights', $categories)) {
                return $content;
            }
        } else {
            return $content;
        }
    }
    
    return doittrading_enhance_insight_content($content);
}

/**
 * Add custom body class for insights
 */
add_filter('body_class', 'doittrading_insight_body_class');
function doittrading_insight_body_class($classes) {
    if (is_singular('insight') || 
        (is_singular('post') && has_category('trading-insights'))) {
        $classes[] = 'single-insight';
        
        // Add insight type class
        $insight_type = doittrading_get_insight_type(get_the_ID());
        $classes[] = 'insight-type-' . $insight_type;
    }
    
    if (is_page('insights') || is_post_type_archive('insight')) {
        $classes[] = 'insights-archive';
    }
    
    return $classes;
}

/**
 * Template redirect for insights
 */
add_filter('template_include', 'doittrading_insight_template');
function doittrading_insight_template($template) {
    // Single insight post
    if (is_singular('insight')) {
        $new_template = locate_template(array('single-insight.php'));
        if ($new_template) {
            return $new_template;
        }
    }
    
    // Archive page
    if (is_post_type_archive('insight')) {
        $new_template = locate_template(array('archive-insight.php'));
        if ($new_template) {
            return $new_template;
        }
    }
    
    return $template;
}

/**
 * Add Open Graph tags for insights
 */
add_action('wp_head', 'doittrading_insight_open_graph');
function doittrading_insight_open_graph() {
    if (!is_singular('insight') && !is_singular('post')) return;
    
    global $post;
    
    // Check if it's an insight-type post
    $is_insight = false;
    if (get_post_type() === 'insight') {
        $is_insight = true;
    } elseif (has_category('trading-insights') || has_tag(array('performance-report', 'setup-guide'))) {
        $is_insight = true;
    }
    
    if (!$is_insight) return;
    
    $title = get_the_title();
    $description = get_the_excerpt() ?: wp_trim_words(get_the_content(), 30);
    $url = get_permalink();
    $image = get_the_post_thumbnail_url($post->ID, 'large');
    
    ?>
    <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($description); ?>" />
    <meta property="og:url" content="<?php echo esc_url($url); ?>" />
    <meta property="og:type" content="article" />
    <?php if ($image): ?>
    <meta property="og:image" content="<?php echo esc_url($image); ?>" />
    <?php endif; ?>
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>" />
    <?php if ($image): ?>
    <meta name="twitter:image" content="<?php echo esc_url($image); ?>" />
    <?php endif;
}

/**
 * Schema markup for insights
 */
add_action('wp_head', 'doittrading_insight_schema');
function doittrading_insight_schema() {
    if (!is_singular('insight') && !is_singular('post')) return;
    
    $schema = doittrading_add_insight_schema(get_the_ID());
    if ($schema) {
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
}