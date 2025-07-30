# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress child theme based on Astra, specifically designed for DoItTrading - a trading platform that sells Expert Advisors (EAs) and forex trading indicators. The theme implements a dark, professional design optimized for trading products and educational content.

## Proceso por defecto para cambios
1) **Plan**: describe el plan en bullets.  
2) **Buscar antes de crear**:
   - Usa **Grep/Glob/Read** para localizar funciones/clases existentes relacionadas.
   - Si existe algo equivalente, **modifica en sitio**. **No dupliques** definiciones.
3) **Proponer diff**: muestra *diffs* por archivo.  
4) **Git**:
   - Trabaja en rama (`feature/*`, `fix/*`), *commit* descriptivo,
     abre PR hacia `develop`/`staging`

## Seguridad y permisos
- **Nunca** edites fuera de las rutas permitidas.
- No ejecutes comandos destructivos (`rm -rf`, `sudo`, etc.).
- Cuando tengas dudas, cambia a **modo "plan"** y pide confirmación

## Theme Architecture

### Core Structure
- **Parent Theme**: Astra (inherits base functionality)
- **Main Functions**: `functions.php` - theme setup, asset enqueuing, and module inclusion
- **Core Functions**: `inc/core-functions.php` - utility functions for trading data, caching, and theme helpers
- **Modular Components**: Organized in `inc/` directory by feature

### Key Modules
- **Products** (`inc/products/`): WooCommerce product display customizations, hero sections, and EA-specific features
- **Insights** (`inc/insights/`): Custom post type for trading educational content with AJAX filtering
- **Homepage** (`inc/homepage/`): Homepage sections and content management
- **Pages** (`inc/pages/`): Specialized page templates for forex bots and indicators

### Custom Post Types
- **Insights CPT**: Registered in `inc/insights/insights-cpt.php`
  - Slug: `/insights/` for individual posts
  - Categories: `insight_category` taxonomy
  - Gutenberg enabled with REST API support

### Asset Management
- **CSS**: Modular stylesheets in `assets/css/` imported via `main.css`
- **JavaScript**: Feature-specific JS files loaded conditionally based on page type
- **Enqueuing**: Version 2.0 with proper dependencies and localization

### WooCommerce Integration
- Product display modifications for EA vs. regular products
- ROI calculator functionality for trading products
- Social proof and marketing features
- Conditional script loading based on product type

## Development Commands

This is a WordPress theme with no build process. Development is done directly with PHP, CSS, and JavaScript files.

### Local Development
- Theme files are edited directly
- WordPress functions handle asset enqueuing and versioning
- No compilation or build steps required

### Testing
- Test theme functionality through WordPress admin and frontend
- Check WooCommerce product pages for EA-specific features
- Verify insights page filtering and AJAX functionality

## Key Features

### Dynamic Content
- Active traders count with hourly cache (`doittrading_get_active_traders()`)
- Last trade information with 5-minute cache (`doittrading_get_last_trade()`)
- WordPress transients used for performance optimization

### Product Differentiation
- EA products get specialized hero sections and features
- Non-EA products use standard WooCommerce display
- ROI calculator integration for trading products

### Content Management
- Insights system for educational trading content
- AJAX-powered filtering and search
- Featured posts and related content functionality

### Responsive Design
- Mobile-optimized layouts
- Dark theme with professional trading aesthetics
- CSS custom properties for consistent theming

## File Organization

- `functions.php`: Main theme setup and module loading
- `inc/`: Modular functionality organized by feature
- `assets/`: CSS and JavaScript files
- `template-parts/`: Custom page templates
- `stubs/`: Development dependencies (WordPress/WooCommerce stubs)