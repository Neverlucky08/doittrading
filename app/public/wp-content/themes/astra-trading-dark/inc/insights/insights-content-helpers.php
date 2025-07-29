<?php
/**
 * DoItTrading Insight Content Helpers
 * Add this to inc/insights/insights-content-helpers.php
 */

/**
 * Add IDs to headings for TOC navigation
 */
function doittrading_add_heading_ids($content) {
    if (empty($content)) return $content;
    
    $pattern = '/<h([2-3])(?:\s+[^>]*)?>(.*?)<\/h\1>/i';
    
    $replacement = function($matches) {
        $level = $matches[1];
        $text = strip_tags($matches[2]);
        $id = sanitize_title($text);
        
        // Check if heading already has an ID
        if (strpos($matches[0], ' id=') !== false) {
            return $matches[0];
        }
        
        // Extract any existing attributes
        preg_match('/<h' . $level . '([^>]*)>/', $matches[0], $attr_matches);
        $existing_attrs = isset($attr_matches[1]) ? $attr_matches[1] : '';
        
        return "<h{$level}{$existing_attrs} id=\"{$id}\">{$matches[2]}</h{$level}>";
    };
    
    return preg_replace_callback($pattern, $replacement, $content);
}

/**
 * Generate Table of Contents items from content
 */
function doittrading_generate_toc_items($content) {
    if (empty($content)) return array();
    
    $toc = array();
    
    // First, add IDs to headings
    $content = doittrading_add_heading_ids($content);
    
    // Then extract headings with their IDs
    preg_match_all('/<h([2-3])[^>]*id="([^"]+)"[^>]*>(.*?)<\/h\1>/i', $content, $matches, PREG_SET_ORDER);
    
    if (!empty($matches)) {
        foreach ($matches as $match) {
            $level = 'h' . $match[1];
            $id = $match[2];
            $text = strip_tags($match[3]);
            
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
 * Enhance insight content with custom formatting
 */
function doittrading_enhance_insight_content($content) {
    if (empty($content)) return $content;
    
    // Add IDs to headings
    $content = doittrading_add_heading_ids($content);
    
    // Wrap tables in responsive container
    $content = doittrading_wrap_content_tables($content);
    
    // Enhance code blocks
    $content = doittrading_enhance_code_blocks($content);
    
    // Enhance blockquotes
    $content = doittrading_enhance_blockquotes($content);
    
    // Add highlight boxes
    $content = doittrading_add_highlight_boxes($content);
    
    // Make first paragraph a lead paragraph
    $content = doittrading_add_lead_paragraph($content);
    
    return $content;
}

/**
 * Wrap tables in responsive container
 */
function doittrading_wrap_content_tables($content) {
    // Wrap all tables
    $content = preg_replace(
        '/<table([^>]*)>/i',
        '<div class="table-responsive"><table$1>',
        $content
    );
    
    $content = str_replace('</table>', '</table></div>', $content);
    
    // Add stats table class if table contains performance data
    $content = preg_replace_callback(
        '/<table([^>]*)>(.*?)<\/table>/is',
        function($matches) {
            $table_content = $matches[2];
            $table_attrs = $matches[1];
            
            // Check if it's a stats table
            if (strpos($table_content, '%') !== false || 
                strpos($table_content, 'Month') !== false ||
                strpos($table_content, 'Performance') !== false ||
                strpos($table_content, 'Results') !== false) {
                
                // Add stats-table class
                if (strpos($table_attrs, 'class=') !== false) {
                    $table_attrs = preg_replace('/class="([^"]*)"/', 'class="$1 stats-table"', $table_attrs);
                } else {
                    $table_attrs .= ' class="stats-table"';
                }
            }
            
            return "<table{$table_attrs}>{$table_content}</table>";
        },
        $content
    );
    
    return $content;
}

/**
 * Enhance code blocks with copy functionality
 */
function doittrading_enhance_code_blocks($content) {
    // Counter for unique IDs
    static $code_counter = 0;
    
    // For <pre> tags
    $content = preg_replace_callback(
        '/<pre([^>]*)>(.*?)<\/pre>/is',
        function($matches) use (&$code_counter) {
            $code_counter++;
            $attrs = $matches[1];
            $code_content = $matches[2];
            
            // Remove <code> tags if nested
            $code_content = preg_replace('/<\/?code[^>]*>/i', '', $code_content);
            
            return '<div class="code-block" data-code-id="code-' . $code_counter . '">
                <button class="copy-button" type="button">Copy</button>
                <pre' . $attrs . ' id="code-' . $code_counter . '">' . $code_content . '</pre>
            </div>';
        },
        $content
    );
    
    // For standalone <code> blocks
    $content = preg_replace_callback(
        '/<p>\s*<code>(.*?)<\/code>\s*<\/p>/is',
        function($matches) use (&$code_counter) {
            $code_counter++;
            
            return '<div class="code-block" data-code-id="code-' . $code_counter . '">
                <button class="copy-button" type="button">Copy</button>
                <pre id="code-' . $code_counter . '"><code>' . $matches[1] . '</code></pre>
            </div>';
        },
        $content
    );
    
    return $content;
}

/**
 * Enhance blockquotes
 */
function doittrading_enhance_blockquotes($content) {
    $content = preg_replace_callback(
        '/<blockquote([^>]*)>(.*?)<\/blockquote>/is',
        function($matches) {
            $attrs = $matches[1];
            $quote_content = trim($matches[2]);
            
            // Remove wrapping <p> tags if present
            $quote_content = preg_replace('/^<p>(.*)<\/p>$/is', '$1', $quote_content);
            
            // Check if it's a pull quote (longer text)
            if (strlen(strip_tags($quote_content)) > 100) {
                return '<div class="pull-quote">' . $quote_content . '</div>';
            }
            
            // Otherwise, make it a highlight box
            return '<div class="highlight-box"><p>' . $quote_content . '</p></div>';
        },
        $content
    );
    
    return $content;
}

/**
 * Add highlight boxes for specific patterns
 */
function doittrading_add_highlight_boxes($content) {
    // Pattern: 💡 Key Insight: or similar
    $patterns = array(
        '/<p>\s*(💡|🎯|⚠️|✅|📌|🔑|💪)\s*(Key Insight|Important|Warning|Note|Tip|Pro Tip|Remember):\s*(.*?)<\/p>/i',
        '/<p>\s*<strong>\s*(💡|🎯|⚠️|✅|📌|🔑|💪)\s*(Key Insight|Important|Warning|Note|Tip|Pro Tip|Remember):\s*<\/strong>\s*(.*?)<\/p>/i'
    );
    
    foreach ($patterns as $pattern) {
        $content = preg_replace(
            $pattern,
            '<div class="highlight-box"><p><strong>$1 $2:</strong> $3</p></div>',
            $content
        );
    }
    
    return $content;
}

/**
 * Make first paragraph a lead paragraph
 */
function doittrading_add_lead_paragraph($content) {
    // Find first <p> tag
    $content = preg_replace(
        '/<p>/',
        '<p class="lead">',
        $content,
        1 // Only replace first occurrence
    );
    
    return $content;
}

/**
 * Format stats tables from simple tables
 */
function doittrading_format_stats_table($content) {
    // This would convert simple tables to our stats grid format
    // For now, just ensuring tables have proper classes
    return $content;
}

/**
 * Add schema markup for insights
 */
function doittrading_add_insight_schema($post_id) {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title($post_id),
        'datePublished' => get_the_date('c', $post_id),
        'dateModified' => get_the_modified_date('c', $post_id),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name', get_post_field('post_author', $post_id))
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => 'DoItTrading',
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_stylesheet_directory_uri() . '/assets/images/logo.png'
            )
        )
    );
    
    if (has_post_thumbnail($post_id)) {
        $schema['image'] = get_the_post_thumbnail_url($post_id, 'full');
    }
    
    return $schema;
}

