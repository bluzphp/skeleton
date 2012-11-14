<?php
/**
 * CLI file
 *
 * @author   C.O.
 * @created  14.11.12 13:20
 */
// Environment
define('ENVIRONMENT_PRODUCTION', 'production');
define('ENVIRONMENT_DEVELOPMENT', 'development');
define('ENVIRONMENT_TESTING', 'testing');
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : ENVIRONMENT_PRODUCTION));

// Debug mode for development environment only
if (isset($_SERVER['argv']) && in_array('--debug', $_SERVER['argv'])) {
    define('DEBUG', true);
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
} else {
    define('DEBUG', false);
    error_reporting(0);
    ini_set('display_errors', 0);
}


// Paths
define('PATH_ROOT', realpath(dirname(__FILE__) . '/../'));
define('PATH_APPLICATION', PATH_ROOT . '/application');
define('PATH_DATA', PATH_ROOT . '/data');
define('PATH_LIBRARY', PATH_ROOT . '/library');
define('PATH_PUBLIC', PATH_ROOT . '/public');
define('PATH_THEME', PATH_ROOT . '/themes');

// Shutdown function for handle critical and other errors
register_shutdown_function('errorHandler');

function errorHandler() {
    $e = error_get_last();
    if (!is_array($e)
        || !in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        return;
    }
    // clean all buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    echo "\033[41m\033[1;37mApplication Error\033[m\033m\n";
    if (defined('DEBUG') && DEBUG && isset($e)) {
        echo "\033[1;37m".$e['message']."\033[m\n";
        echo $e['file'] ."#". $e['line'] ."\n";
    }
}

// Try to run application
try {
    // init loader
    require_once PATH_LIBRARY . '/Bluz/_loader.php';
    require_once PATH_APPLICATION . '/Bootstrap.php';
    require_once PATH_APPLICATION . '/Exception.php';

    /**
     * @var \Application\Bootstrap $app
     */
    $app = \Application\Bootstrap::getInstance();
    $app->init(APPLICATION_ENV)
        ->process()
        ->output();
} catch (Exception $e) {
    echo "\033[41m\033[1;37mApplication Exception\033[m\033m\n";
    if (defined('DEBUG') && DEBUG && isset($e)) {
        echo "\033[1;37m".$e->getMessage()."\033[m\n";
        echo $e->getTraceAsString()."\n";
    }
}