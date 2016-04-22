<?php
/**
 * Test AJAX
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 * @return closure
 */
namespace Application;

use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;

return
/**
 * @accept HTML
 * @accept JSON
 * @param bool $messages
 * @return void
 */
function ($messages = false) use ($data) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    if ($messages) {
        Messages::addNotice('Notice for AJAX call');
        Messages::addSuccess('Success for AJAX call');
        Messages::addError('Error for AJAX call');

        $data->baz = 'qux';
    }
    Messages::addNotice('Method '. Request::getMethod());
    $data->foo = 'bar';
};
