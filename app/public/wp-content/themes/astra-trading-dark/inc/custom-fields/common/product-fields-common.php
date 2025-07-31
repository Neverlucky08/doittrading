<?php
/**
 * Common Product Fields
 * Universal custom fields that apply to all product types
 */

if (!defined('ABSPATH')) {
    exit;
}

class DoItTrading_Common_Product_Fields {
    
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_product_fields'));
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'doittrading_common_fields',
            'DoItTrading Product Fields',
            array($this, 'render_meta_box'),
            'product',
            'normal',
            'high'
        );
    }
    
    public function render_meta_box($post) {
        wp_nonce_field('doittrading_common_fields_nonce', 'doittrading_common_fields_nonce');
        
        $fields = $this->get_field_values($post->ID);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="total_active_users">Total Active Users</label></th>
                <td>
                    <input type="number" id="total_active_users" name="total_active_users" 
                           value="<?php echo esc_attr($fields['total_active_users']); ?>" class="regular-text">
                    <span class="description">Número total de usuarios activos</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="total_volume_traded">Total Volume Traded</label></th>
                <td>
                    <input type="number" id="total_volume_traded" name="total_volume_traded" 
                           value="<?php echo esc_attr($fields['total_volume_traded']); ?>" class="regular-text">
                    <span class="description">Volumen total operado</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="supported_platforms">Supported Platforms</label></th>
                <td>
                    <label>
                        <input type="checkbox" name="supported_platforms[]" value="mt4" 
                               <?php checked(in_array('mt4', $fields['supported_platforms'])); ?>> MT4
                    </label><br>
                    <label>
                        <input type="checkbox" name="supported_platforms[]" value="mt5" 
                               <?php checked(in_array('mt5', $fields['supported_platforms'])); ?>> MT5
                    </label>
                </td>
            </tr>
            
            <tr>
                <th><label for="trading_style">Trading Style</label></th>
                <td>
                    <select id="trading_style" name="trading_style" class="regular-text">
                        <option value="">Select Trading Style</option>
                        <?php 
                        $styles = array(
                            'scalping' => 'Scalping',
                            'trend' => 'Trend Following',
                            'grid' => 'Grid Trading',
                            'news' => 'News Trading',
                            'swing' => 'Swing Trading',
                            'ict' => 'ICT (Inner Circle Trader)'
                        );
                        foreach ($styles as $value => $label) {
                            echo '<option value="' . esc_attr($value) . '" ' . selected($fields['trading_style'], $value, false) . '>' . esc_html($label) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        
        <h3>Purchase Links</h3>
        <table class="form-table">
            <tr>
                <th><label for="mql5_purchase_link_mt4">MQL5 Purchase Link MT4</label></th>
                <td>
                    <input type="url" id="mql5_purchase_link_mt4" name="mql5_purchase_link_mt4" 
                           value="<?php echo esc_attr($fields['mql5_purchase_link_mt4']); ?>" class="regular-text">
                </td>
            </tr>
            
            <tr>
                <th><label for="mql5_purchase_link_mt5">MQL5 Purchase Link MT5</label></th>
                <td>
                    <input type="url" id="mql5_purchase_link_mt5" name="mql5_purchase_link_mt5" 
                           value="<?php echo esc_attr($fields['mql5_purchase_link_mt5']); ?>" class="regular-text">
                </td>
            </tr>
        </table>
        
        <h3>FAQ Section</h3>
        <table class="form-table">
            <?php for ($i = 1; $i <= 3; $i++): ?>
            <tr>
                <th><label for="faq_question_<?php echo $i; ?>">FAQ Question <?php echo $i; ?></label></th>
                <td>
                    <input type="text" id="faq_question_<?php echo $i; ?>" name="faq_question_<?php echo $i; ?>" 
                           value="<?php echo esc_attr($fields['faq_question_' . $i]); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th><label for="faq_answer_<?php echo $i; ?>">FAQ Answer <?php echo $i; ?></label></th>
                <td>
                    <textarea id="faq_answer_<?php echo $i; ?>" name="faq_answer_<?php echo $i; ?>" 
                              rows="3" class="large-text"><?php echo esc_textarea($fields['faq_answer_' . $i]); ?></textarea>
                </td>
            </tr>
            <?php endfor; ?>
        </table>
        
        <h3>Key Features</h3>
        <p class="description">4 características principales del producto</p>
        <table class="form-table">
            <?php for ($i = 1; $i <= 4; $i++): ?>
            <tr>
                <th><label for="key_feature_<?php echo $i; ?>">Key Feature <?php echo $i; ?></label></th>
                <td>
                    <input type="text" id="key_feature_<?php echo $i; ?>" name="key_feature_<?php echo $i; ?>" 
                           value="<?php echo esc_attr($fields['key_feature_' . $i]); ?>" class="regular-text"
                           placeholder="ej: Advanced Risk Management">
                </td>
            </tr>
            <?php endfor; ?>
        </table>
        
        <h3>Product Classification</h3>
        <table class="form-table">
            <tr>
                <th><label for="best_for">Best For</label></th>
                <td>
                    <select id="best_for" name="best_for" class="regular-text">
                        <option value="">Select Category</option>
                        <?php 
                        $categories = array(
                            'beginners' => 'Beginners',
                            'conservative' => 'Conservative Traders',
                            'aggressive' => 'Risk-tolerant Traders',
                            'small_accounts' => 'Small Accounts',
                            'scalpers' => 'Scalpers',
                            'prop_firms' => 'Prop Firm Challenges',
                            'all' => 'All Traders'
                        );
                        foreach ($categories as $value => $label) {
                            echo '<option value="' . esc_attr($value) . '" ' . selected($fields['best_for'], $value, false) . '>' . esc_html($label) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function save_product_fields($post_id) {
        if (!isset($_POST['doittrading_common_fields_nonce']) || 
            !wp_verify_nonce($_POST['doittrading_common_fields_nonce'], 'doittrading_common_fields_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (get_post_type($post_id) !== 'product') {
            return;
        }

        $fields = array(
            'total_active_users' => 'number',
            'total_volume_traded' => 'number',
            'supported_platforms' => 'array',
            'trading_style' => 'text',
            'mql5_purchase_link_mt4' => 'url',
            'mql5_purchase_link_mt5' => 'url',
            'best_for' => 'text'
        );
        
        // Key Features (4 features)
        for ($i = 1; $i <= 4; $i++) {
            $fields['key_feature_' . $i] = 'text';
        }

        foreach ($fields as $field => $type) {
            if ($type === 'checkbox') {
                $value = isset($_POST[$field]) ? 1 : 0;
            } elseif ($type === 'array') {
                $value = isset($_POST[$field]) ? (array) $_POST[$field] : array();
            } elseif ($type === 'number') {
                $value = isset($_POST[$field]) ? floatval($_POST[$field]) : '';
            } elseif ($type === 'url') {
                $value = isset($_POST[$field]) ? esc_url_raw($_POST[$field]) : '';
            } else {
                $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
            }
            
            update_post_meta($post_id, $field, $value);
        }

        for ($i = 1; $i <= 3; $i++) {
            $question = isset($_POST['faq_question_' . $i]) ? sanitize_text_field($_POST['faq_question_' . $i]) : '';
            $answer = isset($_POST['faq_answer_' . $i]) ? sanitize_textarea_field($_POST['faq_answer_' . $i]) : '';
            
            update_post_meta($post_id, 'faq_question_' . $i, $question);
            update_post_meta($post_id, 'faq_answer_' . $i, $answer);
        }
    }
    
    private function get_field_values($post_id) {
        $fields = array(
            'total_active_users' => get_post_meta($post_id, 'total_active_users', true),
            'total_volume_traded' => get_post_meta($post_id, 'total_volume_traded', true),
            'supported_platforms' => get_post_meta($post_id, 'supported_platforms', true) ?: array(),
            'trading_style' => get_post_meta($post_id, 'trading_style', true),
            'mql5_purchase_link_mt4' => get_post_meta($post_id, 'mql5_purchase_link_mt4', true),
            'mql5_purchase_link_mt5' => get_post_meta($post_id, 'mql5_purchase_link_mt5', true),
            'best_for' => get_post_meta($post_id, 'best_for', true),
            'faq_question_1' => get_post_meta($post_id, 'faq_question_1', true),
            'faq_answer_1' => get_post_meta($post_id, 'faq_answer_1', true),
            'faq_question_2' => get_post_meta($post_id, 'faq_question_2', true),
            'faq_answer_2' => get_post_meta($post_id, 'faq_answer_2', true),
            'faq_question_3' => get_post_meta($post_id, 'faq_question_3', true),
            'faq_answer_3' => get_post_meta($post_id, 'faq_answer_3', true),
        );
        
        // Key Features (4 features)
        for ($i = 1; $i <= 4; $i++) {
            $fields['key_feature_' . $i] = get_post_meta($post_id, 'key_feature_' . $i, true);
        }
        
        return $fields;
    }
    
    public static function get_field($post_id, $field_name) {
        return get_post_meta($post_id, $field_name, true);
    }
    
    public static function get_supported_platforms($post_id) {
        $platforms = get_post_meta($post_id, 'supported_platforms', true);
        return is_array($platforms) ? $platforms : array();
    }
    
    public static function has_platform($post_id, $platform) {
        $platforms = self::get_supported_platforms($post_id);
        return in_array($platform, $platforms);
    }
}

new DoItTrading_Common_Product_Fields();