<?php
/**
 * Index file
 *
 * @author   C.O.
 * @created  06.07.11 16:20
 */
// Check CLI
if (PHP_SAPI === 'cli') {
    echo "Use `bin/cli.php` instead of `index.php`\n";
    exit;
}

// Setup environment
/**
 * Debug mode for development environment only,
 * use bookmarklets for enable it
 * @link https://github.com/bluzphp/skeleton/wiki/Module-System
 */
define('DEBUG_KEY', getenv('BLUZ_DEBUG_KEY') ?: 'BLUZ_DEBUG');
if (isset($_COOKIE[DEBUG_KEY])) {
    putenv('BLUZ_DEBUG=1');
}

/**
 * Block iframe embedding for prevent security issues
 * @link https://developer.mozilla.org/en-US/docs/HTTP/X-Frame-Options
 */
header('X-Frame-Options: SAMEORIGIN');

// Make fake header
header('X-Powered-By: backend');

// Error Handler
function errorDisplay() {
    if (!$e = error_get_last()) {
        return;
    }
    if (!is_array($e)
        || !in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        return;
    }
    // clean all buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    require_once 'error.php';
}

// Shutdown function for handle critical and other errors
register_shutdown_function('errorDisplay');
// Try to run application
try {
    /**
     * @var \Composer\Autoload\ClassLoader $loader
     * @see http://getcomposer.org/apidoc/master/Composer/Autoload/ClassLoader.html
     */
    require_once dirname(__DIR__) . '/vendor/autoload.php';
    require_once PATH_APPLICATION . '/Bootstrap.php';
    require_once PATH_APPLICATION . '/Exception.php';

    // Environment
    $env = getenv('BLUZ_ENV') ?: 'production';

    /**
     * @var \Application\Bootstrap $app
     */
    $app = \Application\Bootstrap::getInstance();
    $app->init($env)
        ->process();
    $app->render();
    $app->finish();
} catch (Exception $e) {
    errorLog($e->getMessage() ."\n". $e->getTraceAsString() ."\n");
    require_once 'error.php';
}
