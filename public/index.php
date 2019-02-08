<?php
/**
 * Index file
 *
 * @created  06.07.11 16:20
 */

namespace Application;

// Check CLI
if (PHP_SAPI === 'cli') {
    echo "Use `php ./vendor/bin/bluzman` instead of `index.php`\n";
    exit;
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
    // display error page
    require_once __DIR__ .'/error.php';
    // try to write log "warning"
    errorLog($e);
}
