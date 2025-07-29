<?php

/**
 * DoItTrading Core Functions
 * Helper functions y utilidades generales
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Get active traders count (con cache)
 */
function doittrading_get_active_traders() {
    $cached = get_transient('doittrading_active_traders');
    
    if ($cached !== false) {
        return $cached;
    }
    
    // En producción, esto vendría de una API real
    // Por ahora, número aleatorio consistente por hora
    $base_number = 12;
    $hour_variation = date('H') % 5;
    $active_traders = $base_number + $hour_variation + rand(0, 5);
    
    set_transient('doittrading_active_traders', $active_traders, HOUR_IN_SECONDS);
    
    return $active_traders;
}

/**
 * Get last trade info (con cache)
 */
function doittrading_get_last_trade() {
    $cached = get_transient('doittrading_last_trade');
    
    if ($cached !== false) {
        return $cached;
    }
    
    // En producción, esto vendría de MyFxBook API
    $trade_data = array(
        'pips' => rand(15, 65),
        'time_ago' => rand(2, 15),
        'direction' => rand(0, 1) ? 'profit' : 'loss'
    );
    
    set_transient('doittrading_last_trade', $trade_data, 5 * MINUTE_IN_SECONDS);
    
    return $trade_data;
}

function doittrading_get_start_year() {
    return 2021;
}

/**
 * Format price with currency
 */
function doittrading_format_price($price) {
    return '$' . number_format($price, 0);
}

/**
 * Check if product is EA
 */
function doittrading_is_ea($product_id = null) {
    if (!$product_id) {
        $product_id = get_the_ID();
    }
    
    return has_term('expert-advisors', 'product_cat', $product_id);
}

/**
 * Get time until price increase
 */
function doittrading_get_countdown_target() {
    // Siempre 2 días desde ahora para crear urgencia
    return date('Y-m-d H:i:s', strtotime('+2 days'));
}

/**
 * Get remaining stock (con lógica)
 */
function doittrading_get_remaining_stock() {
    // Lógica: empieza con 20, reduce 1 cada 3 días
    $launch_date = strtotime('2025-01-01');
    $days_since_launch = floor((time() - $launch_date) / (3 * DAY_IN_SECONDS));
    $remaining = max(5, 20 - $days_since_launch); // Mínimo 5
    
    return $remaining;
}

/**
 * Get random recent buyer
 */
function doittrading_get_recent_buyer() {
    $buyers = array(
        array('name' => 'Juan M.', 'country' => 'Spain'),
        array('name' => 'Michael S.', 'country' => 'UK'),
        array('name' => 'Li Wei', 'country' => 'Singapore'),
        array('name' => 'Ahmed K.', 'country' => 'UAE'),
        array('name' => 'Carlos R.', 'country' => 'Mexico'),
        array('name' => 'Thomas B.', 'country' => 'Germany')
    );
    
    return $buyers[array_rand($buyers)];
}

/**
 * Log para debugging (solo si WP_DEBUG activo)
 */
function doittrading_log($message, $data = null) {
    if (!WP_DEBUG) return;
    
    error_log('[DoItTrading] ' . $message);
    if ($data) {
        error_log(print_r($data, true));
    }
}

/**
 * Get product safely
 */
function doittrading_get_product($product_id = null) {
    global $product;
    
    if (!$product_id) {
        $product_id = get_the_ID();
    }
    
    if (empty($product) || !is_a($product, 'WC_Product')) {
        $product = wc_get_product($product_id);
    }
    
    return $product;
}

/**
 * Get product price safely (handles external products)
 */
function doittrading_get_product_price($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product || !is_a($product, 'WC_Product')) {
        return 0;
    }
    
    // Try different methods
    $price = $product->get_price();
    
    if (!$price) {
        $price = $product->get_regular_price();
    }
    
    if (!$price && $product->is_type('external')) {
        // For external products, check meta
        $price = get_post_meta($product->get_id(), '_regular_price', true);
    }
    
    return floatval($price);
}


/**
 * Detect mentioned EA in content
 */
function doittrading_detect_mentioned_ea($content) {
    // EA names to detect
    $eas = array(
        19 => array('GBP Master', 'DoIt GBP Master', 'GBPMaster'),
        30 => array('Gold Guardian', 'DoIt Gold Guardian', 'GoldGuardian'),
        36 => array('Index Vanguard', 'DoIt Index Vanguard', 'IndexVanguard')
    );
    
    foreach ($eas as $ea_id => $names) {
        foreach ($names as $name) {
            if (stripos($content, $name) !== false) {
                return $ea_id;
            }
        }
    }
    
    return null;
}

/**
 * Newsletter subscription handler
 */
add_action('wp_ajax_doittrading_subscribe', 'doittrading_handle_newsletter');
add_action('wp_ajax_nopriv_doittrading_subscribe', 'doittrading_handle_newsletter');

function doittrading_handle_newsletter() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['newsletter_nonce'], 'newsletter_subscribe')) {
        wp_send_json_error('Invalid request');
    }
    
    $email = sanitize_email($_POST['email']);
    
    if (!is_email($email)) {
        wp_send_json_error('Invalid email address');
    }
    
    // Here you would integrate with your email service
    // For now, just save to database
    $subscribers = get_option('doittrading_subscribers', array());
    
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('doittrading_subscribers', $subscribers);
        
        // Send welcome email (implement based on your setup)
        // wp_mail($email, 'Welcome to DoItTrading Insights', '...');
        
        wp_send_json_success('Successfully subscribed!');
    } else {
        wp_send_json_error('Already subscribed');
    }
}

