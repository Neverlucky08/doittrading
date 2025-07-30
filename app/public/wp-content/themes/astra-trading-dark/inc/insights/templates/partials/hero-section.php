<?php
/**
 * Insights Hero Section
 */
$live_data = doittrading_get_live_trading_data();
?>

<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">📊 Trading Insights</h1>
        <p class="hero-subtitle">Real Analysis. Live Results. No BS.</p>
        <div class="live-indicator">
            <div class="live-dot"></div>
            <span>
                <strong>LIVE</strong> 
                GBP Master: <?php echo $live_data['gbp_master']['direction']; ?><?php echo $live_data['gbp_master']['pips']; ?> pips | 
                Gold Guardian: <?php echo $live_data['gold_guardian']['direction']; ?><?php echo $live_data['gold_guardian']['pips']; ?> pips | 
                Index: <?php echo $live_data['index_vanguard']['direction']; ?><?php echo $live_data['index_vanguard']['pips']; ?> pips
            </span>
        </div>
    </div>
</section>