<?php
/**
 * CLI file
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
    "u:e::dlh",
    [
        "uri:",  // required
        "env::", // optional
        "debug", // just flag
        "help"   // display help
    ]
);

// Check help
if (array_key_exists('h', $arguments) || array_key_exists('help', $arguments)) {
    echo "Option `--uri` is required, it's similar to browser query\n";
    echo "Use `--env` option for setup application environment\n";
    echo "Use `--debug` flag for receive more information\n";
    echo "Example:\n";
    echo "\tphp ./bin/cli.php --uri '/index/index/?foo=bar'\n";
    echo "\tphp ./bin/cli.php --uri '/index/index/?foo=bar' --env='dev' --debug\n";
    echo "Example of short syntax:\n";
    echo "\tphp ./bin/cli.php -u '/index/index/?foo=bar'\n";
    echo "\tphp ./bin/cli.php -u '/index/index/?foo=bar' -e='dev' -d\n";
    exit();
}

// Check URI option
if (!array_key_exists('u', $arguments) && !array_key_exists('uri', $arguments)) {
    echo "Option `--uri` is required\n";
    echo "Use `--help` flag for show help notices\n";
    exit();
}

// Check and setup environment
if (array_key_exists('e', $arguments) || array_key_exists('env', $arguments)) {
    putenv('BLUZ_ENV='. (isset($arguments['e'])?$arguments['e']:$arguments['env']));
}

// Check and setup log save
if (array_key_exists('l', $arguments) || array_key_exists('log', $arguments)) {
    putenv('BLUZ_LOG=1');
}

// Debug mode for development environment only
if (array_key_exists('d', $arguments) || array_key_exists('debug', $arguments)) {
    putenv('BLUZ_DEBUG=1');
}

// Display error
function errorDisplay() {
    $e = error_get_last();
    if (!is_array($e)
        || !in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        return;
    }
    echo "Application Error\n";
    if (getenv('BLUZ_DEBUG')) {
        echo $e['message']."\n";
        echo $e['file'] ."#". $e['line'] ."\n";
    }
    // try to write log
    errorLog($e['type'], $e['message'], $e['file'] ."#". $e['line']);
    exit(1);
}

// Shutdown function for handle critical errors
register_shutdown_function('\\Application\\errorDisplay');


// Try to run application
try {
    /**
     * @var \Composer\Autoload\ClassLoader $loader
     * @see http://getcomposer.org/apidoc/master/Composer/Autoload/ClassLoader.html
     */
    require_once dirname(__DIR__) . '/vendor/autoload.php';
    
    // Error handler for log other errors
    set_error_handler('\\Application\\errorLog', E_ALL);

    // Environment
    $env = getenv('BLUZ_ENV')?:'production';

    $app = CliBootstrap::getInstance();
    $app->init($env);
    $app->run();
} catch (Exception $e) {
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
    errorLog(E_USER_ERROR, $e->getMessage());
    exit(1);
}
