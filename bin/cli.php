<?php
/**
 * CLI file
 *
 * @todo Rewrite it to Symfony Console http://symfony.com/doc/current/components/console.html
 *
 * @author   C.O.
 * @created  14.11.12 13:20
 */

/**
 * @namespace
 */
namespace Application;

// Check CLI
if (PHP_SAPI !== 'cli') {
    exit;
}

// Get CLI arguments
$arguments = getopt(
    "u:h",
    [
        "uri:",  // required
        "help"   // display help
    ]
);

// Check help
if (array_key_exists('h', $arguments) || array_key_exists('help', $arguments)) {
    echo "Option `--uri` is required, it's similar to browser query\n";
    echo "Use `export BLUZ_ENV=dev` option for setup application environment\n";
    echo "Use `export BLUZ_DEBUG=1` flag for enable debug output\n";
    echo "Example:\n";
    echo "\tphp ./bin/cli.php --uri '/index/index/?foo=bar'\n";
    echo "\texport BLUZ_ENV=dev && php ./bin/cli.php --uri '/index/index/?foo=bar'\n";
    echo "Example of short syntax:\n";
    echo "\tphp ./bin/cli.php -u '/index/index/?foo=bar'\n";
    exit();
}

// Check URI option
if (!array_key_exists('u', $arguments) && !array_key_exists('uri', $arguments)) {
    echo "Option `--uri` is required\n";
    echo "Use `--help` flag for show help notices\n";
    exit();
}

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
    $env = getenv('BLUZ_ENV')?:'production';

    $app = CliBootstrap::getInstance();
    $app->init($env);
    $app->run();
} catch (\Throwable $e) {
    echo "Application Exception\n";
    if (getenv('BLUZ_DEBUG')) {
        echo strip_tags($e->getMessage())."\n\n";
        echo "# --- \n";
        echo $e->getTraceAsString()."\n";
        echo "# --- \n";
    } else {
        echo "Use `--help` flag for show help notices\n";
        echo "Use `--debug` flag for receive more information\n";
    }
    // try to write log
    errorLog($e);
    exit(1);
}
