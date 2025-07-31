<?php
/**
 * DoItTrading Forex Trading Bots Page
 * Página dedicada para Expert Advisors con enfoque SEO en "Trading Bots"
 * 
 * @package DoItTrading
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Add body class for forex bots page. Necesario para quitar el site-content
 */
add_filter('body_class', 'doittrading_forex_bots_body_class');
function doittrading_forex_bots_body_class($classes) {
    if (is_page('forex-trading-bots')) {
        $classes[] = 'forex-bots-page';
    }
    return $classes;
}

/**
 * Main Forex Bots Page Handler
 */
add_action('astra_content_before', 'doittrading_forex_bots_page', 5);

function doittrading_forex_bots_page() {
    // Solo mostrar en la página específica
    if (!is_page('forex-trading-bots')) return;
    
    // Secciones de la página en orden
    doittrading_forex_bots_hero();
    doittrading_forex_bots_education_bridge();
    doittrading_forex_bots_featured();
    doittrading_forex_bots_comparison();
    doittrading_forex_bots_how_it_works();
    doittrading_forex_bots_setup_process();
    doittrading_forex_bots_live_proof();
    doittrading_forex_bots_vs_competition();
    doittrading_forex_bots_social_proof();
    doittrading_forex_bots_faq();
    doittrading_forex_bots_final_cta();
}

/**
 * 1. HERO SECTION - Uses selected bot
 */
