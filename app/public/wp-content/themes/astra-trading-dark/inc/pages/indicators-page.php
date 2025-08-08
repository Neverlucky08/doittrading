<?php
/**
 * DoItTrading Indicators Page Sections
 * 
 * @package DoItTrading
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Main Indicators Page Handler
 */
add_action('astra_content_before', 'doittrading_indicators_page', 5);

function doittrading_indicators_page() {
    // Solo mostrar en la página específica
    if (!is_page('indicators')) return;
    
    // Secciones de la página en orden
    doittrading_indicators_hero_section();
    doittrading_featured_tool_section();
    doittrading_ict_tools_section();
    doittrading_tools_grid_section();
    doittrading_before_after_section();
    doittrading_indicators_testimonials_section();
}

/**
 * Indicators Hero Section
 */
function doittrading_indicators_hero_section() {
    // Get dynamic data
    $stats = doittrading_get_indicator_stats();
    $aggregate_stats = doittrading_get_aggregate_stats('indicators'); // Get stats from indicator products
    
    $active_traders = $aggregate_stats['total_active_traders']; // From indicator products
    $total_downloads = $aggregate_stats['total_downloads']; // Aggregate downloads from ALL indicators
    $years_active = date('Y') - doittrading_get_start_year(); // Dynamic start year
    ?>
    <div class="doittrading-homepage-hero indicators-hero">
        
        <!-- Hero Content -->
        <div class="hero-content-main">
            
            <!-- Professional Hook -->
            <div class="hero-headline-section">
                <h1 class="hero-main-title">Professional Trading Analysis Tools</h1>
                <p class="hero-subtitle">
                    Get better entries with institutional-grade indicators. Trade like the smart money.
                </p>
            </div>
            
            <!-- Credibility Bar -->
            <div class="hero-credibility-bar">
                <div class="credibility-item">
                    <span class="credibility-number"><?php echo $active_traders; ?>+</span>
                    <span class="credibility-label">Manual Traders</span>
                </div>
                <div class="credibility-item">
                    <span class="credibility-number"><?php echo $total_downloads; ?>+</span>
                    <span class="credibility-label">Downloads</span>
                </div>
                <div class="credibility-item">
                    <span class="credibility-number"><?php echo $years_active; ?>+</span>
                    <span class="credibility-label">Years Proven</span>
                </div>
            </div>
            
            <!-- Professional Trading Indicator -->
            <div class="hero-live-section">
                <div class="live-trading-indicator">
                    <div class="live-dot-hero"></div>
                    <span class="live-text">TRENDING:</span>
                    <span class="live-details">
                        <?php 
                        $trending = $stats['trending_tool'];
                        echo esc_html($trending['name']) . ' - ' . esc_html($trending['downloads']) . ' downloads this month';
                        ?>
                    </span>
                </div>
                <div class="active-traders-count">
                    Professional traders improving their edge daily
                </div>
            </div>
            
            <!-- Hero CTAs -->
            <div class="hero-cta-section">
                <a href="#tools-grid" class="hero-cta-primary">
                    Explore Tools
                </a>
                <a href="#before-after" class="hero-cta-secondary">
                    See Examples
                </a>
            </div>
            
            <!-- Trust Elements -->
            <div class="hero-trust-elements">
                <div class="trust-element">
                    ✓ ICT Based
                </div>
                <div class="trust-element">
                    ✓ Multi-Timeframe
                </div>
                <div class="trust-element">
                    ✓ No Repaint
                </div>
            </div>
            
        </div>
        
        <!-- Hero Visual (Right side - Tool Preview) -->
        <div class="hero-visual-section">
            
            <!-- Featured Tool Stats Card -->
            <div class="hero-stats-card">
                <?php 
                $hero_tool = doittrading_get_featured_indicator(); 
                
                // Get specific data for hero stats
                $active_users = '';
                $rating = '';
                $benefit_1_title = '';
                
                if (isset($hero_tool['id']) && $hero_tool['id'] > 0) {
                    // Get downloads count (not active users - this is for indicators)
                    $active_users_count = doittrading_get_field('downloads_count', $hero_tool['id'], 0);
                    $active_users = $active_users_count > 0 ? number_format($active_users_count) . '+' : $hero_tool['downloads'];
                    
                    // Get rating
                    $rating_value = doittrading_get_field('mql5_average_rating', $hero_tool['id'], $hero_tool['rating']);
                    $rating = $rating_value . '★';
                    
                    // Get first benefit title
                    $benefits = doittrading_get_product_benefits($hero_tool['id']);
                    $benefit_1_title = !empty($benefits[0]['title']) ? $benefits[0]['title'] : '6x Faster Testing';
                } else {
                    // Fallback values
                    $active_users = $hero_tool['downloads'];
                    $rating = $hero_tool['rating'] . '★';
                    $benefit_1_title = '6x Faster Testing';
                }
                
                $hero_stats = array(
                    array(
                        'value' => $active_users,
                        'label' => 'Downloads'
                    ),
                    array(
                        'value' => $rating,
                        'label' => 'Rating'
                    ),
                    array(
                        'value' => $benefit_1_title,
                        'label' => ''
                    )
                );
                ?>
                <div class="stats-card-header">
                    <h3><?php echo esc_html($hero_tool['name']); ?></h3>
                    <span class="live-badge-small">🔥 TRENDING</span>
                </div>
                
                <div class="stats-showcase-grid">
                    <?php foreach ($hero_stats as $stat): ?>
                    <div class="stat-showcase">
                        <div class="stat-number"><?php echo esc_html($stat['value']); ?></div>
                        <div class="stat-label"><?php echo esc_html($stat['label']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="stats-card-footer">
                    <a href="<?php echo esc_url($hero_tool['url']); ?>" class="stats-card-cta">
                        Get <?php echo esc_html(str_replace(array('Backtesting ', 'Simulator'), array('', 'Tool'), $hero_tool['name'])); ?> →
                    </a>
                </div>
            </div>
            
            <!-- Tool Benefits Process -->
            <div class="hero-process-card">
                <h4>Upgrade Your Analysis:</h4>
                <div class="process-steps">
                    <div class="process-step">
                        <span class="step-number">1</span>
                        <span class="step-text">Install Tool</span>
                    </div>
                    <div class="process-step">
                        <span class="step-number">2</span>
                        <span class="step-text">Analyze Markets</span>
                    </div>
                    <div class="process-step">
                        <span class="step-number">3</span>
                        <span class="step-text">Better Entries</span>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    <?php
}

/**
 * Featured Tool Section - Dynamic Featured Tool
 */
function doittrading_featured_tool_section() {
    // Get featured tool data
    $featured_tool = doittrading_get_featured_indicator();
    
    // Get benefits and stats from custom fields if product ID exists
    $benefits = array();
    $stats = array();
    $before_after_data = array();

    if (isset($featured_tool['id']) && $featured_tool['id'] > 0) {
        $product_id = $featured_tool['id'];
        $benefits = doittrading_get_product_benefits($product_id);
        $stats = doittrading_get_product_stats($product_id);
        
        // Get before/after comparison data from ACF (if exists)
        $before_after_data = array(
            'before_win_rate' => get_field('before_win_rate', $product_id) ?: '60%',
            'after_win_rate' => get_field('after_win_rate', $product_id) ?: '79%',
            'time_saved' => get_field('time_saved_percentage', $product_id) ?: '65%',
            'losses_prevented' => get_field('avg_losses_prevented', $product_id) ?: '$2,600'
        );
    }
    
    // Fallback benefits if none from custom fields
    if (empty($benefits)) {
        $benefits = array(
            array(
                'icon' => 'lightning',
                'title' => '6x Faster Testing',
                'description' => 'Test months of data in minutes, not hours'
            ),
            array(
                'icon' => 'chart-line',
                'title' => 'Real Market Data',
                'description' => 'Uses actual broker historical data'
            ),
            array(
                'icon' => 'target',
                'title' => 'Strategy Validation',
                'description' => 'Prove your edge before going live'
            ),
            array(
                'icon' => 'tools',
                'title' => 'Easy Setup',
                'description' => 'Works with any MT4/MT5 strategy'
            )
        );
    }
    
    // Fallback stats if none from custom fields
    if (empty($stats)) {
        $stats = array(
            array('value' => '79%', 'label' => 'Average win rate<br>improvement'),
            array('value' => '65%', 'label' => 'Less time spent<br>testing'),
            array('value' => '$2,600', 'label' => 'Average losses<br>prevented')
        );
    }

    // Map icon names to emojis
    $icon_map = array(
        'lightning' => '⚡',
        'chart-line' => '📊',
        'target' => '🎯',
        'bullseye' => '🎯',
        'tools' => '🛠️',
        'shield-check' => '🛡️',
        'clock' => '⏱️',
        'trophy' => '🏆',
        'users' => '👥',
        'dollar-sign' => '💰',
        'trending-up' => '📈'
    );
    
    // Get additional product data
    $product_url = isset($featured_tool['id']) ? get_permalink($featured_tool['id']) : $featured_tool['url'];
    $mql5_link = get_field('mql5_purchase_link_mt4', $featured_tool['id']) ?: get_field('mql5_purchase_link_mt5', $featured_tool['id']);
    ?>
    <div class="doittrading-featured-tool-section">
        <div class="featured-tool-container">
            
            <!-- Section Header -->
            <div class="featured-tool-header">
                <div class="tool-badge bestseller">⭐ #1 DOWNLOADED</div>
                <h2><?php echo esc_html($featured_tool['name']); ?></h2>
                <p class="tool-stats"><?php echo esc_html($featured_tool['downloads']); ?> downloads | <?php echo esc_html($featured_tool['feature']); ?></p>
                <p class="tool-description">
                    "<?php echo esc_html($featured_tool['description'] ?: 'Professional trading tool for better market analysis'); ?>"
                </p>
            </div>
            
            <!-- Tool Showcase -->
            <div class="tool-showcase-grid">
                
                <!-- Left: Benefits -->
                <div class="tool-benefits">
                    <h3>Why Traders Love It:</h3>
                    <div class="benefits-list">
                        <?php foreach ($benefits as $benefit): ?>
                        <div class="benefit-item">
                            <span class="benefit-icon">
                                <?php echo isset($icon_map[$benefit['icon']]) ? $icon_map[$benefit['icon']] : '✓'; ?>
                            </span>
                            <div class="benefit-content">
                                <strong><?php echo esc_html($benefit['title']); ?></strong>
                                <p><?php echo esc_html($benefit['description']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Right: NEW Visual Showcase -->
                <div class="tool-visual-showcase">
                    <div class="visual-header">
                        <h4>See The Difference</h4>
                        <div class="live-indicator">
                            <div class="live-dot"></div>
                            <span>LIVE COMPARISON</span>
                        </div>
                    </div>
                    
                    <!-- Comparison Charts -->
                    <div class="comparison-charts">
                        <div class="chart-container before">
                            <span class="chart-label">❌ Without Tool</span>
                            <div class="chart-content">
                                <div class="price-line"></div>
                                <!-- Candles representing poor performance -->
                                <div class="candle red" style="height: 30px; bottom: 40%; left: 20%;"></div>
                                <div class="candle red" style="height: 25px; bottom: 35%; left: 30%;"></div>
                                <div class="candle green" style="height: 20px; bottom: 45%; left: 40%;"></div>
                                <div class="candle red" style="height: 35px; bottom: 30%; left: 50%;"></div>
                                <div class="candle red" style="height: 28px; bottom: 25%; left: 60%;"></div>
                                <div class="signal-marker missed-signal">Missed Entry</div>
                                <div class="signal-marker late-exit" style="bottom: 20px; left: 60%;">Late Exit</div>
                            </div>
                        </div>
                        
                        <div class="chart-container after">
                            <span class="chart-label">✅ With <?php echo esc_html(str_replace('Backtesting Simulator', 'Simulator', $featured_tool['name'])); ?></span>
                            <div class="chart-content">
                                <div class="price-line"></div>
                                <!-- Candles representing good performance -->
                                <div class="candle green" style="height: 35px; bottom: 40%; left: 20%;"></div>
                                <div class="candle green" style="height: 40px; bottom: 45%; left: 30%;"></div>
                                <div class="candle green" style="height: 30px; bottom: 50%; left: 40%;"></div>
                                <div class="candle green" style="height: 45px; bottom: 55%; left: 50%;"></div>
                                <div class="candle green" style="height: 50px; bottom: 60%; left: 60%;"></div>
                                <div class="signal-marker perfect-entry">Perfect Entry</div>
                                <div class="signal-marker smart-exit">Smart Exit</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Impact Stats -->
                    <div class="visual-stats">
                        <?php foreach ($stats as $stat): ?>
                        <div class="visual-stat">
                            <div class="stat-value"><?php echo esc_html($stat['value']); ?></div>
                            <div class="stat-label"><?php echo $stat['label']; ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- CTA Section -->
                    <div class="visual-cta-section">
                        <?php if ($mql5_link): ?>
                            <a href="<?php echo esc_url($mql5_link); ?>" target="_blank" class="cta-primary">
                                Get <?php echo esc_html($featured_tool['name']); ?> ($<?php echo esc_html($featured_tool['price']); ?>)
                            </a>
                        <?php else: ?>
                            <a href="<?php echo esc_url($product_url); ?>" class="cta-primary">
                                Get <?php echo esc_html($featured_tool['name']); ?> ($<?php echo esc_html($featured_tool['price']); ?>)
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url($product_url); ?>" class="cta-secondary">
                            View Details
                        </a>
                    </div>
                    
                    <!-- Trust Badges -->
                    <div class="tool-trust-badges">
                        <div class="trust-badge-item">
                            ✓ <strong>Instant Download</strong>
                        </div>
                        <div class="trust-badge-item">
                            ✓ <strong>Free Updates</strong>
                        </div>
                        <div class="trust-badge-item">
                            ✓ <strong>24/7 Support</strong>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * ICT Tools Section
 */
function doittrading_ict_tools_section() {
    // Get ICT tools dynamically
    $ict_query = doittrading_get_indicator_products('ict', 3, 'downloads');
    $ict_tools = array();
    
    if ($ict_query->have_posts()) {
        while ($ict_query->have_posts()) {
            $ict_query->the_post();
            $product_id = get_the_ID();
            $tool_data = doittrading_format_indicator_data($product_id);
            
            if ($tool_data) {
                $ict_tools[] = array(
                    'name' => $tool_data['name'],
                    'description' => $tool_data['description'],
                    'downloads' => $tool_data['downloads'],
                    'rating' => $tool_data['rating'],
                    'price' => $tool_data['price'],
                    'url' => $tool_data['url'],
                    'image' => $tool_data['image'] // Add image for potential future use
                );
            }
        }
        wp_reset_postdata();
    }
    
    // Fallback data if no ICT tools found
    if (empty($ict_tools)) {
        $ict_tools = array(
            array(
                'name' => 'Order Blocks ICT Multi TF',
                'description' => 'Identify institutional order blocks across multiple timeframes',
                'downloads' => '127',
                'rating' => '4.6',
                'price' => '$89',
                'url' => '#'
            ),
            array(
                'name' => 'ICT Breakers Multi TF', 
                'description' => 'Spot market structure breaks with ICT methodology',
                'downloads' => '94',
                'rating' => '4.8',
                'price' => '$79',
                'url' => '#'
            ),
            array(
                'name' => 'SMT Divergences',
                'description' => 'Smart Money Tool divergences for better entries',
                'downloads' => '156',
                'rating' => '4.7', 
                'price' => '$69',
                'url' => '#'
            )
        );
    }
    ?>
    <div class="doittrading-ict-section">
        <div class="ict-container">
            
            <!-- Section Header -->
            <div class="ict-header">
                <div class="ict-badge">📈 TRENDING CATEGORY</div>
                <h2>ICT Smart Money Tools</h2>
                <p class="ict-subtitle">"Trade like the institutions"</p>
            </div>
            
            <!-- ICT Tools Grid -->
            <div class="ict-tools-grid">
                <?php foreach ($ict_tools as $tool): ?>
                <div class="ict-tool-card">
                    <div class="tool-header">
                        <h3><?php echo esc_html($tool['name']); ?></h3>
                        <div class="tool-meta">
                            <span class="downloads">⬇️ <?php echo esc_html($tool['downloads']); ?></span>
                            <span class="rating">⭐ <?php echo esc_html($tool['rating']); ?></span>
                        </div>
                    </div>
                    
                    <p class="tool-description"><?php echo esc_html($tool['description']); ?></p>
                    
                    <div class="tool-footer">
                        <span class="tool-price">$<?php echo esc_html($tool['price']); ?></span>
                        <a href="<?php echo esc_url($tool['url']); ?>" class="tool-btn">
                            View Tool →
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- ICT Section Footer -->
            <div class="ict-footer">
                <p class="ict-note">
                    <strong>ICT Based:</strong> All tools follow Inner Circle Trader methodology for institutional market analysis
                </p>
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 4. Tools Grid Section - Catalog completo
 */
function doittrading_tools_grid_section() {
    // Get all indicator tools dynamically
    $tools_query = doittrading_get_indicator_products('all', 6, 'downloads');
    $all_tools = array();
    
    if ($tools_query->have_posts()) {
        while ($tools_query->have_posts()) {
            $tools_query->the_post();
            $product_id = get_the_ID();
            $tool_data = doittrading_format_indicator_data($product_id);
            
            if ($tool_data) {
                $all_tools[] = array(
                    'id' => 'tool-' . $product_id,
                    'name' => $tool_data['name'],
                    'rating' => $tool_data['rating'],
                    'downloads' => $tool_data['downloads'],
                    'feature' => $tool_data['feature'],
                    'price' => $tool_data['price'],
                    'regular_price' => $tool_data['regular_price'],
                    'url' => $tool_data['url'],
                    'premium' => $tool_data['is_premium'],
                    'image' => $tool_data['image'] // Add product image
                );
            }
        }
        wp_reset_postdata();
    }
    
    // Fallback data if no tools found
    if (empty($all_tools)) {
        $all_tools = array(
            array(
                'id' => 'backtesting-simulator',
                'name' => 'Backtesting Simulator',
                'rating' => '4.8',
                'downloads' => '309',
                'feature' => 'Test 6 months in 1 hour',
                'price' => '$149',
                'url' => '/product/backtesting-simulator/',
                'premium' => true
            ),
            array(
                'id' => 'smt-divergences',
                'name' => 'SMT Divergences',
                'rating' => '4.6',
                'downloads' => '156',
                'feature' => 'Institutional divergence detection',
                'price' => '$89',
                'url' => '#',
                'premium' => false
            ),
            array(
                'id' => 'fear-greed-index',
                'name' => 'Fear & Greed Index',
                'rating' => '4.7',
                'downloads' => '234',
                'feature' => 'Market sentiment analysis',
                'price' => '$79',
                'url' => '#',
                'premium' => false
            ),
            array(
                'id' => 'order-blocks-ict',
                'name' => 'Order Blocks ICT Multi TF',
                'rating' => '4.9',
                'downloads' => '187',
                'feature' => 'Multi-timeframe order blocks',
                'price' => '$99',
                'url' => '#',
                'premium' => false
            ),
            array(
                'id' => 'ict-breakers',
                'name' => 'ICT Breakers Multi TF',
                'rating' => '4.5',
                'downloads' => '143',
                'feature' => 'Market structure breaks',
                'price' => '$79',
                'url' => '#',
                'premium' => false
            )
        );
    }
    ?>
    <div class="doittrading-tools-grid-section" id="tools-grid">
        <div class="tools-grid-container">
            
            <!-- Section Header -->
            <div class="tools-grid-header">
                <h2>Complete Trading Tools Collection</h2>
                <p>Professional indicators for every trading style and strategy</p>
            </div>
            
            <!-- Tools Grid -->
            <div class="tools-grid">
                <?php foreach ($all_tools as $tool): ?>
                <div class="tool-card <?php echo $tool['premium'] ? 'premium' : ''; ?>">
                    
                    <!-- Tool Image/Icon Area -->
                    <div class="tool-image-area">
                        <?php if (!empty($tool['image'])): ?>
                            <img src="<?php echo esc_url($tool['image']); ?>" alt="<?php echo esc_attr($tool['name']); ?>" class="tool-image">
                        <?php else: ?>
                            <div class="tool-icon">
                                📊
                            </div>
                        <?php endif; ?>
                        <div class="tool-overlay">
                            <span class="downloads">⬇️ <?php echo esc_html($tool['downloads']); ?> downloads</span>
                        </div>
                        <?php if ($tool['premium']): ?>
                            <div class="premium-badge">⭐ PREMIUM</div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Tool Content -->
                    <div class="tool-content">
                        <h3 class="tool-name"><?php echo esc_html($tool['name']); ?></h3>
                        
                        <div class="tool-rating">
                            <span class="stars" data-rating="<?php echo esc_attr($tool['rating']); ?>">
                                <?php 
                                $rating = floatval($tool['rating']);
                                
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $rating) {
                                        // Full star
                                        echo '<span class="star full">★</span>';
                                    } elseif ($i - 0.5 <= $rating) {
                                        // Half star
                                        echo '<span class="star half">★</span>';
                                    } else {
                                        // Empty star
                                        echo '<span class="star empty">★</span>';
                                    }
                                }
                                ?>
                            </span>
                            <span class="rating-value"><?php echo esc_html($tool['rating']); ?></span>
                        </div>
                        
                        <p class="tool-feature"><?php echo esc_html($tool['feature']); ?></p>
                        
                        <div class="tool-card-footer">
                            <div class="tool-card-price">
                                <?php if ($tool['price'] == 0): ?>
                                    <span class="price-free">FREE</span>
                                <?php elseif (isset($tool['regular_price']) && $tool['regular_price'] && $tool['price'] < $tool['regular_price']): ?>
                                    <span class="price-current">$<?php echo esc_html($tool['price']); ?></span>
                                    <span class="price-original">$<?php echo esc_html($tool['regular_price']); ?></span>
                                <?php else: ?>
                                    <span class="price-current">$<?php echo esc_html($tool['price']); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo esc_url($tool['url']); ?>" class="tool-card-cta">
                                Get Now
                            </a>
                        </div>
                    </div>
                    
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Grid Footer -->
            <div class="tools-grid-footer">
                <p class="grid-note">
                    All tools work with MT4 & MT5 | Regular updates included | 24/7 support
                </p>
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 5. Before/After Section - Proof of concept
 */
function doittrading_before_after_section() {
    ?>
    <div class="doittrading-before-after-section" id="before-after">
        <div class="before-after-container">
            
            <!-- Section Header -->
            <div class="before-after-header">
                <h2>See the Difference Professional Tools Make</h2>
                <p>Real examples from traders using our indicators</p>
            </div>
            
            <!-- Comparison Grid -->
            <div class="comparison-grid">
                
                <!-- Before: Basic Charts -->
                <div class="comparison-side before">
                    <div class="comparison-header">
                        <h3>❌ Before: Basic Charts</h3>
                        <p class="comparison-subtitle">Trading with standard MT4/MT5 tools only</p>
                    </div>
                    
                    <div class="comparison-image">
                        <div class="chart-mockup basic">
                            <div class="basic-chart">
                                <div class="price-line"></div>
                                <div class="missed-entry">❌ Missed Entry</div>
                                <div class="late-exit">❌ Late Exit</div>
                            </div>
                        </div>
                    </div>
                    
                    <ul class="comparison-points">
                        <li>❌ Missed entry signals</li>
                        <li>❌ Poor timing decisions</li>
                        <li>❌ 60% win rate average</li>
                        <li>❌ Emotional trading</li>
                        <li>❌ No institutional insight</li>
                    </ul>
                </div>
                
                <!-- After: Professional Tools -->
                <div class="comparison-side after">
                    <div class="comparison-header">
                        <h3>✅ After: Professional Tools</h3>
                        <p class="comparison-subtitle">Trading with DoItTrading indicators</p>
                    </div>
                    
                    <div class="comparison-image">
                        <div class="chart-mockup professional">
                            <div class="professional-chart">
                                <div class="price-line"></div>
                                <div class="order-block">📋 Order Block</div>
                                <div class="perfect-entry">✅ Perfect Entry</div>
                                <div class="smart-exit">✅ Smart Exit</div>
                                <div class="smt-signal">📊 SMT Signal</div>
                            </div>
                        </div>
                    </div>
                    
                    <ul class="comparison-points">
                        <li>✅ Precise signal timing</li>
                        <li>✅ Better entry points</li>
                        <li>✅ 78% win rate average</li>
                        <li>✅ Confident decisions</li>
                        <li>✅ Institutional insight</li>
                    </ul>
                </div>
                
            </div>
            
            <!-- Results Summary -->
            <div class="results-summary">
                <div class="summary-header">
                    <h3>Real Impact on Trading Performance</h3>
                </div>
                
                <div class="results-stats">
                    <div class="result-stat">
                        <div class="result-number">+18%</div>
                        <div class="result-label">Win Rate<br>Improvement</div>
                    </div>
                    <div class="result-stat">
                        <div class="result-number">2.3x</div>
                        <div class="result-label">Better Risk/<br>Reward Ratio</div>
                    </div>
                    <div class="result-stat">
                        <div class="result-number">-40%</div>
                        <div class="result-label">Fewer Bad<br>Trades</div>
                    </div>
                    <div class="result-stat">
                        <div class="result-number">65%</div>
                        <div class="result-label">Less Time<br>Analyzing</div>
                    </div>
                </div>
                
                <div class="results-note">
                    <p><strong>Based on data from 500+ traders</strong> using our indicators vs standard tools over 6 months</p>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 6. Trader Testimonials Section - Social proof
 */
function doittrading_indicators_testimonials_section() {
    // Try to get testimonials from actual products first
    $dynamic_testimonials = doittrading_get_indicator_testimonials(6);
    
    // Fallback testimonials if no dynamic ones found
    $fallback_testimonials = array(
        array(
            'name' => 'Alex K.',
            'level' => 'Pro Trader',
            'country' => 'London, UK',
            'rating' => 5,
            'text' => 'My win rate improved from 60% to 78% using the Order Blocks indicator. Finally understand how institutions move the market.',
            'tool' => 'Order Blocks ICT Multi TF',
            'timeframe' => '3 months'
        ),
        array(
            'name' => 'Maria S.',
            'level' => 'Forex Trader',
            'country' => 'Madrid, Spain',
            'rating' => 5,
            'text' => 'The Backtesting Simulator saved me months of trial and error. Tested my strategy in one weekend and found the optimal settings.',
            'tool' => 'Backtesting Simulator',
            'timeframe' => '1 week'
        ),
        array(
            'name' => 'James W.',
            'level' => 'ICT Student',
            'country' => 'New York, USA',
            'rating' => 4,
            'text' => 'SMT Divergences helped me spot market manipulation before major moves. Essential tool for any ICT trader.',
            'tool' => 'SMT Divergences',
            'timeframe' => '2 months'
        ),
        array(
            'name' => 'Sarah L.',
            'level' => 'Day Trader',
            'country' => 'Sydney, Australia',
            'rating' => 5,
            'text' => 'Fear & Greed Index gives me the edge I needed. Now I can time my entries when sentiment is extreme.',
            'tool' => 'Fear & Greed Index',
            'timeframe' => '6 weeks'
        ),
        array(
            'name' => 'Michael B.',
            'level' => 'Swing Trader',
            'country' => 'Toronto, Canada',
            'rating' => 5,
            'text' => 'ICT Breakers completely changed how I see market structure. Catching breakouts before they happen is game-changing.',
            'tool' => 'ICT Breakers Multi TF',
            'timeframe' => '4 months'
        ),
        array(
            'name' => 'Lisa R.',
            'level' => 'Professional Trader',
            'country' => 'Frankfurt, Germany',
            'rating' => 5,
            'text' => 'These tools gave me institutional-level analysis. My clients are impressed with the consistent performance.',
            'tool' => 'Complete Collection',
            'timeframe' => '8 months'
        )
    );
    
    $testimonials = !empty($dynamic_testimonials) ? $dynamic_testimonials : $fallback_testimonials;
    ?>
    <div class="doittrading-indicators-testimonials-section">
        <div class="testimonials-container">
            
            <!-- Section Header -->
            <div class="testimonials-header">
                <h2>What Professional Traders Say</h2>
                <p>Real feedback from traders who upgraded their analysis</p>
            </div>
            
            <!-- Testimonials Grid -->
            <div class="testimonials-grid">
                <?php foreach ($testimonials as $testimonial): ?>
                <div class="testimonial-card">
                    
                    <!-- Testimonial Header -->
                    <div class="testimonial-card-header">
                        <div class="trader-info">
                            <strong class="trader-name"><?php echo esc_html($testimonial['name']); ?></strong>
                            <span class="trader-level"><?php echo esc_html($testimonial['level']); ?></span>
                            <span class="trader-location"><?php echo esc_html($testimonial['country']); ?></span>
                        </div>
                        <div class="testimonial-rating">
                            <?php echo str_repeat('⭐', $testimonial['rating']); ?>
                        </div>
                    </div>
                    
                    <!-- Testimonial Content -->
                    <div class="testimonial-content">
                        <p class="testimonial-text">
                            "<?php echo esc_html($testimonial['text']); ?>"
                        </p>
                    </div>
                    
                    <!-- Testimonial Footer -->
                    <div class="testimonial-footer">
                        <div class="tool-used">
                            <strong>Used:</strong> <?php echo esc_html($testimonial['tool']); ?>
                        </div>
                        <div class="timeframe">
                            <strong>Results in:</strong> <?php echo esc_html($testimonial['timeframe']); ?>
                        </div>
                    </div>
                    
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Success Stats -->
            <div class="success-stats-section">
                <?php 
                $success_stats = doittrading_get_indicator_stats();
                ?>
                <div class="success-header">
                    <h3>Join <?php echo number_format($success_stats['active_users']); ?>+ Successful Traders</h3>
                </div>
                
                <div class="success-stats">
                    <div class="success-stat">
                        <div class="success-number"><?php echo number_format($success_stats['active_users']); ?>+</div>
                        <div class="success-label">Active Users</div>
                    </div>
                    <div class="success-stat">
                        <div class="success-number">78%</div>
                        <div class="success-label">Avg Win Rate</div>
                    </div>
                    <div class="success-stat">
                        <div class="success-number"><?php echo number_format($success_stats['total_downloads']); ?></div>
                        <div class="success-label">Total Downloads</div>
                    </div>
                    <div class="success-stat">
                        <div class="success-number">4.7★</div>
                        <div class="success-label">Avg Rating</div>
                    </div>
                </div>
            </div>
            
            <!-- CTA Section -->
            <div class="testimonials-cta">
                <h3>Ready to Upgrade Your Analysis?</h3>
                <p>Join professional traders who enhanced their edge with our tools</p>
                <div class="testimonials-cta-buttons">
                    <a href="#tools-grid" class="testimonials-cta-primary">
                        Browse All Tools
                    </a>
                    <a href="/doit-chat/" class="testimonials-cta-secondary">
                        Get Recommendations
                    </a>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}
?>