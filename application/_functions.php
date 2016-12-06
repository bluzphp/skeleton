<?php
/**
 * Simple functions of Application
 * be careful with this way
 * @author  Anton Shevchuk
 * @created 25.07.13 13:34
 */

/**
 * @namespace
 */
namespace Application;

/**
 * Write message to log file
 *
 * @param integer $severity
 * @param string  $message
 * @param string  $file
 * @param integer $line
 * @throws \ErrorException
 */
function errorHandler($severity, $message, $file = null, $line = null)
{
    throw new \ErrorException($message, 0, $severity, $file, $line);
}

/**
 * Write Exception to log file
 *
 * @param \Throwable $exception
 * @return bool
 */
function errorLog($exception)
{
    if (getenv('BLUZ_LOG') && is_dir(PATH_DATA .'/logs') && is_writable(PATH_DATA .'/logs')) {
        // [Wed Oct 11 14:32:52 2000] [error] [client 127.0.0.1] client denied by server configuration: /var/www/...
        $message = "[".date("r")."] [".get_class($exception)."] "
            . ($exception->getFile()) .':'. ($exception->getLine())
            . ":\t"
            . trim($exception->getMessage())
            . "\n"
        ;

        // write log
        file_put_contents(
            PATH_DATA .'/logs/'.(date('Y-m-d')).'.log',
            $message,
            FILE_APPEND | LOCK_EX
        );
    }

    return false;
}
