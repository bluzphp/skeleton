<?php
/**
 * Simple loader
 *
 * @author   C.O.
 * @created  16.11.12 14:33
 */
// Check PHP version
if (version_compare(phpversion(), '5.6.0', '<')) {
    printf("PHP 5.6.0 is required, you have %s\n", phpversion());
    exit(1);
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
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
