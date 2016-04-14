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
 * @param integer $type
 * @param string  $message
 * @param string  $file
 * @param integer $line
 * @return bool
 */
function errorLog($type, $message, $file = null, $line = null)
{
    if (getenv('BLUZ_LOG') && is_dir(PATH_DATA .'/logs') && is_writable(PATH_DATA .'/logs')) {
        switch ($type) {
            case E_PARSE:
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                $error = 'error';
                break;
            case E_WARNING:
            case E_USER_WARNING:
            case E_COMPILE_WARNING:
            case E_RECOVERABLE_ERROR:
                $error = 'warning';
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $error = 'notice';
                break;
            case E_STRICT:
                $error = 'strict';
                break;
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $error = 'deprecated';
                break;
            default:
                $error = 'undefined';
                break;
        }

        // TODO: need default log format
        // [Wed Oct 11 14:32:52 2000] [error] [client 127.0.0.1] client denied by server configuration: /var/www/...
        $message = "[".date("Y-m-d H:i:s")."] [$error] "
            . ($file?:$file) .':'. ($line?:$line)
            . "\n\t\t\t"
            . trim($message)
            . "\n"
        ;

        file_put_contents(
            PATH_DATA .'/logs/'.(date('Y-m-d')).'.log',
            $message,
            FILE_APPEND | LOCK_EX
        );
    }

    return false;
}
