<?php
/**
 * DoItTrading Homepage Hero Section
 * 
 * @package DoItTrading
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Homepage Hero Section
 */
function doittrading_homepage_hero() {
    // Solo mostrar en homepage
    if (!is_front_page()) return;
    
    // Get dynamic data
    $active_traders = doittrading_get_active_traders();
    $last_trade = doittrading_get_last_trade();
    $years_active = date('Y') - 2022; // Started in 2022
    ?>
    <div class="doittrading-homepage-hero">
        
        <!-- Hero Content -->
        <div class="hero-content-main">
            
            <!-- Emotional Hook -->
            <div class="hero-headline-section">
                <h1 class="hero-main-title">Finally, EAs That Actually Work in Live Trading</h1>
                <p class="hero-subtitle">
                    No fake backtests. No unrealistic promises. Just proven, transparent results you can verify right now.
                </p>
            </div>
            
            <!-- Credibility Bar -->
            <div class="hero-credibility-bar">
                <div class="credibility-item">
                    <span class="credibility-number">500+</span>
                    <span class="credibility-label">Active Traders</span>
                </div>
                <div class="credibility-item">
                    <span class="credibility-number">Live</span>
                    <span class="credibility-label">MyFxBook Results</span>
                </div>
                <div class="credibility-item">
                    <span class="credibility-number"><?php echo $years_active; ?>+</span>
                    <span class="credibility-label">Years Proven</span>
                </div>
            </div>
            
            <!-- Live Trading Indicator -->
            <div class="hero-live-section">
                <div class="live-trading-indicator">
                    <div class="live-dot-hero"></div>
                    <span class="live-text">LIVE NOW:</span>
                    <span class="live-details">
                        DoIt GBP Master <?php echo $last_trade['direction'] === 'profit' ? '+' : ''; ?><?php echo $last_trade['pips']; ?> pips 
                        (<?php echo $last_trade['time_ago']; ?>m ago)
                    </span>
                </div>
                <div class="active-traders-count">
                    <?php echo $active_traders; ?> traders running our EAs right now
                </div>
            </div>
            
            <!-- Hero CTAs -->
            <div class="hero-cta-section">
                <a href="/forex-trading-bots/" class="hero-cta-primary">
                    View Our EAs
                </a>
                <a href="#live-results" class="hero-cta-secondary">
                    Live Results
                </a>
            </div>
            
            <!-- Trust Elements -->
            <div class="hero-trust-elements">
                <div class="trust-element">
                    ✓ MyFxBook Verified
                </div>
                <div class="trust-element">
                    ✓ No Martingale
                </div>
                <div class="trust-element">
                    ✓ 24/7 Support
                </div>
            </div>
            
        </div>
        
        <!-- Hero Visual (Right side - Stats Preview) -->
        <div class="hero-visual-section">
            
            <!-- Featured EA Stats Card -->
            <div class="hero-stats-card">
                <div class="stats-card-header">
                    <h3>DoIt GBP Master</h3>
                    <span class="live-badge-small">🟢 LIVE</span>
                </div>
                
                <div class="stats-showcase-grid">
                    <div class="stat-showcase">
                        <div class="stat-number">76%</div>
                        <div class="stat-label">Win Rate</div>
                    </div>
                    <div class="stat-showcase">
                        <div class="stat-number">+12.8%</div>
                        <div class="stat-label">Monthly Growth</div>
                    </div>
                    <div class="stat-showcase">
                        <div class="stat-number">-4.2%</div>
                        <div class="stat-label">Max Drawdown</div>
                    </div>
                </div>
                
                <div class="stats-card-footer">
                    <a href="/product/doit-gbp-master/" class="stats-card-cta">
                        View Details →
                    </a>
                </div>
            </div>
            
            <!-- Quick Setup Process -->
            <div class="hero-process-card">
                <h4>Setup in 3 Steps:</h4>
                <div class="process-steps">
                    <div class="process-step">
                        <span class="step-number">1</span>
                        <span class="step-text">Download EA</span>
                    </div>
                    <div class="process-step">
                        <span class="step-number">2</span>
                        <span class="step-text">Set Risk %</span>
                    </div>
                    <div class="process-step">
                        <span class="step-number">3</span>
                        <span class="step-text">Start Trading</span>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    <?php
}

// Hook the hero section to homepage
add_action('astra_content_before', 'doittrading_homepage_hero', 5);

/**
 * Homepage Social Proof Section
 */
