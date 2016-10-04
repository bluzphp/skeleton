<?php
/**
 * Index file
 *
 * @author   C.O.
 * @created  06.07.11 16:20
 */
/**
 * @namespace
 */
namespace Application;

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
$debugKey = getenv('BLUZ_DEBUG_KEY') ?: 'BLUZ_DEBUG';
if (isset($_COOKIE[$debugKey])) {
    putenv('BLUZ_DEBUG=1');
}

/**
 * Block iframe embedding for prevent security issues
 * @link https://developer.mozilla.org/en-US/docs/HTTP/X-Frame-Options
 */
header('X-Frame-Options: SAMEORIGIN');

// Make fake header
header('X-Powered-By: backend');

// Try to run application
try {
    /**
     * @var \Composer\Autoload\ClassLoader $loader
     * @see http://getcomposer.org/apidoc/master/Composer/Autoload/ClassLoader.html
     */
    require_once dirname(__DIR__) . '/vendor/autoload.php';

    // Error handler for log all errors
    set_error_handler('\\Application\\errorHandler', E_ALL);

    // Environment
    $env = getenv('BLUZ_ENV') ?: 'production';
    $app = Bootstrap::getInstance();
    $app->init($env);
    $app->run();
} catch (\Throwable $e) {
    // try to write log "warning"
    errorLog($e);
    // display error page
    require_once 'error.php';
}
