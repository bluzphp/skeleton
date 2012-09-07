<?php
/**
 * Fast functions
 *
 * @author   Anton Shevchuk
 * @created  07.09.12 11:29
 */

if (!function_exists('debug')) {
    /**
     * Debug variables wit \Bluz\Debug::dump
     *
     * @return void
     */
    function debug() {
        if (!defined('DEBUG') or !DEBUG) {
            return;
        }

        //
        // try to get caller information
        //
        $backtrace = debug_backtrace();

        // get variable name
        $arrLines = file($backtrace[0]["file"]);
        $code = $arrLines[($backtrace[0]["line"] - 1)];
        $arrMatches = array();
        // find call to Core_Debug::debug()
        preg_match('/\b\s*debug\s*\(\s*(.+)\s*\);\s*/i', $code, $arrMatches);

        $varName = isset($arrMatches[1]) ? $arrMatches[1] : '???';

        $where = basename($backtrace[0]["file"]) . ':' . $backtrace[0]["line"];

        echo "<h2>Debug: {$varName} ({$where})</h2>";

        //
        // call \Bluz\Debug::dump()
        //
        call_user_func_array(['\Bluz\Debug', 'dump'], func_get_args());
    }
}