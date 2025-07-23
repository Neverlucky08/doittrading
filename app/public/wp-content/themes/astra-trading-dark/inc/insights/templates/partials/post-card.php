<?php
/**
 * Insight Post Card Template - Para Custom Post Type 'insight'
 */

// Detectar si es CPT o post normal
$post_type = get_post_type();
$insight_type = 'education'; // Default
$has_live_data = false;
$reading_time = 5; // Default

if ($post_type === 'insight') {
    // Para Custom Post Type - usar taxonomías personalizadas
    
    // Opción 1: Si tienes taxonomía personalizada 'insight_category'
    $insight_categories = get_the_terms(get_the_ID(), 'insight_category');
    $insight_tags = get_the_terms(get_the_ID(), 'insight_tag'); // Si tienes tags personalizados
    
    // Opción 2: Si usas meta fields personalizados
    $insight_type_meta = get_post_meta(get_the_ID(), 'insight_type', true);
    if ($insight_type_meta) {
        $insight_type = $insight_type_meta;
    }
    
    // Mapear desde taxonomías personalizadas
    if ($insight_categories && !is_wp_error($insight_categories)) {
        foreach ($insight_categories as $category) {
            switch($category->slug) {
                case 'performance':
                case 'performance-report':
                    $insight_type = 'performance';
                    $has_live_data = true;
                    break;
                case 'education':
                case 'guide':
                    $insight_type = 'education';
                    $reading_time = 10;
                    break;
                case 'setup':
                case 'setup-guide':
                    $insight_type = 'setup';
                    $reading_time = 8;
                    break;
                case 'analysis':
                case 'market-analysis':
                    $insight_type = 'analysis';
                    $has_live_data = true;
                    break;
                case 'strategy':
                    $insight_type = 'strategy';
                    $reading_time = 12;
                    break;
                case 'success':
                case 'success-story':
                    $insight_type = 'success';
                    $reading_time = 6;
                    break;
            }
            break; // Solo usar la primera categoría
        }
    }
    
    // Si no tienes taxonomías, usar meta fields directamente
    $live_data_meta = get_post_meta(get_the_ID(), 'has_live_data', true);
    if ($live_data_meta) {
        $has_live_data = true;
    }
    
    $reading_time_meta = get_post_meta(get_the_ID(), 'reading_time', true);
    if ($reading_time_meta) {
        $reading_time = intval($reading_time_meta);
    }
    
} else {
    // Para posts normales - código original
    $categories = get_the_category();
    $tags = get_the_tags();
    
    // Buscar en tags
    if ($tags) {
        foreach ($tags as $tag) {
            switch($tag->slug) {
                case 'performance':
                case 'performance-report':
                    $insight_type = 'performance';
                    $has_live_data = true;
                    break;
                case 'education':
                case 'guide':
                    $insight_type = 'education';
                    $reading_time = 10;
                    break;
                case 'setup':
                case 'setup-guide':
                    $insight_type = 'setup';
                    $reading_time = 8;
                    break;
                case 'analysis':
                case 'market-analysis':
                    $insight_type = 'analysis';
                    $has_live_data = true;
                    break;
                case 'strategy':
                    $insight_type = 'strategy';
                    $reading_time = 12;
                    break;
                case 'success':
                case 'success-story':
                    $insight_type = 'success';
                    $reading_time = 6;
                    break;
            }
        }
    }
}

// Simular views
$views = rand(500, 3000);

// Badge settings
$badge_config = array(
    'performance' => array('text' => '📈 PERFORMANCE', 'class' => 'badge-update'),
    'education' => array('text' => '🎓 EDUCATION', 'class' => 'badge-guide'),
    'setup' => array('text' => '⚙️ SETUP', 'class' => 'badge-setup'),
    'analysis' => array('text' => '📊 ANALYSIS', 'class' => 'badge-update'),
    'strategy' => array('text' => '💡 STRATEGY', 'class' => 'badge-strategy'),
    'success' => array('text' => '🏆 SUCCESS', 'class' => 'badge-success')
);

$badge = $badge_config[$insight_type];

// DEBUG - Remover en producción
if (WP_DEBUG) {
    echo '<!-- DEBUG: Post Type: ' . $post_type . ', Insight Type: ' . $insight_type . ' -->';
}
?>

<div class="post-card" data-category="<?php echo esc_attr($insight_type); ?>">
    <a href="<?php the_permalink(); ?>" class="post-card-link">
        
        <div class="post-thumbnail">
            <?php if ($has_live_data) : ?>
                <div class="post-badge badge-live">🟢 LIVE UPDATE</div>
            <?php else : ?>
                <div class="post-badge <?php echo esc_attr($badge['class']); ?>">
                    <?php echo esc_html($badge['text']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
            <?php else : ?>
                <div class="placeholder-icon">
                    <?php 
                    $icons = array(
                        'performance' => '📈',
                        'education' => '📚',
                        'setup' => '⚙️',
                        'analysis' => '📊',
                        'strategy' => '💡',
                        'success' => '🏆'
                    );
                    echo $icons[$insight_type] ?? '📄';
                    ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="post-content">
            <div class="post-meta">
                <?php 
                if ($post_type === 'insight') {
                    // Para CPT, mostrar taxonomía personalizada o tipo
                    if (isset($insight_categories) && $insight_categories && !is_wp_error($insight_categories)) {
                        echo esc_html($insight_categories[0]->name) . ' • ';
                    } else {
                        echo ucfirst($insight_type) . ' • ';
                    }
                } else {
                    // Para posts normales
                    $categories = get_the_category();
                    if ($categories) {
                        echo esc_html($categories[0]->name) . ' • ';
                    }
                }
                echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; 
                ?>
            </div>
            
            <h3 class="post-title"><?php the_title(); ?></h3>
            
            <p class="post-excerpt">
                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
            </p>
            
            <div class="post-stats">
                <span>👁 <?php echo number_format($views); ?> views</span>
                <span>📖 <?php echo $reading_time; ?> min read</span>
            </div>
        </div>
        
    </a>
</div>