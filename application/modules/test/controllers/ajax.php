<?php
/**
 * Test AJAX
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;

/**
 * @accept ANY
 * @accept JSON
 *
 * @param bool $messages
 * @return void
 */
return function ($messages = false) {
    /**
     * @var Controller $this
     */
    $this->useJson();
    if ($messages) {
        Messages::addNotice('Notice for AJAX call');
        Messages::addSuccess('Success for AJAX call');
        Messages::addError('Error for AJAX call');

        $this->assign('baz', 'qux');
    }
    Messages::addNotice('Method '. Request::getMethod());

    $this->assign('foo', 'bar');
};
