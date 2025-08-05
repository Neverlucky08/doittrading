<?php

/**
 * DoItTrading Core Functions
 * Helper functions y utilidades generales
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Get active traders count (con cache)
 * @param bool $with_variation Add hourly/random variation
 * @param int $cache_duration Cache duration in seconds
 */
function doittrading_get_active_traders($with_variation = false, $cache_duration = HOUR_IN_SECONDS) {
    $cache_key = $with_variation ? 'doittrading_active_traders_varied' : 'doittrading_active_traders';
    $cached = get_transient($cache_key);
    
    if ($cached !== false) {
        return $cached;
    }
    
    // Get base number from aggregate stats
    $stats = doittrading_get_aggregate_stats();
    $base_number = intval($stats['total_active_traders']) ?: 150;
    
    if ($with_variation) {
        // Add hourly and random variation
        $hour_variation = date('H') % 10;
        $random_variation = rand(-5, 10);
        $active_traders = $base_number + $hour_variation + $random_variation;
    } else {
        // Simple base number with minimal variation
        $active_traders = $base_number + rand(0, 5);
    }
    
    set_transient($cache_key, $active_traders, $cache_duration);
    
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
 * Check if product is Indicator
 */
function doittrading_is_indicator_new($product_id = null) {
    if (!$product_id) {
        $product_id = get_the_ID();
    }
    
    return has_term('indicators', 'product_cat', $product_id) || has_term('trading-indicators', 'product_cat', $product_id);
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
 * @param int|null $product_id - Si se proporciona, devuelve comprador específico del producto
 */
function doittrading_get_recent_buyer($product_id = null) {
    // Iniciar sesión si no está iniciada
    if (!session_id()) {
        session_start();
    }
    
    // Determinar la clave de sesión basada en si es específico del producto o global
    $session_key = $product_id ? 'doittrading_buyer_product_' . $product_id : 'doittrading_recent_buyer_global';
    
    // Verificar si ya tenemos un comprador en la sesión
    if (isset($_SESSION[$session_key])) {
        $buyer_data = $_SESSION[$session_key];
        
        // Verificar si debe rotar (después de 45 minutos)
        if (doittrading_should_rotate_buyer($buyer_data['timestamp'])) {
            // Generar nuevo comprador
            $buyer_data = doittrading_generate_new_buyer($product_id);
            $_SESSION[$session_key] = $buyer_data;
        }
        
        return $buyer_data;
    }
    
    // Generar nuevo comprador
    $buyer_data = doittrading_generate_new_buyer($product_id);
    $_SESSION[$session_key] = $buyer_data;
    
    return $buyer_data;
}

/**
 * Generar nuevo comprador
 */
function doittrading_generate_new_buyer($product_id = null) {
    $buyers = array(
        array('name' => 'Juan M.', 'country' => 'Spain'),
        array('name' => 'Michael S.', 'country' => 'UK'),
        array('name' => 'Li Wei', 'country' => 'Singapore'),
        array('name' => 'Ahmed K.', 'country' => 'UAE'),
        array('name' => 'Carlos R.', 'country' => 'Mexico'),
        array('name' => 'Thomas B.', 'country' => 'Germany'),
        array('name' => 'Anna K.', 'country' => 'Poland'),
        array('name' => 'Roberto F.', 'country' => 'Italy'),
        array('name' => 'Yuki T.', 'country' => 'Japan'),
        array('name' => 'David L.', 'country' => 'Canada'),
        array('name' => 'Sofia M.', 'country' => 'Brazil'),
        array('name' => 'Pierre D.', 'country' => 'France')
    );
    
    $buyer = $buyers[array_rand($buyers)];
    $buyer['timestamp'] = time();
    
    // Si es para un producto específico, agregar el nombre del producto
    if ($product_id) {
        $product = wc_get_product($product_id);
        if ($product) {
            $buyer['product_name'] = $product->get_name();
        }
    }
    
    return $buyer;
}

/**
 * Verificar si debe rotar el comprador (después de 45 minutos)
 */
function doittrading_should_rotate_buyer($timestamp) {
    $minutes_elapsed = (time() - $timestamp) / 60;
    return $minutes_elapsed > 45;
}

/**
 * Get recent purchase time (minutes ago) - dinámico basado en timestamp
 * @param array $buyer_data - Datos del comprador con timestamp
 */
function doittrading_get_purchase_time($buyer_data = null) {
    // Si no se proporciona buyer_data, usar el método antiguo para compatibilidad
    if (!$buyer_data || !isset($buyer_data['timestamp'])) {
        // Usar sesión para mantener consistencia
        if (!session_id()) {
            session_start();
        }
        
        // Verificar si ya tenemos un tiempo en la sesión
        if (isset($_SESSION['doittrading_purchase_time'])) {
            return $_SESSION['doittrading_purchase_time'];
        }
        
        // Generar un tiempo aleatorio entre 3 y 15 minutos
        $minutes = rand(3, 15);
        
        // Guardar en sesión
        $_SESSION['doittrading_purchase_time'] = $minutes;
        
        return $minutes;
    }
    
    // Calcular minutos reales transcurridos
    $minutes_elapsed = round((time() - $buyer_data['timestamp']) / 60);
    
    // Agregar el offset inicial (3-15 minutos) para que no empiece en 0
    $initial_offset = ($buyer_data['timestamp'] % 13) + 3; // Genera un número pseudo-aleatorio entre 3-15
    $total_minutes = $minutes_elapsed + $initial_offset;
    
    // Limitar a máximo 120 minutos (2 horas)
    if ($total_minutes > 120) {
        $total_minutes = 120;
    }
    
    return $total_minutes;
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
 * Track post views
 */
function doittrading_track_post_views($post_id) {
    if (!is_single()) return;
    
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count == '') {
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
    } else {
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

/**
 * Calculate reading time
 */
function doittrading_calculate_reading_time($content) {
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average 200 words per minute
    
    return max(1, $reading_time); // Minimum 1 minute
}

/**
 * Get insight type from post
 */
function doittrading_get_insight_type($post_id) {
    // First check custom field
    $type = get_field('insight_type', $post_id);
    if ($type) return $type;
    
    // Check taxonomies
    if (has_term('performance', 'insight_category', $post_id)) return 'performance';
    if (has_term('education', 'insight_category', $post_id)) return 'education';
    if (has_term('setup', 'insight_category', $post_id)) return 'setup';
    if (has_term('analysis', 'insight_category', $post_id)) return 'analysis';
    if (has_term('strategy', 'insight_category', $post_id)) return 'strategy';
    if (has_term('success', 'insight_category', $post_id)) return 'success';
    
    // Check tags for regular posts
    if (has_tag('performance-report', $post_id)) return 'performance';
    if (has_tag('setup-guide', $post_id)) return 'setup';
    if (has_tag('success-story', $post_id)) return 'success';
    
    // Default
    return 'education';
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
 * Get related insights
 */
function doittrading_get_related_insights($current_id, $type = '', $limit = 3) {
    $args = array(
        'post_type' => 'insight',
        'posts_per_page' => $limit,
        'post__not_in' => array($current_id),
        'orderby' => 'rand'
    );
    
    // If type specified, filter by it
    if ($type) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'insight_category',
                'field' => 'slug',
                'terms' => $type
            )
        );
    }
    
    return new WP_Query($args);
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

/**
 * Format author initials for avatar
 */
function doittrading_get_author_initials($author_name) {
    $names = explode(' ', $author_name);
    $initials = '';
    
    foreach ($names as $name) {
        $initials .= strtoupper(substr($name, 0, 1));
    }
    
    return substr($initials, 0, 2);
}

/**
 * Generate Table of Contents from content
 */
function doittrading_generate_toc_items($content) {
    $toc = array();
    
    // Match h2 and h3 tags with their IDs
    preg_match_all('/<h([2-3])(?:\s+id="([^"]+)")?[^>]*>(.*?)<\/h\1>/i', $content, $matches, PREG_SET_ORDER);
    
    if (!empty($matches)) {
        foreach ($matches as $match) {
            $level = 'h' . $match[1];
            $text = strip_tags($match[3]);
            $id = $match[2] ?: sanitize_title($text);
            
            $toc[] = array(
                'level' => $level,
                'text' => $text,
                'id' => $id
            );
        }
    }
    
    return $toc;
}

/**
 * Centralized EA product query helper
 * @param string $type Type of query: 'featured', 'hero', 'top', 'comparison', 'all'
 * @param int $limit Number of products to return
 * @param array $args Additional WP_Query arguments
 * @return WP_Query|array Returns WP_Query object or array of product data
 */
function doittrading_get_ea_products($type = 'featured', $limit = 3, $args = array()) {
    $base_args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'expert-advisors'
            )
        )
    );
    
    // Merge with custom args
    $query_args = array_merge($base_args, $args);
    
    switch ($type) {
        case 'featured':
            $query_args['meta_query'] = array(
                array(
                    'key' => 'is_featured_product',
                    'value' => '1',
                    'compare' => '='
                )
            );
            $query_args['meta_key'] = 'homepage_order';
            $query_args['orderby'] = 'meta_value_num';
            $query_args['order'] = 'ASC';
            break;
            
        case 'hero':
            $query_args['meta_query'] = array(
                array(
                    'key' => 'featured_in_forex_bots_hero',
                    'value' => '1',
                    'compare' => '='
                )
            );
            $query_args['posts_per_page'] = 1;
            break;
            
        case 'top':
            $query_args['meta_key'] = 'monthly_gain';
            $query_args['orderby'] = 'meta_value_num';
            $query_args['order'] = 'DESC';
            $query_args['posts_per_page'] = 1;
            break;
            
        case 'comparison':
            $query_args['meta_query'] = array(
                array(
                    'key' => 'show_in_comparisons',
                    'value' => '1',
                    'compare' => '='
                )
            );
            $query_args['meta_key'] = 'homepage_order';
            $query_args['orderby'] = 'meta_value_num';
            $query_args['order'] = 'ASC';
            break;
            
        case 'all':
        default:
            // Use base args as-is
            break;
    }
    
    $query = new WP_Query($query_args);
    
    // For featured type, return formatted product data array
    if ($type === 'featured' && $query->have_posts()) {
        return doittrading_format_products_array($query);
    }
    
    // For specific product IDs (fallback), also return formatted array
    if (isset($query_args['post__in']) && $query->have_posts()) {
        return doittrading_format_products_array($query);
    }
    
    return $query;
}