/**
 * Get related insights query
 */
function doittrading_get_related_insights($current_id, $type = '', $limit = 3) {
    $args = array(
        'post_type' => array('post', 'insight'), // Support both
        'posts_per_page' => $limit,
        'post__not_in' => array($current_id),
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish'
    );
    
    // If type specified, filter by it
    if ($type) {
        // Try taxonomy first
        $args['tax_query'] = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'insight_category',
                'field' => 'slug',
                'terms' => $type
            ),
            array(
                'taxonomy' => 'post_tag',
                'field' => 'slug',
                'terms' => array($type, $type . '-report', $type . '-guide')
            )
        );
    } else {
        // If no type, get from same category or similar
        $categories = wp_get_post_categories($current_id);
        if (!empty($categories)) {
            $args['category__in'] = $categories;
        }
    }
    
    $query = new WP_Query($args);
    
    // If not enough posts found, get random recent ones
    if ($query->post_count < $limit) {
        unset($args['tax_query']);
        unset($args['category__in']);
        $args['orderby'] = 'rand';
        $args['posts_per_page'] = $limit;
        $query = new WP_Query($args);
    }
    
    return $query;
}

/**
 * Calculate reading time
 */
function doittrading_calculate_reading_time($content) {
    if (empty($content)) return 1;
    
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average 200 words per minute
    
    return max(1, $reading_time); // Minimum 1 minute
}

/**
 * Get author initials for avatar
 */
function doittrading_get_author_initials($author_name) {
    if (empty($author_name)) return 'DT';
    
    $names = explode(' ', $author_name);
    $initials = '';
    
    foreach ($names as $name) {
        if (!empty($name)) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
    }
    
    return substr($initials, 0, 2) ?: 'DT';
}

/**
 * Track post views
 */
function doittrading_track_post_views($post_id) {
    if (!is_single() || !$post_id) return;
    
    // Don't count admin views
    if (current_user_can('administrator')) return;
    
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
 * Get insight type from post
 */
function doittrading_get_insight_type($post_id) {
    if (!$post_id) return 'education';
    
    // First check custom field
    $type = get_field('insight_type', $post_id);
    if ($type) return $type;
    
    // Check custom post type taxonomies
    if (get_post_type($post_id) === 'insight') {
        $terms = get_the_terms($post_id, 'insight_category');
        if ($terms && !is_wp_error($terms)) {
            $term = array_shift($terms);
            return $term->slug;
        }
    }
    
    // Check regular post tags
    if (has_tag('performance-report', $post_id) || has_tag('performance', $post_id)) return 'performance';
    if (has_tag('setup-guide', $post_id) || has_tag('setup', $post_id)) return 'setup';
    if (has_tag('success-story', $post_id) || has_tag('success', $post_id)) return 'success';
    if (has_tag('analysis', $post_id) || has_tag('market-analysis', $post_id)) return 'analysis';
    if (has_tag('strategy', $post_id) || has_tag('trading-strategy', $post_id)) return 'strategy';
    
    // Check categories
    if (has_category('performance-reports', $post_id)) return 'performance';
    if (has_category('setup-guides', $post_id)) return 'setup';
    if (has_category('success-stories', $post_id)) return 'success';
    if (has_category('market-analysis', $post_id)) return 'analysis';
    if (has_category('trading-strategies', $post_id)) return 'strategy';
    
    // Default
    return 'education';
}