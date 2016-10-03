<?php
/**
 * Example Bluz-Bar usage
 *
 * @author Anton Shevchuk
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Proxy\Logger;

/**
 * @return void
 */
return function () {
    $this->disableView();

    Logger::info("Controller Bar");

    return;
};
