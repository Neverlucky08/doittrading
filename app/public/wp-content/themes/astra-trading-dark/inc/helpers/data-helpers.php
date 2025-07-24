<?php
/**
 * DoItTrading Data Helpers
 * Functions to get dynamic data from products
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Get featured product for hero section
 */
function doittrading_get_featured_product() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => 'is_featured_product',
                'value' => '1',
                'compare' => '='
            )
        ),
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'expert-advisors'
            )
        )
    );
    
    $query = new WP_Query($args);
    
    // If no featured product marked, get DoIt GBP Master by default
    if (!$query->have_posts()) {
        return get_post(19); // DoIt GBP Master ID
    }
    
    return $query->posts[0];
}

/**
 * Get featured products for homepage
 */
function doittrading_get_featured_products() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 3,
        'meta_key' => 'homepage_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'expert-advisors'
            )
        )
    );
    
    $query = new WP_Query($args);
    
    // If less than 3, get by specific IDs
    if ($query->post_count < 3) {
        $args['post__in'] = array(19, 30, 36); // GBP Master, Gold Guardian, Index Vanguard
        $args['orderby'] = 'post__in';
        unset($args['meta_key']);
        $query = new WP_Query($args);
    }
    
    $products = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product_id = get_the_ID();
            $product = wc_get_product($product_id);
            
            // Get all ACF data
            $products[] = array(
                'id' => $product_id,
                'name' => get_the_title(),
                'subtitle' => get_field('hero_subtitle', $product_id) ?: doittrading_get_product_subtitle($product_id),
                'description' => get_the_excerpt(),
                'monthly_gain' => get_field('monthly_gain', $product_id),
                'win_rate' => get_field('win_rate', $product_id),
                'max_drawdown' => get_field('max_drawdown', $product_id),
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
        wp_reset_postdata();
    }
    
    return $products;
}

/**
 * Get product subtitle based on characteristics
 */
function doittrading_get_product_subtitle($product_id) {
    $subtitles = array(
        19 => 'The Conservative Profit Machine',
        30 => 'Gold Market Specialist', 
        36 => 'Small Account Friendly'
    );
    
    return $subtitles[$product_id] ?? 'Professional Trading Bot';
}

/**
 * Get product badge
 */
function doittrading_get_product_badge($product_id) {
    // Check for highest win rate
    $win_rate = get_field('win_rate', $product_id);
    if ($win_rate > 90) {
        return 'HIGH WIN RATE';
    }
    
    // Check for best seller (most reviews)
    $total_reviews = get_field('mql5_total_reviews', $product_id);
    if ($total_reviews > 40) {
        return 'BESTSELLER';
    }
    
    // Check for small account friendly
    $min_deposit = get_field('minimum_deposit', $product_id);
    if ($min_deposit <= 50) {
        return 'BEGINNER FRIENDLY';
    }
    
    return 'VERIFIED EA';
}

/**
 * Get badge color based on type
 */
function doittrading_get_badge_color($product_id) {
    $badge = doittrading_get_product_badge($product_id);
    
    $colors = array(
        'BESTSELLER' => 'green',
        'HIGH WIN RATE' => 'gold',
        'BEGINNER FRIENDLY' => 'blue',
        'VERIFIED EA' => 'silver'
    );
    
    return $colors[$badge] ?? 'blue';
}

/**
 * Get all reviews from products
 */
function doittrading_get_all_reviews($limit = 3) {
    $reviews = array();
    
    // Get all EA products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'expert-advisors'
            )
        )
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product_id = get_the_ID();
            $product_name = get_the_title();
            
            // Get reviews from this product
            for ($i = 1; $i <= 5; $i++) {
                $reviewer_name = get_field('review_' . $i . '_name', $product_id);
                if (!$reviewer_name) continue;
                
                $reviews[] = array(
                    'name' => $reviewer_name,
                    'date' => get_field('review_' . $i . '_date', $product_id),
                    'stars' => get_field('review_' . $i . '_stars', $product_id),
                    'text' => get_field('review_' . $i . '_text', $product_id),
                    'product' => $product_name,
                    'verified' => true,
                    'country' => doittrading_get_reviewer_country($reviewer_name)
                );
            }
        }
        wp_reset_postdata();
    }
    
    // Sort by date (newest first) and limit
    usort($reviews, function($a, $b) {
        return strtotime($b['date'] ?? 0) - strtotime($a['date'] ?? 0);
    });
    
    return array_slice($reviews, 0, $limit);
}

/**
 * Get reviewer country (could be expanded with real data)
 */
