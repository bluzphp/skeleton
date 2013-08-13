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
 * @param sd
 * @param bool $messages
 * @return \closure
 */
function ($messages = false) use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    if ($messages) {
        $this->getMessages()->addNotice('Notice Text');
        $this->getMessages()->addSuccess('Success Text');
        $this->getMessages()->addError('Error Text');
    }
    $this->getMessages()->addNotice('Method '. $this->getRequest()->getMethod());

    $view->foo = 'bar';
    //$this->reload();
    //$this->redirect('http://google.com');
    //$this->redirectTo('test', 'index');

    sleep(2);
};
