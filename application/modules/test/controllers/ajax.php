<?php
/**
 * Test AJAX
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 * @return closure
 */
namespace Application;

use Bluz;

return
/**
 * @param bool $messages
 * @return void
 */
function ($messages = false) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    if ($messages) {
        $this->getMessages()->addNotice('Notice for AJAX call');
        $this->getMessages()->addSuccess('Success for AJAX call');
        $this->getMessages()->addError('Error for AJAX call');

        $view->baz = 'qux';
    }
    $this->getMessages()->addNotice('Method '. $this->getRequest()->getMethod());

    $view->foo = 'bar';
};
