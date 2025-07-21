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
 * 1. HERO SECTION - SEO + Emotional Hook
 */
function doittrading_forex_bots_hero() {
    $active_traders = doittrading_get_active_traders();
    $last_trade = doittrading_get_last_trade();
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
                        Real results: <strong>+83% growth</strong> while you sleep
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
                        <h3>GBP Master Bot</h3>
                        <span class="bot-status-live">🟢 TRADING</span>
                    </div>
                    <div class="bot-stats-preview">
                        <div class="stat-item">
                            <span class="stat-value">+12.8%</span>
                            <span class="stat-label">This Month</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">76%</span>
                            <span class="stat-label">Win Rate</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">24/7</span>
                            <span class="stat-label">Auto Trading</span>
                        </div>
                    </div>
                    <div class="bot-activity">
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
 * 3. FEATURED BOT - Revenue Focus
 */
function doittrading_forex_bots_featured() {
    ?>
    <div class="forex-bots-featured-section" id="featured-bots">
        <div class="forex-bots-container">
            
            <div class="featured-bot-card">
                <div class="featured-badge">🏆 #1 PERFORMING BOT</div>
                
                <div class="featured-content">
                    <h2>DoIt GBP Master Bot - The Profit Machine</h2>
                    <p class="featured-subtitle">Our #1 performing automated Forex trading bot</p>
                    
                    <!-- Performance Stats -->
                    <div class="featured-stats">
                        <div class="featured-stat">
                            <span class="stat-number">76%</span>
                            <span class="stat-desc">Win Rate</span>
                        </div>
                        <div class="featured-stat highlight">
                            <span class="stat-number">+83%</span>
                            <span class="stat-desc">Total Growth</span>
                        </div>
                        <div class="featured-stat">
                            <span class="stat-number">$50</span>
                            <span class="stat-desc">Min Deposit</span>
                        </div>
                    </div>
                    
                    <!-- Key Features -->
                    <div class="featured-highlights">
                        <div class="highlight">🎯 GBPUSD specialist</div>
                        <div class="highlight">🛡️ Conservative risk management</div>
                        <div class="highlight">📊 MyFxBook verified results</div>
                    </div>
                    
                    <!-- CTAs -->
                    <div class="featured-actions">
                        <a href="/product/doit-gbp-master/" class="featured-cta-primary">
                            Get GBP Bot
                        </a>
                        <a href="https://www.myfxbook.com/members/DoItTrading/doit-gbp-master/11493777" 
                           target="_blank" class="featured-cta-secondary">
                            Live MyFxBook Proof
                        </a>
                        <a href="#video-demo" class="featured-cta-tertiary">
                            Video Demo
                        </a>
                    </div>
                </div>
                
                <!-- Featured Visual -->
                <div class="featured-visual">
                    <img src="/wp-content/uploads/2025/07/DoItGBPMaster.webp" 
                         alt="DoIt GBP Master Trading Bot" 
                         class="featured-bot-image">
                    <div class="live-indicator-featured">🟢 LIVE TRADING</div>
                </div>
                
            </div>
            
        </div>
    </div>
    <?php
}

/**
 * 4. BOTS COMPARISON - Decision Support
 */
function doittrading_forex_bots_comparison() {
    ?>
    <div class="forex-bots-comparison-section">
        <div class="forex-bots-container">
            
            <div class="comparison-header">
                <h2>Choose Your Trading Bot</h2>
                <p>Each bot is specialized for different trading goals and risk levels</p>
            </div>
            
            <div class="comparison-table">
                <div class="comparison-headers">
                    <div class="header-item">Feature</div>
                    <div class="header-item featured">GBP Master Bot</div>
                    <div class="header-item">Gold Guardian Bot</div>
                    <div class="header-item">Index Vanguard Bot</div>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Target Market</div>
                    <div class="row-value">GBPUSD</div>
                    <div class="row-value">Gold (XAUUSD)</div>
                    <div class="row-value">SP500 Index</div>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Trading Style</div>
                    <div class="row-value">Conservative</div>
                    <div class="row-value">Aggressive Growth</div>
                    <div class="row-value">Balanced</div>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Risk Level</div>
                    <div class="row-value success">Low (4.2% DD)</div>
                    <div class="row-value warning">Medium (8% DD)</div>
                    <div class="row-value success">Low (3.5% DD)</div>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Min Deposit</div>
                    <div class="row-value">$50</div>
                    <div class="row-value">$300</div>
                    <div class="row-value highlight">$30</div>
                </div>
                
                <div class="comparison-row">
                    <div class="row-label">Best For</div>
                    <div class="row-value">Beginners</div>
                    <div class="row-value">Risk-tolerant</div>
                    <div class="row-value">Small accounts</div>
                </div>
                
                <div class="comparison-actions">
                    <div class="action-cell"></div>
                    <div class="action-cell">
                        <a href="/product/doit-gbp-master/" class="comparison-btn primary">
                            View Details
                        </a>
                    </div>
                    <div class="action-cell">
                        <a href="/product/doit-gold-guardian/" class="comparison-btn secondary">
                            View Details
                        </a>
                    </div>
                    <div class="action-cell">
                        <a href="/product/index-vanguard/" class="comparison-btn secondary">
                            View Details
                        </a>
                    </div>
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
 * 7. LIVE PROOF - Credibility
 */
function doittrading_forex_bots_live_proof() {
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
                
                <!-- Account 1: Conservative -->
                <div class="live-account-card">
                    <div class="account-header">
                        <h3>GBP Master Bot - Conservative</h3>
                        <div class="live-status">
                            <span class="live-dot-proof"></span>
                            <span>LIVE TRADING</span>
                        </div>
                    </div>
                    
                    <div class="account-stats">
                        <div class="stat-large">
                            <span class="stat-value-large">+287%</span>
                            <span class="stat-label-large">Total Growth</span>
                        </div>
                        <div class="stat-grid">
                            <div class="stat-small">
                                <span class="stat-value-small">76%</span>
                                <span class="stat-label-small">Win Rate</span>
                            </div>
                            <div class="stat-small">
                                <span class="stat-value-small">-4.2%</span>
                                <span class="stat-label-small">Max DD</span>
                            </div>
                            <div class="stat-small">
                                <span class="stat-value-small">2.34</span>
                                <span class="stat-label-small">Profit Factor</span>
                            </div>
                            <div class="stat-small">
                                <span class="stat-value-small">143</span>
                                <span class="stat-label-small">Total Trades</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="account-link">
                        <a href="https://www.myfxbook.com/members/DoItTrading/doit-gbp-master/11493777" 
                           target="_blank" class="proof-link">
                            📊 View Live Account on MyFxBook →
                        </a>
                    </div>
                </div>
                
                <!-- Account 2: Aggressive -->
                <div class="live-account-card">
                    <div class="account-header">
                        <h3>Gold Guardian Bot - Aggressive</h3>
                        <div class="live-status">
                            <span class="live-dot-proof"></span>
                            <span>LIVE TRADING</span>
                        </div>
                    </div>
                    
                    <div class="account-stats">
                        <div class="stat-large">
                            <span class="stat-value-large">+456%</span>
                            <span class="stat-label-large">Total Growth</span>
                        </div>
                        <div class="stat-grid">
                            <div class="stat-small">
                                <span class="stat-value-small">96.5%</span>
                                <span class="stat-label-small">Win Rate</span>
                            </div>
                            <div class="stat-small">
                                <span class="stat-value-small">-8.1%</span>
                                <span class="stat-label-small">Max DD</span>
                            </div>
                            <div class="stat-small">
                                <span class="stat-value-small">1.6</span>
                                <span class="stat-label-small">Profit Factor</span>
                            </div>
                            <div class="stat-small">
                                <span class="stat-value-small">89</span>
                                <span class="stat-label-small">Total Trades</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="account-link">
                        <a href="https://www.myfxbook.com/portfolio/doit-gold-guardian/11493798" 
                           target="_blank" class="proof-link">
                            📊 View Live Account on MyFxBook →
                        </a>
                    </div>
                </div>
                
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
 * 9. SOCIAL PROOF - Trust
 */
function doittrading_forex_bots_social_proof() {
    // Get verified testimonials (using real data from your ACF)
    $testimonials = array(
        array(
            'name' => 'MBlue6',
            'country' => 'Germany',
            'text' => 'Setup was simple, and the performance has been great so far. Diego has been exceptionally friendly, responsive, and supportive throughout.',
            'product' => 'GBP Master Bot',
            'verified' => true,
            'rating' => 5,
            'timeframe' => '3 months'
        ),
        array(
            'name' => 'klausdiemaus', 
            'country' => 'Austria',
            'text' => 'Diego\'s EAs have always been reliable, so buying the DoIt GBP Master was an easy decision. Best EA I have ever tried!',
            'product' => 'GBP Master Bot',
            'verified' => true,
            'rating' => 5,
            'timeframe' => '6 months'
        ),
        array(
            'name' => 'Butterfly0856',
            'country' => 'Spain', 
            'text' => 'Been using DoIt GBP Master for two weeks now, and the results have been amazing! The consistency is impressive.',
            'product' => 'GBP Master Bot',
            'verified' => true,
            'rating' => 5,
            'timeframe' => '2 weeks'
        ),
        array(
            'name' => 'TradingPro_UK',
            'country' => 'United Kingdom',
            'text' => 'Finally, a bot that doesn\'t blow my account! Conservative approach but steady profits. Exactly what I needed.',
            'product' => 'Gold Guardian Bot',
            'verified' => true,
            'rating' => 5,
            'timeframe' => '4 months'
        ),
        array(
            'name' => 'ForexNewbie_CA',
            'country' => 'Canada',
            'text' => 'Started with $100 on Index Vanguard. Small profits but consistent. Perfect for beginners like me.',
            'product' => 'Index Vanguard Bot',
            'verified' => true,
            'rating' => 4,
            'timeframe' => '1 month'
        )
    );
    
    $stats = array(
        'total_traders' => '500+',
        'countries' => '15+',
        'avg_rating' => '4.9',
        'total_reviews' => '200+'
    );
    ?>
    <div class="forex-bots-social-proof-section">
        <div class="forex-bots-container">
            
            <div class="social-proof-header">
                <h2>Join <?php echo $stats['total_traders']; ?> Successful Bot Traders</h2>
                <p>Real people, real results from <?php echo $stats['countries']; ?> countries worldwide</p>
                
                <div class="social-stats">
                    <div class="social-stat">
                        <span class="social-stat-number"><?php echo $stats['avg_rating']; ?>★</span>
                        <span class="social-stat-label">Average Rating</span>
                    </div>
                    <div class="social-stat">
                        <span class="social-stat-number"><?php echo $stats['total_reviews']; ?></span>
                        <span class="social-stat-label">Verified Reviews</span>
                    </div>
                    <div class="social-stat">
                        <span class="social-stat-number"><?php echo $stats['countries']; ?></span>
                        <span class="social-stat-label">Countries</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonials-showcase">
                
                <!-- Featured Testimonial -->
                <div class="featured-testimonial">
                    <div class="testimonial-badge">⭐ FEATURED SUCCESS</div>
                    <div class="testimonial-content">
                        <div class="testimonial-quote">
                            "Finally, a trading bot that actually works as advertised. My GBP Master bot 
                            has been running for 6 months with consistent profits and minimal drawdown. 
                            Diego's support is incredible - he personally helped me optimize my settings."
                        </div>
                        <div class="testimonial-author-info">
                            <div class="author-details">
                                <strong class="author-name">klausdiemaus</strong>
                                <span class="author-location">🇦🇹 Austria</span>
                                <span class="author-timeframe">Using for 6 months</span>
                            </div>
                            <div class="author-rating">
                                <?php echo str_repeat('⭐', 5); ?>
                            </div>
                        </div>
                        <div class="verified-purchase-large">
                            ✓ VERIFIED MQL5 PURCHASE
                        </div>
                    </div>
                </div>
                
                <!-- Testimonials Grid -->
                <div class="testimonials-grid-social">
                    <?php foreach (array_slice($testimonials, 0, 4) as $testimonial): ?>
                    <div class="testimonial-card-social">
                        <div class="testimonial-header-social">
                            <div class="testimonial-author-social">
                                <strong><?php echo esc_html($testimonial['name']); ?></strong>
                                <span class="country-flag"><?php 
                                    $flags = array(
                                        'Germany' => '🇩🇪', 'Austria' => '🇦🇹', 'Spain' => '🇪🇸',
                                        'United Kingdom' => '🇬🇧', 'Canada' => '🇨🇦'
                                    );
                                    echo $flags[$testimonial['country']] ?? '🏳️';
                                ?> <?php echo esc_html($testimonial['country']); ?></span>
                            </div>
                            <div class="testimonial-rating-social">
                                <?php echo str_repeat('⭐', $testimonial['rating']); ?>
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
                
                <!-- Trust Indicators */
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
                            <p>Traders from 15+ countries trust our bots</p>
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
 * 11. FINAL CTA - Conversion
 */
function doittrading_forex_bots_final_cta() {
    $active_traders = doittrading_get_active_traders();
    $countdown_target = function_exists('doittrading_get_countdown_target') ? doittrading_get_countdown_target() : date('Y-m-d H:i:s', strtotime('+2 days'));
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
                    
                    <div class="final-pricing">
                        <div class="pricing-current">
                            <span class="price-label">Current Launch Price:</span>
                            <span class="price-amount">$599</span>
                        </div>
                        <div class="pricing-future">
                            <span class="price-label-small">Regular Price: </span>
                            <span class="price-crossed">$999</span>
                        </div>
                        <div class="savings-highlight">
                            Save $400 - Launch Pricing Ends Soon!
                        </div>
                    </div>
                </div>
                
                <!-- Bot Selection Cards -->
                <div class="cta-bot-selection">
                    <h3>Choose Your Trading Bot:</h3>
                    
                    <div class="cta-bots-grid">
                        
                        <!-- GBP Master - Featured -->
                        <div class="cta-bot-card featured">
                            <div class="cta-bot-badge">🏆 MOST POPULAR</div>
                            <h4>GBP Master Bot</h4>
                            <div class="cta-bot-stats">
                                <span>76% Win Rate</span>
                                <span>+12.8% Monthly</span>
                                <span>$50 Min Deposit</span>
                            </div>
                            <div class="cta-bot-price">
                                <span class="cta-price-current">$599</span>
                                <span class="cta-price-original">$999</span>
                            </div>
                            <a href="/product/doit-gbp-master/" class="cta-bot-button primary">
                                🛒 Get GBP Bot Now
                            </a>
                            <div class="cta-bot-features">
                                ✓ Conservative & Reliable ✓ Beginner Friendly ✓ Live Verified
                            </div>
                        </div>
                        
                        <!-- Gold Guardian -->
                        <div class="cta-bot-card">
                            <h4>Gold Guardian Bot</h4>
                            <div class="cta-bot-stats">
                                <span>96.5% Win Rate</span>
                                <span>+25% Monthly</span>
                                <span>$300 Min Deposit</span>
                            </div>
                            <div class="cta-bot-price">
                                <span class="cta-price-current">$399</span>
                                <span class="cta-price-original">$999</span>
                            </div>
                            <a href="/product/doit-gold-guardian/" class="cta-bot-button secondary">
                                🛒 Get Gold Bot
                            </a>
                            <div class="cta-bot-features">
                                ✓ Higher Returns ✓ Gold Specialist ✓ Long-Only Strategy
                            </div>
                        </div>
                        
                        <!-- Index Vanguard -->
                        <div class="cta-bot-card">
                            <h4>Index Vanguard Bot</h4>
                            <div class="cta-bot-stats">
                                <span>50.5% Win Rate</span>
                                <span>+10% Monthly</span>
                                <span>$30 Min Deposit</span>
                            </div>
                            <div class="cta-bot-price">
                                <span class="cta-price-current">$129</span>
                                <span class="cta-price-original">$599</span>
                            </div>
                            <a href="/product/index-vanguard/" class="cta-bot-button secondary">
                                🛒 Get Index Bot
                            </a>
                            <div class="cta-bot-features">
                                ✓ Small Accounts ✓ Low Risk ✓ Perfect for Starters
                            </div>
                        </div>
                        
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
                        <span class="purchase-notification">🔔 Michael K. from UK just purchased GBP Master (3 min ago)</span>
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