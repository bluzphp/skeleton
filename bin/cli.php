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

// Get CLI arguments
$arguments = getopt(
    "",
    [
        "uri:",  // required
        "env::", // optional
        "debug", // just flag
        "log",   // just flag too
        "help"   // display help
    ]
);

// Check help
if (array_key_exists('help', $arguments)) {
    echo "Option `--uri` is required, it's similar to browser query\n";
    echo "Use `--env` option for setup applicaiton environment\n";
    echo "Use `--debug` flag for receive more information\n";
    echo "Use `--log` flag for save information about errors in files\n";
    exit();
}
// Check URI option
if (!array_key_exists('uri', $arguments)) {
    echo "\033[41m\033[1;37mOption `--uri` is required\033[m\033m\n";
    exit();
}

// Check and setup environment
if (array_key_exists('env', $arguments)) {
    putenv('BLUZ_ENV='.$arguments['env']);
}
// Check and setup log save
if (array_key_exists('log', $arguments)) {
    putenv('BLUZ_LOG=1');
}

// Debug mode for development environment only
if (array_key_exists('debug', $arguments)) {
    putenv('BLUZ_DEBUG=1');
}

// Display error
function errorDisplay() {
    $e = error_get_last();
    if (!is_array($e)
        || !in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        return;
    }
    echo "\033[41m\033[1;37mApplication Error\033[m\033m\n";
    if (defined('DEBUG') && DEBUG && isset($e)) {
        echo "\033[1;37m".$e['message']."\033[m\n";
        echo $e['file'] ."#". $e['line'] ."\n";
    }
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
    require_once PATH_APPLICATION . '/CliBootstrap.php';
    require_once PATH_APPLICATION . '/Exception.php';

    // Environment
    $env = getenv('BLUZ_ENV')?:'production';

    /**
     * @var \Application\CliBootstrap $app
     */
    $app = \Application\CliBootstrap::getInstance();
    $app->init($env)
        ->process();
    $app->render();
    $app->finish();
} catch (Exception $e) {
    echo "\033[41m\033[1;37mApplication Exception\033[m\033m\n";
    if (defined('DEBUG') && DEBUG && isset($e)) {
        echo "\033[1;37m".strip_tags($e->getMessage())."\033[m\n\n";
        echo "# --- \n";
        echo $e->getTraceAsString()."\n";
        echo "# --- \n";
    } else {
        echo "Use `--help` flag for show help notices\n";
        echo "Use `--debug` flag for receive more information\n";
    }
}