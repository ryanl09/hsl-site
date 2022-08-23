<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

/**
 * Database connection information is automatically provided.
 * There is no need to set or change the following database configuration
 * values:
 *   DB_HOST
 *   DB_NAME
 *   DB_USER
 *   DB_PASSWORD
 *   DB_CHARSET
 *   DB_COLLATE
 */

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'WP_DEBUG_LOG', true );

define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);

if ( WP_DEBUG ) {

  @error_reporting( E_ALL );

  @ini_set( ‘log_errors’, true );

  @ini_set( ‘log_errors_max_len’, ‘0’ );

  define( ‘WP_DEBUG_LOG’, true );

  define( ‘WP_DEBUG_DISPLAY’, false );

  define( ‘CONCATENATE_SCRIPTS’, false );

  define( ‘SAVEQUERIES’, true );

}

define('AUTH_KEY',         'z7h!lZb]IYQ@.Wl-#SG{*E^w2+yHX-zPJSt#y!06cPLtiAKKn,>uhQ=gnP|sSL{)');
define('SECURE_AUTH_KEY',  '$E7a6f=rf[Xu<>b:Y;2Gw7x>I46qK;}!|6JgX;)k>pr]m==V8jN{Kei*nr=wHBhi');
define('LOGGED_IN_KEY',    'wn-Z)-F0Gg.ba|gnyf[OGDrF:7Dds|-@R(-lZ?+BqOR4#0JQ^lp.Dsf=u4:x.5-z');
define('NONCE_KEY',        'K7FCXkKrT7?ld%F;w?2hHbx;*uk7=_f;2EdEJ!1>[tER,vOI@[4T27dnAD@8.$6[');
define('AUTH_SALT',        ':i|CMYVW3?BU0[d%U3b~hJN@ydogg*,1qyMcbeizag[<Sxe97c(NMZ0MN858j=5}');
define('SECURE_AUTH_SALT', 'kMXzYOV=uU%dBXJD76Dd+,LscR%nBI~jdz5MXvTr!DajHV5~#k~!:47mMaFGX8h<');
define('LOGGED_IN_SALT',   'Jub@ZtsHzg+-.)M3sHxXfmrM2hEngjbutoca]bf|8zHX@~kPBxI-QthG|kJ7VW{a');
define('NONCE_SALT',       '4al}{vhmckd?N@gDU8n8<)bNa*7}Xskh#%>9~RF#DA?Q<!Z*++bkfpaI9yU7<nu[');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
