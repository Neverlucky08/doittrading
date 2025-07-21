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

// Placeholder functions for remaining sections
function doittrading_tools_grid_section() {
    echo '<div class="placeholder-section"><h2>Tools Grid Section - Coming Next</h2></div>';
}

function doittrading_before_after_section() {
    echo '<div class="placeholder-section"><h2>Before/After Section - Coming Next</h2></div>';
}

function doittrading_indicators_testimonials_section() {
    echo '<div class="placeholder-section"><h2>Testimonials Section - Coming Next</h2></div>';
}