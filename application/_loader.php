<?php
/**
 * Simple loader
 *
 * @author   C.O.
 * @created  16.11.12 14:33
 */

// Check PHP version
if (version_compare(phpversion(), '5.4.3', '<')) {
    printf("PHP 5.4.3 is required, you have %s\n", phpversion());
    exit();
}

// Root path, double level up
$root = realpath(dirname(dirname(__FILE__)));

// Definitions
define('PATH_ROOT', $root);
define('PATH_APPLICATION', $root . '/application');
define('PATH_DATA', $root . '/data');
define('PATH_VENDOR', $root . '/vendor');
define('PATH_BLUZ', $root . '/vendor/bluzphp/framework/src/Bluz');
define('PATH_PUBLIC', $root . '/public');
