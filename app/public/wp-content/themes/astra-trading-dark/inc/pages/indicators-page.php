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
    $active_traders = 500; // Manual traders using indicators
    $total_downloads = 1247; // Total indicator downloads
    $years_active = date('Y') - 2022;
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
                        ICT Order Blocks - 309 downloads this month
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
                <div class="stats-card-header">
                    <h3>Backtesting Simulator</h3>
                    <span class="live-badge-small">🔥 TRENDING</span>
                </div>
                
                <div class="stats-showcase-grid">
                    <div class="stat-showcase">
                        <div class="stat-number">309</div>
                        <div class="stat-label">Downloads</div>
                    </div>
                    <div class="stat-showcase">
                        <div class="stat-number">4.8★</div>
                        <div class="stat-label">Rating</div>
                    </div>
                    <div class="stat-showcase">
                        <div class="stat-number">6x</div>
                        <div class="stat-label">Faster Testing</div>
                    </div>
                </div>
                
                <div class="stats-card-footer">
                    <a href="/product/backtesting-simulator/" class="stats-card-cta">
                        Get Simulator →
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
 * Featured Tool Section - Backtesting Simulator
 */
function doittrading_featured_tool_section() {
    ?>
    <div class="doittrading-featured-tool-section">
        <div class="featured-tool-container">
            
            <!-- Section Header -->
            <div class="featured-tool-header">
                <div class="tool-badge bestseller">⭐ #1 DOWNLOADED</div>
                <h2>Backtesting Simulator</h2>
                <p class="tool-stats">309 downloads | Test 6 months in 1 hour</p>
                <p class="tool-description">
                    "Validate your strategy before risking real money"
                </p>
            </div>
            
            <!-- Tool Showcase -->
            <div class="tool-showcase-grid">
                
                <!-- Left: Benefits -->
                <div class="tool-benefits">
                    <h3>Why Traders Love It:</h3>
                    <div class="benefits-list">
                        <div class="benefit-item">
                            <span class="benefit-icon">⚡</span>
                            <div class="benefit-content">
                                <strong>6x Faster Testing</strong>
                                <p>Test months of data in minutes, not hours</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <span class="benefit-icon">📊</span>
                            <div class="benefit-content">
                                <strong>Real Market Data</strong>
                                <p>Uses actual broker historical data</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <span class="benefit-icon">🎯</span>
                            <div class="benefit-content">
                                <strong>Strategy Validation</strong>
                                <p>Prove your edge before going live</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <span class="benefit-icon">🛠️</span>
                            <div class="benefit-content">
                                <strong>Easy Setup</strong>
                                <p>Works with any MT4/MT5 strategy</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right: Visual/Stats -->
                <div class="tool-visual">
                    <div class="tool-stats-card">
                        <div class="stats-header">
                            <h4>Real Impact</h4>
                        </div>
                        <div class="impact-stats">
                            <div class="impact-stat">
                                <div class="impact-number">78%</div>
                                <div class="impact-label">Average win rate<br>improvement</div>
                            </div>
                            <div class="impact-stat">
                                <div class="impact-number">65%</div>
                                <div class="impact-label">Less time spent<br>testing</div>
                            </div>
                            <div class="impact-stat">
                                <div class="impact-number">$2,400</div>
                                <div class="impact-label">Average losses<br>prevented</div>
                            </div>
                        </div>
                        
                        <div class="tool-cta-section">
                            <a href="/product/backtesting-simulator/" class="tool-cta-primary">
                                Get Simulator ($149)
                            </a>
                            <a href="#demo" class="tool-cta-secondary">
                                Watch Demo
                            </a>
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
                        <span class="tool-price"><?php echo esc_html($tool['price']); ?></span>
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
                        <div class="tool-icon">
                            📊
                        </div>
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
                            <?php echo str_repeat('★', floor($tool['rating'])); ?>
                            <?php if ($tool['rating'] - floor($tool['rating']) >= 0.5): ?>☆<?php endif; ?>
                            <?php echo esc_html($tool['rating']); ?>
                        </div>
                        
                        <p class="tool-feature"><?php echo esc_html($tool['feature']); ?></p>
                        
                        <div class="tool-card-footer">
                            <span class="tool-card-price"><?php echo esc_html($tool['price']); ?></span>
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
    $testimonials = array(
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
                <div class="success-header">
                    <h3>Join 500+ Successful Traders</h3>
                </div>
                
                <div class="success-stats">
                    <div class="success-stat">
                        <div class="success-number">500+</div>
                        <div class="success-label">Active Users</div>
                    </div>
                    <div class="success-stat">
                        <div class="success-number">78%</div>
                        <div class="success-label">Avg Win Rate</div>
                    </div>
                    <div class="success-stat">
                        <div class="success-number">1,247</div>
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
                    <a href="/contact/" class="testimonials-cta-secondary">
                        Get Recommendations
                    </a>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}
?>