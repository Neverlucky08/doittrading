<?php
/**
 * DoItTrading Theme Functions
 * 
 * @package DoItTrading
 * @since 1.0
 */

// Cargar estilos
add_action('wp_enqueue_scripts', 'doittrading_enqueue_styles');
function doittrading_enqueue_styles() {
    // Estilo del parent theme (Astra)
    wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css');
    
    // Estilo de DoItTrading
    wp_enqueue_style('doittrading-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'));
    
    // CSS principal de DoItTrading
    wp_enqueue_style('doittrading-main', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), '1.0');
}

// Configuración básica del theme
add_action('after_setup_theme', 'doittrading_theme_setup');
function doittrading_theme_setup() {
    // Soporte para WooCommerce
    add_theme_support('woocommerce');
}