<?php
/**
 * Simple loader
 *
 * @author   C.O.
 * @created  16.11.12 14:33
 */

// Check PHP version
if (version_compare(phpversion(), '5.4.3', '<') ) {
    printf("PHP 5.4.3 is required, you have %s\n", phpversion());
    exit();
}

// Check APC and use it for definitions
if (function_exists('apc_load_constants')) {
    function define_array($key, $arr, $case_sensitive = true)
    {
        if (!apc_load_constants($key, $case_sensitive)) {
            apc_define_constants($key, $arr, $case_sensitive);
        }
    }
} else {
    function define_array($key, $arr, $case_sensitive = true)
    {
        foreach ($arr as $name => $value)
            define($name, $value, $case_sensitive);
    }
}


// Paths
$root = realpath(dirname(__FILE__) . '/../');

define_array('PATH-' . $_SERVER['HTTP_HOST'], array(
    'PATH_ROOT' => $root,
    'PATH_APPLICATION' => $root . '/application',
    'PATH_DATA' => $root . '/data',
    'PATH_VENDOR' => $root . '/vendor',
    'PATH_BLUZ' => $root . '/vendor/bluzphp/framework/src/Bluz',
    'PATH_PUBLIC' => $root . '/public'
));

// Shutdown function for handle critical and other errors
register_shutdown_function('errorHandler');