<?php
/**
 * EAS (Expert Advisors) Specific Product Fields
 * Enhanced fields specifically for Expert Advisors products
 */

if (!defined('ABSPATH')) {
    exit;
}

class DoItTrading_EAS_Product_Fields {
    
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_eas_fields'));
    }
    
    public function add_meta_boxes() {
        global $post;
        
        if ($this->is_expert_advisor_product($post->ID)) {
            add_meta_box(
                'doittrading_eas_fields',
                'EAS Enhanced Fields',
                array($this, 'render_meta_box'),
                'product',
                'normal',
                'high'
            );
        }
    }
    
    private function is_expert_advisor_product($post_id) {
        $terms = wp_get_post_terms($post_id, 'product_cat', array('fields' => 'slugs'));
        return in_array('expert-advisors', $terms);
    }
    
    public function render_meta_box($post) {
        wp_nonce_field('doittrading_eas_fields_nonce', 'doittrading_eas_fields_nonce');
        
        $fields = $this->get_field_values($post->ID);
        ?>
        <h3>Product Featuring</h3>
        <table class="form-table">
            <tr>
                <th><label for="is_featured_product">Is Featured Product</label></th>
                <td>
                    <input type="checkbox" id="is_featured_product" name="is_featured_product" value="1" 
                           <?php checked($fields['is_featured_product'], 1); ?>>
                    <span class="description">Para destacar en hero y homepage</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="homepage_order">Homepage Featured Order</label></th>
                <td>
                    <input type="number" id="homepage_order" name="homepage_order" 
                           value="<?php echo esc_attr($fields['homepage_order']); ?>" class="regular-text">
                    <span class="description">Orden en featured products (menor número = mayor prioridad)</span>
                </td>
            </tr>
        </table>
        
        <h3>Trading Performance</h3>
        <table class="form-table">
            <tr>
                <th><label for="minimum_deposit">Minimum Deposit</label></th>
                <td>
                    <span>$</span>
                    <input type="number" id="minimum_deposit" name="minimum_deposit" 
                           value="<?php echo esc_attr($fields['minimum_deposit']); ?>" class="regular-text">
                </td>
            </tr>
            
            <tr>
                <th><label for="monthly_gain">Monthly Gain (%)</label></th>
                <td>
                    <input type="number" id="monthly_gain" name="monthly_gain" step="0.01" 
                           value="<?php echo esc_attr($fields['monthly_gain']); ?>" class="regular-text">
                    <span>%</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="win_rate">Win Rate (%)</label></th>
                <td>
                    <input type="number" id="win_rate" name="win_rate" step="0.01" 
                           value="<?php echo esc_attr($fields['win_rate']); ?>" class="regular-text">
                    <span>%</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="max_drawdown">Max Drawdown (%)</label></th>
                <td>
                    <span>-</span>
                    <input type="number" id="max_drawdown" name="max_drawdown" step="0.01" 
                           value="<?php echo esc_attr($fields['max_drawdown']); ?>" class="regular-text">
                    <span>%</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="profit_factor">Profit Factor</label></th>
                <td>
                    <input type="number" id="profit_factor" name="profit_factor" step="0.01" 
                           value="<?php echo esc_attr($fields['profit_factor']); ?>" class="regular-text">
                </td>
            </tr>
        </table>
        
        <h3>Verification Links</h3>
        <table class="form-table">
            <tr>
                <th><label for="myfxbook_url">MyFxBook URL</label></th>
                <td>
                    <input type="url" id="myfxbook_url" name="myfxbook_url" 
                           value="<?php echo esc_attr($fields['myfxbook_url']); ?>" class="regular-text">
                </td>
            </tr>
        </table>
        
        <h3>EAs Enhanced Fields</h3>
        <p class="description">Campos adicionales específicos para EAs que mejoran la presentación y marketing</p>
        <table class="form-table">
            <tr>
                <th><label for="featured_in_forex_bots_hero">Featured in Forex Bots Hero</label></th>
                <td>
                    <input type="checkbox" id="featured_in_forex_bots_hero" name="featured_in_forex_bots_hero" value="1" 
                           <?php checked($fields['featured_in_forex_bots_hero'], 1); ?>>
                    <span class="description">Mostrar este producto en el hero de la página Forex Trading Bots</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="hero_hook_title">Hero Hook Title</label></th>
                <td>
                    <input type="text" id="hero_hook_title" name="hero_hook_title" maxlength="100"
                           value="<?php echo esc_attr($fields['hero_hook_title']); ?>" class="regular-text"
                           placeholder="Hook emocional para el hero">
                    <span class="description">Título emocional principal (ej: 'Finally, A GBPUSD EA That Actually Works')</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="hero_subtitle">Hero Subtitle</label></th>
                <td>
                    <input type="text" id="hero_subtitle" name="hero_subtitle" maxlength="150"
                           value="<?php echo esc_attr($fields['hero_subtitle']); ?>" class="regular-text"
                           placeholder="Credenciales y diferenciadores">
                    <span class="description">Subtítulo con credenciales</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="product_tagline">Product Tagline</label></th>
                <td>
                    <input type="text" id="product_tagline" name="product_tagline"
                           value="<?php echo esc_attr($fields['product_tagline']); ?>" class="regular-text"
                           placeholder="Tagline descriptivo del producto">
                    <span class="description">Subtítulo corto del producto (ej: 'The Conservative Profit Machine')</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="countdown_end_date">Countdown End Date</label></th>
                <td>
                    <input type="datetime-local" id="countdown_end_date" name="countdown_end_date"
                           value="<?php echo esc_attr($fields['countdown_end_date']); ?>" class="regular-text">
                    <span class="description">Fecha y hora cuando termina la oferta especial</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="stock_remaining">Stock Remaining</label></th>
                <td>
                    <input type="number" id="stock_remaining" name="stock_remaining" min="1" max="50"
                           value="<?php echo esc_attr($fields['stock_remaining']); ?>" class="regular-text">
                    <span class="description">Número de licencias restantes (para crear urgencia)</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="monthly_gain_context">Monthly Gain Context</label></th>
                <td>
                    <input type="text" id="monthly_gain_context" name="monthly_gain_context"
                           value="<?php echo esc_attr($fields['monthly_gain_context']); ?>" class="regular-text"
                           placeholder="Last 6 months">
                    <span class="description">Contexto adicional para monthly gain</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="win_rate_context">Win Rate Context</label></th>
                <td>
                    <input type="text" id="win_rate_context" name="win_rate_context"
                           value="<?php echo esc_attr($fields['win_rate_context']); ?>" class="regular-text"
                           placeholder="247 of 325 trades">
                    <span class="description">Contexto para win rate</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="comparison_benchmark">Comparison Benchmark</label></th>
                <td>
                    <input type="text" id="comparison_benchmark" name="comparison_benchmark"
                           value="<?php echo esc_attr($fields['comparison_benchmark']); ?>" class="regular-text"
                           placeholder="vs 2.1% S&P500">
                    <span class="description">Benchmark para comparar</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="roi_capital_base">ROI Capital Base</label></th>
                <td>
                    <span>$</span>
                    <input type="number" id="roi_capital_base" name="roi_capital_base" min="100" step="100"
                           value="<?php echo esc_attr($fields['roi_capital_base']); ?>" class="regular-text">
                    <span class="description">Capital base para calcular ROI (recomendado: $1000)</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="live_traders_count_min">Live Traders Count (Min-Max)</label></th>
                <td>
                    <input type="number" id="live_traders_count_min" name="live_traders_count_min" min="1" max="50"
                           value="<?php echo esc_attr($fields['live_traders_count_min']); ?>" style="width: 80px;">
                    <span> - </span>
                    <input type="number" id="live_traders_count_max" name="live_traders_count_max" min="1" max="100"
                           value="<?php echo esc_attr($fields['live_traders_count_max']); ?>" style="width: 80px;">
                    <span class="description">Rango para randomizar número de traders en vivo</span>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function save_eas_fields($post_id) {
        if (!isset($_POST['doittrading_eas_fields_nonce']) || 
            !wp_verify_nonce($_POST['doittrading_eas_fields_nonce'], 'doittrading_eas_fields_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (get_post_type($post_id) !== 'product' || !$this->is_expert_advisor_product($post_id)) {
            return;
        }

        $fields = array(
            // Product Featuring
            'is_featured_product' => 'checkbox',
            'homepage_order' => 'number',
            
            // Trading Performance
            'minimum_deposit' => 'number',
            'monthly_gain' => 'number',
            'win_rate' => 'number',
            'max_drawdown' => 'number',
            'profit_factor' => 'number',
            
            // Verification Links
            'myfxbook_url' => 'url',
            
            // EAs Enhanced Fields
            'featured_in_forex_bots_hero' => 'checkbox',
            'hero_hook_title' => 'text',
            'hero_subtitle' => 'text',
            'product_tagline' => 'text',
            'countdown_end_date' => 'datetime',
            'stock_remaining' => 'number',
            'monthly_gain_context' => 'text',
            'win_rate_context' => 'text',
            'comparison_benchmark' => 'text',
            'roi_capital_base' => 'number',
            'live_traders_count_min' => 'number',
            'live_traders_count_max' => 'number'
        );

        foreach ($fields as $field => $type) {
            if ($type === 'checkbox') {
                $value = isset($_POST[$field]) ? 1 : 0;
            } elseif ($type === 'number') {
                $value = isset($_POST[$field]) ? floatval($_POST[$field]) : '';
            } elseif ($type === 'datetime') {
                $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
            } else {
                $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
            }
            
            update_post_meta($post_id, $field, $value);
        }
    }
    
    private function get_field_values($post_id) {
        return array(
            // Product Featuring
            'is_featured_product' => get_post_meta($post_id, 'is_featured_product', true),
            'homepage_order' => get_post_meta($post_id, 'homepage_order', true),
            
            // Trading Performance
            'minimum_deposit' => get_post_meta($post_id, 'minimum_deposit', true),
            'monthly_gain' => get_post_meta($post_id, 'monthly_gain', true),
            'win_rate' => get_post_meta($post_id, 'win_rate', true),
            'max_drawdown' => get_post_meta($post_id, 'max_drawdown', true),
            'profit_factor' => get_post_meta($post_id, 'profit_factor', true),
            
            // Verification Links
            'myfxbook_url' => get_post_meta($post_id, 'myfxbook_url', true),
            
            // EAs Enhanced Fields
            'featured_in_forex_bots_hero' => get_post_meta($post_id, 'featured_in_forex_bots_hero', true),
            'hero_hook_title' => get_post_meta($post_id, 'hero_hook_title', true) ?: 'Finally, A GBPUSD EA That Actually Works',
            'hero_subtitle' => get_post_meta($post_id, 'hero_subtitle', true) ?: 'MyFxBook Verified • No Martingale',
            'product_tagline' => get_post_meta($post_id, 'product_tagline', true) ?: 'Professional Trading Bot',
            'countdown_end_date' => get_post_meta($post_id, 'countdown_end_date', true),
            'stock_remaining' => get_post_meta($post_id, 'stock_remaining', true) ?: 7,
            'monthly_gain_context' => get_post_meta($post_id, 'monthly_gain_context', true) ?: 'Last 6 months',
            'win_rate_context' => get_post_meta($post_id, 'win_rate_context', true) ?: '247 of 325 trades',
            'comparison_benchmark' => get_post_meta($post_id, 'comparison_benchmark', true) ?: 'vs 2.1% S&P500',
            'roi_capital_base' => get_post_meta($post_id, 'roi_capital_base', true) ?: 1000,
            'live_traders_count_min' => get_post_meta($post_id, 'live_traders_count_min', true) ?: 8,
            'live_traders_count_max' => get_post_meta($post_id, 'live_traders_count_max', true) ?: 15,
        );
    }
    
    public static function get_field($post_id, $field_name) {
        return get_post_meta($post_id, $field_name, true);
    }
    
    public static function get_live_traders_count($post_id) {
        $min = (int) get_post_meta($post_id, 'live_traders_count_min', true) ?: 8;
        $max = (int) get_post_meta($post_id, 'live_traders_count_max', true) ?: 15;
        return rand($min, $max);
    }
    
    public static function get_last_trade_pips($post_id) {
        $min = (int) get_post_meta($post_id, 'last_trade_pips_min', true) ?: 25;
        $max = (int) get_post_meta($post_id, 'last_trade_pips_max', true) ?: 65;
        return rand($min, $max);
    }
    
    public static function is_featured_in_hero($post_id) {
        return (bool) get_post_meta($post_id, 'featured_in_forex_bots_hero', true);
    }
}

new DoItTrading_EAS_Product_Fields();