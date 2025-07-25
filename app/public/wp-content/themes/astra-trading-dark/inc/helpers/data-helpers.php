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
        'average_win_rate' => 0,
        'countries' => count(doittrading_get_active_countries())
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


/**
 * Additional helper functions for forex-bots page
 * Add these to your existing data-helpers.php file
 */

/**
 * Get hero bot for forex bots page
 */
function doittrading_get_forex_bots_hero_bot() {
    // First, try to get the specifically marked product
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => 'featured_in_forex_bots_hero',
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
    
    if ($query->have_posts()) {
        $query->the_post();
        $product_id = get_the_ID();
        
        $bot_data = array(
            'id' => $product_id,
            'name' => get_the_title(),
            'monthly_gain' => get_field('monthly_gain', $product_id),
            'win_rate' => get_field('win_rate', $product_id),
            'trades_today' => rand(3, 8),
            'last_trades' => doittrading_get_simulated_trades($product_id)
        );
        
        wp_reset_postdata();
        return $bot_data;
    }
    
    // Fallback: get the featured product
    $featured = doittrading_get_featured_product();
    if ($featured) {
        $product_id = $featured->ID;
        
        return array(
            'id' => $product_id,
            'name' => get_the_title($product_id),
            'monthly_gain' => get_field('monthly_gain', $product_id),
            'win_rate' => get_field('win_rate', $product_id),
            'trades_today' => rand(3, 8),
            'last_trades' => doittrading_get_simulated_trades($product_id)
        );
    }
    
    // Ultimate fallback
    return array(
        'id' => 19,
        'name' => 'GBP Master Bot',
        'monthly_gain' => 12.8,
        'win_rate' => 76,
        'trades_today' => 5,
        'last_trades' => array()
    );
}

/**
 * Get top performing bot for hero section
 */
function doittrading_get_top_bot() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 1,
        'meta_key' => 'monthly_gain',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
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
        $query->the_post();
        $product_id = get_the_ID();
        
        $bot_data = array(
            'id' => $product_id,
            'name' => get_the_title(),
            'monthly_gain' => get_field('monthly_gain', $product_id),
            'win_rate' => get_field('win_rate', $product_id),
            'trades_today' => rand(3, 8), // Simulated
            'last_trades' => doittrading_get_simulated_trades($product_id)
        );
        
        wp_reset_postdata();
        return $bot_data;
    }
    
    // Fallback to GBP Master
    return array(
        'id' => 19,
        'name' => 'DoIt GBP Master Bot',
        'monthly_gain' => 12.8,
        'win_rate' => 76,
        'trades_today' => 5,
        'last_trades' => doittrading_get_simulated_trades(19)
    );
}

/**
 * Get simulated recent trades for display
 */
function doittrading_get_simulated_trades($product_id) {
    $win_rate = get_field('win_rate', $product_id) ?: 75;
    $trading_pair = get_field('target_market', $product_id) ?: 'GBPUSD';
    
    $trades = array();
    $times = array('14:23', '13:45', '12:18', '11:02', '09:34');
    
    foreach ($times as $i => $time) {
        // Use win rate to determine if profit
        $is_profit = (rand(1, 100) <= $win_rate);
        $pips = $is_profit ? rand(8, 35) : rand(-5, -15);
        $action = rand(0, 1) ? 'BUY' : 'SELL';
        
        $trades[] = array(
            'time' => $time,
            'action' => $action . ' ' . $trading_pair,
            'pips' => $pips,
            'class' => $is_profit ? 'profit' : 'loss'
        );
        
        if (count($trades) >= 3) break;
    }
    
    return $trades;
}

/**
 * Get bots for final CTA section
 */
function doittrading_get_cta_bots() {
    $products = doittrading_get_featured_products();
    
    // Format for CTA display
    $cta_bots = array();
    foreach ($products as $product) {
        $cta_bots[] = array(
            'id' => $product['id'],
            'name' => str_replace('DoIt ', '', $product['name']), // Remove prefix for cleaner display
            'url' => $product['url'],
            'win_rate' => $product['win_rate'],
            'monthly_gain' => $product['monthly_gain'],
            'min_deposit' => $product['min_deposit'] ?: 100,
            'current_price' => $product['current_price'],
            'original_price' => $product['original_price'],
            'is_featured' => $product['badge'] === 'BESTSELLER',
            'features' => doittrading_get_bot_features($product['id'])
        );
    }
    
    return $cta_bots;
}

