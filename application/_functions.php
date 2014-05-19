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
     */
    function errorLog($message)
    {
        if (getenv('BLUZ_LOG')
            && is_dir(PATH_DATA .'/logs')
            && is_writable(PATH_DATA .'/logs')) {
            file_put_contents(
                PATH_DATA .'/logs/'.(date('Y-m-d')).'.log',
                "\t[".date("H:i:s")."]\t".$message,
                FILE_APPEND | LOCK_EX
            );
        }
    }
}

// Error Handler
if (!function_exists('errorHandler')) {
    /**
     * Custom error handler
     */
    function errorHandler()
    {
        $e = error_get_last();
        // check error type
        if (!is_array($e)
            || !in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
            return;
        }
        // try to write log
        errorLog($e['message'] ."\n". $e['file'] ."#". $e['line'] ."\n");
    }
}
