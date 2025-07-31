<?php
/**
 * Universal Product Reviews
 * Review functionality that can be applied across all product categories
 */

if (!defined('ABSPATH')) {
    exit;
}

class DoItTrading_Product_Reviews {
    
    private $max_reviews = 5;
    
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_review_fields'));
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'doittrading_reviews',
            'MQL5 Reviews',
            array($this, 'render_meta_box'),
            'product',
            'normal',
            'high'
        );
    }
    
    public function render_meta_box($post) {
        wp_nonce_field('doittrading_reviews_nonce', 'doittrading_reviews_nonce');
        
        $fields = $this->get_field_values($post->ID);
        ?>
        <style>
            .review-section {
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
                background: #f9f9f9;
            }
            .review-section h4 {
                margin-top: 0;
                color: #333;
            }
            .review-fields {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
                margin-bottom: 15px;
            }
            .review-text {
                grid-column: 1 / -1;
            }
            .star-rating {
                display: flex;
                align-items: center;
                gap: 5px;
            }
        </style>
        
        <h3>Review Summary</h3>
        <table class="form-table">
            <tr>
                <th><label for="mql5_total_reviews">MQL5 Total Reviews</label></th>
                <td>
                    <input type="number" id="mql5_total_reviews" name="mql5_total_reviews" min="0"
                           value="<?php echo esc_attr($fields['mql5_total_reviews']); ?>" class="regular-text">
                    <span class="description">Total number of reviews on MQL5</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="mql5_average_rating">MQL5 Average Rating</label></th>
                <td>
                    <input type="number" id="mql5_average_rating" name="mql5_average_rating" 
                           min="1" max="5" step="0.1"
                           value="<?php echo esc_attr($fields['mql5_average_rating']); ?>" class="regular-text">
                    <span class="description">Average rating (1-5 stars)</span>
                </td>
            </tr>
        </table>
        
        <h3>Individual Reviews</h3>
        <p class="description">Add up to <?php echo $this->max_reviews; ?> featured reviews to display on the product page.</p>
        
        <?php for ($i = 1; $i <= $this->max_reviews; $i++): ?>
        <div class="review-section">
            <h4>Review <?php echo $i; ?></h4>
            
            <div class="review-fields">
                <div>
                    <label for="review_<?php echo $i; ?>_name"><strong>Reviewer Name</strong></label>
                    <input type="text" id="review_<?php echo $i; ?>_name" name="review_<?php echo $i; ?>_name"
                           value="<?php echo esc_attr($fields['review_' . $i . '_name']); ?>" class="regular-text"
                           placeholder="Enter reviewer name">
                </div>
                
                <div>
                    <label for="review_<?php echo $i; ?>_date"><strong>Review Date</strong></label>
                    <input type="date" id="review_<?php echo $i; ?>_date" name="review_<?php echo $i; ?>_date"
                           value="<?php echo esc_attr($fields['review_' . $i . '_date']); ?>" class="regular-text">
                </div>
            </div>
            
            <div class="review-fields">
                <div>
                    <label for="review_<?php echo $i; ?>_stars"><strong>Star Rating</strong></label>
                    <div class="star-rating">
                        <select id="review_<?php echo $i; ?>_stars" name="review_<?php echo $i; ?>_stars" class="regular-text">
                            <option value="">Select rating</option>
                            <?php for ($star = 1; $star <= 5; $star++): ?>
                                <option value="<?php echo $star; ?>" <?php selected($fields['review_' . $i . '_stars'], $star); ?>>
                                    <?php echo $star; ?> Star<?php echo $star > 1 ? 's' : ''; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <span class="stars-display" id="stars_<?php echo $i; ?>">
                            <?php echo $this->render_stars($fields['review_' . $i . '_stars']); ?>
                        </span>
                    </div>
                </div>
                
                <div>
                    <label for="review_<?php echo $i; ?>_verified"><strong>Verified Purchase</strong></label>
                    <label>
                        <input type="checkbox" id="review_<?php echo $i; ?>_verified" name="review_<?php echo $i; ?>_verified" value="1" 
                               <?php checked($fields['review_' . $i . '_verified'], 1); ?>>
                        Verified MQL5 purchase
                    </label>
                </div>
            </div>
            
            <div class="review-text">
                <label for="review_<?php echo $i; ?>_text"><strong>Review Text</strong></label>
                <textarea id="review_<?php echo $i; ?>_text" name="review_<?php echo $i; ?>_text" 
                          rows="4" class="large-text" 
                          placeholder="Enter the review text..."><?php echo esc_textarea($fields['review_' . $i . '_text']); ?></textarea>
            </div>
        </div>
        <?php endfor; ?>
        
        <script>
        jQuery(document).ready(function($) {
            // Update star display when rating changes
            $('select[name*="_stars"]').on('change', function() {
                var rating = $(this).val();
                var reviewNumber = $(this).attr('name').match(/review_(\d+)_stars/)[1];
                var starsHtml = '';
                
                for (var i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml += '★';
                    } else {
                        starsHtml += '☆';
                    }
                }
                
                $('#stars_' + reviewNumber).html(starsHtml);
            });
        });
        </script>
        <?php
    }
    
    private function render_stars($rating) {
        if (!$rating) return '';
        
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $html .= '★';
            } else {
                $html .= '☆';
            }
        }
        return $html;
    }
    
    public function save_review_fields($post_id) {
        if (!isset($_POST['doittrading_reviews_nonce']) || 
            !wp_verify_nonce($_POST['doittrading_reviews_nonce'], 'doittrading_reviews_nonce')) {
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

        $summary_fields = array(
            'mql5_total_reviews' => 'number',
            'mql5_average_rating' => 'number'
        );

        foreach ($summary_fields as $field => $type) {
            if ($type === 'number') {
                $value = isset($_POST[$field]) ? floatval($_POST[$field]) : '';
            } else {
                $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
            }
            
            update_post_meta($post_id, $field, $value);
        }

        for ($i = 1; $i <= $this->max_reviews; $i++) {
            $review_fields = array(
                'review_' . $i . '_name' => 'text',
                'review_' . $i . '_date' => 'date',
                'review_' . $i . '_stars' => 'number',
                'review_' . $i . '_verified' => 'checkbox',
                'review_' . $i . '_text' => 'textarea'
            );

            foreach ($review_fields as $field => $type) {
                if ($type === 'checkbox') {
                    $value = isset($_POST[$field]) ? 1 : 0;
                } elseif ($type === 'number') {
                    $value = isset($_POST[$field]) ? intval($_POST[$field]) : '';
                } elseif ($type === 'date') {
                    $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
                } elseif ($type === 'textarea') {
                    $value = isset($_POST[$field]) ? sanitize_textarea_field($_POST[$field]) : '';
                } else {
                    $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
                }
                
                update_post_meta($post_id, $field, $value);
            }
        }
    }
    
    private function get_field_values($post_id) {
        $fields = array(
            'mql5_total_reviews' => get_post_meta($post_id, 'mql5_total_reviews', true),
            'mql5_average_rating' => get_post_meta($post_id, 'mql5_average_rating', true),
        );

        for ($i = 1; $i <= $this->max_reviews; $i++) {
            $fields['review_' . $i . '_name'] = get_post_meta($post_id, 'review_' . $i . '_name', true);
            $fields['review_' . $i . '_date'] = get_post_meta($post_id, 'review_' . $i . '_date', true);
            $fields['review_' . $i . '_stars'] = get_post_meta($post_id, 'review_' . $i . '_stars', true);
            $fields['review_' . $i . '_verified'] = get_post_meta($post_id, 'review_' . $i . '_verified', true);
            $fields['review_' . $i . '_text'] = get_post_meta($post_id, 'review_' . $i . '_text', true);
        }

        return $fields;
    }
    
    public static function get_field($post_id, $field_name) {
        return get_post_meta($post_id, $field_name, true);
    }
    
    public static function get_all_reviews($post_id) {
        $reviews = array();
        
        for ($i = 1; $i <= 5; $i++) {
            $name = get_post_meta($post_id, 'review_' . $i . '_name', true);
            
            if (!empty($name)) {
                $reviews[] = array(
                    'name' => $name,
                    'date' => get_post_meta($post_id, 'review_' . $i . '_date', true),
                    'stars' => get_post_meta($post_id, 'review_' . $i . '_stars', true),
                    'verified' => get_post_meta($post_id, 'review_' . $i . '_verified', true),
                    'text' => get_post_meta($post_id, 'review_' . $i . '_text', true),
                );
            }
        }
        
        return $reviews;
    }
    
    public static function get_review_summary($post_id) {
        return array(
            'total' => get_post_meta($post_id, 'mql5_total_reviews', true),
            'average' => get_post_meta($post_id, 'mql5_average_rating', true)
        );
    }
    
    public static function render_stars_html($rating, $class = '') {
        if (!$rating) return '';
        
        $html = '<span class="star-rating ' . esc_attr($class) . '">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $html .= '<span class="star filled">★</span>';
            } else {
                $html .= '<span class="star empty">☆</span>';
            }
        }
        $html .= '</span>';
        
        return $html;
    }
    
    public static function has_reviews($post_id) {
        $reviews = self::get_all_reviews($post_id);
        return !empty($reviews);
    }
}

new DoItTrading_Product_Reviews();