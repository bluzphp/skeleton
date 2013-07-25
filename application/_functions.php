<?php
/**
 * Simple functions of Application
 * be careful with this way
 * @author  Anton Shevchuk
 * @created 25.07.13 13:34
 */

// Check APC and use it for definitions
if (function_exists('apc_load_constants')) {
    function defineArray($key, $arr, $case_sensitive = true)
    {
        if (!apc_load_constants($key, $case_sensitive)) {
            apc_define_constants($key, $arr, $case_sensitive);
        }
    }
} else {
    function defineArray($key, $arr, $case_sensitive = true)
    {
        foreach ($arr as $name => $value) {
            define($name, $value, $case_sensitive);
        }
    }
}
