<?php
/**
 * ACF Migration Utility
 * Helps migrate existing ACF data to native WordPress custom fields
 */

if (!defined('ABSPATH')) {
    exit;
}

class DoItTrading_ACF_Migration_Utility {
    
    private $field_mapping = array();
    
    public function __construct() {
        $this->setup_field_mapping();
        add_action('wp_ajax_doittrading_migrate_acf', array($this, 'migrate_acf_data'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_migration_scripts'));
    }
    
    /**
     * Setup field mapping from ACF keys to meta keys
     */
    private function setup_field_mapping() {
        $this->field_mapping = array(
            // Common Product Fields
            'field_6882844569eaa' => 'is_featured_product',
            'field_6882847069eab' => 'homepage_order',
            'field_6882848569eac' => 'total_active_users',
            'field_688284a40ad38' => 'total_volume_traded',
            'field_6879526604a66' => 'monthly_gain',
            'field_6879529104a67' => 'win_rate',
            'field_687952ad04a68' => 'max_drawdown',
            'field_687952c404a69' => 'profit_factor',
            'field_687952dc04a6a' => 'supported_platforms',
            'field_6879531104a6b' => 'trading_style',
            'field_687a9f9f4f748' => 'mql5_purchase_link_mt4',
            'field_687a9faa4f749' => 'mql5_purchase_link_mt5',
            'field_687a9fb44f74a' => 'myfxbook_url',
            'field_687a9fbe4f74b' => 'minimum_deposit',
            'field_687aa9169001f' => 'faq_question_1',
            'field_687aa92290020' => 'faq_answer_1',
            'field_687aa92d90021' => 'faq_question_2',
            'field_687aa93490022' => 'faq_answer_2',
            'field_687aa93b90023' => 'faq_question_3',
            'field_687aa94290024' => 'faq_answer_3',
            'field_687aa94890025' => 'key_features',
            
            // EAS Enhanced Fields
            'field_6883ca5445801' => 'featured_in_forex_bots_hero',
            'field_hero_hook_title' => 'hero_hook_title',
            'field_hero_subtitle' => 'hero_subtitle',
            'field_countdown_end_date' => 'countdown_end_date',
            'field_stock_remaining' => 'stock_remaining',
            'field_monthly_gain_context' => 'monthly_gain_context',
            'field_win_rate_context' => 'win_rate_context',
            'field_drawdown_context' => 'drawdown_context',
            'field_comparison_benchmark' => 'comparison_benchmark',
            'field_roi_capital_base' => 'roi_capital_base',
            'field_performance_period' => 'performance_period',
            'field_mql5_reviews_verified' => 'mql5_reviews_verified',
            'field_live_traders_count_min' => 'live_traders_count_min',
            'field_live_traders_count_max' => 'live_traders_count_max',
            'field_last_trade_pips_min' => 'last_trade_pips_min',
            'field_last_trade_pips_max' => 'last_trade_pips_max',
            'field_6883c0f59e76a' => 'product_tagline',
            'field_6883c1b09e76b' => 'best_for',
            'field_6883c2119e76c' => 'risk_level',
            'field_6883c2479e76d' => 'target_market',
            'field_6883c25b9e76e' => 'show_in_comparisons',
            
            // Reviews
            'field_687ab04650fc9' => 'review_1_name',
            'field_687ab05550fca' => 'review_1_date',
            'field_687ab05b50fcb' => 'review_1_stars',
            'field_687ab09950fcc' => 'review_1_text',
            'field_687ab0ec50fcf' => 'review_2_name',
            'field_687ab11c50fd3' => 'review_2_date',
            'field_687ab13a50fd7' => 'review_2_stars',
            'field_687ab15850fdb' => 'review_2_text',
            'field_687ab0f850fd0' => 'review_3_name',
            'field_687ab12450fd4' => 'review_3_date',
            'field_687ab14350fd8' => 'review_3_stars',
            'field_687ab16050fdc' => 'review_3_text',
            'field_687ab10450fd1' => 'review_4_name',
            'field_687ab12b50fd5' => 'review_4_date',
            'field_687ab14a50fd9' => 'review_4_stars',
            'field_687ab16650fdd' => 'review_4_text',
            'field_687ab10d50fd2' => 'review_5_name',
            'field_687ab13350fd6' => 'review_5_date',
            'field_687ab15150fda' => 'review_5_stars',
            'field_687ab16d50fde' => 'review_5_text',
            'field_687ab179a9286' => 'mql5_total_reviews',
            'field_687ab189a9287' => 'mql5_average_rating',
        );
    }
    
    /**
     * Enqueue migration scripts
     */
    public function enqueue_migration_scripts($hook) {
        if ($hook === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
            wp_enqueue_script('jquery');
            wp_add_inline_script('jquery', $this->get_migration_script());
        }
    }
    
    /**
     * Get migration JavaScript
     */
    private function get_migration_script() {
        return "
        function doittrading_migrate_acf() {
            if (confirm('This will migrate all ACF data to native custom fields. This action cannot be undone. Continue?')) {
                jQuery.post(ajaxurl, {
                    action: 'doittrading_migrate_acf',
                    nonce: '" . wp_create_nonce('doittrading_migrate_acf') . "'
                }, function(response) {
                    if (response.success) {
                        alert('Migration completed successfully! ' + response.data.message);
                        location.reload();
                    } else {
                        alert('Migration failed: ' + response.data.message);
                    }
                });
            }
        }
        ";
    }
    
    /**
     * AJAX handler for ACF migration
     */
    public function migrate_acf_data() {
        check_ajax_referer('doittrading_migrate_acf', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $migrated_products = 0;
        $migrated_fields = 0;
        
        // Get all products
        $products = get_posts(array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'any'
        ));
        
        foreach ($products as $product) {
            $product_migrated = false;
            
            // Migrate each field
            foreach ($this->field_mapping as $acf_key => $meta_key) {
                $acf_value = get_field($acf_key, $product->ID);
                
                if ($acf_value !== false && $acf_value !== null && $acf_value !== '') {
                    update_post_meta($product->ID, $meta_key, $acf_value);
                    $migrated_fields++;
                    
                    if (!$product_migrated) {
                        $migrated_products++;
                        $product_migrated = true;
                    }
                }
            }
            
            // Try to migrate by field name as well (fallback)
            foreach ($this->field_mapping as $acf_key => $meta_key) {
                if (strpos($acf_key, 'field_') !== 0) continue;
                
                $field_name = str_replace('field_', '', $acf_key);
                $acf_value = get_field($field_name, $product->ID);
                
                if ($acf_value !== false && $acf_value !== null && $acf_value !== '') {
                    update_post_meta($product->ID, $meta_key, $acf_value);
                    $migrated_fields++;
                }
            }
        }
        
        // Mark migration as completed
        update_option('doittrading_acf_migrated', true);
        update_option('doittrading_acf_migration_date', current_time('mysql'));
        update_option('doittrading_acf_migration_stats', array(
            'products' => $migrated_products,
            'fields' => $migrated_fields
        ));
        
        wp_send_json_success(array(
            'message' => "Migrated {$migrated_fields} fields across {$migrated_products} products."
        ));
    }
    
    /**
     * Rollback migration (for testing purposes)
     */
    public function rollback_migration() {
        if (!current_user_can('manage_options')) {
            return false;
        }
        
        $products = get_posts(array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'any'
        ));
        
        foreach ($products as $product) {
            foreach ($this->field_mapping as $acf_key => $meta_key) {
                delete_post_meta($product->ID, $meta_key);
            }
        }
        
        delete_option('doittrading_acf_migrated');
        delete_option('doittrading_acf_migration_date');
        delete_option('doittrading_acf_migration_stats');
        
        return true;
    }
    
    /**
     * Get migration status
     */
    public static function get_migration_status() {
        return array(
            'migrated' => get_option('doittrading_acf_migrated', false),
            'date' => get_option('doittrading_acf_migration_date', ''),
            'stats' => get_option('doittrading_acf_migration_stats', array())
        );
    }
    
    /**
     * Check if a specific field has been migrated
     */
    public static function is_field_migrated($post_id, $field_name) {
        $meta_value = get_post_meta($post_id, $field_name, true);
        return !empty($meta_value);
    }
}

// Only initialize if ACF is active
if (class_exists('ACF')) {
    new DoItTrading_ACF_Migration_Utility();
}