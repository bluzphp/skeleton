<?php
/**
 * Simple loader
 *
 * @author   C.O.
 * @created  16.11.12 14:33
 */
require_once '_functions.php';

// Check PHP version
if (version_compare(phpversion(), '5.4.3', '<')) {
    printf("PHP 5.4.3 is required, you have %s\n", phpversion());
    exit();
}

// Root path, double level up
$root = realpath(dirname(dirname(__FILE__)));

define('PATH_ROOT', $root);
define('PATH_APPLICATION', $root . '/application');
define('PATH_DATA', $root . '/data');
define('PATH_VENDOR', $root . '/vendor');
define('PATH_PUBLIC', $root . '/public');

// Debug mode for development environment only
if (getenv('BLUZ_DEBUG')) {
    define('DEBUG', true);
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
} else {
    define('DEBUG', false);
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Shutdown function for handle critical and other errors
register_shutdown_function('errorHandler');
