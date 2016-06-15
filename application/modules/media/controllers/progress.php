<?php
/**
 * Check upload progress
 *
 * @author   Anton Shevchuk
 * @created  29.07.13 11:48
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Controller\Controller;

/**
 * @param $file
 * @return string
 * @throws Exception
 */
return function ($file) {
    /**
     * @var Controller $this
     */
    // used native session settings without rewrite save path
    // because PHP_SESSION_UPLOAD_PROGRESS mechanism is epic
    if (session_id() == '') {
        session_start();
    }

    $key = ini_get("session.upload_progress.prefix") . $file;

    if (!isset($_SESSION[$key])) {
        throw new Exception("Invalid file identity");
    }

    $this->useLayout(false);
    return json_encode($_SESSION[$key]);
};
