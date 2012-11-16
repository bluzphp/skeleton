<?php
/**
 * CLI file
 *
 * @author   C.O.
 * @created  14.11.12 13:20
 */
// Check CLI
if (PHP_SAPI !== 'cli') {
    exit;
}

// Require loader
require_once '_loader.php';

// Get CLI arguments
$argv = $_SERVER['argv'];

// Check environment
if (in_array('--env', $argv)) {
    $envOrder = array_search('--env', $argv) + 1;
    if (isset($argv[$envOrder])) {
        putenv('APPLICATION_ENV='.$argv[$envOrder]);
    }
}

// Environment
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : ENVIRONMENT_PRODUCTION));

// Debug mode for development environment only
if (in_array('--debug', $argv)) {
    define('DEBUG', true);
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
} else {
    define('DEBUG', false);
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Error Handler
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