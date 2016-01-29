<?php
/**
 * Test CLI
 *
 * @author   Anton Shevchuk
 * @created  18.11.12 19:41
 */
namespace Application;

use Bluz\Proxy\Messages;

return
/**
 * @method GET
 * @param  bool $flag
 * @return \closure
 */
function ($flag = false) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    if ($flag) {
        Messages::addNotice('Notice Text');
        Messages::addSuccess('Success Text');
        Messages::addError('Error Text');
        Messages::addError('Another Error Text');
    }
    $view->string = 'bar';
    $view->array = ['some', 'array'];
};
