<?php
/**
 * WordPress Function Stubs for IDE
 * This file helps IDEs recognize WordPress functions
 * DO NOT INCLUDE THIS FILE - IT'S ONLY FOR IDE SUPPORT
 */

// WooCommerce Conditional Functions
function is_product() {}
function is_shop() {}
function is_product_category() {}
function is_cart() {}
function is_checkout() {}
function is_account_page() {}

// WordPress Core Functions
function get_template_directory_uri() {}
function get_stylesheet_directory_uri() {}
function get_stylesheet_directory() {}
function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all') {}
function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false) {}
function wp_localize_script($handle, $object_name, $l10n) {}
function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {}
function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {}
function remove_action($tag, $function_to_remove, $priority = 10) {}
function remove_filter($tag, $function_to_remove, $priority = 10) {}
function get_the_ID() {}
function get_post_type($post = null) {}
function has_term($term = '', $taxonomy = '', $post = null) {}
function get_transient($transient) {}
function set_transient($transient, $value, $expiration = 0) {}
function admin_url($path = '', $scheme = 'admin') {}
function wp_create_nonce($action = -1) {}
function wp_get_theme() {}
function is_admin() {}
function wp_kses_post($data) {}
function esc_html($text) {}
function esc_attr($text) {}
function esc_url($url, $protocols = null, $_context = 'display') {}

// ACF Functions
function get_field($selector, $post_id = false, $format_value = true) {}
function the_field($selector, $post_id = false, $format_value = true) {}
function have_rows($selector, $post_id = false) {}
function the_row() {}
function get_sub_field($selector, $format_value = true) {}

// WooCommerce Functions
function woocommerce_template_single_add_to_cart() {}
function woocommerce_template_single_meta() {}
function woocommerce_template_single_rating() {}

// PHP Functions that Intelephense sometimes misses

// Constants
define('ABSPATH', '/path/to/wordpress/');
define('WP_DEBUG', true);
define('HOUR_IN_SECONDS', 3600);
define('DAY_IN_SECONDS', 86400);
define('MINUTE_IN_SECONDS', 60);