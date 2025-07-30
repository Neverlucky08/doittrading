<?php
/**
 * Partial: Single Insight Content
 * 
 * @package DoItTrading
 */

// Apply custom content filters
add_filter('the_content', 'doittrading_enhance_insight_content', 20);

// Display the content
the_content();

// Remove filter after use
remove_filter('the_content', 'doittrading_enhance_insight_content', 20);

/**
 * Enhance insight content with custom elements
 */
function doittrading_enhance_insight_content($content) {
    // Add IDs to headings for TOC
    $content = doittrading_add_heading_ids($content);
    
    // Wrap tables in responsive container
    $content = doittrading_wrap_tables($content);
    
    // Add copy button to code blocks
    $content = doittrading_enhance_code_blocks($content);
    
    // Enhance blockquotes
    $content = doittrading_enhance_blockquotes($content);
    
    // Add highlight boxes
    $content = doittrading_add_highlight_boxes($content);
    
    return $content;
}

/**
 * Add IDs to headings for Table of Contents
 */
function doittrading_add_heading_ids($content) {
    $pattern = '/<h([2-3])[^>]*>(.*?)<\/h\1>/i';
    $replacement = function($matches) {
        $level = $matches[1];
        $text = strip_tags($matches[2]);
        $id = sanitize_title($text);
        return "<h{$level} id=\"{$id}\">{$matches[2]}</h{$level}>";
    };
    
    return preg_replace_callback($pattern, $replacement, $content);
}

/**
 * Wrap tables in responsive container
 */
function doittrading_wrap_tables($content) {
    $content = str_replace('<table', '<div class="table-responsive"><table', $content);
    $content = str_replace('</table>', '</table></div>', $content);
    
    // Add stats table class if table contains performance data
    $content = preg_replace_callback(
        '/<table[^>]*>(.*?)<\/table>/is',
        function($matches) {
            if (strpos($matches[1], '%') !== false || strpos($matches[1], 'Month') !== false) {
                return str_replace('<table', '<table class="stats-table"', $matches[0]);
            }
            return $matches[0];
        },
        $content
    );
    
    return $content;
}

/**
 * Enhance code blocks with copy functionality
 */
function doittrading_enhance_code_blocks($content) {
    // For <pre> tags
    $content = preg_replace_callback(
        '/<pre[^>]*>(.*?)<\/pre>/is',
        function($matches) {
            $code_id = 'code-' . uniqid();
            return '<div class="code-block" data-code-id="' . $code_id . '">
                <button class="copy-button" onclick="copyCode(\'' . $code_id . '\')">Copy</button>
                <pre id="' . $code_id . '">' . $matches[1] . '</pre>
            </div>';
        },
        $content
    );
    
    return $content;
}

/**
 * Enhance blockquotes to pull quotes
 */
function doittrading_enhance_blockquotes($content) {
    $content = preg_replace_callback(
        '/<blockquote[^>]*>(.*?)<\/blockquote>/is',
        function($matches) {
            // Check if it's a testimonial-style quote
            if (strlen(strip_tags($matches[1])) > 100) {
                return '<div class="pull-quote">' . $matches[1] . '</div>';
            }
            return '<div class="highlight-box">' . $matches[1] . '</div>';
        },
        $content
    );
    
    return $content;
}

/**
 * Convert specific text patterns to highlight boxes
 */
function doittrading_add_highlight_boxes($content) {
    // Pattern: 💡 Key Insight: or 🎯 Important: etc
    $content = preg_replace(
        '/<p>[\s]*(💡|🎯|⚠️|✅|📌)\s*(Key Insight|Important|Warning|Note|Tip):\s*(.*?)<\/p>/i',
        '<div class="highlight-box"><p><strong>$1 $2:</strong> $3</p></div>',
        $content
    );
    
    return $content;
}
?>