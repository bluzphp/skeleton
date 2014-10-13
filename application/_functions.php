<?php
/**
 * Simple functions of Application
 * be careful with this way
 * @author  Anton Shevchuk
 * @created 25.07.13 13:34
 */

// Write message to log file
if (!function_exists('errorLog')) {
    /**
     * @param string $message
     * @param string $description
     */
    function errorLog($message, $description = null)
    {
        if (getenv('BLUZ_LOG')
            && is_dir(PATH_DATA .'/logs')
            && is_writable(PATH_DATA .'/logs')) {

            // TODO: need default log format
            // [Wed Oct 11 14:32:52 2000] [error] [client 127.0.0.1] client denied by server configuration: /var/www/...
            $message = "[".date("H:i:s")."]\t"
                . trim($message)
                . ($description?"\n\t\t".$description:"")
                . "\n";
            file_put_contents(
                PATH_DATA .'/logs/'.(date('Y-m-d')).'.log',
                $message,
                FILE_APPEND | LOCK_EX
            );
        }
    }
}