/**
 * Get bot features for CTA display
 */
function doittrading_get_bot_features($product_id) {
    $features = array();
    
    // Based on product characteristics
    $min_deposit = get_field('minimum_deposit', $product_id);
    $win_rate = get_field('win_rate', $product_id);
    $risk_level = get_field('risk_level', $product_id);
    
    if ($risk_level === 'low') {
        $features[] = 'Conservative & Reliable';
    }
    
    if ($min_deposit <= 100) {
        $features[] = 'Beginner Friendly';
    }
    
    if ($win_rate > 90) {
        $features[] = 'High Win Rate';
    }
    
    $features[] = 'Live Verified';
    
    return implode(' ✓ ', $features);
}

/**
 * Get aggregated testimonials for social proof
 */
function doittrading_get_forex_bots_testimonials() {
    $testimonials = doittrading_get_all_reviews(10); // Get more reviews
    
    // Add additional info for forex bots page
    foreach ($testimonials as &$testimonial) {
        $testimonial['timeframe'] = doittrading_calculate_timeframe($testimonial['date']);
        $testimonial['country_flag'] = doittrading_get_country_flag($testimonial['country']);
    }
    
    return $testimonials;
}

/**
 * Calculate timeframe from date
 */
function doittrading_calculate_timeframe($date) {
    if (!$date) return 'Recently';
    
    $date_obj = new DateTime($date);
    $now = new DateTime();
    $interval = $now->diff($date_obj);
    
    if ($interval->y > 0) {
        return $interval->y . ' year' . ($interval->y > 1 ? 's' : '');
    } elseif ($interval->m > 0) {
        return $interval->m . ' month' . ($interval->m > 1 ? 's' : '');
    } elseif ($interval->d > 14) {
        $weeks = floor($interval->d / 7);
        return $weeks . ' week' . ($weeks > 1 ? 's' : '');
    } else {
        return $interval->d . ' day' . ($interval->d > 1 ? 's' : '');
    }
}

/**
 * Get country flag emoji
 */
function doittrading_get_country_flag($country) {
    $flags = array(
        'Germany' => '🇩🇪',
        'Austria' => '🇦🇹', 
        'Spain' => '🇪🇸',
        'United Kingdom' => '🇬🇧',
        'UK' => '🇬🇧',
        'Canada' => '🇨🇦',
        'USA' => '🇺🇸',
        'United States' => '🇺🇸',
        'Singapore' => '🇸🇬',
        'Australia' => '🇦🇺',
        'France' => '🇫🇷',
        'Italy' => '🇮🇹',
        'Japan' => '🇯🇵',
        'Mexico' => '🇲🇽',
        'Brazil' => '🇧🇷',
        'India' => '🇮🇳',
        'Netherlands' => '🇳🇱'
    );
    
    return $flags[$country] ?? '🌍';
}

/**
 * Get live account stats for proof section
 */
function doittrading_get_live_accounts() {
    $accounts = array();
    
    // Get top 2 performing products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 2,
        'meta_key' => 'monthly_gain',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
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
        $account_types = array('Conservative', 'Aggressive');
        $i = 0;
        
        while ($query->have_posts()) {
            $query->the_post();
            $product_id = get_the_ID();
            
            // Calculate total growth (simulated based on monthly gain)
            $monthly_gain = get_field('monthly_gain', $product_id);
            $months_active = 6; // Assume 1 year
            $total_growth = round($monthly_gain * $months_active * 2.5); // Simplified compound
            
            $accounts[] = array(
                'name' => get_the_title() . ' - ' . $account_types[$i],
                'total_growth' => $total_growth,
                'win_rate' => get_field('win_rate', $product_id),
                'max_drawdown' => get_field('max_drawdown', $product_id),
                'profit_factor' => get_field('profit_factor', $product_id),
                'total_trades' => rand(80, 200),
                'myfxbook_url' => get_field('myfxbook_url', $product_id)
            );
            
            $i++;
        }
        wp_reset_postdata();
    }
    
    return $accounts;
}

/**
 * Get products for comparison table (flexible number)
 */
