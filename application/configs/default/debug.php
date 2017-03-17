<?php
/**
 * Debug mode
 *
 * @link https://github.com/bluzphp/framework/wiki/Debug
 * @return bool
 */
return getenv('BLUZ_DEBUG') && isset($_COOKIE['BLUZ_DEBUG']);
