<?
// En single-insight.php
$related_eas = get_field('related_eas');
if ($related_eas) : ?>
    <div class="related-eas-section">
        <h3>Featured in this article:</h3>
        <?php foreach ($related_eas as $ea) : ?>
            <div class="ea-card-mini">
                <h4><?php echo get_the_title($ea); ?></h4>
                <div class="ea-stats">
                    <span>Win Rate: <?php the_field('win_rate', $ea); ?>%</span>
                    <span>Monthly: +<?php the_field('monthly_gain', $ea); ?>%</span>
                </div>
                <a href="<?php echo get_permalink($ea); ?>" class="view-ea-btn">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

?>