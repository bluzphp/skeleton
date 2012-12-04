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

// Setup environment defines
define('ENVIRONMENT_PRODUCTION', 'production');
define('ENVIRONMENT_DEVELOPMENT', 'development');
define('ENVIRONMENT_TESTING', 'testing');

// Paths
define('PATH_ROOT', realpath(dirname(__FILE__) . '/../'));
define('PATH_APPLICATION', PATH_ROOT . '/application');
define('PATH_DATA', PATH_ROOT . '/data');
define('PATH_LIBRARY', PATH_ROOT . '/library');
define('PATH_BLUZ', PATH_LIBRARY . '/bluzphp/framework/src/Bluz');
define('PATH_PUBLIC', PATH_ROOT . '/public');

// Shutdown function for handle critical and other errors
register_shutdown_function('errorHandler');