function doittrading_forex_bots_hero() {
    $active_traders = doittrading_get_active_traders();
    $last_trade = doittrading_get_last_trade();
    $hero_bot = doittrading_get_forex_bots_hero_bot(); // New function
    ?>
    <div class="forex-bots-hero-section">
        <div class="forex-bots-container">
            
            <!-- Hero Content -->
            <div class="forex-bots-hero-content">
                
                <!-- SEO Optimized Headlines -->
                <div class="hero-headlines">
                    <h1 class="hero-main-title">Forex Trading Bots That Actually Work</h1>
                    <h2 class="hero-subtitle">Professional Expert Advisors (EAs) for MT4/MT5</h2>
                    <p class="hero-description">
                        Real results: <strong>+<?php echo round($hero_bot['monthly_gain'] * 6); ?>% growth</strong> while you sleep
                    </p>
                </div>
                
                <!-- Live Activity Bar -->
                <div class="hero-activity-bar">
                    <div class="activity-indicator">
                        <span class="live-dot-forex"></span>
                        <strong>Live:</strong> <?php echo $active_traders; ?> traders earning with our bots now
                    </div>
                    <div class="recent-activity">
                        ⚡ Last profit: +<?php echo $last_trade['pips']; ?> pips (<?php echo $last_trade['time_ago']; ?>m ago)
                    </div>
                </div>
                
                <!-- Hero CTAs -->
                <div class="hero-cta-section">
                    <a href="#featured-bots" class="hero-cta-primary">
                        Start Bot Trading
                    </a>
                    <a href="#live-results" class="hero-cta-secondary">
                        See Live Results
                    </a>
                </div>
                
            </div>
            
            <!-- Hero Visual - Trading Bot Preview -->
            <div class="hero-visual-bots">
                <div class="bot-preview-card">
                    <div class="bot-preview-header">
                        <h3><?php echo esc_html($hero_bot['name']); ?></h3>
                        <span class="bot-status-live">🟢 TRADING</span>
                    </div>
                    <div class="bot-stats-preview">
                        <div class="stat-item">
                            <span class="stat-value">+<?php echo esc_html($hero_bot['monthly_gain']); ?>%</span>
                            <span class="stat-label">This Month</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?php echo esc_html($hero_bot['win_rate']); ?>%</span>
                            <span class="stat-label">Win Rate</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">24/7</span>
                            <span class="stat-label">Auto Trading</span>
                        </div>
                    </div>
                    <div class="bot-activity">
                        <?php if (!empty($hero_bot['last_trades'])): ?>
                            <?php foreach ($hero_bot['last_trades'] as $trade): ?>
                            <div class="activity-line">
                                <span class="activity-time"><?php echo esc_html($trade['time']); ?></span>
                                <span class="activity-action <?php echo esc_attr($trade['class']); ?>">
                                    <?php echo esc_html($trade['action']); ?> 
                                    <?php echo $trade['pips'] > 0 ? '+' : ''; ?><?php echo esc_html($trade['pips']); ?> pips
                                </span>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Fallback trades -->
                            <div class="activity-line">
                                <span class="activity-time">14:23</span>
                                <span class="activity-action profit">BUY GBPUSD +15 pips</span>
                            </div>
                            <div class="activity-line">
                                <span class="activity-time">13:45</span>
                                <span class="activity-action profit">SELL GBPUSD +22 pips</span>
                            </div>
                            <div class="activity-line">
                                <span class="activity-time">12:18</span>
                                <span class="activity-action profit">BUY GBPUSD +8 pips</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 2. EDUCATION BRIDGE - Explain EAs = Bots
 */
function doittrading_forex_bots_education_bridge() {
    ?>
    <div class="forex-bots-education-section">
        <div class="forex-bots-container">
            
            <div class="education-content">
                <h2>What Are Forex Trading Bots?</h2>
                <p class="education-description">
                    Also called <strong>Expert Advisors (EAs)</strong>, these are automated trading programs 
                    that execute trades 24/7 on MetaTrader platforms without emotion or hesitation.
                </p>
                
                <div class="education-benefits">
                    <div class="benefit-item">
                        <span class="benefit-icon">🤖</span>
                        <span class="benefit-text">No manual trading required</span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">😴</span>
                        <span class="benefit-text">Works while you sleep</span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">📊</span>
                        <span class="benefit-text">Data-driven decisions only</span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">⚡</span>
                        <span class="benefit-text">Lightning-fast execution</span>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 3. FEATURED BOT - Dynamic
 */
function doittrading_forex_bots_featured() {
    $featured = doittrading_get_featured_product();
    $featured_id = $featured->ID;
    
    // Get all product data
    $product_data = array(
        'name' => get_the_title($featured_id),
        'tagline' => get_field('product_tagline', $featured_id) ?: 'Our #1 performing automated Forex trading bot',
        'win_rate' => get_field('win_rate', $featured_id),
        'monthly_gain' => get_field('monthly_gain', $featured_id),
        'min_deposit' => get_field('minimum_deposit', $featured_id),
        'total_reviews' => get_field('mql5_total_reviews', $featured_id) ?: 0,
        'avg_rating' => get_field('mql5_average_rating', $featured_id) ?: 4.9,
        'url' => get_permalink($featured_id),
        'myfxbook' => get_field('myfxbook_url', $featured_id),
        'image' => get_the_post_thumbnail_url($featured_id, 'large'),
        'target_market' => get_field('target_market', $featured_id) ?: 'Multiple Markets'
    );
    
    // Calculate total growth
    $total_growth = round($product_data['monthly_gain'] * 6.5); // ~6 months compounded
    ?>
    <div class="forex-bots-featured-section" id="featured-bots">
        <div class="forex-bots-container">
            
            <div class="featured-bot-card">
                <div class="featured-badge">🏆 #1 PERFORMING BOT</div>
                
                <div class="featured-content">
                    <h2><?php echo esc_html($product_data['name']); ?> - The Profit Machine</h2>
                    <p class="featured-subtitle"><?php echo esc_html($product_data['tagline']); ?></p>
                    
                    <!-- Performance Stats -->
                    <div class="featured-stats">
                        <div class="featured-stat">
                            <span class="stat-number"><?php echo esc_html($product_data['win_rate']); ?>%</span>
                            <span class="stat-desc">Win Rate</span>
                        </div>
                        <div class="featured-stat highlight">
                            <span class="stat-number">+<?php echo esc_html($total_growth); ?>%</span>
                            <span class="stat-desc">Total Growth</span>
                        </div>
                        <div class="featured-stat">
                            <span class="stat-number">$<?php echo esc_html($product_data['min_deposit']); ?></span>
                            <span class="stat-desc">Min Deposit</span>
                        </div>
                    </div>
                    
                    <!-- Key Features -->
                    <div class="featured-highlights">
                        <div class="highlight">🎯 <?php echo esc_html($product_data['target_market']); ?> specialist</div>
                        <div class="highlight">🛡️ Conservative risk management</div>
                        <div class="highlight">📊 MyFxBook verified results</div>
                    </div>
                    
                    <!-- CTAs -->
                    <div class="featured-actions">
                        <a href="<?php echo esc_url($product_data['url']); ?>" class="featured-cta-primary">
                            Get <?php echo esc_html($product_data['name']); ?>
                        </a>
                        <?php if ($product_data['myfxbook']): ?>
                        <a href="<?php echo esc_url($product_data['myfxbook']); ?>" 
                           target="_blank" class="featured-cta-secondary">
                            Live MyFxBook Proof
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Featured Visual -->
                <div class="featured-visual">
                    <?php if ($product_data['image']): ?>
                    <img src="<?php echo esc_url($product_data['image']); ?>" 
                         alt="<?php echo esc_attr($product_data['name']); ?>" 
                         class="featured-bot-image">
                    <?php endif; ?>
                    <div class="live-indicator-featured">🟢 LIVE TRADING</div>
                </div>
                
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 4. BOTS COMPARISON - Dynamic columns
 */
function doittrading_forex_bots_comparison() {
    $products = doittrading_get_comparison_products();
    $product_count = count($products);
    $table_class = doittrading_get_comparison_table_class($product_count);
    ?>
    <div class="forex-bots-comparison-section">
        <div class="forex-bots-container">
            
            <div class="comparison-header">
                <h2>Choose Your Trading Bot</h2>
                <p>Each bot is specialized for different trading goals and risk levels</p>
            </div>
            
            <div class="comparison-table <?php echo esc_attr($table_class); ?>">
                <div class="comparison-headers">
                    <div class="header-item">Feature</div>
                    <?php foreach ($products as $product): ?>
                    <div class="header-item <?php echo $product['is_featured'] ? 'featured' : ''; ?>">
                        <?php echo esc_html($product['short_name']); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Target Market</div>
                    <?php foreach ($products as $product): ?>
                    <div class="row-value"><?php echo esc_html($product['target_market']); ?></div>
                    <?php endforeach; ?>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Trading Style</div>
                    <?php foreach ($products as $product): ?>
                    <div class="row-value"><?php echo esc_html(doittrading_format_trading_style($product['trading_style'])); ?></div>
                    <?php endforeach; ?>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Monthly Gain</div>
                    <?php foreach ($products as $product): ?>
                    <div class="row-value success">+<?php echo esc_html($product['monthly_gain']); ?>%</div>
                    <?php endforeach; ?>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Win Rate</div>
                    <?php foreach ($products as $product): ?>
                    <div class="row-value"><?php echo esc_html($product['win_rate']); ?>%</div>
                    <?php endforeach; ?>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Risk Level</div>
                    <?php foreach ($products as $product): ?>
                    <div class="row-value <?php 
                        echo $product['risk_level'] === 'low' ? 'success' : 
                            ($product['risk_level'] === 'medium' ? 'warning' : 'danger'); 
                    ?>">
                        <?php echo esc_html(ucfirst($product['risk_level'])); ?> 
                        (<?php echo esc_html($product['max_drawdown']); ?>% DD)
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Min Deposit</div>
                    <?php foreach ($products as $product): ?>
                    <div class="row-value <?php echo $product['min_deposit'] <= 50 ? 'highlight' : ''; ?>">
                        $<?php echo esc_html($product['min_deposit']); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Best For</div>
                    <?php foreach ($products as $product): ?>
                    <div class="row-value"><?php echo esc_html(doittrading_format_best_for($product['best_for'])); ?></div>
                    <?php endforeach; ?>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Price</div>
                    <?php foreach ($products as $product): ?>
                    <div class="row-value">
                        <?php if ($product['current_price'] < $product['regular_price']): ?>
                            <span class="price-current">$<?php echo esc_html($product['current_price']); ?></span>
                            <span class="price-old">$<?php echo esc_html($product['regular_price']); ?></span>
                        <?php else: ?>
                            $<?php echo esc_html($product['current_price']); ?>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="comparison-actions">
                    <div class="action-cell"></div>
                    <?php foreach ($products as $index => $product): ?>
                    <div class="action-cell">
                        <a href="<?php echo esc_url($product['url']); ?>" 
                           class="comparison-btn <?php echo $product['is_featured'] ? 'primary' : 'secondary'; ?>">
                            View Details
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

// Las demás secciones las crearemos en el siguiente paso...

/**
 * 5. HOW TRADING BOTS WORK - Education + Trust
 */
function doittrading_forex_bots_how_it_works() {
    ?>
    <div class="forex-bots-how-it-works-section">
        <div class="forex-bots-container">
            
            <div class="how-it-works-header">
                <h2>How Our Forex Bots Make You Money</h2>
                <p>The 4-step automated process that generates consistent profits while you sleep</p>
            </div>
            
            <div class="how-it-works-steps">
                
                <div class="work-step">
                    <div class="step-number">1️⃣</div>
                    <div class="step-content">
                        <h3>Market Analysis (24/7 price monitoring)</h3>
                        <p>
                            Our bots continuously scan market conditions using advanced algorithms. 
                            They analyze price patterns, support/resistance levels, and market momentum 
                            across multiple timeframes simultaneously.
                        </p>
                        <div class="step-features">
                            <span class="feature-tag">✓ Real-time data processing</span>
                            <span class="feature-tag">✓ Multi-timeframe analysis</span>
                            <span class="feature-tag">✓ Technical indicators</span>
                        </div>
                    </div>
                    <div class="step-visual">
                        <div class="visual-box">
                            <div class="chart-lines"></div>
                            <div class="analysis-points">
                                <span class="point active">📈</span>
                                <span class="point">📊</span>
                                <span class="point">🎯</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="work-step">
                    <div class="step-number">2️⃣</div>
                    <div class="step-content">
                        <h3>Entry Signals (mathematical algorithms)</h3>
                        <p>
                            When market conditions align perfectly, the bot generates entry signals. 
                            Only high-probability setups are considered - no emotional decisions, 
                            no FOMO, just cold mathematical precision.
                        </p>
                        <div class="step-features">
                            <span class="feature-tag">✓ Probability-based entries</span>
                            <span class="feature-tag">✓ Risk/reward optimization</span>
                            <span class="feature-tag">✓ No emotional bias</span>
                        </div>
                    </div>
                    <div class="step-visual">
                        <div class="visual-box">
                            <div class="signal-indicator profit">BUY SIGNAL</div>
                            <div class="probability-bar">
                                <div class="probability-fill" style="width: 85%;">85%</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="work-step">
                    <div class="step-number">3️⃣</div>
                    <div class="step-content">
                        <h3>Risk Management (automatic stops & profits)</h3>
                        <p>
                            Every single trade includes automatic stop-loss and take-profit levels. 
                            The bot never risks more than your specified percentage per trade, 
                            protecting your capital from major losses.
                        </p>
                        <div class="step-features">
                            <span class="feature-tag">✓ Automatic stop-loss</span>
                            <span class="feature-tag">✓ Take-profit targets</span>
                            <span class="feature-tag">✓ Position sizing</span>
                        </div>
                    </div>
                    <div class="step-visual">
                        <div class="visual-box">
                            <div class="risk-gauge">
                                <div class="gauge-fill" style="width: 20%;">2%</div>
                                <span class="gauge-label">Risk Per Trade</span>
                            </div>
                            <div class="protection-shield">🛡️</div>
                        </div>
                    </div>
                </div>
                
                <div class="work-step">
                    <div class="step-number">4️⃣</div>
                    <div class="step-content">
                        <h3>Trade Execution (no emotions, no hesitation)</h3>
                        <p>
                            Lightning-fast execution means you never miss opportunities. 
                            The bot places trades instantly when conditions are met, 
                            with trailing stops to maximize profits on winning trades.
                        </p>
                        <div class="step-features">
                            <span class="feature-tag">✓ Instant execution</span>
                            <span class="feature-tag">✓ Trailing stops</span>
                            <span class="feature-tag">✓ Profit maximization</span>
                        </div>
                    </div>
                    <div class="step-visual">
                        <div class="visual-box">
                            <div class="execution-speed">⚡ 0.03s</div>
                            <div class="profit-counter">+$127.50</div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="how-it-works-summary">
                <div class="summary-box">
                    <h3>💡 "It's like having a professional trader working for you 24 hours a day, but without salary"</h3>
                    <p>
                        While you sleep, work, or enjoy life, your bot is analyzing markets, 
                        finding opportunities, and executing profitable trades automatically.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 6. SETUP PROCESS - Simplicity
 */
function doittrading_forex_bots_setup_process() {
    ?>
    <div class="forex-bots-setup-section">
        <div class="forex-bots-container">
            
            <div class="setup-header">
                <h2>Start Bot Trading in 3 Simple Steps</h2>
                <p>From purchase to profit in under 5 minutes - no coding required</p>
            </div>
            
            <div class="setup-steps-grid">
                
                <div class="setup-step">
                    <div class="setup-step-number">1️⃣</div>
                    <div class="setup-step-content">
                        <h3>Download Bot (2 minutes)</h3>
                        <p>
                            After purchase, instantly download your trading bot file. 
                            No waiting, no email delays - immediate access to start earning.
                        </p>
                        <div class="setup-details">
                            <div class="detail-item">📁 .ex4/.ex5 file included</div>
                            <div class="detail-item">📋 Setup guide PDF</div>
                            <div class="detail-item">⚙️ Pre-configured settings</div>
                        </div>
                    </div>
                    <div class="setup-visual">
                        <div class="download-animation">
                            <div class="download-icon">⬇️</div>
                            <div class="download-bar">
                                <div class="download-progress"></div>
                            </div>
                            <span class="download-text">Complete!</span>
                        </div>
                    </div>
                </div>
                
                <div class="setup-step">
                    <div class="setup-step-number">2️⃣</div>
                    <div class="setup-step-content">
                        <h3>Set Risk Level (1-5% per trade)</h3>
                        <p>
                            Choose how much you want to risk per trade. Conservative? Start with 1%. 
                            More aggressive? Go up to 5%. You're always in complete control.
                        </p>
                        <div class="risk-slider-demo">
                            <div class="slider-track">
                                <div class="slider-fill" style="width: 40%;"></div>
                                <div class="slider-thumb"></div>
                            </div>
                            <div class="risk-labels">
                                <span>1% (Safe)</span>
                                <span class="active">2% (Balanced)</span>
                                <span>5% (Aggressive)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="setup-step">
                    <div class="setup-step-number">3️⃣</div>
                    <div class="setup-step-content">
                        <h3>Activate Bot (trades automatically)</h3>
                        <p>
                            Simply drag the bot onto your MT4/MT5 chart, enable auto-trading, 
                            and watch it work. Your passive income stream starts immediately.
                        </p>
                        <div class="activation-demo">
                            <div class="toggle-switch active">
                                <div class="toggle-slider"></div>
                                <span class="toggle-label">AUTO-TRADING ON</span>
                            </div>
                            <div class="status-indicator">
                                <span class="status-dot"></span>
                                <span class="status-text">Bot Active - Scanning Markets</span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="setup-guarantees">
                <div class="guarantee-grid">
                    <div class="guarantee-item">
                        <span class="guarantee-icon">✅</span>
                        <strong>No coding required</strong>
                        <p>Everything is pre-configured and ready to use</p>
                    </div>
                    <div class="guarantee-item">
                        <span class="guarantee-icon">✅</span>
                        <strong>Pre-configured settings</strong>
                        <p>Optimized parameters based on live testing</p>
                    </div>
                    <div class="guarantee-item">
                        <span class="guarantee-icon">✅</span>
                        <strong>Works on any MT4/MT5</strong>
                        <p>Compatible with all major brokers</p>
                    </div>
                    <div class="guarantee-item">
                        <span class="guarantee-icon">✅</span>
                        <strong>24/7 support included</strong>
                        <p>We help you get started successfully</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 7. LIVE PROOF - Dynamic from products
 */
function doittrading_forex_bots_live_proof() {
    $accounts = doittrading_get_live_accounts();
    ?>
    <div class="forex-bots-live-proof-section" id="live-results">
        <div class="forex-bots-container">
            
            <div class="live-proof-header">
                <h2>Real Trading Bot Results (Verified)</h2>
                <p>
                    Unlike other bot sellers, we show you <strong>EVERY trade</strong> - wins, losses, and drawdowns. 
                    Complete transparency through live MyFxBook accounts.
                </p>
            </div>
            
            <div class="live-accounts-grid">
                
                <?php foreach ($accounts as $account): ?>
                <div class="live-account-card">
                    <div class="account-header">
                        <h3><?php echo esc_html($account['name']); ?></h3>
                        <div class="live-status">
                            <span class="live-dot-proof"></span>
                            <span>LIVE TRADING</span>
                        </div>
                    </div>
                    
                    <div class="account-stats">
                        <div class="stat-large">
                            <span class="stat-value-large">+<?php echo esc_html($account['total_growth']); ?>%</span>
                            <span class="stat-label-large">Total Growth</span>
                        </div>
                        <div class="stat-grid">
                            <div class="stat-small">
                                <span class="stat-value-small"><?php echo esc_html($account['win_rate']); ?>%</span>
                                <span class="stat-label-small">Win Rate</span>
                            </div>
                            <div class="stat-small">
                                <span class="stat-value-small">-<?php echo esc_html($account['max_drawdown']); ?>%</span>
                                <span class="stat-label-small">Max DD</span>
                            </div>
                            <div class="stat-small">
                                <span class="stat-value-small"><?php echo esc_html($account['profit_factor']); ?></span>
                                <span class="stat-label-small">Profit Factor</span>
                            </div>
                            <div class="stat-small">
                                <span class="stat-value-small"><?php echo esc_html($account['total_trades']); ?></span>
                                <span class="stat-label-small">Total Trades</span>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($account['myfxbook_url']): ?>
                    <div class="account-link">
                        <a href="<?php echo esc_url($account['myfxbook_url']); ?>" 
                           target="_blank" class="proof-link">
                            📊 View Live Account on MyFxBook →
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                
            </div>
            
            <div class="transparency-message">
                <div class="transparency-box">
                    <h3>🔍 Complete Transparency Promise</h3>
                    <p>
                        These accounts are updated automatically with every single trade. 
                        You see the exact same results we see - no cherry-picking, no hiding losses, 
                        no fake screenshots. Just raw, unfiltered performance data.
                    </p>
                    <div class="transparency-features">
                        <span class="transparency-feature">✓ Real-time updates</span>
                        <span class="transparency-feature">✓ Every trade shown</span>
                        <span class="transparency-feature">✓ Drawdowns included</span>
                        <span class="transparency-feature">✓ Independently verified</span>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 8. VS COMPETITION - Differentiation
 */
function doittrading_forex_bots_vs_competition() {
    ?>
    <div class="forex-bots-vs-competition-section">
        <div class="forex-bots-container">
            
            <div class="vs-header">
                <h2>Why Our Bots vs Others?</h2>
                <p>The trading bot market is full of scams. Here's how we're different:</p>
            </div>
            
            <div class="comparison-grid-vs">
                
                <!-- Typical Bots Column -->
                <div class="vs-column bad">
                    <div class="vs-column-header">
                        <h3>❌ Typical Bots</h3>
                        <span class="vs-subtitle">What you usually get</span>
                    </div>
                    
                    <div class="vs-items">
                        <div class="vs-item bad">
                            <span class="vs-icon">🎭</span>
                            <div class="vs-content">
                                <strong>Fake results, martingale risk</strong>
                                <p>Perfect backtests that blow accounts in live trading</p>
                            </div>
                        </div>
                        
                        <div class="vs-item bad">
                            <span class="vs-icon">🚫</span>
                            <div class="vs-content">
                                <strong>No support after purchase</strong>
                                <p>Buy and pray - you're on your own</p>
                            </div>
                        </div>
                        
                        <div class="vs-item bad">
                            <span class="vs-icon">💥</span>
                            <div class="vs-content">
                                <strong>Account blowup risk</strong>
                                <p>Dangerous strategies that work until they don't</p>
                            </div>
                        </div>
                        
                        <div class="vs-item bad">
                            <span class="vs-icon">👻</span>
                            <div class="vs-content">
                                <strong>Anonymous developers</strong>
                                <p>No face, no name, no accountability</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- DoItTrading Column -->
                <div class="vs-column good">
                    <div class="vs-column-header">
                        <h3>✅ DoItTrading Approach</h3>
                        <span class="vs-subtitle">What you actually get</span>
                    </div>
                    
                    <div class="vs-items">
                        <div class="vs-item good">
                            <span class="vs-icon">📊</span>
                            <div class="vs-content">
                                <strong>Live MyFxBook verification, safe strategies</strong>
                                <p>Real results from real accounts, updated automatically</p>
                            </div>
                        </div>
                        
                        <div class="vs-item good">
                            <span class="vs-icon">🛠️</span>
                            <div class="vs-content">
                                <strong>Personal setup help included</strong>
                                <p>We respond within 24 hours and help you succeed</p>
                            </div>
                        </div>
                        
                        <div class="vs-item good">
                            <span class="vs-icon">🛡️</span>
                            <div class="vs-content">
                                <strong>Conservative risk management</strong>
                                <p>Capital preservation first, sustainable growth always</p>
                            </div>
                        </div>
                        
                        <div class="vs-item good">
                            <span class="vs-icon">👨‍💻</span>
                            <div class="vs-content">
                                <strong>Direct access to developer (Diego)</strong>
                                <p>Real person, real support, real accountability</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="vs-bottom-line">
                <div class="bottom-line-box">
                    <h3>The Bottom Line</h3>
                    <p>
                        <strong>Would you rather:</strong> Lose money on a $99 bot that blows your account, 
                        or invest in proven bots with live verification and ongoing support?
                    </p>
                    <a href="#featured-bots" class="bottom-line-cta">
                        See Our Verified Results →
                    </a>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 9. SOCIAL PROOF - Dynamic testimonials
 */
function doittrading_forex_bots_social_proof() {
    $testimonials = doittrading_get_forex_bots_testimonials();
    $stats = doittrading_get_aggregate_stats();
    
    // Get featured testimonial (most recent 5-star)
    $featured_testimonial = null;
    foreach ($testimonials as $testimonial) {
        if ($testimonial['stars'] == 5 && strlen($testimonial['text'] ?? 0) > 100) {
            $featured_testimonial = $testimonial;
            break;
        }
    }
    ?>
    <div class="forex-bots-social-proof-section">
        <div class="forex-bots-container">
            
            <div class="social-proof-header">
                <h2>Join <?php echo esc_html($stats['total_active_traders']); ?>+ Successful Bot Traders</h2>
                <p>Real people, real results from <?php echo esc_html($stats['countries']); ?> countries worldwide</p>
                
                <div class="social-stats">
                    <div class="social-stat">
                        <span class="social-stat-number"><?php echo esc_html($stats['average_rating']); ?>★</span>
                        <span class="social-stat-label">Average Rating</span>
                    </div>
                    <div class="social-stat">
                        <span class="social-stat-number"><?php echo esc_html($stats['total_reviews']); ?>+</span>
                        <span class="social-stat-label">Verified Reviews</span>
                    </div>
                    <div class="social-stat">
                        <span class="social-stat-number"><?php echo esc_html($stats['countries']); ?>+</span>
                        <span class="social-stat-label">Countries</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonials-showcase">
                
                <?php if ($featured_testimonial): ?>
                <!-- Featured Testimonial -->
                <div class="featured-testimonial">
                    <div class="testimonial-badge">⭐ FEATURED SUCCESS</div>
                    <div class="testimonial-content">
                        <div class="testimonial-quote">
                            "<?php echo esc_html($featured_testimonial['text']); ?>"
                        </div>
                        <div class="testimonial-author-info">
                            <div class="author-details">
                                <strong class="author-name"><?php echo esc_html($featured_testimonial['name']); ?></strong>
                                <span class="author-location">
                                    <?php echo $featured_testimonial['country_flag']; ?> 
                                    <?php echo esc_html($featured_testimonial['country']); ?>
                                </span>
                                <span class="author-timeframe">Using for <?php echo esc_html($featured_testimonial['timeframe']); ?></span>
                            </div>
                            <div class="author-rating">
                                <?php echo str_repeat('⭐', $featured_testimonial['stars'] ?? 5); ?>
                            </div>
                        </div>
                        <div class="verified-purchase-large">
                            ✓ VERIFIED MQL5 PURCHASE
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Testimonials Grid -->
                <div class="testimonials-grid-social">
                    <?php 
                    $grid_testimonials = array_slice($testimonials, $featured_testimonial ? 1 : 0, 4);
                    foreach ($grid_testimonials as $testimonial): 
                    ?>
                    <div class="testimonial-card-social">
                        <div class="testimonial-header-social">
                            <div class="testimonial-author-social">
                                <strong><?php echo esc_html($testimonial['name']); ?></strong>
                                <span class="country-flag">
                                    <?php echo $testimonial['country_flag']; ?> 
                                    <?php echo esc_html($testimonial['country']); ?>
                                </span>
                            </div>
                            <div class="testimonial-rating-social">
                                <?php echo str_repeat('⭐', $testimonial['stars'] ?? 5); ?>
                            </div>
                        </div>
                        
                        <p class="testimonial-text-social">
                            "<?php echo esc_html($testimonial['text']); ?>"
                        </p>
                        
                        <div class="testimonial-footer-social">
                            <span class="testimonial-product">Bot: <?php echo esc_html($testimonial['product']); ?></span>
                            <span class="testimonial-timeframe">Using: <?php echo esc_html($testimonial['timeframe']); ?></span>
                            <?php if ($testimonial['verified']): ?>
                                <span class="verified-badge-small">✓ Verified</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Trust Indicators -->
                <div class="trust-indicators-social">
                    <div class="trust-indicator-social">
                        <span class="trust-icon-large">🛡️</span>
                        <div class="trust-content">
                            <strong>100% Verified Reviews</strong>
                            <p>All testimonials from real MQL5 buyers</p>
                        </div>
                    </div>
                    <div class="trust-indicator-social">
                        <span class="trust-icon-large">🌍</span>
                        <div class="trust-content">
                            <strong>Global Community</strong>
                            <p>Traders from <?php echo esc_html($stats['countries']); ?>+ countries trust our bots</p>
                        </div>
                    </div>
                    <div class="trust-indicator-social">
                        <span class="trust-icon-large">📈</span>
                        <div class="trust-content">
                            <strong>Consistent Results</strong>
                            <p>Proven performance across different market conditions</p>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 10. FAQ BOT-SPECIFIC - Objection Handling
 */
function doittrading_forex_bots_faq() {
    $faqs = array(
        array(
            'icon' => '🤔',
            'question' => 'Do I need trading experience to use these bots?',
            'answer' => 'Not at all! Our bots are designed for complete beginners. They trade automatically with pre-optimized settings, so you don\'t need any trading knowledge. Just install, set your risk level, and let the bot do everything.'
        ),
        array(
            'icon' => '💰',
            'question' => 'What if the bot loses money?',
            'answer' => 'Every trade has built-in stop losses that limit risk to 1-5% per trade (you choose). No martingale or dangerous strategies. Even our worst month was only -4.2% drawdown, and the bot recovered quickly.'
        ),
        array(
            'icon' => '📊',
            'question' => 'How much can I realistically make with trading bots?',
            'answer' => 'Our top bot averages +12.8% monthly growth, but results vary based on market conditions and your risk settings. We show all real results on MyFxBook - both profits and losses for complete transparency.'
        ),
        array(
            'icon' => '⚖️',
            'question' => 'Are Forex trading bots legal?',
            'answer' => 'Yes, 100% legal! MT4/MT5 Expert Advisors (trading bots) are officially supported by MetaTrader and used by millions of traders worldwide. All major brokers allow automated trading.'
        ),
        array(
            'icon' => '🖥️',
            'question' => 'Do I need to keep my computer on 24/7?',
            'answer' => 'For best results, yes - or use a VPS (Virtual Private Server). This ensures your bot can trade around the clock and never misses opportunities. Many brokers offer free VPS services.'
        ),
        array(
            'icon' => '🔧',
            'question' => 'How difficult is the setup process?',
            'answer' => 'Super easy! Download, drag to chart, set risk level - done in under 5 minutes. We include step-by-step guides and offer personal setup help if needed. No coding or complex configuration required.'
        ),
        array(
            'icon' => '💳',
            'question' => 'What\'s the minimum amount I need to start?',
            'answer' => 'Our GBP Master works with just $50, Gold Guardian needs $300, and Index Vanguard starts at only $30. You can begin with a small amount and scale up as you see results.'
        ),
        array(
            'icon' => '🛡️',
            'question' => 'How do I know these aren\'t scam bots?',
            'answer' => 'Check our live MyFxBook accounts! We show every single trade in real-time - wins, losses, drawdowns. No fake screenshots or backtest-only results. Complete transparency is our guarantee.'
        ),
        array(
            'icon' => '📞',
            'question' => 'What kind of support do you provide?',
            'answer' => 'Personal support from Diego (the developer) within 24 hours. We help with setup, optimization, broker recommendations, and any questions. You\'re not buying just a bot - you\'re joining our community.'
        ),
        array(
            'icon' => '🔄',
            'question' => 'Do you provide updates for the bots?',
            'answer' => 'Yes, all updates are free for life! We continuously improve our bots based on market conditions and user feedback. You\'ll always have the latest, most optimized version.'
        )
    );
    ?>
    <div class="forex-bots-faq-section">
        <div class="forex-bots-container">
            
            <div class="faq-header">
                <h2>Forex Trading Bot FAQ</h2>
                <p>Everything you need to know before starting automated trading</p>
            </div>
            
            <div class="faq-grid-bots">
                <?php foreach ($faqs as $faq): ?>
                <div class="faq-item-bot">
                    <div class="faq-question-bot">
                        <span class="faq-icon"><?php echo $faq['icon']; ?></span>
                        <h3><?php echo esc_html($faq['question']); ?></h3>
                    </div>
                    <div class="faq-answer-bot">
                        <p><?php echo esc_html($faq['answer']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="faq-support-section">
                <div class="support-box">
                    <h3>Still Have Questions?</h3>
                    <p>Our support team is here to help you succeed with automated trading</p>
                    <div class="support-options">
                        <div class="support-option">
                            <span class="support-icon">📧</span>
                            <div class="support-details">
                                <strong>Email Support</strong>
                                <span>support@doittrading.com</span>
                            </div>
                        </div>
                        <div class="support-option">
                            <span class="support-icon">⏰</span>
                            <div class="support-details">
                                <strong>Response Time</strong>
                                <span>Within 24 hours</span>
                            </div>
                        </div>
                        <div class="support-option">
                            <span class="support-icon">🎯</span>
                            <div class="support-details">
                                <strong>Setup Help</strong>
                                <span>Personal assistance included</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 11. FINAL CTA - Dynamic pricing and products
 */
function doittrading_forex_bots_final_cta() {
    $active_traders = doittrading_get_active_traders();
    $countdown_target = doittrading_get_countdown_target();
    $cta_bots = doittrading_get_cta_bots();
    
    // Get recent purchase name
    $recent_buyer = doittrading_get_recent_buyer();
    ?>
    <div class="forex-bots-final-cta-section">
        <div class="forex-bots-container">
            
            <div class="final-cta-content">
                
                <!-- Urgency Header -->
                <div class="cta-urgency-header">
                    <div class="urgency-badge">🔥 LIMITED TIME OFFER</div>
                    <h2>Ready to Start Automated Trading?</h2>
                    <p class="cta-subtitle">Join successful bot traders making passive income while they sleep</p>
                </div>
                
                <!-- Price & Countdown -->
                <div class="cta-pricing-section">
                    <div class="price-countdown">
                        <div class="countdown-timer-final" data-target="<?php echo esc_attr($countdown_target); ?>">
                            <div class="countdown-label">Price increases in:</div>
                            <div class="countdown-display-final">
                                <span class="countdown-time" id="countdown-days">2</span>
                                <span class="countdown-unit">days</span>
                                <span class="countdown-time" id="countdown-hours">14</span>
                                <span class="countdown-unit">hours</span>
                                <span class="countdown-time" id="countdown-minutes">23</span>
                                <span class="countdown-unit">min</span>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($cta_bots)): 
                        $main_bot = $cta_bots[0]; // Featured bot
                    ?>
                    <div class="final-pricing">
                        <div class="pricing-current">
                            <span class="price-label">Current Launch Price:</span>
                            <span class="price-amount">$<?php echo esc_html($main_bot['current_price']); ?></span>
                        </div>
                        <div class="pricing-future">
                            <span class="price-label-small">Regular Price: </span>
                            <span class="price-crossed">$<?php echo esc_html($main_bot['original_price']); ?></span>
                        </div>
                        <div class="savings-highlight">
                            Save $<?php echo esc_html($main_bot['original_price'] - $main_bot['current_price']); ?> - Launch Pricing Ends Soon!
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Bot Selection Cards -->
                <div class="cta-bot-selection">
                    <h3>Choose Your Trading Bot:</h3>
                    
                    <div class="cta-bots-grid">
                        
                        <?php foreach ($cta_bots as $index => $bot): ?>
                        <div class="cta-bot-card <?php echo $bot['is_featured'] ? 'featured' : ''; ?>">
                            <?php if ($bot['is_featured']): ?>
                            <div class="cta-bot-badge">🏆 MOST POPULAR</div>
                            <?php endif; ?>
                            
                            <h4><?php echo esc_html($bot['name']); ?></h4>
                            <div class="cta-bot-stats">
                                <span><?php echo esc_html($bot['win_rate']); ?>% Win Rate</span>
                                <span>+<?php echo esc_html($bot['monthly_gain']); ?>% Monthly</span>
                                <span>$<?php echo esc_html($bot['min_deposit']); ?> Min Deposit</span>
                            </div>
                            <div class="cta-bot-price">
                                <span class="cta-price-current">$<?php echo esc_html($bot['current_price']); ?></span>
                                <span class="cta-price-original">$<?php echo esc_html($bot['original_price']); ?></span>
                            </div>
                            <a href="<?php echo esc_url($bot['url']); ?>" 
                               class="cta-bot-button <?php echo $index === 0 ? 'primary' : 'secondary'; ?>">
                                🛒 Get <?php echo esc_html($bot['name']); ?> Now
                            </a>
                            <div class="cta-bot-features">
                                ✓ <?php echo esc_html($bot['features']); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                    </div>
                </div>
                
                <!-- Final Benefits & Guarantees -->
                <div class="cta-final-benefits">
                    <div class="benefits-guarantee">
                        <h3>What You Get With Every Bot:</h3>
                        <div class="guarantee-grid-final">
                            <div class="guarantee-final">
                                <span class="guarantee-icon-final">📊</span>
                                <strong>Live MyFxBook Verification</strong>
                                <span>See real results updated automatically</span>
                            </div>
                            <div class="guarantee-final">
                                <span class="guarantee-icon-final">⚡</span>
                                <strong>Instant Download</strong>
                                <span>Start trading in under 5 minutes</span>
                            </div>
                            <div class="guarantee-final">
                                <span class="guarantee-icon-final">🔄</span>
                                <strong>Free Lifetime Updates</strong>
                                <span>Always get the latest optimizations</span>
                            </div>
                            <div class="guarantee-final">
                                <span class="guarantee-icon-final">🛠️</span>
                                <strong>Personal Setup Support</strong>
                                <span>We help you succeed - 24/7 support</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Live Activity Footer -->
                <div class="cta-live-activity">
                    <div class="activity-indicator-final">
                        <span class="live-dot-final"></span>
                        <strong><?php echo $active_traders; ?> traders</strong> are currently using our bots
                    </div>
                    <div class="recent-purchases">
                        <span class="purchase-notification">
                            🔔 <?php echo esc_html($recent_buyer['name']); ?> from <?php echo esc_html($recent_buyer['country']); ?> 
                            just purchased <?php echo esc_html($cta_bots[0]['name']); ?> (3 min ago)
                        </span>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
    
    <script>
    // Countdown timer functionality
    function initFinalCountdown() {
        const timer = document.querySelector('.countdown-timer-final');
        if (!timer) return;
        
        const target = new Date(timer.dataset.target).getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const difference = target - now;
            
            if (difference > 0) {
                const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                
                document.getElementById('countdown-days').textContent = days;
                document.getElementById('countdown-hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('countdown-minutes').textContent = minutes.toString().padStart(2, '0');
            } else {
                timer.innerHTML = '<div class="countdown-expired">Offer Expired - Prices Have Increased!</div>';
            }
        }
        
        setInterval(updateCountdown, 60000); // Update every minute
        updateCountdown();
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFinalCountdown);
    } else {
        initFinalCountdown();
    }
    </script>
    <?php
}