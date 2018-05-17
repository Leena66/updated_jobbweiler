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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'updated_jobbweiler');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '8*iKU?#ZhC_5{zz`WZcy2$ix&o}jv@ ]=,</AA]yNpE_T&j4pLFcikffzg($ou;G');
define('SECURE_AUTH_KEY',  'w!@lr4pxw pZJ;I}:rS#xN~zTZ=H4N4 R`)r~//1.s]*lH cP>/QDp$`?1ZPRd[_');
define('LOGGED_IN_KEY',    'O9KNG;j.YoB<<5n7eff%fOSz $tCp37pmlf;S1$#&9x?,_3:o:NW>/Z{D1oh8xnF');
define('NONCE_KEY',        'EF`{R^&&n6Sa_-)QT2^z:;m()BK(sr9`6mM;0j#yQT{pYES0J6?ufz(wFo{-m_zC');
define('AUTH_SALT',        'FcAC$VK(i(+:/vZda?n}JDgs*`d)c?bLYD&}(2cu&Ng73`I^:l9(Ea7ADriL)B^c');
define('SECURE_AUTH_SALT', 'B3C<OWT<sjoYV2U!Z-CRY9,rI^a;GEUq[p|X7iQ.dPYgJQhip f+Fc~(di9<I}&:');
define('LOGGED_IN_SALT',   '#lPWtWK$>MzVdUl(yTvN03M=e@[I6Zq,)*NU#;nt8I/qKV78x{5:%r$dA63JsBcL');
define('NONCE_SALT',       'F:{D-%]-;IQYh8~#0sZ9`Gx%4sZ@LTnz?bgraAG{X4t#S!$iA-k}j)rmWgneY}3M');

/**#@-*/

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

define( 'WP_MEMORY_LIMIT', '512M' );


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
