# DoItTrading Custom Fields Migration

This directory contains the native WordPress custom fields implementation that replaces the Advanced Custom Fields (ACF) plugin dependency for the DoItTrading theme.

## Architecture Overview

The custom fields system is organized into a modular structure with three main components:

### 1. Common Fields (`common/`)
Universal custom fields that apply to all product types:
- **product-fields-common.php**: Core product fields (performance metrics, FAQ, purchase links, etc.)
- **product-reviews.php**: Universal review system for all products

### 2. EAS-Specific Fields (`eas/`)
Enhanced fields specifically for Expert Advisors products:
- **product-fields-eas.php**: Hero sections, marketing features, ROI calculator settings, social proof

### 3. Indicator-Specific Fields (`indicators/`)
Specialized fields for trading indicators:
- **product-fields-indicators.php**: Signal types, timeframes, accuracy metrics, market conditions

## Key Features

### Modular Design
- Clean separation between common, EAS, and indicator-specific functionality
- Conditional loading based on product categories
- Minimal performance impact

### ACF Compatibility
- Backward compatibility layer for existing templates
- Migration utility to transfer existing ACF data
- Helper functions that work with both systems

### Enhanced Admin Experience
- Native WordPress meta boxes with improved styling
- Organized field groups with contextual help
- Responsive admin interface

## Migration Process

### Automatic Migration
1. The system detects existing ACF installation
2. Shows admin notice with migration option
3. One-click migration transfers all existing data
4. Preserves data integrity and relationships

### Manual Migration
If you need to migrate manually or in batches:

```php
// Check migration status
$status = DoItTrading_ACF_Migration_Utility::get_migration_status();

// Migrate specific product
$utility = new DoItTrading_ACF_Migration_Utility();
$utility->migrate_product_data($product_id);
```

## Usage in Templates

### Basic Field Access
```php
// New native method
$monthly_gain = doittrading_get_field('monthly_gain', $product_id);

// ACF compatibility (still works)
$monthly_gain = get_field('monthly_gain', $product_id);
```

### Product Type Checking
```php
// Check if product is Expert Advisor
if (doittrading_is_expert_advisor($product_id)) {
    $hero_title = doittrading_get_field('hero_hook_title', $product_id);
}

// Check if product is Indicator
if (doittrading_is_indicator($product_id)) {
    $signal_type = doittrading_get_field('signal_type', $product_id);
}
```

### Reviews System
```php
// Get all reviews for a product
$reviews = doittrading_get_product_reviews($product_id);

// Render star rating
echo doittrading_render_stars(5, 'product-rating');

// Check if product has reviews
if (DoItTrading_Product_Reviews::has_reviews($product_id)) {
    // Display reviews
}
```

## Field Reference

### Common Fields
- `is_featured_product` (checkbox)
- `homepage_order` (number)
- `total_active_users` (number)
- `total_volume_traded` (number)
- `monthly_gain` (number)
- `win_rate` (number)
- `max_drawdown` (number)
- `profit_factor` (number)
- `supported_platforms` (array: mt4, mt5)
- `trading_style` (select)
- `mql5_purchase_link_mt4` (url)
- `mql5_purchase_link_mt5` (url)
- `myfxbook_url` (url)
- `minimum_deposit` (number)
- `faq_question_1-3` (text)
- `faq_answer_1-3` (textarea)
- `key_features` (textarea)

### EAS-Specific Fields
- `featured_in_forex_bots_hero` (checkbox)
- `hero_hook_title` (text)
- `hero_subtitle` (text)
- `product_tagline` (text)
- `countdown_end_date` (datetime-local)
- `stock_remaining` (number)
- `monthly_gain_context` (text)
- `win_rate_context` (text)
- `drawdown_context` (text)
- `comparison_benchmark` (text)
- `performance_period` (select: 3,6,12,24)
- `roi_capital_base` (number)
- `best_for` (select)
- `risk_level` (select: low,medium,high)
- `target_market` (text)
- `mql5_reviews_verified` (checkbox)
- `live_traders_count_min/max` (number)
- `last_trade_pips_min/max` (number)
- `show_in_comparisons` (checkbox)

### Indicator-Specific Fields
- `indicator_type` (select)
- `signal_type` (select)
- `timeframes` (array)
- `accuracy_rate` (number)
- `false_signals_rate` (number)
- `average_pips_per_signal` (number)
- `signals_per_day` (number)
- `repaint_status` (select)
- `alert_types` (array)
- `customizable_settings` (checkbox)
- `multi_currency_support` (checkbox)
- `best_trading_sessions` (array)
- `recommended_pairs` (textarea)
- `strategy_combination` (textarea)
- `market_conditions` (array)
- `featured_in_indicators_page` (checkbox)

### Review Fields
- `mql5_total_reviews` (number)
- `mql5_average_rating` (number)
- `review_1-5_name` (text)
- `review_1-5_date` (date)
- `review_1-5_stars` (number 1-5)
- `review_1-5_verified` (checkbox)
- `review_1-5_text` (textarea)

## Benefits Over ACF

1. **Performance**: Native WordPress implementation with no plugin overhead
2. **Reliability**: No dependency on third-party plugins
3. **Customization**: Full control over field rendering and validation
4. **Scalability**: Optimized for large numbers of products
5. **Security**: Direct integration with WordPress core security features
6. **Maintenance**: Reduced plugin conflicts and update issues

## Troubleshooting

### Fields Not Showing
1. Check if custom fields loader is included in functions.php
2. Verify product categories are correctly assigned
3. Clear any caching plugins

### Migration Issues
1. Backup database before migration
2. Check WordPress error logs for specific issues
3. Verify ACF field keys match mapping in migration utility

### Template Compatibility
1. Use compatibility functions for gradual transition
2. Test on staging environment first
3. Update template files to use new helper functions

## File Structure
```
inc/custom-fields/
├── custom-fields-loader.php          # Main loader and initialization
├── acf-migration-utility.php         # ACF data migration utility
├── common/
│   ├── product-fields-common.php     # Universal product fields
│   └── product-reviews.php           # Review system
├── eas/
│   └── product-fields-eas.php        # Expert Advisor specific fields
├── indicators/
│   └── product-fields-indicators.php # Indicator specific fields
└── README.md                         # This documentation
```

## Support

For issues related to the custom fields migration:
1. Check the WordPress debug log
2. Verify all files are properly uploaded
3. Test with a single product first
4. Contact development team for complex migration scenarios