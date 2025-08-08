<?php
/*
Plugin Name: DoIt GPT Chat
Description: Secure GPT proxy with limits, logs, e‑mail unlock y un widget JS modular (core + skins bubble/inline) con autodetección.
Author: DoIt Trading
Version: 1.1
*/

// ───────────────────────────────────────────────────────────────
// 0) Exit si se llama directamente
// ───────────────────────────────────────────────────────────────
defined( 'ABSPATH' ) || exit;

// ───────────────────────────────────────────────────────────────
// 1) Configuración segura (fuera del repo)
// ───────────────────────────────────────────────────────────────

require_once DOIT_GPT_SECURE_CONFIG;
// ───────────────────────────────────────────────────────────────
// 2) Helpers – nucleo del proxy, OpenAI y e‑mail‑unlock
// ───────────────────────────────────────────────────────────────
$includes = [
  'includes/functions_gpt/core.php',
  'includes/functions_gpt/openai.php',
  'includes/functions_gpt/email-unlock.php',
];
foreach ( $includes as $inc ) {
  require_once plugin_dir_path( __FILE__ ) . $inc;
}

// -----------------------------------------------------------------------------
// 3) Encolado de scripts (núcleo + skin) – autodetección bubble | inline
// -----------------------------------------------------------------------------
add_action( 'wp_enqueue_scripts', 'doit_chat_enqueue_hook');
function doit_chat_enqueue_hook() {
  doit_chat_enqueue_widget( 'auto' );
}

/**
 * Encola el núcleo + el “skin” correcto (bubble o inline).
 *
 * @param string $mode  'bubble', 'inline' o 'auto'.
 *                      'auto': → inline si la página contiene el shortcode; bubble en cualquier otro caso.
 */
function doit_chat_enqueue_widget( $mode = 'auto' ) {

  /* ─────────────────────────────────────────────────────
     3.1) Detección de modo si $mode === 'auto'
  ───────────────────────────────────────────────────── */
  if ( $mode === 'auto' ) {
    global $post;
    $has_shortcode = $post && has_shortcode( $post->post_content, 'doit_gpt_chat' );
    $mode          = $has_shortcode ? 'inline' : 'bubble';
  }

  /* ─────────────────────────────────────────────────────
     3.2) Control de carga para el modo burbuja
            (por defecto SOLO en la página "gpt-test" a menos
             que uses el filtro 'doit_chat_load_everywhere').
  ───────────────────────────────────────────────────── */
  if ( $mode === 'bubble' ) {
    $load_everywhere = apply_filters( 'doit_chat_load_everywhere', DOIT_GPT_USE_EVERYWHERE );
    if ( ! $load_everywhere && ! is_page( DOIT_GPT_TEST_SLUG ) ) {
      return; // Salir: no se carga en esta URL
    }
  }

  /* ─────────────────────────────────────────────────────
     3.3) Registrar scripts solo una vez
  ───────────────────────────────────────────────────── */
  $base   = plugin_dir_url( __FILE__ ) . 'assets/';
  $deps   = []; // sin dependencias externas
  $footer = true;

  wp_register_script( 'doit-chat-core',   $base . 'doit-chat-core.js',   $deps, '1.0', $footer );
  wp_register_script( 'doit-chat-bubble', $base . 'doit-chat-bubble.js', [ 'doit-chat-core' ], '1.0', $footer );
  wp_register_script( 'doit-chat-inline', $base . 'doit-chat-inline.js', [ 'doit-chat-core' ], '1.0', $footer );
  
  // Register CSS file con dependencia del tema para cargar después
  wp_register_style( 'doit-chat-styles', $base . 'chat-styles.css', [], '1.0' );

  /* ─────────────────────────────────────────────────────
     3.4) Encolar núcleo + skin
  ───────────────────────────────────────────────────── */
  wp_enqueue_script( 'doit-chat-core' );
  wp_enqueue_style( 'doit-chat-styles' );

  $skin_handle = ( $mode === 'inline' ) ? 'doit-chat-inline' : 'doit-chat-bubble';
  wp_enqueue_script( $skin_handle );

  /* ─────────────────────────────────────────────────────
     3.5) Config global accesible en JS (doitChatCfg)
  ───────────────────────────────────────────────────── */
  wp_localize_script( 'doit-chat-core', 'doitChatCfg', [
    'ajax'                        => admin_url( 'admin-ajax.php' ),
    'nonce'                       => wp_create_nonce( 'doit_chat' ),
    'limit'                       => defined( 'DOIT_MAX_MSGS_FREE' ) ? DOIT_MAX_MSGS_FREE : 5,
    'limit_after_email_confirmed' => defined( 'DOIT_MAX_MSGS_EMAIL_CONFIRMED' ) ? DOIT_MAX_MSGS_EMAIL_CONFIRMED : 20,
  ] );
}

// -----------------------------------------------------------------------------
// 4) Rutas AJAX
// -----------------------------------------------------------------------------
add_action( 'wp_ajax_nopriv_doit_chat_ask',    'doit_ajax_ask' );
add_action( 'wp_ajax_doit_chat_ask',           'doit_ajax_ask' );
add_action( 'wp_ajax_nopriv_doit_chat_email',  'doit_ajax_email' );
add_action( 'wp_ajax_doit_chat_email',         'doit_ajax_email' );
add_action( 'wp_ajax_nopriv_doit_get_status',  'doit_ajax_get_status' );
add_action( 'wp_ajax_doit_get_status',         'doit_ajax_get_status' );
add_action( 'wp_ajax_nopriv_doit_check_confirmation', 'doit_ajax_check_confirmation' );
add_action( 'wp_ajax_doit_check_confirmation',        'doit_ajax_check_confirmation' );

// -----------------------------------------------------------------------------
// 5) Tablas de log al activar el plugin
// -----------------------------------------------------------------------------
register_activation_hook( __FILE__, 'doit_create_tables' );

// -----------------------------------------------------------------------------
// 6) Shortcode [doit_gpt_chat]  – siempre versión INLINE
//    (puedes forzar <mode="bubble|inline"> si lo deseas)
// -----------------------------------------------------------------------------
add_shortcode( 'doit_gpt_chat', 'doit_chat_shortcode_handler' );
function doit_chat_shortcode_handler( $atts ) {
  $atts = shortcode_atts( [
    'mode'      => 'inline',   // inline por defecto cuando usas el shortcode
    'container' => 'doit-chat-wrapper',
  ], $atts, 'doit_gpt_chat' );

  // Encolar JS si aún no está
  if ( ! wp_script_is( 'doit-chat-core', 'enqueued' ) ) {
    doit_chat_enqueue_widget( $atts['mode'] );
  }

  // Devuelve un contenedor; el skin inline se montará dentro
  $id = esc_attr( $atts['container'] );
  return "<div id=\"$id\"></div>";
}
