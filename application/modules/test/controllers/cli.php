<?php
/**
 * Test CLI
 *
 * @author   Anton Shevchuk
 * @created  18.11.12 19:41
 */
namespace Application;

use Bluz;

return
/**
 * @param bool $flag
 * @return \closure
 */
function ($flag = false) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    if ($flag) {
        $this->getMessages()->addNotice('Notice Text');
        $this->getMessages()->addSuccess('Success Text');
        $this->getMessages()->addError('Error Text');
        $this->getMessages()->addError('Another Error Text');
    }
    $view->string = 'bar';
    $view->array = ['some', 'array'];
    $view->object = new \stdClass();
    $view->object->property = 'example';
};
