<?php
/**
 * Test CLI
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  18.11.12 19:41
 */
namespace Application;

use Bluz\Controller\Controller;

/**
 * @method CLI
 *
 * @param bool $flag
 * @return array
 */
return function ($flag = false) {
    /**
     * @var Controller $this
     */
    if ($flag) {
        $this->assign('flag', 'true');
    }

    return ['string' => 'bar', 'array' => ['some', 'array']];
};