/**
 * Helper function to format products query into array
 */
function doittrading_format_products_array($query) {
    $products = array();
    
    while ($query->have_posts()) {
        $query->the_post();
        $product_id = get_the_ID();
        $product = wc_get_product($product_id);
        
        if ($product) {
            $products[] = array(
                'id' => $product_id,
                'name' => get_the_title(),
                'subtitle' => get_field('hero_subtitle', $product_id) ?: doittrading_get_product_subtitle($product_id),
                'description' => get_the_excerpt(),
                'monthly_gain' => get_field('monthly_gain', $product_id),
                'win_rate' => get_field('win_rate', $product_id),
                'max_drawdown' => get_field('max_drawdown', $product_id),
                'min_deposit' => get_field('minimum_deposit', $product_id),
                'profit_factor' => get_field('profit_factor', $product_id),
                'original_price' => $product->get_regular_price(),
                'current_price' => $product->get_price() ?: $product->get_regular_price(),
                'badge' => doittrading_get_product_badge($product_id),
                'badge_color' => doittrading_get_badge_color($product_id),
                'url' => get_permalink($product_id),
                'myfxbook' => get_field('myfxbook_url', $product_id),
                'image' => get_the_post_thumbnail_url($product_id, 'medium')
            );
        }
    }
    
    wp_reset_postdata();
    return $products;
}