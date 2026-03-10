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
define('DB_NAME', 'aavin-com');

/** MySQL database username */
define('DB_USER', 'aavin');

/** MySQL database password */
define('DB_PASSWORD', 'fukUb3u8Hucr');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FTP_USER', 'aavin'); // Your FTP username
define('FTP_PASS', 'Aavin2016'); // Your FTP password
define('FTP_HOST', '127.0.0.1:21'); // Your FTP URL:Your FTP port

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '>AW*F4#U)0}~_u5pLs(r-^(wr1jf[0&#+-u?%_B,fKtT!oX)8YfZPCXh#=Piph`j');
define('SECURE_AUTH_KEY',  '}BHKFX>7Z<1yNA%`DUP:g] TWm((`tRm*ecw.`= ! }aX/zp2$<ZCQq,g~#>N%`Y');
define('LOGGED_IN_KEY',    '!giT;$#rdR`|Y_k1#)#ANj0x)N@1m%3J!xrnMWqeiMs_mV3oQmMLA(M`9t~PeT08');
define('NONCE_KEY',        ' &x6K7.r3LaJAud3xOVGM5/Enq#pV!^pG+}5j)/!v2;%(!;}_M30BEP1FlS=g0NR');
define('AUTH_SALT',        'qM!ahfqD[u=S$OAnV2ed!WF;:SM1uC4z*Zt cfhQ`+9k-!.f1.}+;$+|8wY L:+1');
define('SECURE_AUTH_SALT', 'b`d^<isYj0J*ICn9K<yP:52nMSQLNJWRLw~{2zjt/.]`N}WUU7_d}g`8Y}~Gjybz');
define('LOGGED_IN_SALT',   'ITMq4`dqdfpj7FZ`0.|?;B5/OZkTMX0;Hgm*/z<b.rwmGqA+02[lun$[1zQP`,_O');
define('NONCE_SALT',       '`0$WIm*$8T_MVPJ)FX=``5a}*wO{^;O|^fK0&zUrF$07Ki}}pYhwpiPB,q%;*1^l');

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