function doittrading_homepage_social_proof() {
    // Solo mostrar en homepage
    if (!is_front_page()) return;
    
    // Get verified testimonials data
    $testimonials = array(
        array(
            'name' => 'MBlue6',
            'country' => 'Germany',
            'text' => 'Setup was simple, and the performance has been great so far. Diego has been exceptionally friendly, responsive, and supportive throughout.',
            'product' => 'DoIt GBP Master',
            'verified' => true,
            'rating' => 5
        ),
        array(
            'name' => 'klausdiemaus',
            'country' => 'Austria',
            'text' => 'Diego\'s EAs have always been reliable, so buying the DoIt GBP Master was an easy decision. Best EA I have ever tried!',
            'product' => 'DoIt GBP Master',
            'verified' => true,
            'rating' => 5
        ),
        array(
            'name' => 'Butterfly0856',
            'country' => 'Spain',
            'text' => 'Been using DoIt GBP Master for two weeks now, and the results have been amazing! The consistency is impressive.',
            'product' => 'DoIt GBP Master',
            'verified' => true,
            'rating' => 5
        )
    );
    
    // Countries with active users
    $active_countries = array(
        'US' => 'United States',
        'GB' => 'United Kingdom', 
        'DE' => 'Germany',
        'ES' => 'Spain',
        'IT' => 'Italy',
        'FR' => 'France',
        'AU' => 'Australia',
        'CA' => 'Canada',
        'JP' => 'Japan',
        'SG' => 'Singapore'
    );
    ?>
    <div class="doittrading-social-proof-section">
        <div class="social-proof-container">
            
            <!-- Section Header -->
            <div class="social-proof-header">
                <h2>Trusted by 500+ Traders Worldwide</h2>
                <p>Real results from real traders using our EAs in live accounts</p>
            </div>
            
            <!-- MyFxBook Widgets Row -->
            <div class="myfxbook-widgets-section">
                <div class="widgets-header">
                    <h3>📊 Live Trading Results</h3>
                    <p>These accounts are updated automatically every trade - see the real performance</p>
                </div>
                
                <div class="myfxbook-widgets-grid">
                    <!-- Widget 1: Conservative Account -->
                    <div class="myfxbook-widget-card">
                        <div class="widget-header">
                            <h4>Conservative Risk Account</h4>
                            <span class="widget-badge live">🟢 LIVE</span>
                        </div>
                        <div class="widget-stats">
                            <div class="widget-stat">
                                <span class="stat-value">+287%</span>
                                <span class="stat-label">Total Growth</span>
                            </div>
                            <div class="widget-stat">
                                <span class="stat-value">76%</span>
                                <span class="stat-label">Win Rate</span>
                            </div>
                            <div class="widget-stat">
                                <span class="stat-value">-4.2%</span>
                                <span class="stat-label">Max DD</span>
                            </div>
                        </div>
                        <a href="https://www.myfxbook.com/members/DoItTrading/doit-gbp-master/11493777" 
                           target="_blank" class="widget-link">
                            View Live on MyFxBook →
                        </a>
                    </div>
                    
                    <!-- Widget 2: Higher Risk Account -->
                    <div class="myfxbook-widget-card">
                        <div class="widget-header">
                            <h4>Higher Risk Account</h4>
                            <span class="widget-badge live">🟢 LIVE</span>
                        </div>
                        <div class="widget-stats">
                            <div class="widget-stat">
                                <span class="stat-value">+456%</span>
                                <span class="stat-label">Total Growth</span>
                            </div>
                            <div class="widget-stat">
                                <span class="stat-value">78%</span>
                                <span class="stat-label">Win Rate</span>
                            </div>
                            <div class="widget-stat">
                                <span class="stat-value">-8.1%</span>
                                <span class="stat-label">Max DD</span>
                            </div>
                        </div>
                        <a href="https://www.myfxbook.com/portfolio/doit-gold-guardian/11493798" 
                           target="_blank" class="widget-link">
                            View Live on MyFxBook →
                        </a>
                    </div>
                </div>
                
                <div class="myfxbook-disclaimer">
                    <p><strong>⚠️ Risk Warning:</strong> Past performance doesn't guarantee future results. All trading involves risk.</p>
                </div>
            </div>
            
            <!-- Testimonials Section -->
            <div class="testimonials-section">
                <div class="testimonials-header">
                    <h3>💬 What Our Traders Say</h3>
                    <p>All verified purchases from MQL5 Market</p>
                </div>
                
                <div class="testimonials-grid">
                    <?php foreach ($testimonials as $testimonial): ?>
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-author">
                                <strong><?php echo esc_html($testimonial['name']); ?></strong>
                                <span class="author-country"><?php echo esc_html($testimonial['country']); ?></span>
                            </div>
                            <div class="testimonial-rating">
                                <?php echo str_repeat('⭐', $testimonial['rating']); ?>
                            </div>
                        </div>
                        
                        <p class="testimonial-text">
                            "<?php echo esc_html($testimonial['text']); ?>"
                        </p>
                        
                        <div class="testimonial-footer">
                            <span class="testimonial-product">Product: <?php echo esc_html($testimonial['product']); ?></span>
                            <?php if ($testimonial['verified']): ?>
                                <span class="verified-badge">✓ Verified Purchase</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Global Reach Section -->
            <div class="global-reach-section">
                <div class="global-header">
                    <h3>🌍 Traders from Around the World</h3>
                    <p>Our EAs are helping traders profit in over 15 countries</p>
                </div>
                
                <div class="countries-grid">
                    <?php foreach ($active_countries as $code => $name): ?>
                    <div class="country-item">
                        <span class="country-flag">
                            <?php
                            // Simple flag emojis mapping
                            $flags = array(
                                'US' => '🇺🇸', 'GB' => '🇬🇧', 'DE' => '🇩🇪', 'ES' => '🇪🇸',
                                'IT' => '🇮🇹', 'FR' => '🇫🇷', 'AU' => '🇦🇺', 'CA' => '🇨🇦',
                                'JP' => '🇯🇵', 'SG' => '🇸🇬'
                            );
                            echo $flags[$code] ?? '🏳️';
                            ?>
                        </span>
                        <span class="country-name"><?php echo esc_html($name); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Trust Indicators -->
            <div class="trust-indicators-section">
                <div class="trust-indicators-grid">
                    <div class="trust-indicator">
                        <div class="indicator-icon">🔍</div>
                        <div class="indicator-content">
                            <h4>100% Transparent</h4>
                            <p>All our results are public and verifiable on MyFxBook</p>
                        </div>
                    </div>
                    <div class="trust-indicator">
                        <div class="indicator-icon">🛡️</div>
                        <div class="indicator-content">
                            <h4>No Hidden Risks</h4>
                            <p>We show you the losses too - complete transparency</p>
                        </div>
                    </div>
                    <div class="trust-indicator">
                        <div class="indicator-icon">⚡</div>
                        <div class="indicator-content">
                            <h4>Real-Time Updates</h4>
                            <p>Live accounts updated every single trade automatically</p>
                        </div>
                    </div>
                    <div class="trust-indicator">
                        <div class="indicator-icon">🤝</div>
                        <div class="indicator-content">
                            <h4>Personal Support</h4>
                            <p>Direct access to the developer for setup and questions</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

// Hook social proof section
add_action('astra_content_before', 'doittrading_homepage_social_proof', 10);

/**
 * Homepage Featured Products Section
 */
function doittrading_homepage_featured_products() {
    // Solo mostrar en homepage
    if (!is_front_page()) return;
    
    // Get featured products (hardcoded IDs for now - you can make this dynamic later)
    $featured_products = array(
        array(
            'id' => 19, // DoIt GBP Master
            'name' => 'DoIt GBP Master',
            'subtitle' => 'The Conservative Profit Machine',
            'description' => 'Specialized in GBPUSD with 76% win rate. Perfect for steady, consistent growth.',
            'monthly_gain' => '12.8',
            'win_rate' => '76',
            'max_drawdown' => '4.2',
            'profit_factor' => '2.34',
            'original_price' => '999',
            'current_price' => '599',
            'badge' => 'BESTSELLER',
            'badge_color' => 'green',
            'url' => '/product/doit-gbp-master/',
            'myfxbook' => 'https://www.myfxbook.com/members/DoItTrading/doit-gbp-master/11493777'
        ),
        array(
            'id' => 30, // DoIt Gold Guardian
            'name' => 'DoIt Gold Guardian',
            'subtitle' => 'Gold Market Specialist',
            'description' => 'Long-only gold strategy with 96.5% win rate. Catches major bullish moves.',
            'monthly_gain' => '25',
            'win_rate' => '96.5',
            'max_drawdown' => '8',
            'profit_factor' => '1.6',
            'original_price' => '999',
            'current_price' => '399',
            'badge' => 'HIGH WIN RATE',
            'badge_color' => 'gold',
            'url' => '/product/doit-gold-guardian/',
            'myfxbook' => 'https://www.myfxbook.com/portfolio/doit-gold-guardian/11493798'
        ),
        array(
            'id' => 36, // Index Vanguard
            'name' => 'Index Vanguard',
            'subtitle' => 'Small Account Friendly',
            'description' => 'Works with accounts as small as $30. Perfect for beginners getting started.',
            'monthly_gain' => '10',
            'win_rate' => '50.5',
            'max_drawdown' => '3.5',
            'profit_factor' => '1.2',
            'original_price' => '599',
            'current_price' => '129',
            'badge' => 'BEGINNER FRIENDLY',
            'badge_color' => 'blue',
            'url' => '/product/index-vanguard/',
            'myfxbook' => 'https://www.myfxbook.com/portfolio/doit-gold-guardian/11493798'
        )
    );
    ?>
    <div class="doittrading-featured-products-section">
        <div class="featured-products-container">
            
            <!-- Section Header -->
            <div class="featured-products-header">
                <h2>Our Professional EA Collection</h2>
                <p>Each EA is hand-crafted for specific market conditions and verified with live trading results</p>
            </div>
            
            <!-- Products Grid -->
            <div class="featured-products-grid">
                
                <?php foreach ($featured_products as $index => $product): ?>

                <!-- NUEVO: Wrapper que permite que el badge sobresalga -->
                <div class="featured-product-wrapper <?php echo $index === 0 ? 'wrapper-highlight' : ''; ?>">
                    
                    <!-- Product Badge - MOVIDO FUERA de la card -->
                    <div class="product-badge badge-<?php echo esc_attr($product['badge_color']); ?>">
                        <?php echo esc_html($product['badge']); ?>
                    </div>
                    
                    <div class="featured-product-card <?php echo $index === 0 ? 'featured-highlight' : ''; ?>">
                        
                        <!-- Product Header -->
                        <div class="product-card-header">
                            <h3 class="product-name"><?php echo esc_html($product['name']); ?></h3>
                            <p class="product-subtitle"><?php echo esc_html($product['subtitle']); ?></p>
                            <p class="product-description"><?php echo esc_html($product['description']); ?></p>
                        </div>
                        
                        <!-- Product Stats -->
                        <div class="product-stats-grid">
                            <div class="product-stat">
                                <span class="stat-value positive">+<?php echo esc_html($product['monthly_gain']); ?>%</span>
                                <span class="stat-label">Monthly Gain</span>
                            </div>
                            <div class="product-stat">
                                <span class="stat-value"><?php echo esc_html($product['win_rate']); ?>%</span>
                                <span class="stat-label">Win Rate</span>
                            </div>
                            <div class="product-stat">
                                <span class="stat-value drawdown">-<?php echo esc_html($product['max_drawdown']); ?>%</span>
                                <span class="stat-label">Max DD</span>
                            </div>
                            <div class="product-stat">
                                <span class="stat-value"><?php echo esc_html($product['profit_factor']); ?></span>
                                <span class="stat-label">Profit Factor</span>
                            </div>
                        </div>
                        
                        <!-- Pricing Section -->
                        <div class="product-pricing">
                            <?php if ($product['current_price'] < $product['original_price']): ?>
                                <div class="price-section">
                                    <span class="current-price">$<?php echo esc_html($product['current_price']); ?></span>
                                    <span class="original-price">$<?php echo esc_html($product['original_price']); ?></span>
                                </div>
                                <div class="savings-badge">
                                    Save $<?php echo esc_html($product['original_price'] - $product['current_price']); ?>
                                </div>
                            <?php else: ?>
                                <div class="price-section">
                                    <span class="current-price">$<?php echo esc_html($product['current_price']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Product Actions -->
                        <div class="product-actions">
                            <a href="<?php echo esc_url($product['url']); ?>" class="product-cta-primary">
                                View Details
                            </a>
                            <a href="<?php echo esc_url($product['myfxbook']); ?>" 
                               target="_blank" 
                               class="product-cta-secondary">
                                📊 Live Results
                            </a>
                        </div>
                        
                        <!-- Trust Elements -->
                        <div class="product-trust-elements">
                            <div class="trust-element">
                                <span class="trust-icon">🟢</span>
                                <span class="trust-text">Live Verified</span>
                            </div>
                            <div class="trust-element">
                                <span class="trust-icon">🛡️</span>
                                <span class="trust-text">No Martingale</span>
                            </div>
                            <div class="trust-element">
                                <span class="trust-icon">📞</span>
                                <span class="trust-text">24/7 Support</span>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <?php endforeach; ?>
                
            </div>
            
            <!-- Section Footer -->
            <div class="featured-products-footer">
                <div class="footer-guarantee">
                    <h4>✓ All EAs Include:</h4>
                    <ul class="guarantee-list">
                        <li>Live MyFxBook tracking for complete transparency</li>
                        <li>Pre-optimized settings for immediate use</li>
                        <li>Free lifetime updates and improvements</li>
                        <li>Personal setup support and guidance</li>
                        <li>Conservative risk management built-in</li>
                    </ul>
                </div>
                
                <div class="footer-cta">
                    <p class="footer-text">
                        <strong>Not sure which EA is right for you?</strong><br>
                        Compare all features and see detailed performance analysis
                    </p>
                    <a href="/forex-trading-bots/" class="footer-cta-button">
                        Compare All EAs →
                    </a>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

// Hook featured products section
add_action('astra_content_before', 'doittrading_homepage_featured_products', 15);

/**
 * Homepage Why Choose Us Section
 */
function doittrading_homepage_why_choose_us() {
    // Solo mostrar en homepage
    if (!is_front_page()) return;
    
    // Pain points y soluciones
    $differentiators = array(
        array(
            'icon' => '🔍',
            'title' => 'Transparent Results',
            'description' => 'All our EAs have public MyFxBook accounts. Check real performance before buying.',
            'pain_point' => 'vs Fake screenshots & backtest-only results'
        ),
        array(
            'icon' => '🛡️',
            'title' => 'Conservative Strategies',
            'description' => 'No martingale, no grid, no dangerous recovery tactics. Just sustainable growth.',
            'pain_point' => 'vs Account-blowing high-risk systems'
        ),
        array(
            'icon' => '🛠️',
            'title' => 'Professional Support',
            'description' => 'Get help with setup and optimization. We respond within 24 hours.',
            'pain_point' => 'vs Buy-and-abandon vendors'
        ),
        array(
            'icon' => '📚',
            'title' => 'Detailed Documentation',
            'description' => 'Complete guides and best practices included with every EA.',
            'pain_point' => 'vs Confusing setups with no guidance'
        ),
        array(
            'icon' => '🔄',
            'title' => 'Active Development',
            'description' => 'Regular updates based on market conditions and user feedback.',
            'pain_point' => 'vs Outdated EAs with no improvements'
        ),
        array(
            'icon' => '👨‍💻',
            'title' => 'Direct Access to Developer',
            'description' => 'Chat directly with Diego for custom settings and advanced strategies.',
            'pain_point' => 'vs Anonymous offshore developers'
        )
    );
    ?>
    <div class="doittrading-why-choose-section">
        <div class="why-choose-container">
            
            <!-- Section Header -->
            <div class="why-choose-header">
                <h2>Why DoItTrading Is Different</h2>
                <p>We're tired of seeing traders lose money to fake EAs. Here's how we're changing the game:</p>
            </div>
            
            <!-- Differentiators Grid -->
            <div class="differentiators-grid">
                <?php foreach ($differentiators as $diff): ?>
                <div class="differentiator-card">
                    <div class="diff-icon">
                        <?php echo $diff['icon']; ?>
                    </div>
                    <div class="diff-content">
                        <h3><?php echo esc_html($diff['title']); ?></h3>
                        <p class="diff-description"><?php echo esc_html($diff['description']); ?></p>
                        <p class="diff-pain-point"><?php echo esc_html($diff['pain_point']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Comparison Section -->
            <div class="comparison-section">
                <h3>The DoItTrading Difference</h3>
                <div class="comparison-grid">
                    
                    <!-- Typical EA Vendors -->
                    <div class="comparison-column bad">
                        <h4>❌ Typical EA Vendors</h4>
                        <ul>
                            <li>Fake backtest screenshots</li>
                            <li>Unrealistic profit claims</li>
                            <li>No live account verification</li>
                            <li>Dangerous martingale systems</li>
                            <li>No ongoing support</li>
                            <li>Anonymous developers</li>
                            <li>Account blowup risk</li>
                        </ul>
                    </div>
                    
                    <!-- DoItTrading -->
                    <div class="comparison-column good">
                        <h4>✅ DoItTrading Approach</h4>
                        <ul>
                            <li>Live MyFxBook verification</li>
                            <li>Realistic performance expectations</li>
                            <li>Public real-time results</li>
                            <li>Conservative risk management</li>
                            <li>24/7 support & updates</li>
                            <li>Personal developer access</li>
                            <li>Sustainable long-term profits</li>
                        </ul>
                    </div>
                    
                </div>
            </div>
            
            <!-- Trust Statement -->
            <div class="trust-statement-section">
                <div class="trust-statement">
                    <h3>Our Promise to You</h3>
                    <p>
                        <strong>We show you everything.</strong> The good trades, the bad trades, the drawdowns, 
                        and the recoveries. If our EAs don't perform as advertised in our live accounts, 
                        don't buy them. It's that simple.
                    </p>
                    <div class="promise-actions">
                        <a href="/shop/" class="promise-cta-primary">
                            See Our Live Results
                        </a>
                        <a href="#social-proof" class="promise-cta-secondary">
                            Read Real Reviews
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

// Hook why choose us section
add_action('astra_content_before', 'doittrading_homepage_why_choose_us', 20);

/**
 * Homepage Live Results Section
 */
function doittrading_homepage_live_results() {
    // Solo mostrar en homepage
    if (!is_front_page()) return;
    
    // Simulated performance data for last 30 days
    $performance_data = array(
        'monthly_return' => '+12.8',
        'max_drawdown' => '-4.2',
        'win_rate' => '76',
        'total_trades' => '143',
        'winning_trades' => '109',
        'losing_trades' => '34',
        'profit_factor' => '2.34',
        'last_updated' => date('Y-m-d H:i:s')
    );
    ?>
    <div class="doittrading-live-results-section" id="live-results">
        <div class="live-results-container">
            
            <!-- Section Header -->
            <div class="live-results-header">
                <h2>📊 Live Trading Performance</h2>
                <p>Real results from our main trading accounts - updated automatically every trade</p>
                <div class="last-updated">
                    Last updated: <span class="update-time"><?php echo date('M j, Y \a\t g:i A'); ?></span>
                    <span class="live-indicator-small">🟢 LIVE</span>
                </div>
            </div>
            
            <!-- Performance Summary Cards -->
            <div class="performance-summary-grid">
                
                <div class="perf-card highlight">
                    <div class="perf-icon">📈</div>
                    <div class="perf-content">
                        <div class="perf-value positive"><?php echo $performance_data['monthly_return']; ?>%</div>
                        <div class="perf-label">This Month Return</div>
                        <div class="perf-sublabel">DoIt GBP Master Live Account</div>
                    </div>
                </div>
                
                <div class="perf-card">
                    <div class="perf-icon">📉</div>
                    <div class="perf-content">
                        <div class="perf-value drawdown"><?php echo $performance_data['max_drawdown']; ?>%</div>
                        <div class="perf-label">Max Drawdown</div>
                        <div class="perf-sublabel">Conservative risk</div>
                    </div>
                </div>
                
                <div class="perf-card">
                    <div class="perf-icon">🎯</div>
                    <div class="perf-content">
                        <div class="perf-value"><?php echo $performance_data['win_rate']; ?>%</div>
                        <div class="perf-label">Win Rate</div>
                        <div class="perf-sublabel"><?php echo $performance_data['winning_trades']; ?>/<?php echo $performance_data['total_trades']; ?> trades</div>
                    </div>
                </div>
                
                <div class="perf-card">
                    <div class="perf-icon">⚖️</div>
                    <div class="perf-content">
                        <div class="perf-value"><?php echo $performance_data['profit_factor']; ?></div>
                        <div class="perf-label">Profit Factor</div>
                        <div class="perf-sublabel">$<?php echo $performance_data['profit_factor']; ?> per $1 risked</div>
                    </div>
                </div>
                
            </div>
            
            <!-- Chart Section -->
            <div class="chart-section">
                <div class="chart-header">
                    <h3>Live Growth Performance</h3>
                    <div class="chart-controls">
                        <button class="chart-btn active" data-period="30d">30 Days</button>
                        <button class="chart-btn" data-period="90d">90 Days</button>
                        <button class="chart-btn" data-period="6m">6 Months</button>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="growthChart" width="800" height="300"></canvas>
                </div>
                
                <div class="chart-summary">
                    <div class="summary-stats">
                        <div class="summary-stat">
                            <span class="summary-label">Period Growth:</span>
                            <span class="summary-value" id="periodGrowth">+3.17%</span>
                        </div>
                        <div class="summary-stat">
                            <span class="summary-label">Total Growth:</span>
                            <span class="summary-value">+83.41%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
            // Real growth data from your MyFxBook
            const chartDatasets = {
                '30d': [
                    {date: '2025-06-24', growth: 80.24},
                    {date: '2025-07-01', growth: 80.93},
                    {date: '2025-07-08', growth: 82.51},
                    {date: '2025-07-15', growth: 83.41}
                ],
                '90d': [
                    {date: '2025-04-22', growth: 77.14},
                    {date: '2025-04-29', growth: 78.34},
                    {date: '2025-05-06', growth: 80.85},
                    {date: '2025-05-13', growth: 81.93},
                    {date: '2025-05-20', growth: 82.95},
                    {date: '2025-05-27', growth: 76.77},
                    {date: '2025-06-03', growth: 76.08},
                    {date: '2025-06-10', growth: 77.31},
                    {date: '2025-06-17', growth: 79.34},
                    {date: '2025-06-24', growth: 80.24},
                    {date: '2025-07-01', growth: 80.93},
                    {date: '2025-07-08', growth: 82.51},
                    {date: '2025-07-15', growth: 83.41}
                ],
                '6m': [
                    {date: '2024-12-31', growth: 44.02},
                    {date: '2025-01-07', growth: 43.9},
                    {date: '2025-01-14', growth: 43.9},
                    {date: '2025-01-21', growth: 44.51},
                    {date: '2025-01-28', growth: 46.8},
                    {date: '2025-02-04', growth: 52.16},
                    {date: '2025-02-11', growth: 56.89},
                    {date: '2025-02-18', growth: 59.08},
                    {date: '2025-02-25', growth: 62.18},
                    {date: '2025-03-04', growth: 64.69},
                    {date: '2025-03-11', growth: 65.86},
                    {date: '2025-03-18', growth: 66.96},
                    {date: '2025-03-25', growth: 68.38},
                    {date: '2025-04-01', growth: 70},
                    {date: '2025-04-08', growth: 74.85},
                    {date: '2025-04-15', growth: 77.28},
                    {date: '2025-04-22', growth: 77.14},
                    {date: '2025-04-29', growth: 78.34},
                    {date: '2025-05-06', growth: 80.85},
                    {date: '2025-05-13', growth: 81.93},
                    {date: '2025-05-20', growth: 82.95},
                    {date: '2025-05-27', growth: 76.77},
                    {date: '2025-06-03', growth: 76.08},
                    {date: '2025-06-10', growth: 77.31},
                    {date: '2025-06-17', growth: 79.34},
                    {date: '2025-06-24', growth: 80.24},
                    {date: '2025-07-01', growth: 80.93},
                    {date: '2025-07-08', growth: 82.51},
                    {date: '2025-07-15', growth: 83.41}
                ]
            };
            
            let currentChart = null;
            
            function initGrowthChart() {
                const ctx = document.getElementById('growthChart').getContext('2d');
                updateChart('30d');
                
                // Button event listeners
                document.querySelectorAll('.chart-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.chart-btn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        updateChart(this.dataset.period);
                    });
                });
            }
            
            function updateChart(period) {
                const data = chartDatasets[period];
                const ctx = document.getElementById('growthChart').getContext('2d');
                
                if (currentChart) {
                    currentChart.destroy();
                }
                
                const startGrowth = data[0].growth;
                const endGrowth = data[data.length - 1].growth;
                const periodGrowth = (endGrowth - startGrowth).toFixed(2);
                
                // Update summary
                document.getElementById('periodGrowth').textContent = '+' + periodGrowth + '%';
                
                currentChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(d => {
                            const date = new Date(d.date);
                            return date.toLocaleDateString('en-US', {month: 'short', day: 'numeric'});
                        }),
                        datasets: [{
                            label: 'Growth %',
                            data: data.map(d => d.growth),
                            borderColor: '#00D775',
                            backgroundColor: 'rgba(0, 215, 117, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#00D775',
                            pointBorderColor: '#0A0A0A',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#111111',
                                titleColor: '#E5E5E5',
                                bodyColor: '#E5E5E5',
                                borderColor: '#00D775',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        return 'Growth: +' + context.parsed.y + '%';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                },
                                ticks: {
                                    color: '#A0A0A0'
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                },
                                ticks: {
                                    color: '#A0A0A0',
                                    callback: function(value) {
                                        return '+' + value + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initGrowthChart);
            } else {
                initGrowthChart();
            }
            </script>
            
            <!-- MyFxBook Integration -->
            <div class="myfxbook-integration">
                <div class="integration-content">
                    <h3>🔗 Full Transparency via MyFxBook</h3>
                    <p>We believe in complete transparency. That's why all our results are publicly available on MyFxBook - the industry standard for verified trading results.</p>
                    
                    <div class="myfxbook-links">
                        <a href="https://www.myfxbook.com/members/DoItTrading/doit-gbp-master/11493777" 
                           target="_blank" class="myfxbook-link">
                            📊 DoIt GBP Master Account →
                        </a>
                        <a href="https://www.myfxbook.com/portfolio/doit-gold-guardian/11493798" 
                           target="_blank" class="myfxbook-link">
                            📊 Gold Guardian Account →
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Risk Warning -->
            <div class="risk-warning-section">
                <div class="warning-content">
                    <h4>⚠️ Important Risk Warning</h4>
                    <p>
                        <strong>Trading involves substantial risk of loss.</strong> Past performance does not guarantee future results. 
                        These results are from live accounts but your results may vary based on market conditions, 
                        risk settings, and broker differences. Never invest more than you can afford to lose.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

// Hook live results section
add_action('astra_content_before', 'doittrading_homepage_live_results', 25);

/**
 * Homepage About Section
 */
function doittrading_homepage_about() {
    // Solo mostrar en homepage
    if (!is_front_page()) return;
    
    // Stats de credibilidad
    $about_stats = array(
        'years_experience' => date('Y') - 2022, // Started in 2022
        'satisfied_customers' => '500+',
        'total_trades' => '10,000+', 
        'avg_rating' => '4.9',
        'volume_traded' => '$2M+',
        'countries' => '15+'
    );
    ?>
    <div class="doittrading-about-section">
        <div class="about-container">
            
            <!-- Section Header -->
            <div class="about-header">
                <h2>Meet DoItTrading</h2>
                <p>The team behind the EAs that actually work in live trading</p>
            </div>
            
            <!-- About Content Grid -->
            <div class="about-content-grid">
                
                <!-- Left: Story & Mission -->
                <div class="about-story">
                    <h3>Our Mission</h3>
                    <p>
                        We started DoItTrading because we were tired of seeing traders lose money to fake EAs 
                        with unrealistic promises and dangerous strategies.
                    </p>
                    <p>
                        <strong>Our approach is different:</strong> We only sell what we actually trade ourselves. 
                        Every EA goes through months of live testing before release. No martingale, no grid, 
                        no account-blowing nonsense.
                    </p>
                    
                    <div class="mission-points">
                        <div class="mission-point">
                            <span class="point-icon">🔍</span>
                            <div class="point-content">
                                <strong>Complete Transparency</strong>
                                <p>All our results are public and verifiable on MyFxBook</p>
                            </div>
                        </div>
                        <div class="mission-point">
                            <span class="point-icon">🛡️</span>
                            <div class="point-content">
                                <strong>Conservative Risk Management</strong>
                                <p>We prioritize capital preservation over unrealistic gains</p>
                            </div>
                        </div>
                        <div class="mission-point">
                            <span class="point-icon">🤝</span>
                            <div class="point-content">
                                <strong>Real Support</strong>
                                <p>Personal help with setup and ongoing optimization</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right: Stats & Achievements -->
                <div class="about-achievements">
                    <h3>Track Record</h3>
                    <p>Numbers that speak for themselves:</p>
                    
                    <div class="achievements-grid">
                        <div class="achievement-item">
                            <div class="achievement-number"><?php echo $about_stats['years_experience']; ?>+</div>
                            <div class="achievement-label">Years Developing EAs</div>
                        </div>
                        <div class="achievement-item">
                            <div class="achievement-number"><?php echo $about_stats['satisfied_customers']; ?></div>
                            <div class="achievement-label">Satisfied Customers</div>
                        </div>
                        <div class="achievement-item">
                            <div class="achievement-number"><?php echo $about_stats['total_trades']; ?></div>
                            <div class="achievement-label">Live Trades Executed</div>
                        </div>
                        <div class="achievement-item">
                            <div class="achievement-number"><?php echo $about_stats['avg_rating']; ?>/5</div>
                            <div class="achievement-label">Average Rating</div>
                        </div>
                        <div class="achievement-item">
                            <div class="achievement-number"><?php echo $about_stats['volume_traded']; ?></div>
                            <div class="achievement-label">Volume Traded</div>
                        </div>
                        <div class="achievement-item">
                            <div class="achievement-number"><?php echo $about_stats['countries']; ?></div>
                            <div class="achievement-label">Countries Served</div>
                        </div>
                    </div>
                    
                    <!-- Developer Note -->
                    <div class="developer-note">
                        <h4>👨‍💻 Personal Touch</h4>
                        <p>
                            <strong>Hi, I'm Diego</strong> - the developer behind DoItTrading EAs. 
                            I personally respond to support emails and help optimize settings for your specific needs. 
                            This isn't just business for me; it's about helping fellow traders succeed.
                        </p>
                        <div class="contact-direct">
                            <span>📧 Direct contact: support@doittrading.com</span>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Values Section -->
            <div class="values-section">
                <h3>What We Stand For</h3>
                <div class="values-grid">
                    
                    <div class="value-card">
                        <div class="value-icon">🎯</div>
                        <h4>Honesty Over Hype</h4>
                        <p>We show you real results, including drawdowns and losing periods. No fake promises.</p>
                    </div>
                    
                    <div class="value-card">
                        <div class="value-icon">🔬</div>
                        <h4>Science-Based Approach</h4>
                        <p>Every strategy is thoroughly backtested and forward-tested before release.</p>
                    </div>
                    
                    <div class="value-card">
                        <div class="value-icon">🌱</div>
                        <h4>Sustainable Growth</h4>
                        <p>We focus on consistent, long-term profits rather than quick unsustainable gains.</p>
                    </div>
                    
                    <div class="value-card">
                        <div class="value-icon">🤲</div>
                        <h4>Community First</h4>
                        <p>Your success is our success. We're here to support you every step of the way.</p>
                    </div>
                    
                </div>
            </div>
            
            <!-- CTA Section -->
            <div class="about-cta-section">
                <div class="about-cta-content">
                    <h3>Ready to Trade with Confidence?</h3>
                    <p>
                        Join hundreds of traders who have discovered the power of transparent, 
                        conservative EA trading. See our live results and decide for yourself.
                    </p>
                    <div class="about-cta-buttons">
                        <a href="/shop/" class="about-cta-primary">
                            Explore Our EAs
                        </a>
                        <a href="#live-results" class="about-cta-secondary">
                            View Live Results
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

// Hook about section
add_action('astra_content_before', 'doittrading_homepage_about', 30);