<?php

// BEGIN iThemes Security - No modifiques ni borres esta línea
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Desactivar editor de archivos - Seguridad > Ajustes > Ajustes WordPress > Editor de archivos
define( 'FORCE_SSL_ADMIN', true ); // Fuerza SSL en el escritorio - Securidad > Ajustes > Capas de Conexión Segura (SSL) > SSL en el escritorio
// END iThemes Security - No modifiques ni borres esta línea

/**
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'qze557');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Xk753Ja96N');

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
/*define('AUTH_KEY', 'pon aquí tu frase aleatoria'); // Cambia esto por tu frase aleatoria.
define('SECURE_AUTH_KEY', 'pon aquí tu frase aleatoria'); // Cambia esto por tu frase aleatoria.
define('LOGGED_IN_KEY', 'pon aquí tu frase aleatoria'); // Cambia esto por tu frase aleatoria.
define('NONCE_KEY', 'pon aquí tu frase aleatoria'); // Cambia esto por tu frase aleatoria.
define('AUTH_SALT', 'pon aquí tu frase aleatoria'); // Cambia esto por tu frase aleatoria.
define('SECURE_AUTH_SALT', 'pon aquí tu frase aleatoria'); // Cambia esto por tu frase aleatoria.
define('LOGGED_IN_SALT', 'pon aquí tu frase aleatoria'); // Cambia esto por tu frase aleatoria.
define('NONCE_SALT', 'pon aquí tu frase aleatoria'); // Cambia esto por tu frase aleatoria.*/

define('AUTH_KEY',         'Py- ?g[Ny7Ao3lQyLI$OV+6&dIXT3$q6mB|>xVQNS|-1+a(Y>7Z|:-(bc:kxcLCQ');
define('SECURE_AUTH_KEY',  'yHpG`?Q+>D?V(>ObkebiT6A1w1&1rHeT:l6ai,iT]SerNyaoz}%q2g<cTanN758$');
define('LOGGED_IN_KEY',    '_+/};|l+bXjm3TDVmImfu 7dzwwy(&eh_J<=9e+SUWs&9(mrv-Zl_C*89%<We5R]');
define('NONCE_KEY',        '~/A<EXa[n gCDmj2TQ5@7C.`>Z5%ihsJ-3-M=bh]9^b|HUSJs +iS.ggB*`e`^6n');
define('AUTH_SALT',        '5`L9n:/WJ. 9T}I6{}aH0=POD#pH[wjU`5NHw~z02tXNYX)O(gk})R8{p>Pac[b@');
define('SECURE_AUTH_SALT', 'QI8bgRG7}+%f!pw?Ql> .-Fhvk@k  /&+AX/G{7lA@RKYA9V%AwlmwH*#yg~JO1g');
define('LOGGED_IN_SALT',   'I]%c.&LRO-+P1uAnYytssp^Ieb-*vUcaE0Iz}hMc_6BMKqU6TD1EgLi,NYyy$@=6');
define('NONCE_SALT',       'uzl4xmg{0VA!|v^u:De{0A50;c:M1iyNu@dFK[[FaoiF(+:&%Ofv|(x$I&Q~qVht');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix = 'wpvivo_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('FS_METHOD','direct');
define('ALLOW_UNFILTERED_UPLOADS', true);


