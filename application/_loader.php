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

// Definitions
defineArray(
    'PATH-' .$root,
    array(
        'PATH_ROOT' => $root,
        'PATH_APPLICATION' => $root . '/application',
        'PATH_DATA' => $root . '/data',
        'PATH_VENDOR' => $root . '/vendor',
        'PATH_BLUZ' => $root . '/vendor/bluzphp/framework/src/Bluz',
        'PATH_PUBLIC' => $root . '/public'
    )
);
