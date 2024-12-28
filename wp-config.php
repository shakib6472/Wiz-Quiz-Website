<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wiz' );

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
define( 'AUTH_KEY',         'tm%v%W6[+*y_%F,6RDeBRfyMrxP>0-b(yJc((GODoaW~srd)6/};_#f7Zdrm{nCQ' );
define( 'SECURE_AUTH_KEY',  'o@Lbn^L&QivD+|r9%G2{ixl4;Dt&1N~u8(~#A+=$koltLc:^)e._Ox0;>d/s^GsR' );
define( 'LOGGED_IN_KEY',    'zikPRsno+vg&8O5]_moPWdP0CQ^b>Dciyjv)U$e?wN^Zg9uY_.}Pf1@rsg/xv=F-' );
define( 'NONCE_KEY',        'uBDXW$:~5Cx4.n~G~(imd3>[G}.&[t)CCnmez{@s3Q_@So52qllaVu|XH@WMK0]:' );
define( 'AUTH_SALT',        'I[C&XL-gdRS+~bG1T-f~^<9sEW/@FI[Z/abEaHbOC >Ozz44N(*ZzJ9CQYz;wr6e' );
define( 'SECURE_AUTH_SALT', 'f;<jVRqf*mB/`$jIxoJV/Z%?< >j0{P3z,H O0h p~}}6LPn%1J_Ipao~%{)4ACW' );
define( 'LOGGED_IN_SALT',   'Z5Hk%1ic/DfBSHf1df7(Hv{},3rl6:kJ{Y#h[cnLqEGu?Kmga)~6 :lf*V&Ad9M1' );
define( 'NONCE_SALT',       'Z4{||cUUiiL;J50a}rj`Do=W4oNjJ-JyU#~Xy)%M5?4*|}+;[.%r*nB0HYUc5x7S' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
