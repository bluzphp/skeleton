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

// Require loader
require_once dirname(dirname(__FILE__)) . '/application/_loader.php';

/**
 * Debug mode for development environment only,
 * use bookmarklets for enable it
 * @link https://github.com/bluzphp/skeleton/wiki/Module-System
 */
define('DEBUG_KEY', isset($_SERVER['BLUZ_DEBUG_KEY'])? $_SERVER['BLUZ_DEBUG_KEY']:'debug');
define('DEBUG_LOG', isset($_SERVER['BLUZ_LOG']));

if (isset($_COOKIE[DEBUG_KEY])) {
    define('DEBUG', true);
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
} else {
    define('DEBUG', false);
    error_reporting(0);
    ini_set('display_errors', 0);
}

/**
 * Block iframe embedding for prevent security issues
 * @link https://developer.mozilla.org/en-US/docs/HTTP/X-Frame-Options
 */
header('X-Frame-Options: SAMEORIGIN');

// Error Log
function errorLog($message) {
    if (defined('DEBUG_LOG')
        && is_dir(PATH_DATA .'/logs')
        && is_writable(PATH_DATA .'/logs')) {
        file_put_contents(
            PATH_DATA .'/logs/'.(date('Y-m-d')).'.log',
            "\t[".date("H:i:s")."]\t".$message,
            FILE_APPEND | LOCK_EX
        );
    }
}

// Error Handler
function errorHandler() {
    if (!$e = error_get_last()) {
        return;
    }
    if ($e['type'] == E_USER_NOTICE) {
        // already handled error
        return;
    }
    errorLog($e['message'] ."\n". $e['file'] ."#". $e['line'] ."\n");

    require_once 'error.php';
}

// Shutdown function for handle critical and other errors
register_shutdown_function('errorHandler');

// Try to run application
try {

    /**
     * @var \Composer\Autoload\ClassLoader $loader
     * @link http://getcomposer.org/apidoc/master/Composer/Autoload/ClassLoader.html
     */
    require PATH_VENDOR . '/autoload.php';

    require_once PATH_APPLICATION . '/Bootstrap.php';
    require_once PATH_APPLICATION . '/Exception.php';

    // Environment
    $env = isset($_SERVER['BLUZ_ENV'])? $_SERVER['BLUZ_ENV']:'production';

    /**
     * @var \Application\Bootstrap $app
     */
    $app = \Application\Bootstrap::getInstance();
    $app->init($env);
    $app->process();
    $app->render();
    $app->finish();
} catch (Exception $e) {
    errorLog($e->getMessage() ."\n". $e->getTraceAsString() ."\n");
    require_once 'error.php';
}