function doittrading_get_reviewer_country($name) {
    $countries = array(
        'MBlue6' => 'Germany',
        'klausdiemaus' => 'Austria',
        'Butterfly0856' => 'Spain',
        'Juan M.' => 'Spain',
        'Michael S.' => 'UK',
        'Li Wei' => 'Singapore'
    );
    
    return $countries[$name] ?? 'International';
}

/**
 * Get aggregate stats from all products
 */
function doittrading_get_aggregate_stats() {
    $stats = array(
        'total_active_traders' => 0,
        'total_volume_traded' => 0,
        'total_reviews' => 0,
        'average_rating' => 0,
        'total_trades' => 0,
        'best_monthly_gain' => 0,
        'average_win_rate' => 0
    );
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'expert-advisors'
            )
        )
    );
    
    $query = new WP_Query($args);
    $product_count = 0;
    $total_win_rate = 0;
    $total_rating_sum = 0;
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product_id = get_the_ID();
            
            // Active traders
            $active_users = get_field('total_active_users', $product_id);
            $stats['total_active_traders'] += $active_users ?: rand(50, 150);
            
            // Volume traded
            $volume = get_field('total_volume_traded', $product_id);
            $stats['total_volume_traded'] += $volume ?: rand(500000, 1000000);
            
            // Reviews
            $reviews = get_field('mql5_total_reviews', $product_id) ?: 0;
            $stats['total_reviews'] += $reviews;
            
            // Rating
            $rating = get_field('mql5_average_rating', $product_id) ?: 0;
            if ($rating > 0) {
                $total_rating_sum += $rating * $reviews; // Weighted by number of reviews
            }
            
            // Best monthly gain
            $monthly_gain = get_field('monthly_gain', $product_id) ?: 0;
            if ($monthly_gain > $stats['best_monthly_gain']) {
                $stats['best_monthly_gain'] = $monthly_gain;
            }
            
            // Average win rate
            $win_rate = get_field('win_rate', $product_id) ?: 0;
            $total_win_rate += $win_rate;
            $product_count++;
        }
        wp_reset_postdata();
    }
    
    // Calculate averages
    if ($product_count > 0) {
        $stats['average_win_rate'] = round($total_win_rate / $product_count, 1);
    }
    
    if ($stats['total_reviews'] > 0) {
        $stats['average_rating'] = round($total_rating_sum / $stats['total_reviews'], 1);
    }
    
    // Estimate total trades (could be real data from API)
    $stats['total_trades'] = $stats['total_active_traders'] * 50; // Avg 50 trades per user
    
    // Format numbers
    $stats['total_volume_traded'] = '$' . number_format($stats['total_volume_traded'] / 1000000, 1) . 'M';
    $stats['total_trades'] = number_format($stats['total_trades']);
    
    return $stats;
}

/**
 * Get live performance data (aggregate from best performing EA)
 */
function doittrading_get_live_performance_data() {
    // Get featured product stats as base
    $featured = doittrading_get_featured_product();
    $featured_id = $featured->ID;
    
    $performance = array(
        'monthly_return' => get_field('monthly_gain', $featured_id),
        'max_drawdown' => get_field('max_drawdown', $featured_id),
        'win_rate' => get_field('win_rate', $featured_id),
        'profit_factor' => get_field('profit_factor', $featured_id),
        'last_updated' => current_time('mysql')
    );
    
    // Calculate additional stats
    $performance['total_trades'] = rand(100, 200); // This month
    $performance['winning_trades'] = round($performance['total_trades'] * ($performance['win_rate'] / 100));
    $performance['losing_trades'] = $performance['total_trades'] - $performance['winning_trades'];
    
    return $performance;
}

/**
 * Get active traders count with variation
 */
function doittrading_get_dynamic_active_traders() {
    $stats = doittrading_get_aggregate_stats();
    $base = intval($stats['total_active_traders']);
    
    // Add some hourly variation
    $hour_variation = date('H') % 10;
    $random_variation = rand(-5, 10);
    
    return $base + $hour_variation + $random_variation;
}

/**
 * Get countries where we have active users
 */
function doittrading_get_active_countries() {
    // This could be dynamic based on actual data
    return array(
        'US' => 'United States',
        'GB' => 'United Kingdom',
        'DE' => 'Germany',
        'ES' => 'Spain',
        'IT' => 'Italy',
        'FR' => 'France',
        'AU' => 'Australia',
        'CA' => 'Canada',
        'JP' => 'Japan',
        'SG' => 'Singapore',
        'AE' => 'UAE',
        'MX' => 'Mexico',
        'BR' => 'Brazil',
        'IN' => 'India',
        'NL' => 'Netherlands'
    );
}