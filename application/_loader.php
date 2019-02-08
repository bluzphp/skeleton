<?php
/**
 * Simple loader
 *
 * @author Captain Obvious
 */

// Check PHP version
if (PHP_VERSION_ID < 70100) {
    printf("PHP 7.1.0 is required, you have %s\n", PHP_VERSION);
    exit(1);
}

// Root path - level up
$root = realpath(dirname(__DIR__));

defined('PATH_ROOT') ?: define('PATH_ROOT', $root);
defined('PATH_APPLICATION') ?: define('PATH_APPLICATION', $root . '/application');
defined('PATH_DATA') ?: define('PATH_DATA', $root . '/data');
defined('PATH_VENDOR') ?: define('PATH_VENDOR', $root . '/vendor');
defined('PATH_PUBLIC') ?: define('PATH_PUBLIC', $root . '/public');

// Debug mode for development environment only
if (getenv('BLUZ_DEBUG')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
