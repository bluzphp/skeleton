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
use Bluz\Proxy\Messages;

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
        Messages::addNotice('Notice Text');
        Messages::addSuccess('Success Text');
        Messages::addError('Error Text');
        Messages::addError('Another Error Text');
    }
    return ['string' => 'bar', 'array' => ['some', 'array']];
};
