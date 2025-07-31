<?php
/**
 * Indicators Specific Product Fields
 * Custom fields specifically for trading indicators products
 */

if (!defined('ABSPATH')) {
    exit;
}

class DoItTrading_Indicators_Product_Fields {
    
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_indicator_fields'));
    }
    
    public function add_meta_boxes() {
        global $post;
        
        if ($this->is_indicator_product($post->ID)) {
            add_meta_box(
                'doittrading_indicator_fields',
                'Indicator Specific Fields',
                array($this, 'render_meta_box'),
                'product',
                'normal',
                'high'
            );
        }
    }
    
    private function is_indicator_product($post_id) {
        $terms = wp_get_post_terms($post_id, 'product_cat', array('fields' => 'slugs'));
        return in_array('indicators', $terms) || in_array('trading-indicators', $terms);
    }
    
    public function render_meta_box($post) {
        wp_nonce_field('doittrading_indicator_fields_nonce', 'doittrading_indicator_fields_nonce');
        
        $fields = $this->get_field_values($post->ID);
        ?>
        <h3>Why Traders Love It - Benefits List</h3>
        <p class="description">4 puntos principales que destacan por qué los traders aman este producto. Se mostrarán en la sección benefits-list.</p>
        <table class="form-table">
            <?php for ($i = 1; $i <= 4; $i++): ?>
            <tr>
                <th><label for="benefit_<?php echo $i; ?>_title">Benefit <?php echo $i; ?> - Title</label></th>
                <td>
                    <input type="text" id="benefit_<?php echo $i; ?>_title" name="benefit_<?php echo $i; ?>_title" 
                           value="<?php echo esc_attr($fields['benefit_' . $i . '_title']); ?>" class="regular-text"
                           placeholder="ej: Easy Setup">
                </td>
            </tr>
            <tr>
                <th><label for="benefit_<?php echo $i; ?>_description">Benefit <?php echo $i; ?> - Description</label></th>
                <td>
                    <textarea id="benefit_<?php echo $i; ?>_description" name="benefit_<?php echo $i; ?>_description" 
                              rows="2" class="large-text" 
                              placeholder="Descripción breve del beneficio..."><?php echo esc_textarea($fields['benefit_' . $i . '_description']); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="benefit_<?php echo $i; ?>_icon">Benefit <?php echo $i; ?> - Icon</label></th>
                <td>
                    <select id="benefit_<?php echo $i; ?>_icon" name="benefit_<?php echo $i; ?>_icon" class="regular-text">
                        <option value="">Select Icon</option>
                        <?php 
                        $icons = array(
                            'chart-line' => '📈 Chart Line',
                            'clock' => '⏰ Clock',
                            'shield-check' => '🛡️ Shield Check',
                            'lightning' => '⚡ Lightning',
                            'target' => '🎯 Target',
                            'trophy' => '🏆 Trophy',
                            'rocket' => '🚀 Rocket',
                            'gem' => '💎 Gem',
                            'star' => '⭐ Star',
                            'fire' => '🔥 Fire'
                        );
                        foreach ($icons as $value => $label) {
                            echo '<option value="' . esc_attr($value) . '" ' . selected($fields['benefit_' . $i . '_icon'], $value, false) . '>' . esc_html($label) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php if ($i < 4): ?>
            <tr><td colspan="2"><hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;"></td></tr>
            <?php endif; ?>
            <?php endfor; ?>
        </table>
        
        <h3>Tool Visual Showcase</h3>
        <p class="description">3 puntos que se muestran en la sección tool-visual con capturas de pantalla o demostraciones.</p>
        <table class="form-table">
            <?php for ($i = 1; $i <= 3; $i++): ?>
            <tr>
                <th><label for="visual_<?php echo $i; ?>_title">Visual <?php echo $i; ?> - Title</label></th>
                <td>
                    <input type="text" id="visual_<?php echo $i; ?>_title" name="visual_<?php echo $i; ?>_title" 
                           value="<?php echo esc_attr($fields['visual_' . $i . '_title']); ?>" class="regular-text"
                           placeholder="ej: Real-Time Analysis">
                </td>
            </tr>
            <tr>
                <th><label for="visual_<?php echo $i; ?>_description">Visual <?php echo $i; ?> - Description</label></th>
                <td>
                    <textarea id="visual_<?php echo $i; ?>_description" name="visual_<?php echo $i; ?>_description" 
                              rows="3" class="large-text" 
                              placeholder="Descripción de lo que muestra esta característica visual..."><?php echo esc_textarea($fields['visual_' . $i . '_description']); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="visual_<?php echo $i; ?>_image">Visual <?php echo $i; ?> - Image URL</label></th>
                <td>
                    <input type="url" id="visual_<?php echo $i; ?>_image" name="visual_<?php echo $i; ?>_image" 
                           value="<?php echo esc_attr($fields['visual_' . $i . '_image']); ?>" class="regular-text"
                           placeholder="https://example.com/screenshot.png">
                    <span class="description">URL de la imagen que muestra esta característica</span>
                </td>
            </tr>
            <?php if ($i < 3): ?>
            <tr><td colspan="2"><hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;"></td></tr>
            <?php endif; ?>
            <?php endfor; ?>
        </table>
        
        <h3>Tool Stats Card</h3>
        <p class="description">3 estadísticas principales que se muestran en la tarjeta de estadísticas del producto</p>
        <table class="form-table">
            <?php for ($i = 1; $i <= 3; $i++): ?>
            <tr>
                <th><label for="stat_<?php echo $i; ?>_value">Stat <?php echo $i; ?> - Value</label></th>
                <td>
                    <input type="text" id="stat_<?php echo $i; ?>_value" name="stat_<?php echo $i; ?>_value" 
                           value="<?php echo esc_attr($fields['stat_' . $i . '_value']); ?>" class="regular-text"
                           placeholder="ej: 89% o 2.5M+ o 24/7">
                    <span class="description">Valor principal de la estadística</span>
                </td>
            </tr>
            <tr>
                <th><label for="stat_<?php echo $i; ?>_label">Stat <?php echo $i; ?> - Label</label></th>
                <td>
                    <input type="text" id="stat_<?php echo $i; ?>_label" name="stat_<?php echo $i; ?>_label" 
                           value="<?php echo esc_attr($fields['stat_' . $i . '_label']); ?>" class="regular-text"
                           placeholder="ej: Accuracy Rate, Active Users, Support">
                    <span class="description">Etiqueta descriptiva de la estadística</span>
                </td>
            </tr>
            <tr>
                <th><label for="stat_<?php echo $i; ?>_icon">Stat <?php echo $i; ?> - Icon</label></th>
                <td>
                    <select id="stat_<?php echo $i; ?>_icon" name="stat_<?php echo $i; ?>_icon" class="regular-text">
                        <option value="">Select Icon</option>
                        <?php 
                        $icons = array(
                            'chart-line' => '📈 Chart Line',
                            'bullseye' => '🎯 Bullseye/Target',
                            'users' => '👥 Users',
                            'clock' => '⏰ Clock',
                            'shield-check' => '🛡️ Shield Check',
                            'lightning' => '⚡ Lightning',
                            'trophy' => '🏆 Trophy',
                            'star' => '⭐ Star',
                            'fire' => '🔥 Fire',
                            'diamond' => '💎 Diamond',
                            'rocket' => '🚀 Rocket',
                            'zap' => '⚡ Zap',
                            'trending-up' => '📊 Trending Up',
                            'activity' => '📈 Activity',
                            'dollar-sign' => '💲 Dollar Sign'
                        );
                        foreach ($icons as $value => $label) {
                            echo '<option value="' . esc_attr($value) . '" ' . selected($fields['stat_' . $i . '_icon'], $value, false) . '>' . esc_html($label) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php if ($i < 3): ?>
            <tr><td colspan="2"><hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;"></td></tr>
            <?php endif; ?>
            <?php endfor; ?>
        </table>
        
        <h3>Display Settings</h3>
        <table class="form-table">
            <tr>
                <th><label for="featured_in_indicators_page">Featured in Indicators Page</label></th>
                <td>
                    <input type="checkbox" id="featured_in_indicators_page" name="featured_in_indicators_page" value="1" 
                           <?php checked($fields['featured_in_indicators_page'], 1); ?>>
                    <span class="description">Feature this product on the main indicators page</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="show_in_comparisons">Show in Comparisons</label></th>
                <td>
                    <input type="checkbox" id="show_in_comparisons" name="show_in_comparisons" value="1" 
                           <?php checked($fields['show_in_comparisons'], 1); ?>>
                    <span class="description">Include in product comparison tables</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="is_trending">Trending Tool</label></th>
                <td>
                    <input type="checkbox" id="is_trending" name="is_trending" value="1" 
                           <?php checked($fields['is_trending'], 1); ?>>
                    <span class="description">Mark as trending tool</span>
                </td>
            </tr>
            
        </table>
        <?php
    }
    
    public function save_indicator_fields($post_id) {
        if (!isset($_POST['doittrading_indicator_fields_nonce']) || 
            !wp_verify_nonce($_POST['doittrading_indicator_fields_nonce'], 'doittrading_indicator_fields_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (get_post_type($post_id) !== 'product' || !$this->is_indicator_product($post_id)) {
            return;
        }

        $fields = array(
            'featured_in_indicators_page' => 'checkbox',
            'show_in_comparisons' => 'checkbox',
            'is_trending' => 'checkbox'
        );

        // Benefits fields (4 benefits)
        for ($i = 1; $i <= 4; $i++) {
            $fields['benefit_' . $i . '_title'] = 'text';
            $fields['benefit_' . $i . '_description'] = 'textarea';
            $fields['benefit_' . $i . '_icon'] = 'text';
        }

        // Visual showcase fields (3 visuals)
        for ($i = 1; $i <= 3; $i++) {
            $fields['visual_' . $i . '_title'] = 'text';
            $fields['visual_' . $i . '_description'] = 'textarea';
            $fields['visual_' . $i . '_image'] = 'url';
        }

        // Tool stats card fields (3 stats)
        for ($i = 1; $i <= 3; $i++) {
            $fields['stat_' . $i . '_value'] = 'text';
            $fields['stat_' . $i . '_label'] = 'text';
            $fields['stat_' . $i . '_icon'] = 'text';
        }

        foreach ($fields as $field => $type) {
            if ($type === 'checkbox') {
                $value = isset($_POST[$field]) ? 1 : 0;
            } elseif ($type === 'array') {
                $value = isset($_POST[$field]) ? (array) $_POST[$field] : array();
            } elseif ($type === 'number') {
                $value = isset($_POST[$field]) ? floatval($_POST[$field]) : '';
            } elseif ($type === 'textarea') {
                $value = isset($_POST[$field]) ? sanitize_textarea_field($_POST[$field]) : '';
            } else {
                $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
            }
            
            update_post_meta($post_id, $field, $value);
        }
    }
    
    private function get_field_values($post_id) {
        $fields = array(
            'featured_in_indicators_page' => get_post_meta($post_id, 'featured_in_indicators_page', true),
            'show_in_comparisons' => get_post_meta($post_id, 'show_in_comparisons', true) ?: 1,
            'is_trending' => get_post_meta($post_id, 'is_trending', true),
        );

        // Benefits fields (4 benefits)
        for ($i = 1; $i <= 4; $i++) {
            $fields['benefit_' . $i . '_title'] = get_post_meta($post_id, 'benefit_' . $i . '_title', true);
            $fields['benefit_' . $i . '_description'] = get_post_meta($post_id, 'benefit_' . $i . '_description', true);
            $fields['benefit_' . $i . '_icon'] = get_post_meta($post_id, 'benefit_' . $i . '_icon', true);
        }

        // Visual showcase fields (3 visuals)
        for ($i = 1; $i <= 3; $i++) {
            $fields['visual_' . $i . '_title'] = get_post_meta($post_id, 'visual_' . $i . '_title', true);
            $fields['visual_' . $i . '_description'] = get_post_meta($post_id, 'visual_' . $i . '_description', true);
            $fields['visual_' . $i . '_image'] = get_post_meta($post_id, 'visual_' . $i . '_image', true);
        }

        // Tool stats card fields (3 stats)
        for ($i = 1; $i <= 3; $i++) {
            $fields['stat_' . $i . '_value'] = get_post_meta($post_id, 'stat_' . $i . '_value', true);
            $fields['stat_' . $i . '_label'] = get_post_meta($post_id, 'stat_' . $i . '_label', true);
            $fields['stat_' . $i . '_icon'] = get_post_meta($post_id, 'stat_' . $i . '_icon', true);
        }

        return $fields;
    }
    
    public static function get_field($post_id, $field_name) {
        return get_post_meta($post_id, $field_name, true);
    }
    
    public static function get_benefits($post_id) {
        $benefits = array();
        
        for ($i = 1; $i <= 4; $i++) {
            $title = get_post_meta($post_id, 'benefit_' . $i . '_title', true);
            
            if (!empty($title)) {
                $benefits[] = array(
                    'title' => $title,
                    'description' => get_post_meta($post_id, 'benefit_' . $i . '_description', true),
                    'icon' => get_post_meta($post_id, 'benefit_' . $i . '_icon', true)
                );
            }
        }
        
        return $benefits;
    }
    
    public static function get_visuals($post_id) {
        $visuals = array();
        
        for ($i = 1; $i <= 3; $i++) {
            $title = get_post_meta($post_id, 'visual_' . $i . '_title', true);
            
            if (!empty($title)) {
                $visuals[] = array(
                    'title' => $title,
                    'description' => get_post_meta($post_id, 'visual_' . $i . '_description', true),
                    'image' => get_post_meta($post_id, 'visual_' . $i . '_image', true)
                );
            }
        }
        
        return $visuals;
    }
    
    public static function get_stats($post_id) {
        $stats = array();
        
        for ($i = 1; $i <= 3; $i++) {
            $value = get_post_meta($post_id, 'stat_' . $i . '_value', true);
            
            if (!empty($value)) {
                $stats[] = array(
                    'value' => $value,
                    'label' => get_post_meta($post_id, 'stat_' . $i . '_label', true),
                    'icon' => get_post_meta($post_id, 'stat_' . $i . '_icon', true)
                );
            }
        }
        
        return $stats;
    }
}

new DoItTrading_Indicators_Product_Fields();