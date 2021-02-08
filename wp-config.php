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
define('FS_METHOD','direct');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'demo_shop_db' );

/** MySQL database username */
define( 'DB_USER', 'xcrea_phpmyadmin' );

/** MySQL database password */
define( 'DB_PASSWORD', 'yE=!wjeV<d_m5k>4' );

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
define( 'AUTH_KEY',         '!c6ZS_%Jy}J(ZJpv8]JvFRiF2|I8oyYxE8srb`?J%c9U%ESZXB5e+(:5Z<l6u2%y' );
define( 'SECURE_AUTH_KEY',  'x|q3D]4InVh_CtAO`SL0Y`xVW-YEJOr~9!6%#I1d*H/9..{R%d2U]<tt]s<;DYIH' );
define( 'LOGGED_IN_KEY',    '+bu0$v%{?T.<KXr*|gf_Gw-zs:|!.p?Bpmx-rhXaD0Co}n62fV>7Z9%CXZ.1,~/U' );
define( 'NONCE_KEY',        'g+B|S5~kLZD5@]e7/u$V$G?Hk-TFN%hn%!BR;R}}t2a3v-{16B[UE9X_L!=Bojf#' );
define( 'AUTH_SALT',        'o]| ho,%baexF5eppPtlZ2;DV69ZC_Ss&Us0%lI}zUjjr{Eb%xSA2Rm9L4[O-ic*' );
define( 'SECURE_AUTH_SALT', '0-nmZZ7EQh.k$Xb0JOoI,_[<g}K+P5>OU:d6K~`lq%bdo 6TYu@4!MTn9sl;H`)s' );
define( 'LOGGED_IN_SALT',   'j&eNew6Ph0k.DSi=QoA!v0b#=oKK6uh1%_zBM_JTK?Hf&!?8-<7e%GGJyVL#gA3%' );
define( 'NONCE_SALT',       ' 5$i>$F(,UY;9eUL]Q$|sDRi5hQdpoc m.l+V`nkF@n[Gb20bDKtr)WE5Mm1,+9v' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
