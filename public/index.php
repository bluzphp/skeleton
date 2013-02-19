<?php
/**
 * Index file
 *
 * @author   C.O.
 * @created  06.07.11 16:20
 */
// Check CLI
if (PHP_SAPI === 'cli') {
    echo "Use `cli.php` instead of `index.php`\n";
    exit;
}

// Require loader
require_once dirname(dirname(__FILE__)) . '/application/_loader.php';

// Debug mode for development environment only
define('DEBUG_KEY', isset($_SERVER['BLUZ_DEBUG_KEY'])? $_SERVER['BLUZ_DEBUG_KEY']:'debug');

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
    if (!$e = error_get_last()) {
        return;
    }
    require_once 'error.php';
}

// Try to run application
try {

    require_once PATH_VENDOR . '/autoload.php';

    require_once PATH_BLUZ . '/_loader.php';

    require_once PATH_APPLICATION . '/Bootstrap.php';
    require_once PATH_APPLICATION . '/Exception.php';

    // Environment
    $env = isset($_SERVER['BLUZ_ENV'])? $_SERVER['BLUZ_ENV']:'production';

    /**
     * @var \Application\Bootstrap $app
     */
    $app = \Application\Bootstrap::getInstance();
    $app->init($env)
        ->process();
    $app->render();
} catch (Exception $e) {
    require_once 'error.php';
}
