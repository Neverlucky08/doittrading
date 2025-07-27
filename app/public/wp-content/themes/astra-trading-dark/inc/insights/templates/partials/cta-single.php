<?php
/**
 * Partial: Single Insight CTA Section
 * 
 * @package DoItTrading
 */

// Get passed args
$related_ea = $args['related_ea'] ?? null;
$insight_type = $args['insight_type'] ?? 'education';

// Determine CTA content based on insight type
$cta_content = array(
    'performance' => array(
        'title' => 'See Live Results for Yourself',
        'description' => 'Join hundreds of traders already profiting with our verified EAs',
        'button_text' => 'View All EAs',
        'button_url' => '/shop/'
    ),
    'education' => array(
        'title' => 'Ready to Apply What You\'ve Learned?',
        'description' => 'Start trading smarter with our professionally developed EAs',
        'button_text' => 'Explore Our EAs',
        'button_url' => '/shop/'
    ),
    'setup' => array(
        'title' => 'Need Help with Setup?',
        'description' => 'Get personal support from our team for a smooth start',
        'button_text' => 'Contact Support',
        'button_url' => '/contact/'
    ),
    'success' => array(
        'title' => 'Ready to Start Your Success Story?',
        'description' => 'Join hundreds of traders already profiting with DoItTrading EAs',
        'button_text' => 'Get Started Today',
        'button_url' => '/shop/'
    ),
    'analysis' => array(
        'title' => 'Trade These Insights Automatically',
        'description' => 'Let our EAs execute professional strategies while you sleep',
        'button_text' => 'View Trading Bots',
        'button_url' => '/forex-trading-bots/'
    ),
    'strategy' => array(
        'title' => 'Automate This Strategy',
        'description' => 'Our EAs implement proven strategies with discipline and precision',
        'button_text' => 'Explore EAs',
        'button_url' => '/shop/'
    )
);

$cta = $cta_content[$insight_type] ?? $cta_content['education'];

// Override if specific EA is mentioned
if ($related_ea) {
    $ea_id = is_object($related_ea) ? $related_ea->ID : $related_ea;
    $ea_title = get_the_title($ea_id);
    $ea_url = get_permalink($ea_id);
    
    $cta = array(
        'title' => 'Ready to Start Trading with ' . $ea_title . '?',
        'description' => 'Join hundreds of traders already profiting with this verified EA',
        'button_text' => 'Get Started with ' . str_replace('DoIt ', '', $ea_title),
        'button_url' => $ea_url
    );
}
?>

<section class="insight-cta-section">
    <div class="container">
        <h2><?php echo esc_html($cta['title']); ?></h2>
        <p><?php echo esc_html($cta['description']); ?></p>
        <a href="<?php echo esc_url($cta['button_url']); ?>" class="cta-button">
            <?php echo esc_html($cta['button_text']); ?>
        </a>
    </div>
</section>