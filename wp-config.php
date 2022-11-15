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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'mockup' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         '*8zDyQ}>11pV03M+q4(w`Z%7f BZKMTg*|?p%?.v|Y>alBs;LiLSqw Y`],,<zx2' );
define( 'SECURE_AUTH_KEY',  'V,k1@a4L/dv>M<9rIAe(ILk&H*5MH-d]eI)KqA5N43DGb[7 %/b1{#JuCD|)WQZO' );
define( 'LOGGED_IN_KEY',    '=<*CK:V(IhE|p uLO$hLl~3y]mJ;s%FUGM~fCRDII)e5(@,,wbPw{;[XNf*U2^Tf' );
define( 'NONCE_KEY',        'B>s1M$oq:rrEsC]zyF2^H>5!9j/ !{d6950=zS;@_]_%IZK>#-s<w;f0_r;2p|9j' );
define( 'AUTH_SALT',        'Rt=B-b98h9Raty}Q2HkOLFR*MwiU}!N|#Q8KCA~2W}I+6;|+&YGEPXYw(WKU_;Uo' );
define( 'SECURE_AUTH_SALT', '0,zCcgiu?QV=T^aSD)E[- ~Y@Gy~h+Z*pOVOiP7Aig6jsHjd=(704~GCi6dKKbRu' );
define( 'LOGGED_IN_SALT',   ';Yco>)TKz Za~Pq8O9$Td{?*X~3e2MrT|Jse!NajBHnx{:tA{1w?!ZQ h?zeE>yc' );
define( 'NONCE_SALT',       '|6@^W@S-S);}<T,$ Z >Yvz/^?}Io YrahmsA8WD@&,S5KL}j@$03$Q2i%,Qb-P~' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
