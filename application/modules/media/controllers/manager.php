<?php
/**
 * Media manager for user
 *
 * @author   Anton Shevchuk
 * @created  02.09.16 14:48
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Controller\Controller;

/**
 * @return array
 */
return function () {
    /**
     * @var Controller $this
     */
    return [
        'images' => Media\Table::getInstance()->getImages()
    ];
};
