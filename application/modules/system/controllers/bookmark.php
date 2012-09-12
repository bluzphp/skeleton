<?php
/**
 * Debug bookmarklet
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

return
/**
 * @privilege Info
 *
 * @return \closure
 */
function($key = null) {
    $key = $key?:'BLUZ_DEBUG';
    return ['key' => $key];
};
