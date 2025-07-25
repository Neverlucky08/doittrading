<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('HIDE_DEPRECATED_ERRORS', true); // Tu switch personalizado

if (defined('HIDE_DEPRECATED_ERRORS') && HIDE_DEPRECATED_ERRORS) {
    define('WP_DEBUG', true);
    define('WP_DEBUG_LOG', true);
    define('WP_DEBUG_DISPLAY', false);
    ini_set('display_errors', 0);
} else {
    define('WP_DEBUG', true);
    define('WP_DEBUG_LOG', true);
    define('WP_DEBUG_DISPLAY', true);
}

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '#}cI{y0,^%$Y/lc$Gv?m>S]`fE7S0m4a#zOhTkUkmD2E?vLE$PW*H.1E)X?z24]F' );
define( 'SECURE_AUTH_KEY',   'VNwRC1&=%_5N(-{=rZ(!1dT#g6&!6d(NW %|cHZ.28YS2[H<{;%@|lLeFc^-`Gs(' );
define( 'LOGGED_IN_KEY',     '<W(Qk<%0/S|MRS1;A~N1 KF;DZ]W *?lc0I6+ sXC=W3w?Ab?A63.hahAHpE41Mq' );
define( 'NONCE_KEY',         '966o<lgA3J~?kF!u:hBX ZDa*Ek~0=$BET >oYVw@@?R=Z<d?#A_5CAYp~C#!|V=' );
define( 'AUTH_SALT',         '}5Io/ff]{_WFQ,/yqj!Yw&m)4F?H#9eKBq+I1y9%ADdR6wHQ_}^9K4.%dio)sup7' );
define( 'SECURE_AUTH_SALT',  'p5X(N&_`fMztoD?Y9/$h]39.HBelDJhD7Szx_UYA,F&S|Q.SS~Ks;<jZPkv t]5r' );
define( 'LOGGED_IN_SALT',    'd;Yt&#|gjVhWdKbh?mX>qyybXRjt?.rLk~qHcDV;aY1?CMuVEjuvg{S(,ya5 =C ' );
define( 'NONCE_SALT',        '.~rG0[;?gZKLUDAD K[9B?h:goE~@Fo{X~FATJ9xIxuTi6.VQu(.-+o~<EB$*k<}' );
define( 'WP_CACHE_KEY_SALT', ')+;B*rKVrc?`;avVdM#x?#Y<1R.-^- v]O3=()omv`TIv1t)!Sn4hN4J!Ms&v!tr' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
