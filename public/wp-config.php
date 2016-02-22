<?php
require_once(dirname(__DIR__) . '/vendor/autoload.php');

$root_dir = dirname(__DIR__);
$webroot_dir = __DIR__;
$wp_dir = '/wp';
$content_dir = '/app';

define('WPLANG', "ja");
define('WP_DEFAULT_THEME', "default");

if (!defined('ABSPATH')) {
  define('ABSPATH', $webroot_dir . $wp_dir);
}

$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();
$dotenv->required(array('DB_NAME', 'DB_USER', 'DB_PASSWORD'));

switch (getenv('WP_ENV')) {
case 'development':
  ini_set('display_errors', 1);
  define('SAVEQUERIES', true);
  define('WP_DEBUG', true);
  define('SCRIPT_DEBUG', true);
  break;
default:
  ini_set('display_errors', 0);
  define('WP_DEBUG_DISPLAY', false);
  define('SCRIPT_DEBUG', false);
  define('DISALLOW_FILE_MODS', true);
  break;
}

if (!($_SERVER['PHP_AUTH_USER'] == getenv('PHP_AUTH_USER') && $_SERVER['PHP_AUTH_PW'] == getenv('PHP_AUTH_PW'))) {
  header('WWW-Authenticate: Basic realm="Private Page"');
  header('HTTP/1.0 401 Unauthorized');
  die();
}

$proto = $_SERVER['HTTPS'] ? "https://" : "http://";

$defaults = [
  'AUTOMATIC_UPDATER_DISABLED' => true,
  'DISABLE_WP_CRON'            => true,
  'DISALLOW_FILE_EDIT'         => true,
  'WP_POST_REVISIONS'          => 20,
  'WP_HOME'                    => $proto . $_SERVER['SERVER_NAME'],
  'WP_CONTENT_DIR'             => $webroot_dir . $content_dir,
  'WP_SITEURL'                 => $proto . $_SERVER['SERVER_NAME'] . $wp_dir,
  'DB_NAME'                    => '',
  'DB_USER'                    => '',
  'DB_PASSWORD'                => '',
  'DB_HOST'                    => 'localhost',
  'DB_CHARSET'                 => 'utf8',
  'DB_COLLATE'                 => '',
  'AUTH_KEY'                   => null,
  'SECURE_AUTH_KEY'            => null,
  'LOGGED_IN_KEY'              => null,
  'NONCE_KEY'                  => null,
  'AUTH_SALT'                  => null,
  'SECURE_AUTH_SALT'           => null,
  'LOGGED_IN_SALT'             => null,
  'NONCE_SALT'                 => null,
];

foreach($defaults as $key => $val) {
  if(getenv($key)) {
    define($key, getenv($key));
  } elseif(!is_null($val)) {
    define($key, $val);
  }
}

define('WP_CONTENT_URL' , WP_HOME . $content_dir);
$table_prefix = getenv('DB_PREFIX') ? getenv('DB_PREFIX') : 'wp_';

require_once(ABSPATH . 'wp-settings.php');
