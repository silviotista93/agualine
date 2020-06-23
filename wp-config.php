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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
 ini_set('display_errors','Off');
 ini_set('error_reporting', E_ALL );
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'agualine' );

/** MySQL database username */
define( 'DB_USER', 'forge' );

/** MySQL database password */
define( 'DB_PASSWORD', 'MalISmNADCRP1YtdcmNO' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'zpvP2vV64v+V7a*TB/4xJ;-;Z1{6dT7W#<J{TCk9Y2HuhoKDF/l!gP@|,!(T}1Qp' );
define( 'SECURE_AUTH_KEY',  'q%wlVqXUbc$-:a~!CY):x$}v;fnZf1x7tu|=n|oK:,,rI+ch&5+`!XQ`d0bQDMDh' );
define( 'LOGGED_IN_KEY',    'W|)TM[>UuR@}2:I2lReGB#{_KdxuoU+a7fH3B;$u(j8f@]p3]<E[A}V/DN1mF$i-' );
define( 'NONCE_KEY',        '[F8$smg)%14}>SHX/-1F%CZ?K/m$BSa>Eb2Em*mT(X<iKx}q=iL9[wp6mhV:3<&P' );
define( 'AUTH_SALT',        'ZSENzmmdU{|XpzOLyp]_kk6@s?;(L(~@rm18V>[0BswfG%+fot1U1Atw<t_m3I$B' );
define( 'SECURE_AUTH_SALT', '59Q3,XC/Tmle;<HY|08HrB; V>S|U;Hkw(?}CI7GC>&KL>~@S3U/UnI;o?3UkQJb' );
define( 'LOGGED_IN_SALT',   'p<AA9+&O*bVZJJgorB:NGN$E k_%}FMT*+NX*;by;&:_t==vwEG&)2!W-XesJSE=' );
define( 'NONCE_SALT',       'xgv.pB^==&[=`pO)hfmM&G FcxtG@5NPkl3#zE5F-p.^wkg#S8$|2(3 BD,Wgv0n' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'sm_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
