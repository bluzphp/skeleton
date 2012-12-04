<?php
/**
 * Index file
 *
 * @author   C.O.
 * @created  06.07.11 16:20
 */

// Require loader
require_once '_loader.php';

// Environment
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : ENVIRONMENT_PRODUCTION));

// Debug mode for development environment only
define('DEBUG_KEY', 'BLUZ_DEBUG');

if (isset($_COOKIE[DEBUG_KEY])) {
    define('DEBUG', true);
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
} else {
    define('DEBUG', false);
    error_reporting(0);
    ini_set('display_errors', 0);
}

// iframe header - prevent security issues
header('X-Frame-Options: SAMEORIGIN');

// Error Handler
function errorHandler() {
    $e = error_get_last();
    if (!is_array($e)
        || !in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        return;
    }
    require_once 'error.php';
}

// Try to run application
try {
    // init loader
    require_once PATH_BLUZ . '/_loader.php';
    require_once PATH_LIBRARY . '/autoload.php';

    require_once PATH_APPLICATION . '/Bootstrap.php';
    require_once PATH_APPLICATION . '/Exception.php';

    /**
     * @var \Application\Bootstrap $app
     */
    $app = \Application\Bootstrap::getInstance();
    $app->init(APPLICATION_ENV)
        ->process()
        ->render();
} catch (Exception $e) {
    require_once 'error.php';
}