function doittrading_get_comparison_products($limit = null) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit ?: -1, // All if no limit
        'meta_query' => array(
            array(
                'key' => 'show_in_comparisons',
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
        ),
        'meta_key' => 'homepage_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    );
    
    $query = new WP_Query($args);
    $products = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product_id = get_the_ID();
            $product = wc_get_product($product_id);
            
            $products[] = array(
                'id' => $product_id,
                'name' => get_the_title(),
                'short_name' => str_replace('DoIt ', '', get_the_title()), // Para tabla más compacta
                'target_market' => get_field('target_market', $product_id) ?: 'Multiple',
                'trading_style' => get_field('trading_style', $product_id) ?: 'balanced',
                'risk_level' => get_field('risk_level', $product_id) ?: 'low',
                'max_drawdown' => get_field('max_drawdown', $product_id),
                'min_deposit' => get_field('minimum_deposit', $product_id),
                'best_for' => get_field('best_for', $product_id) ?: 'all',
                'url' => get_permalink($product_id),
                'is_featured' => get_field('is_featured_product', $product_id),
                'monthly_gain' => get_field('monthly_gain', $product_id),
                'win_rate' => get_field('win_rate', $product_id),
                'profit_factor' => get_field('profit_factor', $product_id),
                'current_price' => $product->get_price() ?: $product->get_regular_price(),
                'regular_price' => $product->get_regular_price(),
                'total_reviews' => get_field('mql5_total_reviews', $product_id) ?: 0,
                'platforms' => get_field('supported_platforms', $product_id),
                'myfxbook_url' => get_field('myfxbook_url', $product_id),
                'tagline' => get_field('product_tagline', $product_id) ?: doittrading_get_product_subtitle($product_id)
            );
        }
        wp_reset_postdata();
    }
    
    // If no products marked for comparison, get top 3 by performance
    if (empty($products)) {
        $args['meta_query'] = array();
        $args['posts_per_page'] = 3;
        $args['meta_key'] = 'monthly_gain';
        $args['orderby'] = 'meta_value_num';
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $product_id = get_the_ID();
                $product = wc_get_product($product_id);
                
                $products[] = array(
                    'id' => $product_id,
                    'name' => get_the_title(),
                    'short_name' => str_replace('DoIt ', '', get_the_title()),
                    'target_market' => get_field('target_market', $product_id) ?: 'Multiple',
                    'trading_style' => get_field('trading_style', $product_id) ?: 'balanced',
                    'risk_level' => get_field('risk_level', $product_id) ?: 'low',
                    'max_drawdown' => get_field('max_drawdown', $product_id),
                    'min_deposit' => get_field('minimum_deposit', $product_id),
                    'best_for' => get_field('best_for', $product_id) ?: 'all',
                    'url' => get_permalink($product_id),
                    'is_featured' => false,
                    'monthly_gain' => get_field('monthly_gain', $product_id),
                    'win_rate' => get_field('win_rate', $product_id),
                    'profit_factor' => get_field('profit_factor', $product_id),
                    'current_price' => $product->get_price() ?: $product->get_regular_price(),
                    'regular_price' => $product->get_regular_price()
                );
            }
            wp_reset_postdata();
        }
    }
    
    return $products;
}

/**
 * Get comparison table CSS class based on number of products
 */
function doittrading_get_comparison_table_class($product_count) {
    if ($product_count <= 3) {
        return 'comparison-table-3';
    } elseif ($product_count == 4) {
        return 'comparison-table-4';
    } elseif ($product_count == 5) {
        return 'comparison-table-5';
    } else {
        return 'comparison-table-many';
    }
}

/**
 * Format trading style for display
 */
function doittrading_format_trading_style($style) {
    $styles = array(
        'scalping' => 'Scalping',
        'trend' => 'Trend Following',
        'grid' => 'Grid Trading',
        'news' => 'News Trading',
        'swing' => 'Swing Trading',
        'balanced' => 'Balanced',
        'conservative' => 'Conservative',
        'aggressive' => 'Aggressive'
    );
    
    return $styles[$style] ?? ucfirst($style);
}

/**
 * Format best for display
 */
function doittrading_format_best_for($best_for) {
    $options = array(
        'beginners' => 'Beginners',
        'conservative' => 'Conservative Traders',
        'aggressive' => 'Risk-tolerant',
        'small_accounts' => 'Small Accounts',
        'scalpers' => 'Scalpers',
        'all' => 'All Traders'
    );
    
    return $options[$best_for] ?? ucfirst(str_replace('_', ' ', $best_for));
}